# Eventos Marta Abreu

Esta es una aplicación web desarrollada con Laravel para la gestión de eventos de Marta Abreu. Permite administrar eventos, ediciones, categorías, modalidades y usuarios.

## Requisitos Previos

Antes de comenzar, asegúrate de tener instalados los siguientes componentes en tu sistema:

- **PHP** >= 8.1
- **Composer** (gestor de dependencias de PHP)
- **Node.js** >= 16.x y **npm** (para gestionar dependencias de JavaScript)
- **Git** (para clonar el repositorio)
- Un servidor de base de datos compatible con Laravel (MySQL, PostgreSQL, SQLite, etc.)

## Instalación y Configuración

Sigue estos pasos para configurar y ejecutar la aplicación en tu entorno local:

### 1. Clonar el Repositorio

```bash
git clone <URL_DEL_REPOSITORIO>
cd eventos_marta_abreu
```

Reemplaza `<URL_DEL_REPOSITORIO>` con la URL real del repositorio en GitHub.

### 2. Instalar Dependencias de PHP

Instala las dependencias de PHP utilizando Composer:

```bash
composer install
```

### 3. Instalar Dependencias de JavaScript

Instala las dependencias de JavaScript utilizando npm:

```bash
npm install
```

### 4. Configurar el Entorno

Copia el archivo de configuración de ejemplo y configura tu entorno:

```bash
cp .env.example .env
```

Edita el archivo `.env` para configurar:

- La conexión a la base de datos (DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- Otras configuraciones necesarias (APP_NAME, APP_URL, etc.)

### 5. Generar la Clave de la Aplicación

Genera una clave única para la aplicación:

```bash
php artisan key:generate
```

### 6. Migrar la Base de Datos

Ejecuta las migraciones para crear las tablas en la base de datos:

```bash
php artisan migrate
```

### 7. Ejecutar los Seeders (Opcional)

Si deseas poblar la base de datos con datos de ejemplo, incluyendo un usuario administrador:

```bash
php artisan db:seed
```

Esto ejecutará el seeder `AdminUserSeeder` y otros seeders definidos en `DatabaseSeeder`.

### 8. Construir los Assets

Compila los assets de frontend (CSS y JS) utilizando Vite:

Para desarrollo (con recarga automática):

```bash
npm run dev
```

Para producción:

```bash
npm run build
```

### 9. Ejecutar la Aplicación

Inicia el servidor de desarrollo de Laravel:

```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`.

## Estructura del Proyecto

- **app/Models/**: Modelos de Eloquent (Evento, Edicion, Categoria, Modalidad, User)
- **app/Http/Controllers/**: Controladores de la aplicación
- **database/migrations/**: Migraciones de base de datos
- **database/seeders/**: Seeders para poblar datos iniciales
- **resources/views/**: Vistas Blade
- **routes/web.php**: Rutas web
- **public/**: Archivos públicos (CSS, JS compilados, imágenes)

