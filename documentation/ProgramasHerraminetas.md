# Programas y Herramientas Utilizados en el Proyecto "Sistema de Asignación de Salones"

Basado en la implementación de la Épica 1 (autenticación, gestión de usuarios y roles), aquí va un resumen de los programas/herramientas principales usados, con sus versiones extraídas de los logs y configuraciones del proyecto (Laravel 12, PHP 8.3). Estos son los esenciales para desarrollo, ejecución, pruebas y despliegue. 
#### 1. **Lenguajes y Frameworks**
- **PHP**: Versión 8.3.6 (motor principal para backend, usado en controllers, models, middleware).
- **Laravel**: Versión 12.31.1 (framework MVC, con Breeze para scaffolding de autenticación).

#### 2. **Gestores de Dependencias y Assets**
- **Composer**: Versión 2.x (no especificada en logs, pero estándar para PHP; instala Laravel/Breeze).
- **npm/Node.js**: Versión 22.x (para Vite/Tailwind; `npm run dev` compila CSS/JS).

#### 3. **Base de Datos**
- **MySQL**: Versión 8.0.x (local, con InnoDB; migraciones para users, salones, grupos; optimizado con índices, particiones y triggers).

#### 4. **Herramientas de Desarrollo y Control de Versión**
- **Git**: Versión 2.39.x (control de versiones, branching para features).
- **GitHub**: Plataforma para repositorio, Issues, Projects y Actions (CI/CD).
- **Visual Studio Code**: Versión 1.84.x (editor principal, con extensiones Laravel/VS Code).

#### 5. **Contenedores y Despliegue**
- **Docker**: Versión 27.x (contenedores para nginx, MySQL, PHP; facilita entornos dev/staging/prod).
- **Docker Compose**: Versión 2.x (orquestación multi-contenedor; archivos por entorno: develop, staging, production).
- **Render**: Plataforma de despliegue (hosting backend/frontend, integrado con GitHub para CI/CD automático).

#### 6. **Herramientas de Pruebas y Desarrollo**
- **PHPUnit**: Versión 11.x (para tests unitarias/feature; 25 passed en suite).
- **Artisan**: Versión integrada en Laravel 12.31.1 (CLI para migrate, make:model, test, serve).
- **GitHub Actions**: Para CI/CD (pruebas TDD, linting, despliegue automático a Render).

#### Notas
- **Sistema Operativo**: Ubuntu 24.04 (Noble) / WSL (mencionado en logs para apt install).
- **Paquetes Adicionales**: Laravel Breeze 2.3 (auth), Vite 7.1.9 (assets), Tailwind CSS (estilos en vistas).
- **Cumplimiento**: Todo alineado con README (Laravel MVC, MySQL, Git, Composer, npm, Docker).
