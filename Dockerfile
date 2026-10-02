# Digi-Santé Junior — Development Image
#
# FrankenPHP provides PHP 8.5 and the Caddy web server in a single container.
# The Symfony application runs from the /app working directory.

FROM dunglas/frankenphp:1-php8.5-bookworm

# Install system dependencies required by Composer.
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by the application.
# - pdo_mysql: MySQL database connectivity
# - intl:      Internationalization support
# - opcache:   PHP bytecode caching
# - zip:       ZIP archive support
RUN install-php-extensions \
    pdo_mysql \
    intl \
    opcache \
    zip

# Install Composer from the official Composer image.
COPY --from=composer/composer:2-bin /composer /usr/bin/composer

# Allow Composer to run as root inside the container.
ENV COMPOSER_ALLOW_SUPERUSER=1

# Load the application's development PHP configuration.
COPY docker/php.ini $PHP_INI_DIR/conf.d/zz-app.ini

# Configure FrankenPHP to serve the application over HTTP on port 80.
ENV SERVER_NAME=:80

# Set the Symfony application directory as the container working directory.
WORKDIR /app
