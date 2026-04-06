<div class="space-y-6">
    <div id="form-errors" class="hidden bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg">
        <p class="font-semibold mb-2">Corrige los siguientes campos:</p>
        <ul class="list-disc list-inside text-sm space-y-1"></ul>
    </div>
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-2">
            <label for="name" class="font-semibold text-slate-700">Nombre <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $usuario->name ?? '') }}" oninput="this.value=this.value.toUpperCase()" class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="field-error text-sm text-red-600 mt-1" id="name-error"></p>
        </div>

        <div class="space-y-2">
            <label for="email" class="font-semibold text-slate-700">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" id="email" value="{{ old('email', $usuario->email ?? '') }}" class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="field-error text-sm text-red-600 mt-1" id="email-error"></p>
        </div>

        <div class="space-y-2">
            <label for="role_label" class="font-semibold text-slate-700">Rol <span class="text-red-500">*</span></label>
            <select name="role_label" id="role_label"
                class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                @php
                $selectedRole = old('role_label', isset($usuario) ? $usuario->roles->first()?->name : '');
                @endphp

                <option value="">Selecciona un rol</option>

                @foreach($roles as $role)
                <option value="{{ $role->name }}"
                    {{ $selectedRole === $role->name ? 'selected' : '' }}>
                    {{ ucfirst($role->name) }}
                </option>
                @endforeach

            </select>
            <p class="field-error text-sm text-red-600 mt-1" id="role_label-error"></p>
        </div>

        <div class="space-y-2">
            <label for="estado" class="font-semibold text-slate-700">Estado <span class="text-red-500">*</span></label>
            <select name="estado" id="estado" class="w-full border border-slate-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="1" {{ old('estado', $usuario->estado ?? 1) ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ old('estado', $usuario->estado ?? 1) ? '' : 'selected' }}>Inactivo</option>
            </select>
            <p class="field-error text-sm text-red-600 mt-1" id="estado-error"></p>
        </div>
    </div>
    @if(isset($usuario))
    <div class="bg-blue-50 border border-blue-200 text-blue-700 p-4 rounded-lg">
        <p class="text-sm"><i class="fas fa-info-circle mr-2"></i>Si desea actualizar la contraseña, debe llenar los campos de contraseña y confirmación.</p>
    </div>
    @endif
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-2">
            <label for="password" class="font-semibold text-slate-700">Contraseña</label>
            <div class="relative">
                <input type="password" name="password" id="password" class="w-full border border-slate-300 rounded-lg px-4 py-2 pr-12 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-700">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <p class="field-error text-sm text-red-600 mt-1" id="password-error"></p>
        </div>

        <div class="space-y-2">
            <label for="password_confirmation" class="font-semibold text-slate-700">Confirmar contraseña</label>
            <div class="relative">
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border border-slate-300 rounded-lg px-4 py-2 pr-12 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="button" id="toggle-password-confirmation" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-700">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <p class="field-error text-sm text-red-600 mt-1" id="password_confirmation-error"></p>
        </div>
    </div>



    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pt-4 border-t border-slate-200">
        <a href="{{ route('usuarios.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
            <i class="fas fa-arrow-left"></i>
            Volver
        </a>

        <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
            <i class="fas fa-save"></i>
            {{ $buttonText }}
        </button>
    </div>
</div>