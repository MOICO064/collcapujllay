<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        return view('admin.roles.index');
    }

    public function data()
    {
        $query = Role::with('permissions')->withCount('permissions');

        return DataTables::of($query)
            ->addColumn('permisos', function ($role) {
                $count = $role->permissions_count;

                $names = $role->permissions->pluck('name')->implode(', ');
                return $role->permissions->map(function ($perm) {
                    return '<span class="inline-block bg-emerald-100 text-emerald-800 text-xs font-semibold mr-1 px-2.5 py-0.5 rounded">'
                        . e($perm->name)
                        . '</span>';
                })->implode('');
            })
            ->editColumn('created_at', function ($role) {
                return $role->created_at ? $role->created_at->format('d/m/Y') : '';
            })
            ->addColumn('acciones', function ($role) {
                return view('admin.roles.partials.actions', compact('role'))->render();
            })
            ->rawColumns(['acciones', 'permisos']) // 🔹 permitimos HTML en permisos
            ->make(true);
    }

    public function create()
    {
        $guards = $this->availableGuards();
        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.create', compact('guards', 'permissions'));
    }

    public function store(Request $request)
    {
        $guards = $this->availableGuards();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')],
            'guard_name' => ['required', 'string', Rule::in($guards)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        Log::info('RoleController@store payload', [
            'user_id' => $request->user()?->id,
            'data' => $data,
        ]);

        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'],
        ]);

        $permissions = Permission::whereIn('id', $data['permissions'] ?? [])->pluck('name');
        $role->syncPermissions($permissions);

        $response = [
            'message' => 'Rol creado correctamente.',
            'redirect' => route('roles.index'),
        ];

        return response()->json($response, 201);
    }

    public function edit(Role $role)
    {
        $guards = $this->availableGuards();
        $permissions = Permission::orderBy('name')->get();
        $role->load('permissions');

        return view('admin.roles.edit', compact('role', 'guards', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $guards = $this->availableGuards();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
            'guard_name' => ['required', 'string', Rule::in($guards)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        Log::info('RoleController@update payload', [
            'user_id' => $request->user()?->id,
            'role_id' => $role->id,
            'data' => $data,
        ]);

        $role->update([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'],
        ]);

        // 🔹 Convertir IDs a nombres antes de syncPermissions
        $permissions = Permission::whereIn('id', $data['permissions'] ?? [])->pluck('name');
        $role->syncPermissions($permissions);

        return response()->json([
            'message' => 'Rol actualizado correctamente.',
            'redirect' => route('roles.index'),
        ]);
    }

    public function destroy(Request $request, Role $role)
    {
        $role->delete();

        $response = ['message' => 'Rol eliminado correctamente.'];

        if ($request->expectsJson()) {
            return response()->json($response);
        }

        return redirect()->route('roles.index')->with('success', $response['message']);
    }

    private function availableGuards(): array
    {
        $guards = array_keys(config('auth.guards', []));

        return empty($guards) ? ['web'] : $guards;
    }
}
