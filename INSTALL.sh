#!/bin/bash

composer install
yarn install
touch .env.local
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
npm run dev