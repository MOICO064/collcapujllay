@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">Roles</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Card -->
    <div class="bg-white shadow-lg rounded-lg p-6">
        <!-- Encabezado -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <h1 class="text-2xl font-bold flex items-center mb-4 sm:mb-0">
                <i class="fas fa-shield-alt mr-2 text-green-600"></i>
                Roles
            </h1>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                <button id="reload-roles-table" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center justify-center">
                    <i class="fas fa-sync-alt mr-2"></i>
                    <span id="reload-roles-text">Recargar</span>
                    <i id="reload-roles-spinner" class="fas fa-spinner fa-spin ml-2 hidden"></i>
                </button>
                <a href="{{ route('roles.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Nuevo Rol
                </a>
            </div>
        </div>

        <!-- Tabla -->
        <table id="roles-table" class="display responsive w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th>Nombre</th>
                    <th>Guard</th>
                    <th>Permisos</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/roles/index.js') }}"></script>
@endpush
