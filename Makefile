install:
		sh bin/install.sh

ps:
		docker-compose ps

up:
		docker-compose up -d

bash:
		docker exec -ti symfony-leboncoin bash

stop:
		docker-compose stop

deploy:
		sh bin/deploy.sh

restart: stop up

build-dev:
	    docker exec symfony-leboncoin chown -R www-data: var/
		docker exec symfony-leboncoin sh -c 'composer install'
		docker exec symfony-leboncoin sh -c 'bin/console assets:install public'
		docker exec symfony-leboncoin sh -c 'bin/console doctrine:schema:update --force'
		docker exec symfony sh -c 'bin/console cache:clear'
