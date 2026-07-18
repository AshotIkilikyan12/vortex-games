FROM php:8.4-apache

# Install system packages
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libicu-dev \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        intl \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache Rewrite
RUN a2enmod rewrite

# Configure Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri \
    -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" \
    /etc/apache2/sites-available/000-default.conf

RUN printf '%s\n' \
'<Directory "/var/www/html/public">' \
'    AllowOverride All' \
'    Require all granted' \
'</Directory>' \
>> /etc/apache2/apache2.conf

# Optional (removes Apache warning)
RUN echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project
COPY . /var/www/html

WORKDIR /var/www/html

ENV APP_ENV=prod
ENV APP_DEBUG=0
ENV FLEX_SKIP_REGISTRATION_CHECK=1

# Install PHP dependencies
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --ignore-platform-reqs \
    --no-interaction

# Create writable directories
RUN mkdir -p \
        var/cache \
        var/log \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R ug+rwX var

# Warm Symfony cache (ignore if DATABASE_URL isn't available during build)
RUN php bin/console cache:clear --env=prod --no-debug || true
RUN php bin/console cache:warmup --env=prod || true

# Entrypoint
RUN chmod +x docker-entrypoint.sh

ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]

EXPOSE 80