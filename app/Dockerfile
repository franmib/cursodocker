#Para la APP
FROM php:8.1-apache
COPY index.php /var/www/html/
COPY conn.php /var/www/html/
COPY info.php /var/www/html/
RUN docker-php-ext-install mysqli

# Para el SimpleSAML

RUN mkdir -p /var/www/simplesaml
COPY ./simplesaml /var/www/simplesaml
COPY ./simplesaml/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite
RUN service apache2 restart
EXPOSE 80