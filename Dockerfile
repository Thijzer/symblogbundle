FROM php:7.1.9-apache

COPY .docker/php/php.ini /usr/local/etc/php/
COPY . /var/www/html
COPY .docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install opcache

# blackfire Extension
RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/linux/amd64/$version \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp \
    && mv /tmp/blackfire-*.so $(php -r "echo ini_get('extension_dir');")/blackfire.so \
    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\n" > $PHP_INI_DIR/conf.d/blackfire.ini