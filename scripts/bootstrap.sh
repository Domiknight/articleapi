#!/usr/bin/env bash

# Additional Repos
## php 5.6
apt-get install -y software-properties-common
apt-get install -y python-software-properties
add-apt-repository -y ppa:ondrej/php

# Update
apt-get update

# Apache
apt-get install -y apache2

# PHP
apt-get install -y php5.6-cli php5.6 php5.6-mysql php5.6-mbstring php5.6-curl php5.6-common php5.6-xml php5.6-pdo php5.6-dom php5.6-intl

# MySQL
debconf-set-selections <<< 'mysql-server mysql-server/root_password password Password'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password Password'
apt-get install -y mysql-server

# Git & zip
apt-get install -y git zip unzip

# load the changes
a2enmod rewrite
service apache2 restart