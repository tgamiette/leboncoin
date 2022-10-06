install:
		sh bin/install.sh

ps:
		docker-compose ps

up:
		docker-compose up -d

fix:
		docker-compose exec symfony sh -c 'vendor/bin/php-cs-fixer fix src/'

bash:
		docker exec symfony bash

stop:
		docker-compose stop

deploy:
		sh bin/deploy.sh

restart: stop up

build-dev:
	    docker exec symfony chown -R www-data: var/
		docker exec symfony sh -c 'composer install'
		docker exec symfony sh -c 'bin/console assets:install public'
		docker exec symfony sh -c 'bin/console doctrine:schema:update --force'
		#docker-compose exec apache sh -c 'bin/console lexik:translations:import'
		docker exec symfony sh -c 'bin/console cache:clear'
