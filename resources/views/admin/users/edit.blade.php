<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Usuario') }}
            </h2>
            <a href="{{ route('admin.users.show', $user) }}" class="text-gray-600 hover:text-gray-800">
                &larr; Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="flex items-center space-x-6 mb-6">
                            <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover">
                            <div>
                                <x-input-label for="photo" :value="__('Cambiar Foto de Perfil')" />
                                <input type="file" id="photo" name="photo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombre -->
                            <div>
                                <x-input-label for="name" :value="__('Nombre')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <!-- Contraseña (opcional) -->
                            <div>
                                <x-input-label for="password" :value="__('Nueva Contraseña (dejar vacío para no cambiar)')" />
                                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                                <x-input-error class="mt-2" :messages="$errors->get('password')" />
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirmar Nueva Contraseña')" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" />
                            </div>

                            <!-- Rol -->
                            <div>
                                <x-input-label for="is_admin" :value="__('Rol')" />
                                <select id="is_admin" name="is_admin" class="mt-1 block w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" required>
                                    <option value="0" {{ old('is_admin', $user->is_admin) == '0' ? 'selected' : '' }}>Alumno</option>
                                    <option value="1" {{ old('is_admin', $user->is_admin) == '1' ? 'selected' : '' }}>Administrador</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('is_admin')" />
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <x-input-label for="phone" :value="__('Teléfono')" />
                                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" />
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                            </div>

                            <!-- URL Profesional -->
                            <div>
                                <x-input-label for="professional_url" :value="__('URL Profesional')" />
                                <x-text-input id="professional_url" name="professional_url" type="url" class="mt-1 block w-full" :value="old('professional_url', $user->professional_url)" placeholder="https://linkedin.com/in/..." />
                                <x-input-error class="mt-2" :messages="$errors->get('professional_url')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>{{ __('Guardar Cambios') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
