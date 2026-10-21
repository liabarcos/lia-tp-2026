import Alpine from 'alpinejs';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Conexión WebSocket en tiempo real con Laravel Reverb.
// La configuración la inyecta el servidor (layouts/app.blade.php) según
// el dominio desde el que se ingresa: funciona en local y en el túnel.
window.Pusher = Pusher;

const reverb = window.APP_REVERB || {};

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: reverb.key,
    wsHost: reverb.host,
    wsPort: reverb.port ?? 8080,
    wssPort: reverb.port ?? 8080,
    forceTLS: reverb.scheme === 'https',
    enabledTransports: ['ws', 'wss'],
});

window.Alpine = Alpine;

Alpine.start();
