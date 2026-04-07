<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ItemController extends Controller
{
    public function index()
    {
        return view('admin.items.index');
    }

    public function data()
    {
        $query = Item::with('category')
            ->select([
                'id',
                'name',
                'category_id',
                'price',
                'description',
                'created_at',
            ]);

        return DataTables::of($query)
            ->addColumn('category', function (Item $item) {
                return $item->category?->name ?? 'Sin categoría';
            })
            ->editColumn('price', function (Item $item) {
                return number_format($item->price, 2, ',', '.');
            })
            ->editColumn('description', function (Item $item) {
                return Str::limit($item->description ?? 'Sin descripción', 60);
            })
            ->editColumn('created_at', function (Item $item) {
                return $item->created_at?->format('d/m/Y') ?? '';
            })
            ->addColumn('acciones', function (Item $item) {
                return view('admin.items.partials.actions', compact('item'))->render();
            })
            ->rawColumns(['acciones'])
            ->make(true);
    }

    public function create()
    {
        $categorias = Category::orderBy('name')->get();
        return view('admin.items.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $item = Item::create($data);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Item creado correctamente.',
                'redirect' => route('items.index'),
            ], 201);
        }

        return redirect()
            ->route('items.index')
            ->with('success', 'Item creado correctamente.');
    }

    public function edit(Item $item)
    {
        $categorias = Category::orderBy('name')->get();
        return view('admin.items.edit', compact('item', 'categorias'));
    }

    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $item->update($data);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Item actualizado correctamente.',
                'redirect' => route('items.index'),
            ]);
        }

        return redirect()
            ->route('items.index')
            ->with('success', 'Item actualizado correctamente.');
    }

    public function destroy(Request $request, Item $item)
    {
        $item->delete();

        $response = ['message' => 'Item eliminado correctamente.'];

        if ($request->expectsJson()) {
            return response()->json($response);
        }

        return redirect()
            ->route('items.index')
            ->with('success', $response['message']);
    }
}
