#!/usr/bin/env bash


DIR=$(basename `pwd`)

if [ $DIR == "scripts" ]
        then
		cd ..
fi

if [ ! -f "composer.json" ]
	then
		echo "ERROR: Run this script from the project root."
		exit
fi


echo "[--- Downloading composer packages ---]"
composer install


echo "[--- Running php artisan key:generate ---]"
php artisan key:generate


echo "[--- Running php artisan migrate ---]"
php artisan migrate

echo "[--- Running php artisan migrate --database=mysql_test ---]"
php artisan migrate --database=mysql_test

if [[ "$@" = "-ds" ]]
	then
		echo "[--- running php artisan db:seed --class=DevelopmentSeeder ---]"
		php artisan db:seed --class=DevelopmentSeeder
fi

if [[ "$@" = "-ps" ]]
	then
		echo "[--- running php artisan db:seed ---]"
		php artisan db:seed
fi

echo "[--- Running php artisan ide-helper:generate ---]"
php artisan ide-helper:generate

echo "[--- Running php artisan search:rebuild ---]"
php artisan search:rebuild


echo "[--- Running npm install in / ---]"
npm install



mkdir -p ./resources/assets/asimov/node_modules

echo "[--- Running npm install in /resources/assets/asimov ---]"
npm install ./resources/assets/asimov --prefix ./resources/assets/asimov

echo "[--- Running grunt in /resources/assets/asimov ---]"
grunt --base resources/assets/asimov --gruntfile resources/assets/asimov/gruntfile.js


mkdir -p ./resources/assets/frontend/node_modules

echo "[--- Running npm install in /resources/assets/asimov ---]"
npm install ./resources/assets/frontend --prefix ./resources/assets/frontend

echo "[--- Running grunt in /resources/assets/frontend ---]"
grunt --base resources/assets/frontend --gruntfile resources/assets/frontend/gruntfile.js
