FROM richarvey/nginx-php-fpm:2.1.2

LABEL maintainer="John Fredy Velasco Bareño <jovel882@gmail.com>"
LABEL author="John Fredy Velasco Bareño <jovel882@gmail.com>"

# Install dependencies
RUN apk --no-cache update && \
    apk --no-cache add ${PHPIZE_DEPS} nodejs npm && \
    docker-php-ext-configure pcntl --enable-pcntl && \
    docker-php-ext-install pcntl && \
    apk del ${PHPIZE_DEPS}

# Create supervisor configuration directory
RUN mkdir -p /etc/supervisor/conf.d/

# Copy Nginx and Supervisor configurations
COPY --chmod=755 ./Docker/default.conf /etc/nginx/sites-available/default.conf
COPY --chmod=755 ./Docker/horizon.conf /etc/supervisor/conf.d/horizon.conf

# Copy file run
COPY --chmod=755 ./Docker/start.sh /start_zoe.sh

# Copy app file
COPY . /var/www/html

CMD ["/start_zoe.sh"]