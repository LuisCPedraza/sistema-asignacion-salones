# 🚀 Acciones para pruebas (Testing)

Estas Actions se usan para ejecutar automáticamente tus tests de Laravel (`php artisan test`) o pruebas unitarias en general.

| Propósito | Action | Descripción |
|------------|---------|-------------|
| Ejecutar pruebas con PHPUnit / Pest | `shivammathur/setup-php` | Instala PHP en el runner de GitHub y permite correr `php artisan test` o `vendor/bin/pest`. |
| Crear base de datos temporal (MySQL, SQLite, PostgreSQL) | `actions/setup-mysql` | Prepara un entorno con base de datos para pruebas automáticas. |
| Ejecutar Laravel Dusk | Usa un contenedor con Chrome | Permite pruebas de navegador (UI) para Laravel. |

### 🧪 Ejemplo típico
```yaml
- name: Run Laravel Tests
  run: php artisan test
```

---

# 🧹 Acciones para calidad del código

Estas verifican estilo, errores y buenas prácticas en tu código Laravel.

| Propósito | Action | Descripción |
|------------|---------|-------------|
| Linter de Laravel | `aglipanci/laravel-pint-action` | Ejecuta Laravel Pint automáticamente para revisar formato de código. |
| Análisis estático | `oskarstark/phpstan-ga` | Ejecuta PHPStan para revisar errores y tipos en tu código. |
| Análisis de seguridad | `symfonycorp/security-checker-action` | Detecta vulnerabilidades en tus dependencias de Composer. |

### 🧾 Ejemplo
```yaml
- name: Run Laravel Pint
  uses: aglipanci/laravel-pint-action@latest
```

---

# 🚀 Acciones para despliegue (Deploy)

Estas se usan para publicar tu aplicación Laravel automáticamente en un servidor, cuando los tests pasan.

| Propósito | Action | Descripción |
|------------|---------|-------------|
| Desplegar a Laravel Forge | `SamKirkland/FTP-Deploy-Action` | Despliega tu app por FTP/SFTP (útil si no usas Forge). |
| Desplegar a Laravel Forge | `beyondcode/forge-deploy` | Ejecuta un deploy directamente en Laravel Forge. |
| Desplegar a Ploi, Envoyer o VPS | Scripts personalizados (SSH, rsync) | Automatiza la publicación en tu servidor. |

### 🖥️ Ejemplo básico (Forge)
```yaml
- name: Deploy to Laravel Forge
  uses: beyondcode/forge-deploy@v1
  with:
    forge_api_token: ${{ secrets.FORGE_API_TOKEN }}
    server_id: 123456
    site_id: 654321
```

---

# ⚙️ Acciones para dependencias y mantenimiento

| Propósito | Action | Descripción |
|------------|---------|-------------|
| Instalar dependencias PHP | `php-actions/composer` | Ejecuta `composer install` o `composer update` automáticamente. |
| Caché de Composer | `actions/cache` | Guarda el caché de `vendor` para acelerar los builds. |
| Instalar dependencias de JS | `actions/setup-node` | Permite usar `npm install`, `vite`, o `mix`. |

### 🧩 Ejemplo
```yaml
- name: Install Composer dependencies
  uses: php-actions/composer@v6
```

---

# 🔒 Acciones para seguridad

| Propósito | Action | Descripción |
|------------|---------|-------------|
| Revisar dependencias vulnerables | `github/dependency-review-action` | Escanea vulnerabilidades en tu código y dependencias. |
| Auditoría de Composer | `ramsey/composer-audit-action` | Ejecuta `composer audit` en tu workflow. |

---

# 🧰 Acciones personalizadas para Laravel

Estas son específicas para entornos Laravel modernos:

| Action | Propósito |
|---------|------------|
| `laravel/pint` | Revisión de estilo. |
| `laravel/dusk` | Pruebas automatizadas del navegador. |
| `laravel/octane` | Puedes crear un workflow para mantener Octane en ejecución. |
| `[laravel/breeze, laravel/jetstream]` | No tienen Actions oficiales, pero puedes usar otras para probar autenticación, migraciones, etc. |

---

# 🧭 En resumen

| Tipo de Action | Ejemplos | Propósito |
|----------------|-----------|------------|
| **Testing** | `shivammathur/setup-php`, `actions/setup-mysql` | Ejecutar tus tests automáticamente |
| **Calidad de código** | `aglipanci/laravel-pint-action`, `phpstan-ga` | Analizar estilo y errores |
| **Despliegue** | `beyondcode/forge-deploy`, `FTP-Deploy-Action` | Publicar tu app automáticamente |
| **Mantenimiento** | `php-actions/composer`, `actions/cache` | Instalar y optimizar dependencias |
| **Seguridad** | `ramsey/composer-audit-action` | Detectar vulnerabilidades |
