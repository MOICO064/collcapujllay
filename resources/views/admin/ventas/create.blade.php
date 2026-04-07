@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <i class="fas fa-plus text-green-600"></i>
                    Registrar venta
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Crea una nueva venta agregando los ítems correspondientes.
                </p>
            </div>
        </div>

        @php
            $saleItemsData = old('items', []);
            $itemsData = $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => (float) $item->price,
                ];
            });

            $promotionsData = $promotions->map(function ($promotion) {
                return [
                    'id' => $promotion->id,
                    'name' => $promotion->name,
                    'description' => $promotion->description,
                    'discount_type' => $promotion->discount_type,
                    'discount_value' => (float) $promotion->discount_value,
                    'single_use' => $promotion->single_use,
                ];
            });
        @endphp

        <form id="venta-form"
            data-url="{{ route('ventas.store') }}"
            data-method="POST"
            data-items='@json($itemsData)'
            data-sale-items='@json($saleItemsData)'
            data-promotions='@json($promotionsData)'
            data-selected-promotion="{{ old('promotion_id', '') }}">
            @csrf

            @php
            $buttonText = 'Guardar venta';
            @endphp

            @include('admin.ventas.form', [
            'promotions' => $promotions
            ])
        </form>

        @push('scripts')
        <script src="{{ asset('js/admin/ventas/form.js') }}"></script>
        @endpush
    </div>
</div>
@endsection
