# 💬 Sala de Chat — Aplicación en Tiempo Real (TP LIA)

Sala de chat web construida con **Laravel 13** y **WebSockets (Laravel Reverb)**:
los mensajes de todos los usuarios aparecen al instante, sin recargar la página.

## ✨ Funcionalidades

- Registro e inicio de sesión (Breeze auth).
- Sala de chat única en tiempo real: los mensajes llegan por WebSocket a todos los conectados.
- Historial persistido en **MySQL** (últimos 50 mensajes al entrar).
- Interfaz con **Tailwind CSS + Alpine.js**: burbujas propias/ajenas, auto-scroll, indicador "En vivo".

## 🛠️ Tecnologías

| Capa | Herramienta |
|---|---|
| Backend | Laravel 13 (PHP 8.3) |
| WebSockets | Laravel Reverb + laravel-echo + pusher-js |
| Base de datos | MySQL |
| Frontend | Blade + Tailwind CSS + Alpine.js |
| Build de assets | Vite |

## 🔁 Cómo funciona (flujo de un mensaje)

1. El usuario escribe y presiona **Enviar** (Alpine.js envía un `POST /chat` con `fetch`).
2. `ChatController@store` valida, **guarda el mensaje en MySQL** y emite el evento `MessageSent`.
3. El evento (`ShouldBroadcastNow`) se publica en el canal público `chat` vía **Reverb** (servidor WebSocket propio).
4. **Laravel Echo** (conexión WebSocket en el navegador de *todos* los usuarios) recibe el evento
   y Alpine.js lo agrega a la lista al instante, sin recargar.
5. Al entrar a la sala, se cargan los últimos 50 mensajes desde la base de datos.

## 🚀 Ejecución local

```bash
composer install
cp .env.example .env          # configurar DB y REVERB_*
php artisan key:generate
php artisan migrate
npm install
npm run build

php artisan serve             # web: http://localhost:8000
php artisan reverb:start      # websockets: ws://localhost:8080
```

Usuarios de prueba (`php artisan db:seed`):
- `lia@lia.com` / `password`
- `demo@lia.com` / `password`

## 📁 Estructura clave

```
app/Events/MessageSent.php          # Evento que se transmite por WebSocket
app/Http/Controllers/ChatController.php  # index (historial) + store (guardar y emitir)
app/Models/Message.php              # Mensaje (user_id + content)
resources/views/chat/index.blade.php    # Sala de chat (UI en tiempo real)
resources/js/app.js                 # Conexión de Laravel Echo con Reverb
routes/web.php                      # GET/POST /chat
```
