FROM wodby/php:8.1

USER root

RUN set -xe; \
    apk add --update autoconf;

# Change to non-root privilege
USER wodby

RUN set -xe; \
    wget https://get.symfony.com/cli/installer -O - | bash; \
    mv /home/wodby/.symfony5/bin/symfony /usr/local/bin/symfony;
