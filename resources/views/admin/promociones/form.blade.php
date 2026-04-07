<div class="space-y-6">
    <div id="form-errors" class="hidden bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg">
        <p class="font-semibold mb-2">Corrige los siguientes campos:</p>
        <ul class="list-disc list-inside text-sm space-y-1"></ul>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-2">
            <label for="name" class="font-semibold text-slate-700">Nombre <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', isset($promotion) ? $promotion->name : '') }}" class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="field-error text-sm text-red-600 mt-1" id="name-error"></p>
        </div>

        <div class="space-y-2">
            <label for="discount_type" class="font-semibold text-slate-700">Tipo de descuento <span class="text-red-500">*</span></label>
            @php
                $discountType = old('discount_type', isset($promotion) ? $promotion->discount_type : 'percentage');
            @endphp
            <select name="discount_type" id="discount_type" class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach($discountTypes as $key => $label)
                <option value="{{ $key }}" {{ $discountType === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <p class="field-error text-sm text-red-600 mt-1" id="discount_type-error"></p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-2">
            <label for="discount_value" class="font-semibold text-slate-700">Valor del descuento <span class="text-red-500">*</span></label>
            <input type="number" min="0" step="0.01" name="discount_value" id="discount_value" value="{{ old('discount_value', isset($promotion) ? $promotion->discount_value : '') }}" class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="field-error text-sm text-red-600 mt-1" id="discount_value-error"></p>
        </div>

        <div class="space-y-2">
            <label for="single_use" class="font-semibold text-slate-700">Uso único</label>
            @php
                $singleUseChecked = old('single_use', isset($promotion) ? $promotion->single_use : false);
            @endphp
            <div class="flex items-center gap-3">
                <input type="checkbox" name="single_use" id="single_use" value="1" class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" {{ $singleUseChecked ? 'checked' : '' }}>
                <span class="text-sm text-slate-600">Limita la promoción a un único uso por cliente.</span>
            </div>
            <p class="field-error text-sm text-red-600 mt-1" id="single_use-error"></p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-2">
            <label for="start_date" class="font-semibold text-slate-700">Fecha inicio <span class="text-red-500">*</span></label>
            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', isset($promotion) ? $promotion->start_date?->toDateString() : '') }}" class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="field-error text-sm text-red-600 mt-1" id="start_date-error"></p>
        </div>

        <div class="space-y-2">
            <label for="end_date" class="font-semibold text-slate-700">Fecha fin <span class="text-red-500">*</span></label>
            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', isset($promotion) ? $promotion->end_date?->toDateString() : '') }}" class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="field-error text-sm text-red-600 mt-1" id="end_date-error"></p>
        </div>
    </div>

    <div class="space-y-2">
        <label for="description" class="font-semibold text-slate-700">Descripción</label>
        <textarea name="description" id="description" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', isset($promotion) ? $promotion->description : '') }}</textarea>
        <p class="field-error text-sm text-red-600 mt-1" id="description-error"></p>
    </div>

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pt-4 border-t border-slate-200">
        <a href="{{ route('promociones.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
            <i class="fas fa-arrow-left"></i>
            Volver
        </a>

        <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
            <i class="fas fa-save"></i>
            {{ $buttonText }}
        </button>
    </div>
</div>
