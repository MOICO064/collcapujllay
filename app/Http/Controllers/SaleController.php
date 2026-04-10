<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sale;
use App\Models\SaleItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $query = Sale::with([
                'saleItems.item',
                'user:id,name'
            ])
            ->where('status', 'active')
            ->withCount('saleItems')
            ->select(['id', 'sale_date', 'subtotal', 'discount_type', 'discount_value', 'total', 'payment_method', 'status', 'user_id', 'glosa']);

        $user = Auth::user();
        if ($user && $user->hasRole('cajas')) {
            $query->whereDate('sale_date', now()->format('Y-m-d'))
                ->where('user_id', $user->id);
        }
        $query->orderBy('sale_date', 'desc');

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
            ->addColumn('payment_method', function (Sale $sale) {
                return ucfirst($sale->payment_method ?? 'efectivo');
            })
            ->addColumn('user', function (Sale $sale) {
                return $sale->user?->name ?? 'Sistema';
            })
            ->addColumn('glosa', function (Sale $sale) {
                if (!$sale->glosa) {
                    return '';
                }
                return Str::limit($sale->glosa, 60);
            })
            ->addColumn('discount_display', function (Sale $sale) {
                $value = number_format($sale->discount_value, 2, ',', '.');
                return $sale->discount_type === 'percentage' ? "{$value} %" : "Bs {$value}";
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
            'user:id,name',
        ]);

        $pdf = Pdf::loadView('admin.ventas.pdf', compact('venta'))
            ->setPaper([0, 0, 396, 612])
            ->setOption('isRemoteEnabled', false)
            ->setOption('dpi', 72);

        return $pdf->stream(
            'factura-' . str_pad($venta->id, 6, '0', STR_PAD_LEFT) . '.pdf'
        );
    }

    public function create()
    {
        $items = Item::where('enabled', true)
            ->orderBy('name')
            ->select('id', 'name', 'price', 'use_once', 'category_id')
            ->with('category:id,name')
            ->get();

        return view('admin.ventas.create', compact('items'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'exists:items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:0'],
            'items.*.use_once_number' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', 'in:efectivo,qr'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'glosa' => ['nullable', 'string', 'max:2000'],
        ]);

        $data = $validator->validate();

        $this->ensureUseOnceNumbers($data['items']);
        $this->ensureUniqueUseOnceCodes($data['items']);
        $this->ensureItemsEnabled($data['items']);
        $preparedItems = $this->prepareItems($data['items']);

        if ($preparedItems->isEmpty()) {
            throw ValidationException::withMessages([
                'items' => 'Debe seleccionar al menos un ítem con cantidad mayor a cero.',
            ]);
        }

        $saleDate = now();
        $invoiceNumber = Sale::whereDate('sale_date', $saleDate)->max('invoice_number') ?? 0;
        $invoiceNumber++;

        $totals = $this->calculateTotals($preparedItems, 'fixed', 0);
        $paidAmount = round((float) $data['paid_amount'], 2);

        $sale = DB::transaction(function () use ($preparedItems, $totals, $saleDate, $invoiceNumber, $data, $paidAmount) {
            $sale = Sale::create([
                'sale_date' => $saleDate,
                'invoice_number' => $invoiceNumber,
                'status' => Sale::STATUS_ACTIVE,
                'subtotal' => $totals['subtotal'],
                'discount_type' => 'fixed',
                'discount_value' => 0,
                'payment_method' => $data['payment_method'],
                'paid_amount' => $paidAmount,
                'balance_due' => 0,
                'total' => $totals['total'],
                'customer_code' => $this->extractCustomerCode($preparedItems),
                'user_id' => Auth::id(),
                'glosa' => $data['glosa'] ?? null,
            ]);

            foreach ($preparedItems as $row) {
                $sale->saleItems()->create($row);
            }

            return $sale;
        });

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
        $venta->loadMissing('saleItems');
        $itemIds = $venta->saleItems->pluck('item_id')->filter()->unique()->values();

        $itemsQuery = Item::orderBy('name')
            ->select('id', 'name', 'price', 'use_once', 'category_id')
            ->with('category:id,name');
        $itemsQuery->where(function ($query) use ($itemIds) {
            $query->where('enabled', true);
            if ($itemIds->isNotEmpty()) {
                $query->orWhereIn('items.id', $itemIds->toArray());
            }
        });

        $items = $itemsQuery->get();
        return view('admin.ventas.edit', compact('venta', 'items'));
    }

    public function update(Request $request, Sale $venta)
    {
        $validator = Validator::make($request->all(), [
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'exists:items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:0'],
            'items.*.use_once_number' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', 'in:efectivo,qr'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'glosa' => ['nullable', 'string', 'max:2000'],
        ]);

        $data = $validator->validate();

        $this->ensureUseOnceNumbers($data['items']);
        $this->ensureUniqueUseOnceCodes($data['items'], $venta);
        $venta->loadMissing('saleItems');
        $this->ensureItemsEnabled($data['items']);
        $preparedItems = $this->prepareItems($data['items']);

        if ($preparedItems->isEmpty()) {
            throw ValidationException::withMessages([
                'items' => 'Debe seleccionar al menos un ítem con cantidad mayor a cero.',
            ]);
        }

        $totals = $this->calculateTotals($preparedItems, 'fixed', 0);
        $paidAmount = round((float) $data['paid_amount'], 2);

        DB::transaction(function () use ($venta, $preparedItems, $totals, $data, $paidAmount) {
            $venta->update([
                'subtotal' => $totals['subtotal'],
                'discount_type' => 'fixed',
                'discount_value' => 0,
                'payment_method' => $data['payment_method'],
                'paid_amount' => $paidAmount,
                'balance_due' => 0,
                'total' => $totals['total'],
                'customer_code' => $this->extractCustomerCode($preparedItems),
                'user_id' => Auth::id(),
                'glosa' => $data['glosa'] ?? null,
            ]);

            $venta->saleItems()->delete();

            foreach ($preparedItems as $row) {
                $venta->saleItems()->create($row);
            }
        });

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
        $venta->update(['status' => Sale::STATUS_ANNULLED]);

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
        return collect($items)->map(function ($row) {
            $item = Item::find($row['item_id']);
            if (!$item) {
                return null;
            }

            $quantity = max(0, (int) $row['quantity']);
            if ($quantity < 1) {
                return null;
            }

            $unitPrice = $item->price;

            $useOnceNumber = isset($row['use_once_number']) ? trim($row['use_once_number']) : null;

            return [
                'item_id' => $item->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total' => round($unitPrice * $quantity, 2),
                'use_once_number' => $useOnceNumber,
            ];
        })->filter();
    }

    private function extractCustomerCode($items): ?string
    {
        return collect($items)
            ->pluck('use_once_number')
            ->filter()
            ->first();
    }

    private function ensureUniqueUseOnceCodes(array $items, ?Sale $venta = null): void
    {
        $codes = [];

        foreach ($items as $index => $row) {
            $item = Item::find($row['item_id']);
            if (!$item) {
                continue;
            }

            $quantity = max(0, (int) ($row['quantity'] ?? 0));
            if ($quantity < 1) {
                continue;
            }

            $code = trim($row['use_once_number'] ?? '');
            if ($code === '') {
                continue;
            }

            if (in_array($code, $codes, true)) {
                throw ValidationException::withMessages([
                    'items.' . $index . '.use_once_number' => 'Este código ya se está utilizando en la venta actual.',
                ]);
            }

            $codes[] = $code;

            $query = SaleItem::where('item_id', $item->id)
                ->where('use_once_number', $code)
                ->whereHas('sale', function ($query) {
                    $query->where('status', Sale::STATUS_ACTIVE);
                });

            if ($venta) {
                $query->where('sale_id', '<>', $venta->id);
            }

            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'items.' . $index . '.use_once_number' => 'Ya se usó ese número en otra venta activa.',
                ]);
            }
        }
    }

    private function ensureItemsEnabled(array $items): void
    {
        $requestedIds = collect($items)
            ->filter(function ($row) {
                return isset($row['quantity']) && (int) $row['quantity'] > 0;
            })
            ->pluck('item_id')
            ->filter()
            ->unique();

        if ($requestedIds->isEmpty()) {
            return;
        }

        $invalidItems = Item::whereIn('id', $requestedIds)
            ->where('enabled', false)
            ->exists();

        if ($invalidItems) {
            throw ValidationException::withMessages([
                'items' => 'Solo se pueden seleccionar ítems habilitados.',
            ]);
        }

    }

    private function ensureUseOnceNumbers(array $items): void
    {
        foreach ($items as $index => $row) {
            $item = Item::find($row['item_id']);
            if (!$item) {
                continue;
            }

            $quantity = max(0, (int) ($row['quantity'] ?? 0));
            if (!$item->use_once) {
                continue;
            }
            if ($quantity > 0 && empty(trim($row['use_once_number'] ?? ''))) {
                throw ValidationException::withMessages([
                    'items.' . $index . '.use_once_number' => 'El número único es obligatorio para los ítems de uso único.',
                ]);
            }
        }
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

}
