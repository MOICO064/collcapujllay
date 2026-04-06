@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <i class="fas fa-shield-alt text-yellow-600"></i>
                    Editar rol
                </h1>
                <p class="text-sm text-slate-500 mt-1">Actualiza el nombre y permisos asociados al rol <strong>{{ $role->name }}</strong>.</p>
            </div>
        </div>

        <form id="roles-form" data-url="{{ route('roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')
            @php $buttonText = 'Actualizar rol'; @endphp
            @include('admin.roles.form')
        </form>

        @push('scripts')
            <script src="{{ asset('js/admin/roles/form.js') }}"></script>
        @endpush
    </div>
</div>
@endsection
