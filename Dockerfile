FROM webdevops/php-apache:8.2

# Սահմանում ենք Apache-ի Document Root-ը դեպի public թղթապանակ
ENV WEB_DOCUMENT_ROOT=/app/public
ENV APP_ENV=prod

# Տեղափոխում ենք նախագծի ֆայլերը կոնտեյների մեջ
COPY . /app

# Անցնում ենք աշխատանքային թղթապանակ
WORKDIR /app

# Տեղադրում ենք PHP-ի գրադարանները առանց platform-ի սահմանափակումների
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Տալիս ենք ճիշտ թույլտվություններ cache-ի համար
RUN mkdir -p /app/var && chown -R application:application /app/var

EXPOSE 80