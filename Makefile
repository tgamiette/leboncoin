install:
		sh bin/install.sh

ps:
		docker-compose ps

up:
		docker-compose up -d

bash:
		docker exec -ti symfony-leboncoin sh

stop:
		docker-compose stop

restart: stop up

build-dev:
	    docker exec symfony-leboncoin chown -R www-data: var/
		docker exec symfony-leboncoin sh -c 'composer install'
		docker exec symfony-leboncoin sh -c 'symfony console assets:install public'
		docker exec symfony-leboncoin sh -c 'symfony console doctrine:schema:update --force'
		docker exec symfony-leboncoin sh -c 'symfony console cache:clear'