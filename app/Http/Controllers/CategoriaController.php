<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoriaController extends Controller
{
    public function index()
    {
        return view('admin.categorias.index');
    }

    public function data()
    {
        $query = Category::with('parent')
            ->select([
                'id',
                'name',
                'slug',
                'parent_id',
                'description',
                'created_at',
            ]);

        return DataTables::of($query)
            ->addColumn('parent', function (Category $category) {
                return $category->parent?->name ?? 'Sin padre';
            })
            ->editColumn('description', function (Category $category) {
                if (empty($category->description)) {
                    return 'Sin descripción';
                }
                return Str::limit($category->description, 60);
            })
            ->editColumn('created_at', function (Category $category) {
                return $category->created_at->format('d/m/Y');
            })
            ->addColumn('acciones', function (Category $category) {
                return view('admin.categorias.partials.actions', compact('category'))->render();
            })
            ->rawColumns(['acciones'])
            ->make(true);
    }

    public function create()
    {
        $categorias = Category::orderBy('name')->get();
        return view('admin.categorias.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $data['slug'] = $this->buildSlug($data['slug'] ?? null, $data['name']);

        $category = Category::create($data);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Categoría creada correctamente.',
                'redirect' => route('categorias.index'),
            ], 201);
        }

        return redirect()
            ->route('categorias.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Category $categoria)
    {
        $categorias = Category::where('id', '<>', $categoria->id)
            ->orderBy('name')
            ->get();

        return view('admin.categorias.edit', compact('categoria', 'categorias'));
    }

    public function update(Request $request, Category $categoria)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        if (!empty($data['parent_id']) && (int)$data['parent_id'] === $categoria->id) {
            $data['parent_id'] = null;
        }

        $data['slug'] = $this->buildSlug($data['slug'] ?? null, $data['name'], $categoria->id);

        $categoria->update($data);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Categoría actualizada correctamente.',
                'redirect' => route('categorias.index'),
            ]);
        }

        return redirect()
            ->route('categorias.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $categoria)
    {
        $categoria->delete();

        return response()->json(['message' => 'Categoría eliminada correctamente.']);
    }

    private function buildSlug(?string $slug, string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($slug ?: $name);
        if (empty($baseSlug)) {
            $baseSlug = Str::slug($name) ?: 'categoria';
        }

        $slugCandidate = $baseSlug;
        $counter = 1;

        while (Category::where('slug', $slugCandidate)
            ->when($ignoreId, fn ($query) => $query->where('id', '<>', $ignoreId))
            ->exists()) {
            $slugCandidate = $baseSlug . '-' . $counter++;
        }

        return $slugCandidate;
    }
}
