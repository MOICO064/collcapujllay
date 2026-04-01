<x-guest-layout>
    <div x-data="loginForm()" x-init="email = @json(old('email'));" class="space-y-6">
        <div class="text-center">
            <h1 class="text-3xl font-black text-amber-200">Ingreso al Parque Collcapujllay</h1>
            <p class="mt-2 text-amber-100/80">Inicia sesión sin recargar la página con validación en tiempo real.</p>
        </div>

        <template x-if="errors.form">
            <div class="rounded-2xl border border-red-400 bg-red-50/90 px-4 py-3 text-sm text-red-700">
                <span x-text="errors.form"></span>
            </div>
        </template>

        <form @submit.prevent="submit" novalidate class="space-y-6">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Correo electrónico')" />
                <x-text-input id="email" x-ref="email" x-model.debounce.250ms="email" @input="validateEmail" @blur="validateEmail"
                    class="block mt-1 w-full" type="email" name="email" autocomplete="username" />
                <p x-show="errors.email" x-text="errors.email" class="mt-2 text-sm text-red-300"></p>
            </div>

            <div>
                <x-input-label for="password" :value="__('Contraseña')" />
                <x-text-input id="password" x-model.debounce.250ms="password" @input="validatePassword" @blur="validatePassword"
                    class="block mt-1 w-full" type="password" name="password" autocomplete="current-password" />
                <p x-show="errors.password" x-text="errors.password" class="mt-2 text-sm text-red-300"></p>
            </div>

            <div class="flex items-center justify-between">
                <label class="inline-flex items-center gap-2 text-sm text-amber-100">
                    <input type="checkbox" x-model="remember" class="rounded border-gray-300 text-amber-500 shadow-sm focus:ring-amber-400" />
                    <span>Recordarme</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-amber-100 hover:text-white underline">¿Olvidaste tu contraseña?</a>
                @endif
            </div>

            <div>
                <button type="submit" :disabled="loading"
                    class="w-full inline-flex items-center justify-center rounded-xl bg-amber-400 px-4 py-3 font-semibold text-slate-900 shadow-lg shadow-amber-500/30 transition hover:bg-amber-300 disabled:cursor-not-allowed disabled:opacity-60">
                    <span x-show="!loading">Iniciar sesión</span>
                    <span x-show="loading">Validando...</span>
                </button>
            </div>
        </form>

        <script>
            function loginForm() {
                return {
                    email: @json(old('email')),
                    password: '',
                    remember: false,
                    loading: false,
                    errors: { email: '', password: '', form: '' },

                    validateEmail() {
                        this.errors.email = '';
                        if (!this.email) {
                            this.errors.email = 'El correo es obligatorio.';
                            return false;
                        }
                        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!regex.test(this.email)) {
                            this.errors.email = 'Ingresa un correo válido.';
                            return false;
                        }
                        return true;
                    },

                    validatePassword() {
                        this.errors.password = '';
                        if (!this.password) {
                            this.errors.password = 'La contraseña es obligatoria.';
                            return false;
                        }
                        if (this.password.length < 6) {
                            this.errors.password = 'La contraseña debe tener al menos 6 caracteres.';
                            return false;
                        }
                        return true;
                    },

                    async submit() {
                        this.errors.form = '';
                        if (!this.validateEmail() | !this.validatePassword()) {
                            return;
                        }

                        this.loading = true;

                        try {
                            const token = document.querySelector('meta[name=csrf-token]').content;
                            const response = await fetch('{{ route('login') }}', {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                },
                                body: JSON.stringify({
                                    email: this.email,
                                    password: this.password,
                                    remember: this.remember,
                                }),
                            });

                            const data = await response.json().catch(() => ({}));

                            if (!response.ok) {
                                this.errors.form = data.message || 'No se pudo iniciar sesión.';
                                if (data.errors) {
                                    this.errors.email = data.errors.email?.[0] ?? this.errors.email;
                                    this.errors.password = data.errors.password?.[0] ?? this.errors.password;
                                }
                                return;
                            }

                            window.location.href = data.intended || '{{ route('dashboard') }}';
                        } catch (error) {
                            this.errors.form = 'Error de conexión. Intenta nuevamente.';
                        } finally {
                            this.loading = false;
                        }
                    },
                };
            }
        </script>
    </div>
</x-guest-layout>
