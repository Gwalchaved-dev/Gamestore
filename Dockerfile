# Utiliser l'image de base PHP avec Apache
FROM php:8.2-apache

# Mettre à jour les paquets et installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    curl git libzip-dev nodejs npm unzip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*  # Nettoyage après l'installation

# Copier le fichier de configuration Apache personnalisé
COPY ./config/apache/apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Installer Composer directement à partir de l'image officielle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer la CLI Symfony
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Copier le contenu du projet dans le répertoire web du conteneur
COPY . /var/www/html/

# Changer l'utilisateur en root pour créer et configurer les répertoires
USER root

# Créer les répertoires cache et npm pour composer et npm
RUN mkdir -p /var/www/.cache/composer /var/www/.npm \
    && chown -R www-data:www-data /var/www/.cache /var/www/.npm \
    && chown -R www-data:www-data /var/www/html

# Installer l'extension MongoDB via PECL et l'activer
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Vérifier que l'extension MongoDB est bien installée
RUN php -m | grep mongodb

# Revenir à l'utilisateur www-data pour exécuter les actions sur les fichiers
USER www-data

# Installer les dépendances PHP avec Composer
RUN composer install --optimize-autoloader --no-interaction

# Installer les dépendances npm
RUN npm install --no-audit --no-fund --prefer-offline

# Configurer les permissions des dossiers var (cache et logs)
RUN mkdir -p /var/www/html/var/cache /var/www/html/var/log \
    && chown -R www-data:www-data /var/www/html/var

# Exposer le port 80 pour le serveur Apache
EXPOSE 80

# Démarrer le serveur Apache
CMD ["apache2-foreground"]