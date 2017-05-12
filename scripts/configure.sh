#!/usr/bin/env bash

# PHP
if ! [ -f /etc/php/5.6/apache2/php.ini.orig ]; then
    # Apache
    cp /etc/php/5.6/apache2/php.ini /etc/php/5.6/apache2/php.ini.orig
    sed -e 's/;date\.timezone =/date\.timezone = Australia\/Melbourne/' < /etc/php/5.6/apache2/php.ini > /etc/php/5.6/apache2/php.ini.new
    mv /etc/php/5.6/apache2/php.ini.new /etc/php/5.6/apache2/php.ini

    # CLI
    cp /etc/php/5.6/apache2/php.ini /etc/php/5.6/cli/php.ini.orig
    sed -e 's/;date\.timezone =/date\.timezone = Australia\/Melbourne/' < /etc/php/5.6/cli/php.ini > /etc/php/5.6/cli/php.ini.new
    mv /etc/php/5.6/cli/php.ini.new /etc/php/5.6/cli/php.ini
fi

# Apache
if ! [ -f /etc/apache2/sites-enabled/000-default.conf.orig ]; then
    # Point the Document Root to the right place
    cp /etc/apache2/sites-enabled/000-default.conf /etc/apache2/sites-enabled/000-default.conf.orig
    sed -e 's/DocumentRoot \/var\/www\/html/DocumentRoot \/vagrant\/web/' < /etc/apache2/sites-enabled/000-default.conf > /etc/apache2/sites-enabled/000-default.conf.new
    mv /etc/apache2/sites-enabled/000-default.conf.new /etc/apache2/sites-enabled/000-default.conf

    # Make apache run as the vagrant user - solves perms issues with shared folders
    echo "Make apache run as vagrant user"
    cp /etc/apache2/envvars /etc/apache2/envvars.orig
    sed -e 's/www\-data/vagrant/' < /etc/apache2/envvars > /etc/apache2/envvars.new
    mv /etc/apache2/envvars.new /etc/apache2/envvars
fi

# load the changes
a2enmod rewrite
service apache2 restart

