FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && \
    apt-get install -y libpq-dev postgresql-client unzip curl git && \
    docker-php-ext-install pdo pdo_pgsql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable mod_rewrite
RUN a2enmod rewrite
RUN a2enmod headers

# Allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copy files
#COPY ./src/ /var/www/html/
COPY wait-for-postgres.sh /wait-for-postgres.sh

# Set permissions
RUN chmod +x /wait-for-postgres.sh

CMD ["/wait-for-postgres.sh"]