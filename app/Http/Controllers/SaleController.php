<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Promotion;
use App\Models\PromotionUsage;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{

    public function index()
    {
        return view('admin.ventas.index');
    }

    public function data()
    {
        $query = Sale::with(['promotion', 'saleItems.item'])
            ->where('status', 'active')
            ->withCount('saleItems')
            ->select(['id', 'sale_date', 'subtotal', 'discount_type', 'discount_value', 'total', 'promotion_id', 'status']);

        return DataTables::of($query)
            ->editColumn('sale_date', function (Sale $sale) {
                return $sale->sale_date ? $sale->sale_date->format('d/m/Y H:i') : '';
            })
            ->addColumn('items_list', function (Sale $sale) {
                if ($sale->saleItems->isEmpty()) {
                    return 'Sin ítems';
                }

                return $sale->saleItems->map(function ($line) {
                    $name = $line->item?->name ?? 'Ítem eliminado';
                    return "{$name} × {$line->quantity}";
                })->implode('<br>');
            })
            ->addColumn('discount_display', function (Sale $sale) {
                $value = number_format($sale->discount_value, 2, ',', '.');
                return $sale->discount_type === 'percentage' ? "{$value} %" : "Bs {$value}";
            })
            ->addColumn('promotion', function (Sale $sale) {
                return $sale->promotion?->name ?? 'Sin promoción';
            })
            ->addColumn('acciones', function (Sale $sale) {
                return view('admin.ventas.partials.actions', compact('sale'))->render();
            })
            ->rawColumns(['acciones', 'items_list'])
            ->make(true);
    }


    public function facturaPdf(Sale $venta)
    {
        ini_set('memory_limit', '512M');

        $venta->load([
            'saleItems.item:id,name,price',
            'promotion:id,name,discount_type,discount_value'
        ]);

        $pdf = Pdf::loadView('admin.ventas.pdf', compact('venta'))
            ->setPaper([0, 0, 792, 1008]) 
            ->setOption('isRemoteEnabled', false)
            ->setOption('dpi', 72);

        return $pdf->stream(
            'factura-' . str_pad($venta->id, 6, '0', STR_PAD_LEFT) . '.pdf'
        );
    }

    public function create()
    {
        $items = Item::orderBy('name')->select('id', 'name', 'price')->get();
        $promotions = $this->availablePromotions();
        return view('admin.ventas.create', compact('items', 'promotions'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'exists:items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'promotion_id' => ['nullable', 'exists:promotions,id'],
            'ci' => ['nullable', 'string', 'max:50'],
        ]);

        $data = $validator->validate();
        $promotions = $this->availablePromotions();
        $promotion = $this->resolvePromotion($data['promotion_id'] ?? null, $promotions);
        if (($data['promotion_id'] ?? null) && !$promotion) {
            throw ValidationException::withMessages([
                'promotion_id' => 'La promoción seleccionada no se encuentra disponible.',
            ]);
        }

        $ci = $data['ci'] ?? null;
        if ($promotion && $promotion->single_use) {
            if (empty($ci)) {
                throw ValidationException::withMessages([
                    'ci' => 'Debes registrar el número de cédula para promociones de uso único.',
                ]);
            }
            $this->ensurePromotionUsageIsUnique($promotion, $ci);
        }

        $preparedItems = $this->prepareItems($data['items']);

        if ($preparedItems->isEmpty()) {
            throw ValidationException::withMessages([
                'items' => 'Debe seleccionar al menos un ítem.',
            ]);
        }

        $discountType = $promotion ? $promotion->discount_type : 'fixed';
        $discountValue = $promotion ? (float) $promotion->discount_value : 0;
        $saleDate = now();
        $invoiceNumber = Sale::whereDate('sale_date', $saleDate)->max('invoice_number') ?? 0;
        $invoiceNumber++;

        $totals = $this->calculateTotals($preparedItems, $discountType, $discountValue);

        $sale = DB::transaction(function () use ($preparedItems, $totals, $promotion, $ci, $discountType, $discountValue, $saleDate, $invoiceNumber) {
            $sale = Sale::create([
                'sale_date' => $saleDate,
                'invoice_number' => $invoiceNumber,
                'status' => 'active',
                'subtotal' => $totals['subtotal'],
                'discount_type' => $discountType,
                'discount_value' => $discountValue,
                'promotion_id' => $promotion?->id,
                'customer_ci' => $ci,
                'total' => $totals['total'],
            ]);

            foreach ($preparedItems as $row) {
                $sale->saleItems()->create($row);
            }

            return $sale;
        });

        $this->syncPromotionUsage($sale, $promotion, $ci);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Venta registrada correctamente.',
                'redirect' => route('ventas.index'),
                'pdf_url' => route('ventas.factura.pdf', $sale),
            ], 201);
        }

        return redirect()
            ->route('ventas.index')
            ->with('success', 'Venta registrada correctamente.');
    }

    public function edit(Sale $venta)
    {
        $items = Item::orderBy('name')->select('id', 'name', 'price')->get();
        $promotions = $this->availablePromotions();
        return view('admin.ventas.edit', compact('venta', 'items', 'promotions'));
    }

    public function update(Request $request, Sale $venta)
    {
        $validator = Validator::make($request->all(), [
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'exists:items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'promotion_id' => ['nullable', 'exists:promotions,id'],
            'ci' => ['nullable', 'string', 'max:50'],
        ]);

        $data = $validator->validate();
        $promotions = $this->availablePromotions();
        $promotion = $this->resolvePromotion($data['promotion_id'] ?? null, $promotions);
        if (($data['promotion_id'] ?? null) && !$promotion) {
            throw ValidationException::withMessages([
                'promotion_id' => 'La promoción seleccionada no se encuentra disponible.',
            ]);
        }

        $ci = $data['ci'] ?? null;
        if ($promotion && $promotion->single_use) {
            if (empty($ci)) {
                throw ValidationException::withMessages([
                    'ci' => 'Debes registrar el número de cédula para promociones de uso único.',
                ]);
            }
            $this->ensurePromotionUsageIsUnique($promotion, $ci, $venta);
        }

        $preparedItems = $this->prepareItems($data['items']);

        if ($preparedItems->isEmpty()) {
            throw ValidationException::withMessages([
                'items' => 'Debe seleccionar al menos un ítem.',
            ]);
        }

        $discountType = $promotion ? $promotion->discount_type : 'fixed';
        $discountValue = $promotion ? (float) $promotion->discount_value : 0;

        $totals = $this->calculateTotals($preparedItems, $discountType, $discountValue);

        DB::transaction(function () use ($venta, $preparedItems, $totals, $promotion, $ci, $discountType, $discountValue) {
            $venta->update([
                'subtotal' => $totals['subtotal'],
                'discount_type' => $discountType,
                'discount_value' => $discountValue,
                'promotion_id' => $promotion?->id,
                'customer_ci' => $ci,
                'total' => $totals['total'],
            ]);

            $venta->saleItems()->delete();

            foreach ($preparedItems as $row) {
                $venta->saleItems()->create($row);
            }
        });

        $this->syncPromotionUsage($venta, $promotion, $ci);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Venta actualizada correctamente.',
                'redirect' => route('ventas.index'),
            ]);
        }

        return redirect()
            ->route('ventas.index')
            ->with('success', 'Venta actualizada correctamente.');
    }

    public function destroy(Request $request, Sale $venta)
    {
        $venta->update(['status' => 'annulled']);
        PromotionUsage::where('sale_id', $venta->id)->delete();

        $response = ['message' => 'Venta eliminada correctamente.'];

        if ($request->expectsJson()) {
            return response()->json($response);
        }

        return redirect()
            ->route('ventas.index')
            ->with('success', $response['message']);
    }

    private function prepareItems(array $items)
    {
        $collection = collect($items);

        return $collection->map(function ($row) {
            $item = Item::find($row['item_id']);
            if (!$item) {
                return null;
            }

            $quantity = max(1, (int) $row['quantity']);
            $unitPrice = $item->price;

            return [
                'item_id' => $item->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total' => round($unitPrice * $quantity, 2),
            ];
        })->filter();
    }

    private function calculateTotals($items, string $discountType, float $discountValue): array
    {
        $subtotal = $items->sum('total');

        if ($discountType === 'percentage') {
            $discountAmount = ($discountValue / 100) * $subtotal;
        } else {
            $discountAmount = min($discountValue, $subtotal);
        }

        $total = max($subtotal - $discountAmount, 0);

        return [
            'subtotal' => round($subtotal, 2),
            'discount_amount' => round($discountAmount, 2),
            'total' => round($total, 2),
        ];
    }

    private function availablePromotions()
    {
        $today = now();
        return Promotion::whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderBy('name')
            ->get();
    }

    private function resolvePromotion(?int $promotionId, $promotions)
    {
        if (!$promotionId) {
            return null;
        }

        return $promotions->firstWhere('id', $promotionId);
    }

    private function ensurePromotionUsageIsUnique(Promotion $promotion, string $ci, ?Sale $sale = null): void
    {
        $query = PromotionUsage::where('promotion_id', $promotion->id)
            ->where('ci', $ci);

        if ($sale) {
            $query->where('sale_id', '<>', $sale->id);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'ci' => 'Esta cédula ya usó la promoción seleccionada.',
            ]);
        }
    }

    private function syncPromotionUsage(Sale $sale, ?Promotion $promotion, ?string $ci): void
    {
        if ($promotion && $promotion->single_use) {
            if (empty($ci)) {
                return;
            }

            PromotionUsage::updateOrCreate(
                ['sale_id' => $sale->id],
                [
                    'promotion_id' => $promotion->id,
                    'ci' => $ci,
                    'used_at' => now(),
                ]
            );
        } else {
            PromotionUsage::where('sale_id', $sale->id)->delete();
        }
    }
}
