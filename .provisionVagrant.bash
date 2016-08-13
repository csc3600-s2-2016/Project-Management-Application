#!/bin/bash
echo "Provisioning box..."
echo "Installing laravel dependencies with composer..."
cd /var/www/taskmanager
composer install

echo "Creating .env..."
sudo cp ../.env.scotchbox .env
sudo cp ../scotchbox.local.conf /etc/apache2/sites-available/
sudo a2ensite scotchbox.local.conf
sudo service apache2 restart

php artisan key:generate > /dev/null

echo "Installing dependencies with npm..."
sudo chown -R $(whoami) $(npm config get prefix) > /dev/null
#Upgrade to latest npm
npm install -g npm
#Upgrade to latest node
npm cache clean
npm install -g n
n stable

npm install #install required node modules / packages

#install additional packages required for project
npm install vue-resource	
npm install vue-sortable
npm install bootstrap-material-design # For android-like bootstrap theme

gulp

