#!/bin/bash

# Copy config files
#DOCKER_COMPOSE_FILE="docker-compose.override.yml"
#if [ ! -f "$DOCKER_COMPOSE_FILE" ]; then
#  cp docker-compose.override.yml.dist docker-compose.override.yml
#  echo "$DOCKER_COMPOSE_FILE is copied"
#fi

#SYMFONY_ENV_FILE="app/symfony/.env"
#if [ ! -f "$SYMFONY_ENV_FILE" ]; then
#  cp app/symfony/.env.dist app/symfony/.env
#  echo "$SYMFONY_ENV_FILE is copied"
#fi

DOCKER_ENV_FILE=".env"
if [ ! -f "$DOCKER_ENV_FILE" ]; then
  cp .env.dist .env
  echo "$DOCKER_ENV_FILE is copied"
fi

# build docker compose
docker-compose up --force-recreate --build -d

docker exec symfony-leboncoin sh -c 'composer install'
docker exec symfony-leboncoin sh -c 'symfony console assets:install public'

docker exec symfony-leboncoin sh -c 'symfony console doctrine:database:create --if-not-exists'
docker exec symfony-leboncoin sh -c 'symfony console cache:clear'

docker exec symfony-leboncoin sh -c 'chown -Rf www-data: var/'
docker exec symfony-leboncoin sh -c 'symfony console d:s:u --force'
docker exec symfony-leboncoin sh -c 'symfony console d:f:l --append'

docker exec symfony-leboncoin sh -c 'npm install'
docker exec symfony-leboncoin sh -c 'npm run watch'