# Teddy
# Php-fpm-alpine & Nginx
### Symfony
#### Avec MariaDB & MailDev & Adminer & PMA

**Pensez à modifier le nom de votre bdd dans le fichier .env destiné à docker**

Pour lancer le projet (si vous souhaiter partir d'un projet vide supprimer le dossier symfony dans app)

````shell
make install
````

si vous avez déjà installé votre container  pour le reprendre dessus avec d'eventuel mise a jour  

````shell
make build-dev
````

start container 
````shell
make up
````


# J-F
# Php-fpm-alpine x Nginx
### Symfony | Docker

Avec MariaDB & MailDev

Pour lancer le projet :
````shell
docker-compose up -d
docker exec symfony_docker composer create-project symfony/skeleton symfony
sudo chown -R $USER ./
````
c
Pensez ensuite à aller exécuter toutes vos commandes depuis l'intérieur du container.

Par exemple :
````shell
cd symfony_project
composer require orm
````
(Demandez à Composer de NE PAS créer une config Docker pour la database)

Enfin, modifiez la config DB dans le fichier .env de Symfony :
````shell
DATABASE_URL=mysql://root:ChangeMeLater@db:3306/symfony_db?serverVersion=mariadb-10.7.1
````
