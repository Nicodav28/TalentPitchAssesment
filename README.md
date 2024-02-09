### Manera 1
## Ejecución de la Prueba Técnica en Laravel utilizando Postman

Si no se cuenta con la posibilidad de clonar el proyecto, también puedes probar la API utilizando Postman. Sigue los siguientes pasos:

1. **Descarga e instala Postman:**
   Descarga e instala Postman desde [su sitio web oficial](https://www.postman.com/downloads/).

2. **Abre Postman:**
   Abre Postman en tu computadora.

3. **Importa la colección de solicitudes:**
   Haz clic en el botón "Importar" en la esquina superior izquierda de Postman. Selecciona la opción "Subir archivos" y selecciona el archivo de colección de solicitudes proporcionado.

4. **Modifica la URL base:**
   Una vez importada la colección, busca y selecciona la variable de entorno "BASE_URL". Modifica su valor para que coincida con la URL base del proyecto: `talentpitchassesment-production.up.railway.app`.

5. **Realiza las solicitudes:**
   Ahora puedes realizar solicitudes a las diferentes rutas de la API utilizando las solicitudes predefinidas en la colección de Postman. Cada solicitud está configurada con los métodos y rutas correspondientes para interactuar con los diferentes recursos del sistema.

6. **Visualiza y gestiona los resultados:**
   Después de realizar cada solicitud, Postman mostrará la respuesta del servidor. Puedes visualizar y gestionar los resultados según sea necesario.

Siguiendo estos pasos, podrás probar y familiarizarte con la Prueba Técnica de Laravel utilizando Postman sin necesidad de clonar el proyecto.

### Manera 2
## Guía para ejecutar la Prueba Técnica en Laravel

### Requisitos previos
- Tener instalado PHP8.2 o superior y Composer en su sistema.
- Acceso a una base de datos PostgreSQL.
- Conocimiento básico de Laravel y manejo de terminal.

### Pasos para ejecutar la Prueba Técnica

1. **Clonar el repositorio:**
   Clona el repositorio de la Prueba Técnica de Laravel desde el dominio especificado:

   ```bash
   [git clone talentpitchassesment-production.up.railway.app](https://github.com/Nicodav28/TalentPitchAssesment.git)
   ```

2. **Configurar el entorno:**
   - Copia el contenido del archivo `.env.example` y pégalo en un nuevo archivo llamado `.env`.
   - Modifica las variables del archivo `.env` para que coincidan con la configuración de tu base de datos PostgreSQL y otras configuraciones específicas del proyecto.

   ```dotenv
   APP_NAME=Laravel
   APP_ENV=local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://localhost

   LOG_CHANNEL=stack
   LOG_DEPRECATIONS_CHANNEL=null
   LOG_LEVEL=debug

    #En caso de no contar con una BDD PGSQL usar los datos de conexion que se encuentran a continuacion.
   DB_CONNECTION=pgsql
   DB_HOST=roundhouse.proxy.rlwy.net
   DB_PORT=35060
   DB_DATABASE=TalentPitch
   DB_USERNAME=postgres
   DB_PASSWORD=f3e-3DbD5*GbBcfBFB5gbcBaAfC4e51a

   BROADCAST_DRIVER=log
   CACHE_DRIVER=file
   FILESYSTEM_DISK=local
   QUEUE_CONNECTION=sync
   SESSION_DRIVER=file
   SESSION_LIFETIME=120

   OPEN_API_KEY="TU_API_KEY_AQUI"
   MAX_TOKENS_QUANTITY=3000
   ```

3. **Instalar dependencias:**
   Ejecuta el siguiente comando para instalar todas las dependencias del proyecto:

   ```bash
   composer install
   ```

4. **Generar la clave de la aplicación:**
   Ejecuta el siguiente comando para generar la clave de la aplicación:

   ```bash
   php artisan key:generate
   ```

5. **Ejecutar migraciones:**
   Ejecuta el siguiente comando para migrar la base de datos y crear las tablas necesarias:

   ```bash
   php artisan migrate
   ```

6. **Iniciar el servidor:**
   Puedes iniciar un servidor local ejecutando el siguiente comando:

   ```bash
   php artisan serve
   ```

   Una vez iniciado, puedes acceder al proyecto en `http://localhost:8000`.

### Uso de la API

La API proporciona las siguientes rutas para interactuar con los recursos del sistema:

- **Usuarios:**
  - Generar datos de usuarios: `GET /api/users/generate/{dataQuantity}`
  - Obtener todos los usuarios: `GET /api/users/fetch`
  - Obtener un usuario específico: `GET /api/users/{id}`
  - Actualizar un usuario: `PUT /api/users/{id}`
    - **Solicitud JSON de actualización:**
    ```json
    {
        "name": "Nuevo nombre",
        "email": "nuevoemail@example.com",
        "image_path": "nueva_ruta_de_imagen.jpg"
    }
    ```
  - Eliminar un usuario: `DELETE /api/users/{id}`

- **Desafíos:**
  - Generar datos de desafíos: `GET /api/challenges/generate/{dataQuantity}`
  - Obtener todos los desafíos: `GET /api/challenges/fetch`
  - Obtener un desafío específico: `GET /api/challenges/{id}`
  - Actualizar un desafío: `PUT /api/challenges/{id}`
    - **Solicitud JSON de actualización:**
    ```json
    {
        "title": "Nuevo título del desafío",
        "description": "Nueva descripción del desafío",
        "difficulty": 5,
        "user_id": 1
    }
    ```
  - Eliminar un desafío: `DELETE /api/challenges/{id}`

- **Empresas:**
  - Generar datos de empresas: `GET /api/companies/generate/{dataQuantity}`
  - Obtener todas las empresas: `GET /api/companies/fetch`
  - Obtener una empresa específica: `GET /api/companies/{id}`
  - Actualizar una empresa: `PUT /api/companies/{id}`
    - **Solicitud JSON de actualización:**
    ```json
    {
        "name": "Nuevo nombre de la empresa",
        "image_path": "nueva_ruta_de_imagen_empresa.jpg",
        "location": "Nueva ubicación",
        "industry": "Nueva industria",
        "user_id": 1
    }
    ```
  - Eliminar una empresa: `DELETE /api/companies/{id}`

- **Programas:**
  - Generar datos de programas: `GET /api/programs/generate/{dataQuantity}`
  - Obtener todos los programas: `GET /api/programs/fetch`
  - Obtener un programa específico: `GET /api/programs/{id}`
  - Actualizar un programa: `PUT /api/programs/{id}`
    - **Solicitud JSON de actualización:**
    ```json
    {
      "title": "Nuevo título del programa",
      "description": "Nueva descripción del programa",
      "start_date": "2024-02-09",
      "end_date": "2024-02-16",
      "user_id": 1
    }
    ```
  - Eliminar un programa: `DELETE /api/programs/{id}`

- **Participantes del programa:**
  - Generar datos de participantes del programa: `GET /api/program-participants/generate/{dataQuantity}`
  - Obtener todos los participantes del programa: `GET /api/program-participants/fetch`
  - Obtener un participante del programa específico: `GET /api/program-participants/{id}`
  - Actualizar un participante del programa: `PUT /api/program-participants/{id}`
    - **Solicitud JSON de actualización:**
    ```json
    {
      "program_id": 1,
      "entity_type": "user",
      "entity_id": 1
    }
  - Eliminar un participante del programa: `DELETE /api/program-participants/{id}`