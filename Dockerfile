FROM php:8.2-apache

# Տեղադրում ենք անհրաժեշտ համակարգային գրադարանները
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_pgsql intl zip

# Միացնում ենք Apache mod_rewrite-ը Symfony-ի համար
RUN a2enmod rewrite

# Փոխում ենք Apache-ի Document Root-ը դեպի public թղթապանակ
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Տեղափոխում ենք նախագծի ֆայլերը
COPY . /var/www/html

# Տեղադրում ենք Composer-ը
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ավելացնում ենք --ignore-platform-reqs, որպեսզի platform extension-ների պատճառով չկանգնի
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Տալիս ենք թույլտվություններ (Permissions) cache-ի համար
RUN mkdir -p /var/www/html/var && chown -R www-data:www-data /var/www/html/var

EXPOSE 80