@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8">

    <h1 class="text-3xl font-bold mb-6">Dashboard</h1>

    <!-- Tarjetas de estadísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-500 text-white rounded-lg shadow p-5">
            <h5 class="text-lg font-semibold">Usuarios</h5>
            <p class="text-3xl font-bold">120</p>
        </div>
        <div class="bg-green-500 text-white rounded-lg shadow p-5">
            <h5 class="text-lg font-semibold">Ventas</h5>
            <p class="text-3xl font-bold">75</p>
        </div>
        <div class="bg-yellow-400 text-white rounded-lg shadow p-5">
            <h5 class="text-lg font-semibold">Pedidos Pendientes</h5>
            <p class="text-3xl font-bold">8</p>
        </div>
        <div class="bg-red-500 text-white rounded-lg shadow p-5">
            <h5 class="text-lg font-semibold">Alertas</h5>
            <p class="text-3xl font-bold">3</p>
        </div>
    </div>

    <!-- Estadísticas gráficas simples -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Estadísticas de Ventas (Últimos 7 días)</h2>
        <div class="space-y-2">
            <div class="flex items-center">
                <span class="w-24 text-gray-700">Lunes</span>
                <div class="bg-blue-400 h-6 rounded" style="width: 40%"></div>
                <span class="ml-2 text-gray-600">40</span>
            </div>
            <div class="flex items-center">
                <span class="w-24 text-gray-700">Martes</span>
                <div class="bg-blue-400 h-6 rounded" style="width: 60%"></div>
                <span class="ml-2 text-gray-600">60</span>
            </div>
            <div class="flex items-center">
                <span class="w-24 text-gray-700">Miércoles</span>
                <div class="bg-blue-400 h-6 rounded" style="width: 50%"></div>
                <span class="ml-2 text-gray-600">50</span>
            </div>
            <div class="flex items-center">
                <span class="w-24 text-gray-700">Jueves</span>
                <div class="bg-blue-400 h-6 rounded" style="width: 70%"></div>
                <span class="ml-2 text-gray-600">70</span>
            </div>
            <div class="flex items-center">
                <span class="w-24 text-gray-700">Viernes</span>
                <div class="bg-blue-400 h-6 rounded" style="width: 30%"></div>
                <span class="ml-2 text-gray-600">30</span>
            </div>
        </div>
    </div>

    <!-- Tabla de usuarios -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Últimos usuarios registrados</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registrado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">1</td>
                        <td class="px-6 py-4 whitespace-nowrap">Juan Pérez</td>
                        <td class="px-6 py-4 whitespace-nowrap">juan@example.com</td>
                        <td class="px-6 py-4 whitespace-nowrap">2026-04-01</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button class="bg-blue-500 text-white px-3 py-1 rounded mr-2">Ver</button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded">Eliminar</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">2</td>
                        <td class="px-6 py-4 whitespace-nowrap">María López</td>
                        <td class="px-6 py-4 whitespace-nowrap">maria@example.com</td>
                        <td class="px-6 py-4 whitespace-nowrap">2026-03-28</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button class="bg-blue-500 text-white px-3 py-1 rounded mr-2">Ver</button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded">Eliminar</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">3</td>
                        <td class="px-6 py-4 whitespace-nowrap">Carlos Gómez</td>
                        <td class="px-6 py-4 whitespace-nowrap">carlos@example.com</td>
                        <td class="px-6 py-4 whitespace-nowrap">2026-03-25</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button class="bg-blue-500 text-white px-3 py-1 rounded mr-2">Ver</button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection