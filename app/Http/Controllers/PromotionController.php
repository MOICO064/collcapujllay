<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class PromotionController extends Controller
{
    private const DISCOUNT_TYPES = [
        'percentage' => 'Porcentaje',
        'fixed' => 'Monto fijo',
    ];

    public function index()
    {
        return view('admin.promociones.index');
    }

    public function data()
    {
        $query = Promotion::select([
            'id',
            'name',
            'description',
            'start_date',
            'end_date',
            'single_use',
            'discount_type',
            'discount_value',
            'created_at',
        ]);

        return DataTables::of($query)
            ->addColumn('periodo', function (Promotion $promotion) {
                $start = $promotion->start_date ? $promotion->start_date->format('d/m/Y') : '';
                $end = $promotion->end_date ? $promotion->end_date->format('d/m/Y') : '';
                return trim("{$start} - {$end}", ' -');
            })
            ->addColumn('discount', function (Promotion $promotion) {
                $value = number_format($promotion->discount_value, 2, ',', '.');
                if ($promotion->discount_type === 'percentage') {
                    return "{$value} %";
                }
                return "{$value} Bs.";
            })
            ->addColumn('single_use', function (Promotion $promotion) {
                return $promotion->single_use
                    ? '<span class="px-2 py-1 text-xs bg-emerald-100 text-emerald-600 rounded-full">Uso único</span>'
                    : '<span class="px-2 py-1 text-xs bg-slate-100 text-slate-600 rounded-full">Uso múltiple</span>';
            })
            ->editColumn('created_at', function (Promotion $promotion) {
                return $promotion->created_at ? $promotion->created_at->format('d/m/Y') : '';
            })
            ->addColumn('acciones', function (Promotion $promotion) {
                return view('admin.promociones.partials.actions', compact('promotion'))->render();
            })
            ->rawColumns(['acciones', 'single_use'])
            ->make(true);
    }

    public function create()
    {
        $discountTypes = self::DISCOUNT_TYPES;
        return view('admin.promociones.create', compact('discountTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'single_use' => ['nullable', 'boolean'],
            'discount_type' => ['required', 'string', Rule::in(array_keys(self::DISCOUNT_TYPES))],
            'discount_value' => ['required', 'numeric', 'min:0'],
        ]);

        $data['single_use'] = $request->boolean('single_use');

        $promotion = Promotion::create($data);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Promoción creada correctamente.',
                'redirect' => route('promociones.index'),
            ], 201);
        }

        return redirect()
            ->route('promociones.index')
            ->with('success', 'Promoción creada correctamente.');
    }

    public function edit(Promotion $promotion)
    {
        $discountTypes = self::DISCOUNT_TYPES;
        return view('admin.promociones.edit', compact('promotion', 'discountTypes'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'single_use' => ['nullable', 'boolean'],
            'discount_type' => ['required', 'string', Rule::in(array_keys(self::DISCOUNT_TYPES))],
            'discount_value' => ['required', 'numeric', 'min:0'],
        ]);

        $data['single_use'] = $request->boolean('single_use');

        $promotion->update($data);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Promoción actualizada correctamente.',
                'redirect' => route('promociones.index'),
            ]);
        }

        return redirect()
            ->route('promociones.index')
            ->with('success', 'Promoción actualizada correctamente.');
    }

    public function destroy(Request $request, Promotion $promotion)
    {
        $promotion->delete();

        $response = ['message' => 'Promoción eliminada correctamente.'];

        if ($request->expectsJson()) {
            return response()->json($response);
        }

        return redirect()
            ->route('promociones.index')
            ->with('success', $response['message']);
    }
}
