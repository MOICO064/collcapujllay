@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <i class="fas fa-edit text-green-600"></i>
                    Editar promoción
                </h1>
                <p class="text-sm text-slate-500 mt-1">Actualiza los detalles de la promoción existente.</p>
            </div>
        </div>

        <form id="promocion-form" data-url="{{ route('promociones.update', $promotion) }}" data-method="PUT">
            @csrf
            @method('PUT')
            @php $buttonText = 'Actualizar promoción'; @endphp
            @include('admin.promociones.form')
        </form>

        @push('scripts')
            <script src="{{ asset('js/admin/promociones/form.js') }}"></script>
        @endpush
    </div>
</div>
@endsection
