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
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-16 h-16 rounded-full bg-purple-600 text-white flex items-center justify-center text-2xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h1>
                            <p class="text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="inline-block bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                        {{ __('Editar Perfil') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
