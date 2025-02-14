#!/bin/sh
php artisan migrate --force

/usr/bin/supervisord -c /etc/supervisord.conf