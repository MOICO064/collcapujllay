# Imagen base oficial de PHP con FPM
FROM php:8.3-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libpq-dev \
    libxslt1-dev \
    libmagickwand-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configurar e instalar extensiones PHP necesarias para Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    bcmath \
    exif \
    pcntl \
    gd \
    intl \
    zip

# Instalar Composer (oficial)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar archivos del proyecto (opcional si usas volumes)
COPY . .

# Permisos (importante para Laravel)
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Cambiar al usuario www-data
USER www-data

# Exponer puerto
EXPOSE 9000

# Comando por defecto
CMD ["php-fpm"]