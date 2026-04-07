@php
    $promotionList = $promotions ?? collect();
    $selectedPromotionId = old('promotion_id', $venta->promotion_id ?? '');
@endphp

<div class="space-y-6">
    <div id="form-errors" class="hidden bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg">
        <p class="font-semibold mb-2">Corrige los siguientes campos:</p>
        <ul class="list-disc list-inside text-sm space-y-1"></ul>
    </div>

    <div class="space-y-2">
        <p class="font-semibold text-slate-700">Fecha y hora de venta</p>
        <p class="text-sm text-slate-600">
            {{ isset($venta) ? $venta->sale_date?->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}
            <span class="text-xs text-slate-500 ml-2">Se asigna automáticamente al guardar.</span>
        </p>
    </div>

    <div class="space-y-2">
        <label for="promotion_id" class="font-semibold text-slate-700">Promoción disponible</label>
        <select name="promotion_id" id="promotion_id" class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Sin promoción</option>
            @foreach($promotionList as $promotion)
            <option value="{{ $promotion->id }}" {{ (string) $selectedPromotionId === (string) $promotion->id ? 'selected' : '' }}>
                {{ $promotion->name }} · {{ $promotion->discount_type === 'percentage' ? $promotion->discount_value . ' %' : 'Bs ' . number_format($promotion->discount_value, 2, ',', '.') }}
            </option>
            @endforeach
        </select>
        <p class="text-sm text-slate-500 mt-1" id="promotion-description"></p>
        <p class="field-error text-sm text-red-600 mt-1" id="promotion_id-error"></p>
    </div>

    <div id="ci-field" class="space-y-2 hidden">
        <label for="customer-ci" class="font-semibold text-slate-700">Cédula del cliente</label>
        <input type="text" name="ci" id="customer-ci" value="{{ old('ci', $venta->customer_ci ?? '') }}" class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <p class="text-sm text-slate-500 mt-1">Se registrará para que la promoción de uso único no pueda usarse nuevamente.</p>
        <p class="field-error text-sm text-red-600 mt-1" id="ci-error"></p>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-700">Ítems vendidos</h2>
            <button type="button" id="add-item-btn" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-500 transition">
                <i class="fas fa-plus"></i>
                Agregar ítem
            </button>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-500">
                    <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                        <tr>
                            <th class="px-3 py-2">Ítem</th>
                            <th class="px-3 py-2">Precio unitario</th>
                            <th class="px-3 py-2">Cantidad</th>
                            <th class="px-3 py-2">Total</th>
                            <th class="px-3 py-2"></th>
                        </tr>
                    </thead>
                    <tbody id="venta-items-body" class="divide-y divide-slate-200">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-slate-50 border border-slate-200 rounded-lg px-4 py-4 shadow-sm grid gap-4 md:grid-cols-3">
        <div>
            <p class="text-xs text-slate-500">Subtotal</p>
            <p class="text-xl font-semibold text-slate-800" id="subtotal-display">Bs 0.00</p>
        </div>
        <div>
            <p class="text-xs text-slate-500">Descuento aplicado</p>
            <p class="text-xl font-semibold text-slate-800" id="discount-display">Bs 0.00</p>
        </div>
        <div>
            <p class="text-xs text-slate-500">Total a pagar</p>
            <p class="text-2xl font-bold text-emerald-600" id="total-display">Bs 0.00</p>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pt-4 border-t border-slate-200">
        <a href="{{ route('ventas.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
            <i class="fas fa-arrow-left"></i>
            Volver
        </a>

        <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
            <i class="fas fa-save"></i>
            {{ $buttonText ?? 'Guardar venta' }}
        </button>
    </div>
</div>
