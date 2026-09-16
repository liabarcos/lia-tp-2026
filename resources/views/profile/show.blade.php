<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status') === 'profile-updated')
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ __('Perfil actualizado correctamente.') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center space-x-6 mb-6">
                        <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h1>
                            <p class="text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase">Teléfono</h3>
                            @if ($user->phone)
                                <a href="{{ $user->whatsapp_url }}" target="_blank" class="text-green-600 hover:underline flex items-center space-x-2">
                                    <span>{{ $user->phone }}</span>
                                    <span class="text-xs">(WhatsApp)</span>
                                </a>
                            @else
                                <p class="text-gray-400">No especificado</p>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase">Red Profesional</h3>
                            @if ($user->professional_url)
                                <a href="{{ $user->professional_url }}" target="_blank" class="text-blue-600 hover:underline">
                                    {{ $user->professional_url }}
                                </a>
                            @else
                                <p class="text-gray-400">No especificado</p>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="inline-block bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                        {{ __('Editar Perfil') }}
                    </a>

                    @if ($user->is_admin)
                        <a href="{{ route('admin.users.index') }}" class="inline-block ml-2 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                            {{ __('Ver Usuarios') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
