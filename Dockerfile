FROM php:8.4-apache

# Տեղադրում ենք անհրաժեշտ համակարգային գրադարանները
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql intl zip

# Միացնում ենք Apache mod_rewrite-ը
RUN a2enmod rewrite

# Փոխում ենք Apache-ի Document Root-ը
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Տեղափոխում ենք ֆայլերը
COPY . /var/www/html

# Տեղադրում ենք Composer-ը
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV APP_ENV=prod
ENV FLEX_SKIP_REGISTRATION_CHECK=1

# Տեղադրում ենք կախվածությունները առանց սկրիպտների
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts

# Թույլտվություններ var թղթապանակի համար
RUN mkdir -p /var/www/html/var && chown -R www-data:www-data /var/www/html/var

EXPOSE 80