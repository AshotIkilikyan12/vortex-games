FROM php:8.4-apache

# Տեղադրում ենք անհրաժեշտ համակարգային գրադարանները
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

# Փոխում ենք Apache-ի Document Root-ը
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Տեղափոխում ենք ֆայլերը
COPY . /var/www/html

# Տեղադրում ենք Composer-ը
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV APP_ENV=prod

# Ավելացնում ենք հատուկ հրաման, որը build-ի ժամանակ անջատում է Symfony-ի cache-ի ավտոմատ գեներացումը,
# որպեսզի այն կատարվի միայն սերվերը վերջնական միանալիս
ENV FLEX_SKIP_REGISTRATION_CHECK=1
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts

# Հիմա աշխատեցնում ենք սկրիպտները առանձին
RUN php bin/console cache:clear --env=prod

# Թույլտվություններ cache-ի համար
RUN mkdir -p /var/www/html/var && chown -R www-data:www-data /var/www/html/var

EXPOSE 80