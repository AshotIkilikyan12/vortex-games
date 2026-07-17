FROM php:8.3-apache

# Տեղադրում ենք անհրաժեշտ գրադարանները Symfony-ի և PostgreSQL-ի համար
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_pgsql intl zip

# Միացնում ենք Apache mod_rewrite-ը
RUN a2enmod rewrite

# Փոխում ենք Apache-ի Document Root-ը դեպի public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Տեղափոխում ենք ֆայլերը
COPY . /var/www/html

# Տեղադրում ենք Composer-ը
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Կարևոր է սահմանել APP_ENV-ը հենց build-ի ժամանակ
ENV APP_ENV=prod

# Տեղադրում ենք PHP գրադարանները
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Թույլտվություններ cache-ի համար
RUN mkdir -p /var/www/html/var && chown -R www-data:www-data /var/www/html/var

EXPOSE 80