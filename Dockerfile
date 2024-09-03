# Utiliser l'image de base PHP avec Apache
FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier le contenu du projet dans le répertoire web du conteneur
COPY . /var/www/html/

# Changer le propriétaire des fichiers pour www-data
RUN chown -R www-data:www-data /var/www/html

# Passer en utilisateur www-data
USER www-data

# Installer les dépendances PHP avec Composer
RUN composer install --optimize-autoloader

# Configurer les permissions du dossier var (cache et logs)
RUN mkdir -p /var/www/html/var/cache /var/www/html/var/log && \
    chown -R www-data:www-data /var/www/html/var

# Exposer le port 80 (déjà fait par l'image php:apache de base)
EXPOSE 80