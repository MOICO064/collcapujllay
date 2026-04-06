@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <i class="fas fa-user-plus text-green-600"></i>
                    Crear Usuario
                </h1>
                <p class="text-sm text-slate-500 mt-1">Agrega un nuevo usuario al sistema.</p>
            </div>
        </div>

        <form id="usuario-form" data-url="{{ route('usuarios.store') }}" data-method="POST" method="POST">
            @csrf
            @php $buttonText = 'Guardar usuario'; @endphp
            @include('admin.usuarios.form')
        </form>

        @push('scripts')
            <script src="{{ asset('js/admin/usuarios/form.js') }}"></script>
        @endpush
    </div>
</div>
@endsection
