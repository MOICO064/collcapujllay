@php
    $selectedPaymentMethod = old('payment_method', $venta->payment_method ?? 'efectivo');
    $paidAmountValue = old('paid_amount', $venta->paid_amount ?? '');
@endphp

<div class="space-y-6">
    <div id="form-errors" class="hidden bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg">
        <p class="font-semibold mb-2">Corrige los siguientes campos:</p>
        <ul class="list-disc list-inside text-sm space-y-1"></ul>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200">
            <div>
                <h2 class="text-lg font-semibold text-slate-700">Ítems</h2>
                <p class="text-sm text-slate-500">Selecciona los ítems de uso único y registra las cantidades necesarias.</p>
            </div>
        </div>
        <div class="space-y-6 px-4 py-4" id="venta-items-body"></div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm space-y-6 px-4 py-6">
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="space-y-2">
                <label for="payment_method" class="font-semibold text-slate-700">Método de pago</label>
                <select name="payment_method" id="payment_method" class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="efectivo" {{ $selectedPaymentMethod === 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                    <option value="qr" {{ $selectedPaymentMethod === 'qr' ? 'selected' : '' }}>QR</option>
                </select>
                <p class="text-sm text-slate-500 mt-1">Selecciona cómo se realiza el cobro.</p>
                <p class="field-error text-sm text-red-600 mt-1" id="payment_method-error"></p>
            </div>

            <div class="space-y-2">
                <label for="paid_amount" class="font-semibold text-slate-700">Monto pagado</label>
                <input type="number" step="0.01" min="0" name="paid_amount" id="paid_amount" value="{{ $paidAmountValue }}" class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-slate-500 mt-1">Ingresa el monto recibido para calcular cambio y saldo pendiente.</p>
                <p class="field-error text-sm text-red-600 mt-1" id="paid_amount-error"></p>
            </div>
        </div>

        <div class="space-y-2">
            <label for="glosa" class="font-semibold text-slate-700">Glosa (opcional)</label>
            <textarea name="glosa" id="glosa" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('glosa', $venta->glosa ?? '') }}</textarea>
            <p class="text-sm text-slate-500 mt-1">Agrega una nota breve o referencia para esta venta.</p>
            <p class="field-error text-sm text-red-600 mt-1" id="glosa-error"></p>
        </div>
    </div>

    <div class="bg-slate-50 border border-slate-200 rounded-lg px-4 py-4 shadow-sm grid gap-4 md:grid-cols-3">
        <div>
            <p class="text-xs text-slate-500">Total</p>
            <p class="text-2xl font-bold text-emerald-600" id="total-display">Bs 0.00</p>
        </div>
        <div>
            <p class="text-xs text-slate-500">Saldo pendiente</p>
            <p class="text-2xl font-bold text-orange-600" id="balance-due-display">Bs 0.00</p>
        </div>
        <div>
            <p class="text-xs text-slate-500">Cambio</p>
            <p class="text-2xl font-bold text-slate-900" id="change-display">Bs 0.00</p>
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
