# Utiliser l'image de base PHP avec Apache
FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git \
    && docker-php-ext-install zip pdo pdo_mysql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer Node.js et npm
RUN apt-get install -y nodejs npm

# Installer la CLI Symfony
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Copier le contenu du projet dans le répertoire web du conteneur
COPY . /var/www/html/

# Changer le propriétaire des fichiers pour www-data
RUN chown -R www-data:www-data /var/www/html

# Créer le répertoire de cache Composer en tant que root
RUN mkdir -p /var/www/.cache/composer && \
    chown -R www-data:www-data /var/www/.cache

# Changer les permissions du cache npm
RUN mkdir -p /var/www/.npm && \
    chown -R www-data:www-data /var/www/.npm

# Passer en utilisateur www-data
USER www-data

# Installer les dépendances PHP avec Composer
RUN composer install --optimize-autoloader

# Installer les dépendances npm
RUN npm install

# Configurer les permissions du dossier var (cache et logs)
RUN mkdir -p /var/www/html/var/cache /var/www/html/var/log && \
    chown -R www-data:www-data /var/www/html/var

# Exposer le port 80 (déjà fait par l'image php:apache de base)
EXPOSE 80

# Démarrer le serveur Apache
CMD ["apache2-foreground"]