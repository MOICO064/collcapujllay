@php
    $guardOptions = $guards ?? ['web'];
    $selectedGuard = old('guard_name', $permiso->guard_name ?? ($guardOptions[0] ?? 'web'));
    $buttonText = $buttonText ?? 'Guardar permiso';
@endphp

<div class="space-y-6">
    <div id="form-errors" class="hidden bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg">
        <p class="font-semibold mb-2">Corrige los siguientes campos:</p>
        <ul class="list-disc list-inside text-sm space-y-1"></ul>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-2">
            <label for="name" class="font-semibold text-slate-700">Nombre <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name"
                   value="{{ old('name', $permiso->name ?? '') }}"
                   oninput="this.value = this.value.toLowerCase()"
                   class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="field-error text-sm text-red-600 mt-1" id="name-error"></p>
        </div>

        <div class="space-y-2">
            <label for="guard_name" class="font-semibold text-slate-700">Guard <span class="text-red-500">*</span></label>
            <select name="guard_name" id="guard_name"
                    class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach($guardOptions as $guard)
                    <option value="{{ $guard }}" {{ $selectedGuard === $guard ? 'selected' : '' }}>{{ ucfirst($guard) }}</option>
                @endforeach
            </select>
            <p class="field-error text-sm text-red-600 mt-1" id="guard_name-error"></p>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pt-4 border-t border-slate-200">
        <a href="{{ route('permisos.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
            <i class="fas fa-arrow-left"></i>
            Volver
        </a>

        <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
            <i class="fas fa-save"></i>
            {{ $buttonText }}
        </button>
    </div>
</div>
