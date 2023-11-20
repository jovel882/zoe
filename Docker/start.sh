#!/bin/bash

echo -e "zend_extension=xdebug.so\nxdebug.mode=coverage" >> /usr/local/etc/php/conf.d/xdebug.ini

cp .env.example .env && composer install && php artisan key:generate && php artisan migrate --seed && npm install && npm run build && vendor/bin/phpunit --coverage-html ./public/coverage --coverage-filter ./src

sed -i -r 's/^(APP_ENV=).*/\1production/' ".env" && sed -i -r 's/^(APP_DEBUG=).*/\1false/' ".env"

echo "* * * * * php /var/www/html/artisan schedule:run >> /dev/null 2>&1" >> /etc/crontabs/nginx && chmod 777 /etc/crontabs/nginx && crond

echo "8.8.8.8" >> /etc/resolv.conf

/bin/bash /start.sh