@php
    $guardOptions = $guards ?? ['web'];
    $selectedGuard = old('guard_name', $role->guard_name ?? ($guardOptions[0] ?? 'web'));
    $selectedPermissions = old('permissions', isset($role) ? $role->permissions->pluck('id')->toArray() : []);
    if (!is_array($selectedPermissions)) {
        $selectedPermissions = [];
    }
    $buttonText = $buttonText ?? 'Guardar rol';
@endphp

<div class="space-y-6">
    <div id="form-errors" class="hidden bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg">
        <p class="font-semibold mb-2">Corrige los siguientes campos:</p>
        <ul class="list-disc list-inside text-sm space-y-1"></ul>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-2">
            <label for="name" class="font-semibold text-slate-700">Nombre del rol <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name"
                   value="{{ old('name', $role->name ?? '') }}"
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

    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <label class="font-semibold text-slate-700">Permisos</label>
            <span class="text-xs text-slate-500">Selecciona los permisos asociados a este rol.</span>
        </div>
        @if($permissions->isNotEmpty())
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 max-h-64 overflow-y-auto border border-slate-100 rounded-lg p-3 bg-slate-50">
                @foreach($permissions as $permission)
                    <label class="flex items-center gap-2 bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 cursor-pointer transition hover:border-emerald-300">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                               class="h-4 w-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-600"
                               {{ in_array($permission->id, $selectedPermissions) ? 'checked' : '' }}>
                        <span class="truncate">{{ ucwords(str_replace('_', ' ', $permission->name)) }}</span>
                    </label>
                @endforeach
            </div>
        @else
            <p class="text-sm text-slate-600">No hay permisos registrados en el sistema.</p>
        @endif
        <p class="field-error text-sm text-red-600 mt-1" id="permissions-error"></p>
    </div>

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pt-4 border-t border-slate-200">
        <a href="{{ route('roles.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
            <i class="fas fa-arrow-left"></i>
            Volver
        </a>

        <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
            <i class="fas fa-save"></i>
            {{ $buttonText }}
        </button>
    </div>
</div>
