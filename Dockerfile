# Définir l'image de base
FROM php:8.0-apache

# Copier les fichiers de l'application dans le conteneur
WORKDIR /var/www/html

COPY . .

# Installer les dépendances de l'application
RUN apt-get update && \
    apt-get install -y \
        libicu-dev \
        libzip-dev \
        zip \
        unzip \
        git && \
    docker-php-ext-configure intl && \
    docker-php-ext-install intl pdo_mysql zip && \
    pecl install redis && \
    docker-php-ext-enable redis && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-dev

# Définir les variables d'environnement pour Laravel
# ENV APP_NAME=Laravel \
#     APP_ENV=production \
#     APP_DEBUG=false \
#     APP_URL=http://localhost \
#     LOG_CHANNEL=stderr \
#     DB_CONNECTION=mysql \
#     DB_HOST=mysql \
#     DB_PORT=3306 \
#     DB_DATABASE=laravel \
#     DB_USERNAME=root \
#     DB_PASSWORD=

# Exposer le port 80 pour Apache
EXPOSE 8000

# COPY 000-default.conf /etc/apache2/sites-available/

# RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# RUN a2ensite 000-default.conf

# RUN service apache2 restart

# RUN chmod -R 777 /var/www/html/storage

# Lancer Apache au démarrage du conteneur
CMD php artisan serve  --port=8000