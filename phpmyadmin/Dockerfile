FROM phpmyadmin/phpmyadmin
COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite
RUN service apache2 restart 
EXPOSE 80