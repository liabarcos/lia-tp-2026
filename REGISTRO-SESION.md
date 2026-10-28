# 📋 Registro de la Sesión — Construcción de la Sala de Chat

**Proyecto:** Sala de Chat en tiempo real (TP LIA)
**Fecha:** 21–22 de octubre de 2026 (presentación)
**Repositorio:** https://github.com/liabarcos/lia-tp-2026
**Commits:** `276bc9a` (chat + limpieza) · `4363026` (fixes túnel/runtime) · `a0a9026` (este registro) · `7bf241d` (interfaz galaxia)

---

## 1. 🔗 Links

| Link | Uso |
|---|---|
| **https://cia-forum-helmet-tour.trycloudflare.com** | **Sala de chat EN VIVO** (link actual — si la demo se reinicia, cambia) |
| https://github.com/liabarcos/lia-tp-2026 | Código en GitHub |
| http://localhost:8000 | Sala en local (solo en la PC del proyecto) |

> ⚠️ El link público es **temporal**: existe mientras la PC del proyecto esté encendida con la demo corriendo. Si se cae, doble clic en `iniciar-demo.bat` → **nuevo link**.

**Usuarios de prueba** (contraseña `password`):
- `lia@lia.com` — Lia Alumno
- `demo@lia.com` — Compañero Demo

---

## 2. 🖥️ Cómo presentar desde OTRA computadora

La app corre en **la laptop del proyecto**; cualquier otra PC solo necesita el link:

1. **Tu laptop:** `iniciar-demo.bat` abierto, encendida, sin suspensión, con internet.
2. **Computadora del aula:** abrir Chrome/Edge → pegar el link → login (`lia@lia.com` / `password`) → **F11** pantalla completa.
3. **Demo en vivo:** dos ventanas (o una PC + el **celular** con el link) y mandarse mensajes entre dispositivos — se ven al instante.
4. **Comprobá 5 minutos antes** que la otra máquina abra el link (por si el WiFi del aula filtra).

---

## 3. 🎤 Guion para presentar

**Apertura:**
> "Les voy a mostrar una sala de chat en tiempo real: cuando uno manda un mensaje, todos lo ven al instante, sin recargar. Está hecha con Laravel y WebSockets y publicada con Cloudflare."

**Stack:**
> "Backend Laravel 13 (PHP). Tiempo real con Laravel Reverb (servidor de WebSockets). Base de datos MySQL. Frontend Blade + Tailwind + Alpine.js. Build con Vite. Despliegue con túnel de Cloudflare. Código en GitHub."

**Flujo de un mensaje (5 pasos):**
1. El navegador manda `POST /chat` (fetch + CSRF).
2. El controlador valida y **guarda en MySQL**.
3. Emite el evento **`MessageSent`** → **Reverb** lo publica en el canal `chat` por WebSocket.
4. **Laravel Echo** lo recibe en todos los navegadores conectados.
5. **Alpine.js** lo dibuja **sin recargar** (dedupe por id).
   (Al entrar: carga de MySQL los últimos 50 mensajes = historial.)

**Cómo se construyó:**
> "Migración `messages` + modelo `Message`, controlador con `index`/`store`, evento de broadcast, vista de la sala, conexión de Echo en el frontend y auth de Laravel. El navegador no conecta directo al WebSocket: usa la clave de aplicación que Laravel genera."

**Despliegue:**
> "Túnel de Cloudflare: expone la PC con link https público y enruta web y WebSockets por el mismo link."

**Por si preguntan:**

| Pregunta | Respuesta |
|---|---|
| ¿Por qué WebSockets? | Canal abierto: Reverb avisa al instante; HTTP tardaría y no notifica. |
| ¿Dónde quedan los mensajes? | En MySQL — WebSocket es solo la notificación en vivo. |
| ¿Dos al mismo tiempo? | Cada mensaje tiene id único; el frontend descarta duplicados. |
| ¿Seguridad? | Login obligatorio, validación y escapado de mensajes, HTTPS por Cloudflare. |
| ¿Cola/límites? | Evento directo (`ShouldBroadcastNow`), sin cola — bien para este volumen. |

---

## 4. 🌌 Interfaz (rediseño del 22/10)

- **Fondo de galaxia** global: espacio oscuro con estrellas + nebulosas (violeta/azul/fucsia), nav y encabezado en cristal esmerilado.
- **Mensajes que resaltan:** propios en degradado **fucsia→violeta con brillo neón**; ajenos en **blanco puro con halo**.
- El fondo también se ve en login/registro.
- Commit `7bf241d`.

---

## 5. 🛠️ Problemas resueltos durante el desarrollo

1. **ParseError de Blade** en `@json()` con `fn()` anidado → mapeo de mensajes en el controlador.
2. **`x-data="chatRoom()"` faltante** → Alpine no iniciaba la sala.
3. **`x-if` fuera de `<template>`** → el estado vacío no se ocultaba.
4. **`require()` en ES module** → `demo-proxy.js` → `demo-proxy.cjs`.
5. **419 "Page Expired" en el túnel** → `trustProxies(at: ['127.0.0.1', '::1'])` para detectar https detrás de cloudflared.
6. **SyntaxError `->` en el `<head>`** → expresión PHP sin envolver en el script de config.
7. **`key: null` de Reverb** → estructura nueva: `reverb.apps.apps.0.key`.
8. **Config de WebSocket en runtime** (`window.APP_REVERB`) → mismo build sirve en local (`ws://localhost:8080`) y en túnel (`wss://…:443`) sin reconstruir.
9. **MySQL caído tras el reinicio** (22/10) → levantado con XAMPP; nada que ver con la app.

---

## 6. 🧹 Alcance: qué se quitó y qué se construyó

**Se quitó (no pertenecía a la sala):** ABM de usuarios admin, vistas/middleware/policy de administración, campos teléfono/URL profesional/foto/is_admin, WhatsApp, `Documento_TP_LIA.pdf`.

**Se construyó:** sala de chat completa en tiempo real, registro/login directo a la sala, dashboard con acceso, usuario demo, README, registro, `iniciar-demo.bat` + `demo-proxy.cjs`.

---

## 7. ⚡ Comandos útiles

```bash
iniciar-demo.bat                          # levantar todo (abre el link nuevo)

# Borrar mensajes de prueba
php artisan tinker --execute="DB::table('messages')->truncate(); echo 'limpio';"

# Servicios por separado
php artisan serve --port=8000
php artisan reverb:start
node demo-proxy.cjs
"C:\Program Files (x86)\cloudflared\cloudflared.exe" tunnel --url http://127.0.0.1:8088
```

**Stack final:** Laravel 13 · PHP 8.3 · Laravel Reverb · laravel-echo + pusher-js · MySQL · Blade + Tailwind + Alpine.js · Vite · cloudflared.
