

FROM php:8.2-apache

# Copier tout le contenu du projet dans le répertoire web du conteneur
COPY . /var/www/html/

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql

USER www-data