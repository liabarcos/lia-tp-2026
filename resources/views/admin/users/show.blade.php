<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle de Usuario') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-800">
                &larr; Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center space-x-6 mb-6">
                        <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover">
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h1>
                            <p class="text-gray-500">{{ $user->email }}</p>
                            @if ($user->is_admin)
                                <span class="inline-block mt-1 px-3 py-1 text-sm font-semibold text-purple-700 bg-purple-100 rounded-full">Administrador</span>
                            @else
                                <span class="inline-block mt-1 px-3 py-1 text-sm font-semibold text-green-700 bg-green-100 rounded-full">Alumno</span>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                                {{ __('Editar') }}
                            </a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                                    {{ __('Eliminar') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">Teléfono</h3>
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
                            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">Red Profesional</h3>
                            @if ($user->professional_url)
                                <a href="{{ $user->professional_url }}" target="_blank" class="text-blue-600 hover:underline">
                                    {{ $user->professional_url }}
                                </a>
                            @else
                                <p class="text-gray-400">No especificado</p>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">Fecha de Registro</h3>
                            <p class="text-gray-700">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">Última Actualización</h3>
                            <p class="text-gray-700">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
