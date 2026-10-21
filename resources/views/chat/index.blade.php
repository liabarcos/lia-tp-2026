<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sala de Chat') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-0">
            <div x-data="chatRoom()" class="bg-white shadow rounded-lg flex flex-col overflow-hidden" style="height: calc(100vh - 11rem);">

                <!-- Encabezado de la sala -->
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-sm text-gray-600">{{ __('En vivo · mensajes en tiempo real') }}</span>
                    </div>
                    <span class="text-xs text-gray-500">
                        {{ __('Conectado como') }} <span class="font-semibold text-purple-700">{{ Auth::user()->name }}</span>
                    </span>
                </div>

                <!-- Lista de mensajes -->
                <div x-ref="list" class="flex-1 overflow-y-auto p-4 space-y-3 bg-white">
                    <template x-if="messages.length === 0">
                        <div class="text-center text-gray-400 py-10 text-sm">
                            {{ __('Todavía no hay mensajes. ¡Escribí el primero!') }}
                        </div>
                    </template>

                    <template x-for="m in messages" :key="m.id">
                        <div class="flex" :class="m.user_id === authId ? 'justify-end' : 'justify-start'">
                            <div class="max-w-[75%]">
                                <div class="text-xs mb-1 px-1 flex items-center space-x-2"
                                     :class="m.user_id === authId ? 'justify-end text-purple-600' : 'text-gray-500'">
                                    <span class="font-semibold" x-text="m.user_id === authId ? '{{ __('Tú') }}' : m.name"></span>
                                    <span x-text="fmt(m.created_at)"></span>
                                </div>
                                <div class="px-4 py-2 rounded-2xl whitespace-pre-wrap break-words text-sm leading-relaxed"
                                     :class="m.user_id === authId
                                        ? 'bg-purple-600 text-white rounded-br-md'
                                        : 'bg-gray-200 text-gray-800 rounded-bl-md'"
                                     x-text="m.content"></div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Error -->
                <div x-show="error" x-cloak x-text="error" class="px-4 py-1 text-xs text-red-500 bg-red-50"></div>

                <!-- Formulario de envío -->
                <form x-on:submit.prevent="send" class="border-t border-gray-100 p-3 flex items-center space-x-2 bg-gray-50">
                    <input type="text"
                           x-model="draft"
                           maxlength="1000"
                           autocomplete="off"
                           placeholder="{{ __('Escribí un mensaje…') }}"
                           class="flex-1 rounded-full border-gray-300 text-sm focus:border-purple-500 focus:ring-purple-500" />
                    <button type="submit"
                            :disabled="sending || !draft.trim()"
                            class="bg-purple-600 text-white rounded-full px-5 py-2 text-sm font-medium hover:bg-purple-700 disabled:opacity-40 transition">
                        {{ __('Enviar') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            window.Alpine.data('chatRoom', () => ({
                messages: @json($messages),
                authId: @json(Auth::id()),
                draft: '',
                sending: false,
                error: '',

                init() {
                    // Escucha los mensajes que llegan por WebSocket (Reverb)
                    window.Echo.channel('chat').listen('.message.sent', (payload) => {
                        this.append(payload);
                    });
                    this.scrollToEnd();
                },

                fmt(iso) {
                    return new Date(iso).toLocaleTimeString('es-AR', { hour: '2-digit', minute: '2-digit' });
                },

                append(payload) {
                    if (this.messages.some(m => m.id === payload.id)) return;
                    this.messages.push({
                        id: payload.id,
                        user_id: payload.user_id,
                        name: payload.name,
                        content: payload.content,
                        created_at: payload.created_at,
                    });
                    this.scrollToEnd();
                },

                async send() {
                    const text = this.draft.trim();
                    if (!text || this.sending) return;

                    this.sending = true;
                    this.error = '';

                    try {
                        const res = await fetch(@json(route('chat.store')), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ content: text }),
                        });

                        if (!res.ok) throw new Error('fetch_failed');

                        const message = await res.json();
                        this.append(message);
                        this.draft = '';
                    } catch (e) {
                        this.error = '{{ __('No se pudo enviar el mensaje. Verificá tu conexión e intentá de nuevo.') }}';
                    } finally {
                        this.sending = false;
                    }
                },

                scrollToEnd() {
                    this.$nextTick(() => {
                        const el = this.$refs.list;
                        if (el) el.scrollTop = el.scrollHeight;
                    });
                },
            }));
        });
    </script>
</x-app-layout>
