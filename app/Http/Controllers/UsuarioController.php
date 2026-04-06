<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function index()
    {
        return view('admin.usuarios.index');
    }

    public function data()
    {
        $query = User::with('roles')->select([
            'id',
            'name',
            'email',
            'estado',
            'created_at'
        ]);

        return DataTables::of($query)

            ->addColumn('role', function ($user) {
                return $user->roles->first()?->name ?? 'Sin rol';
            })

            ->editColumn('estado', function ($user) {
                return $user->estado
                    ? '<span class="px-2 py-1 text-xs bg-emerald-100 text-emerald-600 rounded-full">Activo</span>'
                    : '<span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded-full">Inactivo</span>';
            })

            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('d/m/Y');
            })

            ->addColumn('acciones', function ($user) {
                return view('admin.usuarios.partials.actions', compact('user'))->render();
            })

            ->rawColumns(['estado', 'acciones'])
            ->make(true);
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role_label' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'estado' => ['required', 'boolean'],
        ]);


        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'estado' => $data['estado'],
        ]);

        $user->assignRole($data['role_label']);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Usuario creado correctamente.',
                'redirect' => route('usuarios.index')
            ], 201);
        }

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }


    public function update(Request $request, User $usuario)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $usuario->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_label' => ['required', 'string', 'exists:roles,name'],
            'estado' => ['required'],
        ]);

        $data['estado'] = filter_var($data['estado'], FILTER_VALIDATE_BOOLEAN);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $usuario->update($data);

        $usuario->syncRoles([$data['role_label']]);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Usuario actualizado correctamente.',
                'redirect' => route('usuarios.index')
            ], 200);
        }

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        if (Auth::check() && Auth::id() === $usuario->id) {
            return response()->json([
                'message' => 'No se puede eliminar el usuario que está actualmente conectado.'
            ], 403);
        }

        $usuario->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente.']);
    }
}
