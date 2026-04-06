<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Permission;

class PermisoController extends Controller
{
    public function index()
    {
        return view('admin.permisos.index');
    }

    public function data()
    {
        $query = Permission::with('roles');

        return DataTables::of($query)
            ->addColumn('roles', function ($permiso) {
               
                return $permiso->roles->map(function ($role) {
                    return '<span class="inline-block bg-emerald-100 text-emerald-800 text-xs font-semibold mr-1 px-2.5 py-0.5 rounded">'
                        . e($role->name)
                        . '</span>';
                })->implode(''); 
            })
            ->editColumn('created_at', function ($permiso) {
                return $permiso->created_at ? $permiso->created_at->format('d/m/Y') : '';
            })
            ->addColumn('acciones', function ($permiso) {
                return view('admin.permisos.partials.actions', compact('permiso'))->render();
            })
            ->rawColumns(['acciones', 'roles']) 
            ->make(true);
    }

    public function create()
    {
        $guards = $this->availableGuards();
        return view('admin.permisos.create', compact('guards'));
    }

    public function store(Request $request)
    {
        $guards = $this->availableGuards();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')],
            'guard_name' => ['required', 'string', Rule::in($guards)],
        ]);

        $permission = Permission::create([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'],
        ]);

        $response = [
            'message' => 'Permiso creado correctamente.',
            'redirect' => route('permisos.index'),
        ];

        if ($request->wantsJson()) {
            return response()->json($response, 201);
        }

        return redirect()->route('permisos.index')->with('success', $response['message']);
    }

    public function edit(Permission $permiso)
    {
        $guards = $this->availableGuards();
        return view('admin.permisos.edit', compact('permiso', 'guards'));
    }

    public function update(Request $request, Permission $permiso)
    {
        $guards = $this->availableGuards();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($permiso->id)],
            'guard_name' => ['required', 'string', Rule::in($guards)],
        ]);

        $permiso->update([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'],
        ]);

        $response = [
            'message' => 'Permiso actualizado correctamente.',
            'redirect' => route('permisos.index'),
        ];

        if ($request->wantsJson()) {
            return response()->json($response);
        }

        return redirect()->route('permisos.index')->with('success', $response['message']);
    }

    public function destroy(Request $request, Permission $permiso)
    {
        $permiso->delete();

        $response = ['message' => 'Permiso eliminado correctamente.'];

        if ($request->expectsJson()) {
            return response()->json($response);
        }

        return redirect()->route('permisos.index')->with('success', $response['message']);
    }

    private function availableGuards(): array
    {
        $guards = array_keys(config('auth.guards', []));
        return empty($guards) ? ['web'] : $guards;
    }
}
