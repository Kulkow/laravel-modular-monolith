#  Modular Monolith is variant structure

## Description
Example separation per modules - application

## Technologies
- PHP 8.4, Laravel 13
- PostgreSQL
- Redis
- Docker

## Installation and launch for local
````
  docker-compose up -d
````

## Run unit test
````
  vendor/bin/phpunit --testsuite=Modules_Unit
````

## Run stat-analise

````
  vendor/bin/phpstan analyse app/Modules/Identity
````

## Make console command

````
php artisan app:make-migration add_role_slug_in_roles --module=Identity
````
