# Test task for reg.media company
## Installation

For install you need to be installed:
- Docker
- Docker-Compose

As a first step run:
```sh
git clone git@github.com:ZinoviL/test-task-reg-media.git
cd test-task-reg-media
```

Then change the .env file for yourself and execute next commands for start containers and install dependencies:
```sh
cp src/.env.example src/.env
docker-compose up
docker exec -ti mysql mysql -ppassword -e 'source /data/init.sql'
docker exec -ti app composer install
docker exec -ti app php artisan key:generate
docker exec -ti app php artisan migrate
```

For start tests use:
```sh
docker exec -ti app ./vendor/bin/phpunit
```
