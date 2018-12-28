FROM git-registry.egs.kz/docker-images/php-apache:php-7.1.0

COPY config/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY config/php.ini /etc/php/7.1/apache2/php.ini
COPY src/ /var/www/html/


RUN chown -R www-data:www-data /var/www/html

#RUN chmod +x /var/www/html/composer.phar && \
#    php /var/www/html/composer.phar config -g github-oauth.github.com d3a1b53fc3b0383844e2d4bf69b0344f9f3170c5 && \
#    php /var/www/html/composer.phar install && \
