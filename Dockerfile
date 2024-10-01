# Utiliser l'image de base PHP avec Apache
FROM php:8.2-apache

# Installer les extensions PHP nécessaires et modules Apache requis
RUN apt-get update && apt-get install -y \
    curl git libzip-dev nodejs npm unzip \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Copier le fichier de configuration Apache personnalisé
COPY ./config/apache/apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer la CLI Symfony
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Copier le contenu du projet dans le répertoire web du conteneur
COPY . /var/www/html/

# Changer le propriétaire des fichiers pour www-data
RUN chown -R www-data:www-data /var/www/html

# Créer et donner les permissions sur les répertoires cache et npm
RUN mkdir -p /var/www/.cache/composer /var/www/.npm \
    && chown -R www-data:www-data /var/www/.cache /var/www/.npm

# Passer en utilisateur www-data
USER www-data

# Installer les dépendances PHP avec Composer
RUN composer install --optimize-autoloader

# Installer les dépendances npm
RUN npm install

# Configurer les permissions des dossiers var (cache et logs)
RUN mkdir -p /var/www/html/var/cache /var/www/html/var/log \
    && chown -R www-data:www-data /var/www/html/var

# Exposer le port 80 (déjà fait par l'image php:apache de base)
EXPOSE 80

# Démarrer le serveur Apache
CMD ["apache2-foreground"]