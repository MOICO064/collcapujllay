@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <i class="fas fa-user-shield text-blue-600"></i>
                    Permisos del usuario
                </h1>
                <p class="text-sm text-slate-500 mt-1">Revisa los roles y permisos asignados a este usuario.</p>
            </div>
            <a href="{{ route('usuarios.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
                <i class="fas fa-arrow-left"></i>
                Volver a usuarios
            </a>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="bg-slate-50 border border-slate-200 rounded-lg p-5">
                <h2 class="text-lg font-semibold mb-3">Información</h2>
                <p class="text-sm text-slate-600"><span class="font-semibold">Nombre:</span> {{ $usuario->name }}</p>
                <p class="text-sm text-slate-600"><span class="font-semibold">Email:</span> {{ $usuario->email }}</p>
                <p class="text-sm text-slate-600"><span class="font-semibold">Estado:</span>
                    @if($usuario->estado)
                        <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">Activo</span>
                    @else
                        <span class="px-2 py-1 rounded-full bg-red-100 text-red-700">Inactivo</span>
                    @endif
                </p>
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-lg p-5">
                <h2 class="text-lg font-semibold mb-3">Roles</h2>
                @php $roles = $usuario->getRoleNames(); @endphp
                @if($roles->isNotEmpty())
                    <ul class="space-y-2">
                        @foreach($roles as $role)
                            <li class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-700">
                                <i class="fas fa-user-tag text-slate-500"></i>
                                {{ $role }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-slate-600">No tiene roles asignados.</p>
                @endif
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-lg p-5">
                <h2 class="text-lg font-semibold mb-3">Permisos directos</h2>
                @php $directPermissions = $usuario->getDirectPermissions(); @endphp
                @if($directPermissions->isNotEmpty())
                    <ul class="space-y-2">
                        @foreach($directPermissions as $permission)
                            <li class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-700">
                                <i class="fas fa-check text-emerald-500"></i>
                                {{ $permission->name }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-slate-600">No tiene permisos directos.</p>
                @endif
            </div>
        </div>

        <div class="mt-6 bg-slate-50 border border-slate-200 rounded-lg p-5">
            <h2 class="text-lg font-semibold mb-3">Permisos totales</h2>
            @php $allPermissions = $usuario->getAllPermissions(); @endphp
            @if($allPermissions->isNotEmpty())
                <ul class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($allPermissions as $permission)
                        <li class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-700">
                            <i class="fas fa-check-circle text-blue-500"></i>
                            {{ $permission->name }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-slate-600">No tiene permisos asignados.</p>
            @endif
        </div>
    </div>
</div>
@endsection