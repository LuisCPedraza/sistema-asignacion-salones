### Guía de Inicio para Nuevos Integrantes

Esta guía te ayudará a configurar tu entorno de trabajo y a obtener el código del proyecto.

---

### Paso 1: Configurar el Entorno

1.  **Abre la terminal de tu laptop (en Ubuntu).**

2.  **Verifica que los programas necesarios estén instalados:**
    Ejecuta estos comandos uno por uno y verifica que cada uno muestre una versión, lo que indica que está instalado:
    ```bash
    docker --version
    docker compose version
    php --version
    composer --version
    git --version
    ```
    Si alguno de los programas no está instalado, por favor avísale a tu líder de equipo para que te brinde los comandos de instalación.
    
**Comandos de instalación (si son necesarios)**
* Para instalar **Git**: `sudo apt update && sudo apt install git`
* Para instalar **Ubuntu** `wsl --install -d Ubuntu`
* Para instalar **Docker Desktop en Windows**
  - Ve a la página oficial: Docker Desktop Windows.
  - Descarga e instala la versión para Windows 10/11.
  - Durante la instalación, asegúrate de habilitar WSL2 backend.
  - Reinicia tu laptop.
  - Abre Docker Desktop → en Settings revisa que “Use the WSL2 based engine” esté activado.
  - Desde Ubuntu Terminal (WSL2), prueba:
    * docker --version
    * docker run hello-world
* Para instalar **Docker**: `sudo apt update && sudo apt install docker.io`
* Para instalar **Docker Compose**: Sigue las instrucciones oficiales de la documentación de Docker para tu sistema operativo, ya que su instalación puede variar.
* Para instalar **PHP**: `sudo apt update && sudo apt install php8.3-fpm`
* Para instalar **Composer**: `php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer && rm composer-setup.php`
* Para instalar **Node.js y npm**: `sudo apt update && sudo apt install nodejs npm`

---

### Paso 2: Obtener el Código del Proyecto

1.  **Clona el repositorio:**
    Asegúrate de estar en el directorio donde quieres que se guarde el proyecto y clónalo desde GitHub.
    ```bash
    git clone [https://github.com/LuisCPedraza/sistema-asignacion-salones.git](https://github.com/LuisCPedraza/sistema-asignacion-salones.git)
    ```

2.  **Accede a la carpeta del proyecto:**
    ```bash
    cd sistema-asignacion-salones
    ```

---

### Paso 3: Iniciar y Configurar el Entorno de Docker

1.  **Inicia los contenedores:**
    Este comando construye las imágenes y levanta los servicios de la aplicación y la base de datos que se definen en los archivos `docker-compose.yml` y `Dockerfile`.
    ```bash
    docker compose up -d --build
    ```

2.  **Ejecuta las migraciones de la base de datos:**
    Este paso es crucial para crear las tablas necesarias en la base de datos.
    ```bash
    docker exec -it sistema-salones-app php artisan migrate
    ```

3.  **Inicia el servidor de desarrollo:**
    ```bash
    docker exec -it sistema-salones-app php artisan serve --host=0.0.0.0 --port=80
    ```
    Ahora puedes abrir tu navegador y visitar `http://localhost:8000` para ver el proyecto en funcionamiento.

---

¡Listo! Tu entorno de trabajo está configurado y el proyecto está corriendo. Ahora puedes empezar a trabajar en las tareas asignadas.
