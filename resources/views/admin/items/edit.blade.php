@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <i class="fas fa-edit text-green-600"></i>
                    Editar item
                </h1>
                <p class="text-sm text-slate-500 mt-1">Actualiza la información del producto.</p>
            </div>
        </div>

        <form id="item-form" data-url="{{ route('items.update', $item) }}" data-method="PUT">
            @csrf
            @method('PUT')
            @php $buttonText = 'Actualizar item'; @endphp
            @include('admin.items.form')
        </form>

        @push('scripts')
            <script src="{{ asset('js/admin/items/form.js') }}"></script>
        @endpush
    </div>
</div>
@endsection
