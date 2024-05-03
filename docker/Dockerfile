FROM wodby/php:8.1

USER root

RUN set -xe; \
    apk add --update autoconf;

# Change to non-root privilege
USER wodby

RUN set -xe; \
    wget https://get.symfony.com/cli/installer -O - | bash; \
    mv /home/wodby/.symfony5/bin/symfony /usr/local/bin/symfony;

#WORKDIR /var/www/html
#CMD ["composer install"]
#RUN composer install --ignore-platform-reqs --prefer-dist --no-scripts --no-progress
#RUN php bin/console doctrine:database:create && php bin/console doctrine:migrations:migrate --no-interaction
