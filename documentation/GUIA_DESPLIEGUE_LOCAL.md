# Guía rápida para ejecutar el proyecto en local

Esta guía está pensada para que cualquier persona (por ejemplo, un profesor) pueda clonar su fork y levantar el proyecto sin tropiezos.

## Requisitos previos
- PHP 8.2+ con extensiones: `pdo_sqlite`, `pdo_pgsql`, `mbstring`, `openssl`, `zip`
- Composer
- Node.js 18+ y npm (o yarn)
- Git
- SQLite (solo para crear el archivo)
- Opcional: Docker si se quiere usar contenedores

## Pasos básicos (SQLite, recomendado para pruebas rápidas)
1. Clonar el fork:
   ```bash
   git clone https://github.com/<su-usuario>/sistema-asignacion-salones.git
   cd sistema-asignacion-salones
   ```
2. Instalar dependencias PHP:
   ```bash
   composer install
   ```
3. Instalar dependencias JS:
   ```bash
   npm install
   ```
4. Copiar el entorno base:
   ```bash
   cp .env.example .env
   ```
5. Generar clave de app:
   ```bash
   php artisan key:generate
   ```
6. Crear la base SQLite y apuntar el `.env` (ya viene configurado):
   ```bash
   mkdir -p database
   touch database/database.sqlite
   # Verifica en .env:
   # DB_CONNECTION=sqlite
   # DB_DATABASE=./database/database.sqlite
   ```
7. Migraciones y seed de datos de prueba:
   ```bash
   php artisan migrate --seed
   ```
8. Levantar backend (Laravel):
   ```bash
   php artisan serve
   # Por defecto: http://127.0.0.1:8000
   ```
9. (Opcional) Frontend con Vite:
   ```bash
   npm run dev      # modo desarrollo
   # o
   npm run build    # compilar assets
   ```

## Usuarios de prueba (seed)
- Admin: `admin@example.com` / `password`
- Coordinador: `coordinador@example.com` / `password`
- Profesor: `profesor@example.com` / `password`
- Todos los usuarios seed usan contraseña `password`. Se pueden editar en `database/seeders` y volver a sembrar con `php artisan migrate:fresh --seed`.

## Alternativa: Postgres local
Si prefiere Postgres en vez de SQLite:
1. En `.env` coloca:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=sas
   DB_USERNAME=postgres
   DB_PASSWORD=<tu_clave>
   ```
2. Crea la base `sas` (CLI o GUI) y corre:
   ```bash
   php artisan migrate --seed
   ```

## Errores frecuentes y soluciones
- **"Database file does not exist" (SQLite):** asegúrate de crear `database/database.sqlite` y que `.env` apunte a esa ruta.
- **"password authentication failed" (Postgres):** revisa usuario, contraseña, host y puerto en `.env`.
- **500 en el navegador:** limpia cachés y revisa logs:
  ```bash
  php artisan config:clear
  php artisan cache:clear
  tail -f storage/logs/laravel.log
  ```
- **Assets no cargan:** corre `npm run dev` y verifica `APP_URL`/`ASSET_URL` en `.env` (para local, `http://127.0.0.1:8000`).
- **Puerto ocupado:** inicia con otro puerto `php artisan serve --port=8001` y ajusta URLs si es necesario.
- **Extensiones PHP faltantes:** instala `pdo_sqlite` y `mbstring` (en Debian/Ubuntu: `sudo apt-get install php8.2-sqlite3 php8.2-mbstring`).

## Notas rápidas
- Con SQLite no necesitas ningún servicio externo; ideal para revisión rápida.
- Para resetear datos de prueba: `php artisan migrate:fresh --seed`.
- En Windows, es más sencillo usar WSL; si no, asegúrate de tener PHP y Composer en PATH y habilitar `pdo_sqlite` en `php.ini`.
