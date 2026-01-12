# Base image PHP CLI 8.4
FROM php:8.4-cli

# Instalar dependencias del sistema y extensiones necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libxml2-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    && docker-php-ext-install dom mbstring pdo pdo_mysql zip

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /app

# Copiar proyecto
COPY testlab/backend /app

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Limpiar caches de Laravel
RUN php artisan config:clear && php artisan route:clear 

# Variables de entorno (se leen automáticamente desde Railway)
ENV DB_CONNECTION=mysql \
    DB_HOST=mysql.railway.internal \
    DB_PORT=3306 \
    DB_DATABASE=railway \
    DB_USERNAME=root \
    DB_PASSWORD=${DB_PASSWORD} \
    DEFAULT_ADMIN_USER=${DEFAULT_ADMIN_USER} \
    DEFAULT_ADMIN_PASS=${DEFAULT_ADMIN_PASS}

# Exponer puerto
EXPOSE 8080

# Arrancar Laravel y ejecutar migraciones + seed
CMD php artisan migrate:fresh --force --seed && php -S 0.0.0.0:${PORT:-8080} -t public