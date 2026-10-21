@echo off
title Sala de Chat - Demo
cd /d "%~dp0"

echo ============================================
echo   SALA DE CHAT - Iniciando la demo...
echo ============================================
echo.

start "Sala de Chat - Servidor web" cmd /k "php artisan serve --host=127.0.0.1 --port=8000"
start "Sala de Chat - WebSockets" cmd /k "php artisan reverb:start"
start "Sala de Chat - Proxy" cmd /k "node demo-proxy.cjs"

timeout /t 3 /nobreak >nul

echo.
echo   Abriendo el tunel de Cloudflare...
echo   (copiá el link https que aparezca abajo y abrilo en el navegador)
echo.
cloudflared tunnel --url http://127.0.0.1:8088

pause
