<div class="space-y-6">
    <div id="form-errors" class="hidden bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg">
        <p class="font-semibold mb-2">Corrige los siguientes campos:</p>
        <ul class="list-disc list-inside text-sm space-y-1"></ul>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-2">
            <label for="name" class="font-semibold text-slate-700">Nombre <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', isset($item) ? $item->name : '') }}" class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="field-error text-sm text-red-600 mt-1" id="name-error"></p>
        </div>

        <div class="space-y-2">
            <label for="category_id" class="font-semibold text-slate-700">Categoría <span class="text-red-500">*</span></label>
            @php
                $selectedCategory = old('category_id', isset($item) ? $item->category_id : '');
            @endphp
            <select name="category_id" id="category_id" class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Selecciona una categoría</option>
                @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}" {{ $selectedCategory == $categoria->id ? 'selected' : '' }}>
                    {{ $categoria->name }}
                </option>
                @endforeach
            </select>
            <p class="field-error text-sm text-red-600 mt-1" id="category_id-error"></p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-2">
            <label for="price" class="font-semibold text-slate-700">Precio <span class="text-red-500">*</span></label>
            <input type="number" step="0.01" min="0" name="price" id="price" value="{{ old('price', isset($item) ? $item->price : '') }}" class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="field-error text-sm text-red-600 mt-1" id="price-error"></p>
        </div>

        <div class="space-y-2">
            <label for="description" class="font-semibold text-slate-700">Descripción</label>
            <textarea name="description" id="description" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', isset($item) ? $item->description : '') }}</textarea>
            <p class="field-error text-sm text-red-600 mt-1" id="description-error"></p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-2">
            @php
                $useOnceValue = (string) old('use_once', isset($item) ? (int) $item->use_once : 1);
            @endphp
            <p class="font-semibold text-slate-700">Uso único</p>
            <div class="flex flex-wrap gap-4">
                <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input type="radio" name="use_once" value="1" class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-slate-300" {{ $useOnceValue === '1' ? 'checked' : '' }}>
                    Sí
                </label>
                <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input type="radio" name="use_once" value="0" class="h-4 w-4 text-slate-600 focus:ring-slate-500 border-slate-300" {{ $useOnceValue === '0' ? 'checked' : '' }}>
                    No
                </label>
            </div>
            <p class="text-xs text-slate-500 mt-1">Solo los ítems marcados como uso único estarán disponibles en ventas.</p>
            <p class="field-error text-sm text-red-600 mt-1" id="use_once-error"></p>
        </div>

        <div class="space-y-2">
            @php
                $enabledDefault = isset($item) ? (int) $item->enabled : 1;
                $enabledValue = (string) old('enabled', $enabledDefault);
            @endphp
            <p class="font-semibold text-slate-700">Estado</p>
            <div class="flex flex-wrap gap-4">
                <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input type="radio" name="enabled" value="1" id="enabled-true" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300" {{ $enabledValue === '1' ? 'checked' : '' }}>
                    Habilitado
                </label>
                <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input type="radio" name="enabled" value="0" id="enabled-false" class="h-4 w-4 text-rose-600 focus:ring-rose-500 border-slate-300" {{ $enabledValue === '0' ? 'checked' : '' }}>
                    Inhabilitado
                </label>
            </div>
            <p class="field-error text-sm text-red-600 mt-1" id="enabled-error"></p>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pt-4 border-t border-slate-200">
        <a href="{{ route('items.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
            <i class="fas fa-arrow-left"></i>
            Volver
        </a>

        <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
            <i class="fas fa-save"></i>
            {{ $buttonText }}
        </button>
    </div>
</div>
