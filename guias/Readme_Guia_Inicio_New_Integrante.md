### Guía de Inicio para Nuevos Integrantes

Esta guía te ayudará a configurar tu entorno de trabajo y a obtener el código del proyecto.

---

### Paso 1: Configurar el Entorno

1. **Instala Ubuntu desde la terminal de Windows**
* Para instalar **Ubuntu**
  ```bash
  wsl --install -d Ubuntu
  ```
2.  **Abre la terminal de tu laptop (en Ubuntu).**

3.  **Actualizar el Sistema**
   - Primero, es una buena práctica actualizar la lista de paquetes de tu sistema.
  ```bash
  sudo apt update && sudo apt upgrade -y
  ```
4.  **Verifica que los programas necesarios estén instalados:**
    Ejecuta estos comandos uno por uno y verifica que cada uno muestre una versión, lo que indica que está instalado:
    ```bash
    docker --version
    docker compose version
    php --version
    composer --version
    git --version
    ```
    Si alguno de los programas no está instalado, por favor sigue los siguientes pasos.    
**Comandos de instalación (si son necesarios)**
* Para instalar **Git**: 
  ```bash
  sudo apt install git -y
  ```
* Para instalar **PHP**: 
  ```bash
  # Instala las dependencias necesarias
  sudo apt install -y software-properties-common
  # Agrega el repositorio de PHP
  sudo add-apt-repository ppa:ondrej/php -y
  # Actualiza la lista de paquetes nuevamente
  sudo apt update
  # Instala PHP 8.3 y algunas extensiones comunes
  sudo apt install php8.3-cli php8.3-common php8.3-xml php8.3-zip php8.3-curl php8.3-mbstring -y
  ```
* Para instalar **Composer**: 
  ```bash
  # Descarga el instalador de Composer
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  # Ejecuta el instalador
  sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
  # Elimina el archivo de instalación
  php -r "unlink('composer-setup.php');"
  ```
* Para instalar **Docker y Docker Compose**: 
  ```bash
  # Descarga y ejecuta el script de instalación oficial de Docker
  curl -fsSL https://get.docker.com -o get-docker.sh
  sudo sh get-docker.sh    
  # Agrega tu usuario al grupo 'docker' para no tener que usar 'sudo' siempre
  sudo usermod -aG docker $USER
  ```
    - Importante: Después de ejecutar usermod, cierra la sesión o reinicia la terminal para que los cambios surtan efecto. Docker Compose ya viene incluido con esta instalación de Docker.
* Verificación final ✅
  - Una vez que hayas terminado (y reiniciado la terminal), puedes verificar que todo se instaló correctamente con los mismos comandos que usaste al principio.
  ```bash
  git --version
  php --version
  composer --version
  docker --version
  docker compose version
  ```
---

### Paso 2: Bifurcación (Fork) del Repositorio

1.  **Fork:**
- Dev B debe crear una bifurcación (fork) del repositorio de Dev A en su propia cuenta de GitHub.
    - Abra github.com con su cuenta
    - En la barra de navegación escriba la dirección completa del repositorio original de Dev A (https://github.com/LuisCPedraza/sistema-asignacion-salones)
    - En el botón "Fork", selecciona de la lista la opción + create a new fork, Esto crea una copia exacta del repositorio en la cuenta de Dev B en tu cuenta.

---

### Paso 3: Clonar el Fork (Local)

1.  **Clona el repositorio:**
    Desde la terminal de Ubuntu, asegúrate de estar en el directorio donde quieres que se guarde el proyecto y clónalo desde GitHub.
    ```bash
    git clone https://github.com/cambiar_por_nombre_de_usuario/sistema-asignacion-salones.git
    ```

2.  **Accede a la carpeta del proyecto:**
    ```bash
    cd sistema-asignacion-salones
    ```

---

### Paso 4: Iniciar y Configurar el Entorno de Docker

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
