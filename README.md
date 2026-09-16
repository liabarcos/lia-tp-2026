# App de Alumnos — Laravel 12 + MySQL

Aplicación web para gestión de usuarios con autenticación y roles (Docente/Administrador y Alumno).

## Stack

- Laravel 12
- MySQL 8.0
- Laravel Breeze (Blade + TailwindCSS)
- PHP 8.3

## Requisitos

- PHP 8.3+
- Composer
- MySQL 8.0
- Node.js (para compilar assets)

## Instalación

```bash
# Clonar el repositorio
git clone <url-del-repositorio>
cd lia-app

# Instalar dependencias PHP
composer install

# Instalar dependencias Node
npm install

# Copiar archivo de entorno
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate

# Configurar base de datos en .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lia_tp
DB_USERNAME=root
DB_PASSWORD=

# Crear base de datos
mysql -u root -e "CREATE DATABASE lia_tp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Ejecutar migraciones y seeders
php artisan migrate:fresh --seed

# Crear storage link para fotos
php artisan storage:link

# Compilar assets
npm run build

# Iniciar servidor
php artisan serve
```

## Usuarios de Prueba

| Rol | Email | Contraseña |
|-----|-------|------------|
| Administrador | admin@lia.com | password |
| Alumno | lia@lia.com | password |

## Funcionalidades

### Administrador (Docente)
- Accede a `/admin/users` para ver listado de usuarios
- Ve detalle de cada usuario
- Acceso total a la información de todos los usuarios

### Alumno
- Se registra con foto de perfil (obligatoria)
- Ve y edita su propio perfil
- Teléfono con enlace a WhatsApp
- URL profesional (LinkedIn, GitHub, etc.) en nueva pestaña

## Estructura

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/UserController.php    # Listado y detalle de usuarios
│   │   ├── Auth/RegisteredUserController.php
│   │   └── ProfileController.php       # Perfil del alumno
│   ├── Middleware/AdminMiddleware.php
│   └── Requests/
│       ├── ProfileUpdateRequest.php
│       └── RegisterRequest.php
├── Models/User.php
└── Policies/UserPolicy.php

database/
├── migrations/
│   └── *_add_custom_fields_to_users_table.php
└── seeders/DatabaseSeeder.php

resources/views/
├── admin/users/
│   ├── index.blade.php
│   └── show.blade.php
├── auth/register.blade.php
├── profile/
│   ├── show.blade.php
│   ├── edit.blade.php
│   └── partials/update-profile-information-form.blade.php
└── dashboard.blade.php

routes/web.php
```

## Configuración WhatsApp

El prefijo de país para WhatsApp se configura en `.env`:

```
WHATSAPP_COUNTRY_PREFIX=54
```

## Seguridad

- Middleware `auth` en rutas protegidas
- Middleware `admin` para rutas de administrador
- Policies para control de acceso (viewAny, view, update)
- Validación de formularios con FormRequest
- Foto de perfil obligatoria en registro
