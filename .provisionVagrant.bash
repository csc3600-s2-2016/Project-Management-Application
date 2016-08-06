#!/bin/bash
echo "Provisioning box..."
echo "Installing laravel dependencies with composer..."
cd /var/www/taskmanager
composer install

echo "Creating .env..."
cp ../.env.scotchbox .env
php artisan key:generate > /dev/null

echo "Installing dependencies with npm..."
sudo chown -R $(whoami) $(npm config get prefix)/{lib/node_modules,bin,share} > /dev/null
npm install bootstrap-sass
npm install vue
sudo npm install -g browserify

