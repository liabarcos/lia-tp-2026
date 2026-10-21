# 📋 Registro de la Sesión — Construcción de la Sala de Chat

**Fecha:** 21 de octubre de 2026 (mañana de la presentación)
**Repositorio:** https://github.com/liabarcos/lia-tp-2026
**Commits:** `276bc9a` (sala de chat + limpieza) · `4363026` (fixes de túnel y config en runtime)

---

## 1. Link público de la demo

```
https://soundtrack-ultram-metallic-governance.trycloudflare.com
```

- Corre en la PC del proyecto mediante `iniciar-demo.bat` (4 procesos: Laravel, Reverb, proxy y túnel de Cloudflare).
- **Nota:** al reiniciar la demo, Cloudflare genera un link nuevo (túnel temporal). Abrir la demo **antes** de presentar y usar el link que aparezca.

**Usuarios de prueba** (contraseña `password`):
- `lia@lia.com` — Lia Alumno
- `demo@lia.com` — Compañero Demo

---

## 2. Qué se hizo en esta sesión

### Limpieza (lo que "no iba en la app" — contenido de gestión de usuarios TP)
| Elemento eliminado | Motivo |
|---|---|
| `Admin/UserController` + vistas `admin/users/*` | ABM de usuarios no pertenece a la sala de chat |
| `AdminMiddleware` + alias `admin` | Solo existía para el panel admin |
| `UserPolicy` (is_admin) | Control de roles admin |
| Migración `add_custom_fields_to_users_table` | Campos teléfono/URL profesional/foto/is_admin |
| Campos en registro, perfil y `RegisterRequest` | Foto obligatoria, teléfono, URL profesional |
| `ProfileController` con subida de fotos | Simplificado a perfil estándar |
| Accesorios `photo_url` / `whatsapp` de `User` | Restos del TP de gestión |
| `whatsapp` en `config/services.php` | Config del TP anterior |
| `Documento_TP_LIA.pdf` | Documento de otro alcance |

### Sala de chat construida (nuevo)
| Archivo | Función |
|---|---|
| `database/migrations/2026_10_21_100000_create_messages_table.php` | Tabla `messages` (user_id + content) |
| `app/Models/Message.php` | Modelo con relación `belongsTo(User)` |
| `app/Http/Controllers/ChatController.php` | `index` (últimos 50) y `store` (guarda + emite) |
| `app/Events/MessageSent.php` | Evento `ShouldBroadcastNow` → canal público `chat` |
| `resources/views/chat/index.blade.php` | UI de la sala: burbujas, auto-scroll, "En vivo" |
| `resources/js/app.js` | Conexión **Laravel Echo** + Pusher.js con Reverb |
| `routes/web.php` | `GET/POST /chat` bajo middleware `auth` |

### Extras de integración
- Registro e inicio de sesión redirigen **directo a la sala**.
- Dashboard con botón "Entrar a la Sala de Chat"; menú con pestaña "Sala de Chat".
- Seeder con 2 usuarios de prueba.
- `README.md` con arquitectura y flujo de mensajes.
- `iniciar-demo.bat` + `demo-proxy.cjs` (proxy HTTP/WebSocket de un solo puerto).

---

## 3. Problemas detectados y resueltos durante los testeos

1. **ParseError de Blade** en `@json()` con `fn()` anidado → los mensajes se mapean ahora en `ChatController`.
2. **`x-data="chatRoom()"` faltante** en el contenedor → Alpine no inicializaba la sala (franja rosa visible).
3. **`x-if` en `<template>`** → el mensaje de "sala vacía" no se ocultaba (x-if solo funciona en `<template>`).
4. **`require()` en ES module** → `demo-proxy.js` renombrado a `.cjs`.
5. **419 "Page Expired" en el túnel** → Laravel no confiaba en el proxy: agregado `$middleware->trustProxies(at: ['127.0.0.1', '::1'])` en `bootstrap/app.php`.
6. **SyntaxError `->` en el `<head>`** → expresión PHP sin envolver en el script de config; corregida con `@php`.
7. **`key: null` de Reverb** → estructura nueva del config: `reverb.apps.apps.0.key`.
8. **Config de WebSocket en runtime** (`window.APP_REVERB`): host/esquema/puerto se calculan por petición → **el mismo build funciona en local (ws://localhost:8080) y en el túnel (wss://…:443) sin reconstruir**.

---

## 4. Cómo funciona (flujo de un mensaje)

```
Navegador A  →  POST /chat (fetch + CSRF)
                    │
Laravel: valida → guarda en MySQL → evento MessageSent
                    │
        Reverb (WebSocket server, puerto 8080) publica en canal "chat"
                    │
Navegador A, B, C ← Laravel Echo recibe .message.sent ← WebSocket
                    │
        Alpine.js agrega la burbuja (sin recargar, con dedupe por id)
```

Al entrar a la sala: `ChatController@index` carga los últimos 50 mensajes de MySQL.

**Stack:** Laravel 13 (PHP 8.3) · Laravel Reverb · laravel-echo + pusher-js · MySQL · Blade + Tailwind + Alpine.js · Vite · cloudflared (túnel).

---

## 5. Guion corto para exponer

> "Es una **sala de chat en tiempo real** con **Laravel 13**. El mensaje se **guarda en MySQL** y se **emite por WebSockets con Reverb**. **Laravel Echo** escucha en todos los navegadores y **Alpine.js** lo dibuja **sin recargar**. El despliegue público usa un **túnel de Cloudflare**."

**Ver en vivo:** dos navegadores (o incógnito), un usuario en cada uno, mandar mensajes y ver cómo llegan al instante.

---

## 6. Comandos útiles

```bash
# Levantar la demo
iniciar-demo.bat          # (doble clic)

# Borrar los mensajes de prueba antes de presentar
php artisan tinker --execute="DB::table('messages')->truncate(); echo 'limpio';"

# Servicios por separado (modo manual)
php artisan serve --port=8000
php artisan reverb:start
node demo-proxy.cjs
"C:\Program Files (x86)\cloudflared\cloudflared.exe" tunnel --url http://127.0.0.1:8088
```
