### Sección 1: Introducción al curso!! 1 / 1|7 min
### [1. Bienvenidos a este curso! Algunas recomendaciones antes de empezar 7 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17450464#questions/9295602)
- ![](https://trello-attachments.s3.amazonaws.com/5b014dcaf4507eacfc1b4540/5e7777d6cd7def249ee578fb/a1a623aba60aed0a7ecb57a28f31fc2a/image.png)
- Necesitas saber:
  - Haber trabajado con algún framework (laravel o symf)
  - Unit testing
    - Unitario y funcional
  - Conocimientos básicos de docker
  - xdebug
- Recomienda revisar todos los videos de la *Sección 5: Autenticación y registro 0 / 3|1 h 41 min* y despues realizarlo

### Sección2: Creación del proyecto
### [Repo original - bitbucket](https://bitbucket.org/juanwilde/sf5-expenses-api/src/master/)
- `composer create-project symfony/skeleton expenses_api`
  - Installing symfony/skeleton (v5.0.99)
- Abrir phpstorm para configurar la version de php 7.4
- Instala plugin en phpstorm que no hay en vscode (Symfony support by Daniel Espandiller)
- Configura `composer.json` la version de php a 7.4 y la descripcion del proyecto
- En phpstorm, preferences le indica la version de php que va a usar (phplint de sintaxis)
- Crear repo en bitbucket
- Configuración de gitignore
  - Hay que incluir en este fichero, archivos de certificados, contraseñas o que tengan datos sensibles que no se deben publicar
  - Agrego .vscode, no sabia que hay un pseudo lenguaje de marcado `##>tag y <##tag`
- Configuracion de .env
  - solo se deja esto:
  ```js
  ###> symfony/framework-bundle ###
  APP_ENV=dev
  APP_SECRET=8d38c09d701d0a11b3185428eb30d08b
  ###< symfony/framework-bundle ###
  ```
  - se crea una copia de lo anterior en .env.local
  - el **`.env`**, configuración común. Debe estar en nuestro repositorio para que nuestros compañeros 
  - en **`.env.local`** para entornos de cada de desarrollador
  - en **.gitignore** se agrega `.env.local`
- Comandos personalizados, como mi: `gitpush.sh`
  - `gita` git add
  - `gits` git status
  - `git commit -m "Initial commit"`
- Configura repo en **Bitbucket**
  - Enviar los cambios al repo:
    - `git remote add origin git@bitbucket.org:<milogin>/<nombre-repo>.git`
    - `git push -u origin master` 
      - `-u o --set-upstream` Esto crea la rama master

### Sección 3: Configuración de Docker 3 / 3|45 min
### [3. Configuración de Docker en Mac 26 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451272#questions/9295602)
- [Bitbucket rama - section2-video1-docker-config](https://bitbucket.org/juanwilde/sf5-expenses-api/src/b953a0090139df2d90c8eea1e7f8e11912315136/?at=section2%2Fvideo1-docker-config)
- Para usuarios de linux hay que instalar vbox
- Maq virtual con version 18.04 ubuntu, con git, docker y todos los paquetes relacionados con el curso
- También explicará como hacerlo en Windows
- Por cada sección creará una rama, de modo que se pueda cambiar de rama para consulta del código
  - `git checkout -b section1/vieo1-create-project; git push origin section1/video1-create-project`
  - Vemos que ahora tenemos esta rama en Bitbucket
- `git checkout -b section2/video1-docker-config`
- [Bitbucket rama: section2/video1-docker-config](https://bitbucket.org/juanwilde/sf5-expenses-api/src/5d07e74988543272786a6fc859836162e79bab3c/?at=section2%2Fvideo1-docker-config)
- copia archivos de configuración (que ya los tenía preparados) al proyecto.rama en curso
  ```js
  var/
  vendor/
  .env.local
  docker-compose.linux.yml
  docker-compose.macos.yml
  docker-sync.yml
  Makefile
  ```
  - ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/392x488/b5d1f52542385661e13ce24df6b9a7bf/image.png)
  ```js
  - Se incluyen ficheros:
  - docker-compose.linux.yml
  - docker-compose.macos.yml
  - docker-sync.yml
  - Makefile
  - docker/nginx/
    - default.conf
    - Dockerfile
  - docker/php/
    - Dockerfile
    - php.ini
    - xdebug.ini
    - xdebug-linux.ini  
  ```
- Se va a usar nginx en lugar de apache.
  - Es más ligero
  - Más sencillo de configurar
  - Es asincrono
  - Evita tener que instalar el paquete apache-pack para symfony ya que según el s.o se puede complicar su configuración
- **Empezamos la explicación:**
  - **makefile** `expenses_api/makefile`
  - `UID  = $(shell id -u)`
  - **UI_D** será una variable de entorno que se utilizará en los ficheros .yml de modo que se pueda crear un usuario con este id en los contenedores
  ```js
  #makefile
  ifeq ($(OS),Darwin)
    docker volume create --name=sf5-expenses-api-be-sync
    # levantará: la bd, el backend y el servicio web
    # servicios en cascada
    U_ID=${UID} docker-compose -f docker-compose.macos.yml up -d
    # docker-sync ayuda en al optimizacion del funcionamiento en mac
    U_ID=${UID} docker-sync start
  else
    ...
  ```
- **docker-sync**
  > Cuando tu app escribe un log se tienen que hacer varias peticiones entre entornos ya que no se puede escribir dirctamente si el kernel de mac no lo permite, entonces hay una latencia desde que se escribe en el contendor y se sincroniza con la carpeta local. En cortas palabras es lo que hace netbeans si se activa `crear copia en`
- **makefile docker-sync-restart** 
  > permite un reinicio en caso que haya mucha latencia entre el contenedor y el host
  ```js
  #makefile
  docker-sync-restart: ## Rebuild docker-sync stack and prepare environment
	U_ID=${UID} docker-sync-stack clean
	$(MAKE) run
	$(MAKE) prepare
  ```
  - [Que es prepare?](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451272#questions/9774800)
```js
#makefile
#!/bin/bash
# https://bitbucket.org/juanwilde/sf5-expenses-api/src/b953a0090139df2d90c8eea1e7f8e11912315136/Makefile?at=section2%2Fvideo1-docker-config

# en mac devuelve Darwin (código de la terminal de mac), ubuntu en linux y para windows hay 2
OS := $(shell uname)
# docker backend, que coincide con el nombre en docker-compose.yml container_name
DOCKER_BE = expenses_api

ifeq ($(OS),Darwin)
  # id de usuario que se va a usar en el entorno
  UID  = $(shell id -u)
else ifeq ($(OS),Linux)
  UID = $(shell id -u)
else
  UID = 1000 # windows hay q asignarle manualmente
endif

# U_ID será un argumento para el resto de archivos

help: ## Show this help message
  @echo "usage: make [target]"
  @echo
  @echo "targets:"
  @egrep "^(.+)\:\ ##\ (.+)" ${MAKEFILE_LIST} | column -t -c 2 -s ":#"

run: ## Start the containers
ifeq ($(OS),Darwin)
	docker volume create --name=sf5-expenses-api-be-sync
  # levantará: la bd, el backend y el servicio web
  # servicios en cascada
	U_ID=${UID} docker-compose -f docker-compose.macos.yml up -d
  # docker-sync ayuda en al optimizacion del funcionamiento en mac
	U_ID=${UID} docker-sync start
else
  U_ID=${UID} docker-compose -f docker-compose.linux.yml up -d
endif

stop: ## Stop the containers
ifeq ($(OS),Darwin)
	U_ID=${UID} docker-compose -f docker-compose.macos.yml stop
	U_ID=${UID} docker-sync stop
else
	U_ID=${UID} docker-compose -f docker-compose.linux.yml stop
endif

docker-sync-restart: ## Rebuild docker-sync stack and prepare environment
	U_ID=${UID} docker-sync-stack clean
	$(MAKE) run
	$(MAKE) prepare

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) run

build: ## Rebuilds all the containers
ifeq ($(OS),Darwin)
	U_ID=${UID} docker-compose -f docker-compose.macos.yml build --compress --parallel
else
	U_ID=${UID} docker-compose -f docker-compose.linux.yml build
endif

prepare: ## Runs backend commands
	$(MAKE) be-sf-permissions
	$(MAKE) composer-install
#	$(MAKE) migrations

# Backend commands
be-sf-permissions: ## Configure the Symfony permissions
	U_ID=${UID} docker exec -it -uroot ${DOCKER_BE} sh /usr/bin/sf-permissions

composer-install: ## Installs composer dependencies
	U_ID=${UID} docker exec --user ${UID} -it ${DOCKER_BE} composer install --no-scripts --no-interaction --optimize-autoloader

migrations: ## Runs the migrations
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} bin/console doctrine:migrations:migrate -n

be-logs: ## Tails the Symfony dev log
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} tail -f var/log/dev.log
# End backend commands

ssh-be: ## ssh's into the be container
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} bash

code-style: ## Runs php-cs to fix code styling following Symfony rules
  # es como un php linter para estilos de codificación.
	php-cs-fixer fix src --rules=@Symfony
#	php-cs-fixer fix tests --rules=@Symfony
```
- Instalar docker-sync: `gem install docker-sync`
```yml
# docker-sync.yml
syncs:
  sf5-expenses-api-be-sync:
    notify_terminal: true
    src: "."
    sync_strategy: "native_osx"
    sync_excludes: [".git",".gitignore","*\.md","vendor/*","var/cache/*","var/log/*"]
    sync_userid: $U_ID
    sync_groupid: $U_ID
```
- **docker-compose.macos.yml**
```yml
# docker-compose.macos.yml
volumes:
  # nocopy evita hacer la copia del contenedor al host (nuestra maquina)
  - sf5-expenses-api-be-sync:/appdata/www:nocopy
```
- En el caso de linux (docker-compose) no hay que hacer un :nocopy ya que no se usa docker-sync puesto que docker puede comunicarse directamente con el core
- **red**:
  - Especificacion de nombre de red
  ```yml
  #docker-compose.macos.yml
  ...
    sf5-expenses-api-network:
    # name: sf5-expenses-api-local-network no es necesario
  ```
- **make**
  - Ejecuta: `make` es como si fuera `make -h`
  - En su mac, al hacer make le muestra esto:
  - ![](https://trello-attachments.s3.amazonaws.com/5b014dcaf4507eacfc1b4540/5e7777d6cd7def249ee578fb/9305cdc88a1bf005a113ba9ee72b2d38/image.png)
  - a mi me daba `missing separator linea 22`
    - El makefile debe estar indentado con tabulaciones
    - ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/570x253/767444f405d5ee973eefb7206db57f29/image.png)
  - Ejecutar: `make build`
    - Hace todo el proceso de docker-compose
  - Ejecutar: `make run`
    - docker-sync: `command not found`
    - Hay que instalar con: `gem install docker-sync`
    - **error:** `error  (<unknown>): found unknown escape character while parsing a quoted scalar at line 12 column 41`
    - Hay que cambiar las dobles comillas a simples en: `sync_excludes: ['.idea', '.git'...]`
    - ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/620x160/93b88a337a3939fc659907f1c419e694/image.png)
  - `php -v` deberias tener la misma version en local q en el contenedor
  - `composer self-update`
  - `composer install`
    > Deprecation warning: Your package name udemy-expenses-api is invalid, it should have a vendor name, a forward slash, and a package name. The vendor and package name can be words separated by
    - He cambiado el nombre en *composer.json* de `Udemy - Expenses Api` a `udemy-expenses-api/app`
  - Siempre que se instale alguna dependencia hay que hacerlo tanto dentro del **contenedor** como fuera, en mi maq local.
- Según la configuración en `dcoker-compose.macos.yml` si vamos al puerto 200 deberiamos ver la web. 
- [http://localhost:200](http://localhost:200)
  - Error: 2020/03/26 19:25:44 [emerg] 1#1: unexpected "}" in /etc/nginx/conf.d/default.conf:27
  - He copiado el conf del repo de bitbucket y ya ha funcionado.
  - Error en include de vendor autoload
    - [Pregunta Udemy](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451272#questions/9794838)
    - Tenía un bug en makefile:
      - `DOCKER_BE = sf5-expenses-api-be` antes era `expenses_api` con lo que todas las lineas relacionadas con este contenedor daban error
      - Ahora se ha instalado composer en `sf5-expenses-api-be` y esto es lo que hay despues de la instalación
      - ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/558x340/08a0172220886aee609dad5266f9f602/image.png)

### [4. Configuración de Docker en Linux 8 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451450#questions/9295602)
- En un pc con windows se instala virtualbox
- Descargamos la imagen de ubuntu creada por Juan
  - [Ubuntu - docker](https://drive.google.com/open?id=1v1eOGV7Un-FC7YUxKOIzkXxdCId0Hrio)
  - Tiene: Git, Docker, Ubuntu 18.4
- Otra opción es particionar el disco con linux
- Se crea carpeta $HOME/www
- Se clona el repo de bitbucket dentro de esta
- Se ejecuta 
  - `make build`
  - `make run`
  - `make prepare`
- En el navegador poner: `localhost:200`

### [5. Configuración de Docker en Windows (Bonus)](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451456#questions/9295602)
- Se instala docker desktop for windows
- Se instala gitbash
- Instalación de make [Make for win](http://gnuwin32.sourceforge.net/packages/make.htm)
- Bajar `make-<version>-without-guile-w32-bin.zip`
- Se extraen en la carpeta: `<archivos-de-programa>/git/mingw64`
- Ya se puede hacer: 
  - `make build`
  - `make run`
  - `winpty make prepare`
    - **error**
    - intenta ejecutar el ORM
    ```
    There are no commands defined in the "doctrine:migrations" namespace.                                                                               
    You may be looking for a command provided by the "Doctrine ORM" which is currently not installed. 
    Try running "composer require symfony/orm-pack".
    ```
- Instalación de workbench (en mi caso beaver)
- Para crear los usuarios:
  - `winpty make ssh-be`
  - Dentro del contenedor:
    - `sf d:f:l -n`
    - Purga la bd
    - Crea la bd desde 0 con datos de prueba
- Si vamos a [localhost:85/api/v1/docs](http://localhost:85/api/v1/docs)
  - Podemos ver la app funcionando
- Instalación de **docker-sync** en windows
  - Hay que instalar el subsistema de linux en windows
  - ![](https://trello-attachments.s3.amazonaws.com/5b014dcaf4507eacfc1b4540/5e7777d6cd7def249ee578fb/ccb612c9403eba6e03cf91c8ae99f31b/image.png)
  - Ir al store y buscar linux:
  - ![](https://trello-attachments.s3.amazonaws.com/5b014dcaf4507eacfc1b4540/5e7777d6cd7def249ee578fb/1e57c72408fc9e8c7dd5862072e8d53a/image.png)
  - despues de ejecutar launch ya contamos con un linux de este tipo:
  - ![](https://trello-attachments.s3.amazonaws.com/5b014dcaf4507eacfc1b4540/5e7777d6cd7def249ee578fb/e35ce0331869844a4bdb5563af87e526/image.png)
  - Despues de contar con linux hay que seguir la serie de pasos del manual que muchos no son tan directos
- En resumen habría que instalar mejor un linux en **virtualbox** y ahi instalar **docker-sync**

### Sección 4: Instalación de librerías adicionales 1 / 1|5 min
### [6. Instalación de librerías adicionales 5 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17450464#questions)
- Crea nuna nueva rama
  - `git checkout -b section3/video1-install-doctrine-and-monolog`
- `make ssh-be`
  - crea sesion en: `appuser@a94a428f574e:/appdata/www$`
- `composer require symfony/orm-pack`
- `composer require monolog`
```js
//archivo: .env
###> doctrine/doctrine-bundle ###
# estos datos se obtienen de docker-compose.macos.yml - sf5-expenses-api-db
# en este caso uso root // root pq son los datos genericos, se deberia usar user//password
DATABASE_URL=mysql://root:root@sf5-expenses-api-db:3306/sf5-expenses-api_api?serverVersion=5.7
###< doctrine/doctrine-bundle ###
```
- Dentro del contenedor: **@a94a428f574e:/appdata/www**
- `sf doctrine:migrations:migrate`
  - ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/541x78/52bc362ec18ed57fce515e156534d96f/image.png)
  - Este mensaje indica que hay conexion
- ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/734x596/43851d0cb7614ada079077425e9f4353/image.png)

### Sección 5: Autenticación y registro 0 / 3|1 h 41 min
### [7. Instalar librería para usar JSON Web Tokens 7 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451540#questions/9295602)
- `git checkout -b section4/video1-install-jwt-library`
- `make ssh-be`
- `composer require "lexik/jwt-authentication-bundle"`
- **en host:** `composer install` para mantener sincronizados los dos lados
- La instalacion de jwt toca varios ficheros:
  - expenses_api/config/packages/lexik_jwt_authentication.yaml
  - expenses_api/config/packages/security.yaml
  - expenses_api/config/packages/bundles.php
  - expenses_api/.env
- Despues de instalar queda configurar la libreria:
  - Hay que crear los certificados
  ```js
  //makefile
  //esto tiene relacion con el fichero .env, la contraseña hay que replicarla ahi
  //https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#generate-the-ssh-keys
  generate-ssh-keys: ## Generate ssh keys in the container
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} mkdir -p config/jwt
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} openssl genrsa -passout pass:sf5-expenses-api -out config/jwt/private.pem -aes256 4096
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} openssl rsa -pubout -passin pass:sf5-expenses-api -in config/jwt/private.pem -out config/jwt/public.pem
  ```
  - se configura **.env** con la nueva clave
  - se configura **lexik_jwt_authentication.yaml** con el TTL
  - se ejecuta `make generate-ssh-keys`
    - Crea una una carpeta **config/jwt** con **private.pem y public.pem**
- En este punto ya estaría configurada la libreria

### [8. Configurar sistema de autenticación 56 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451544#questions/9295602)
- Vamos a instalar una libreria para unique id
- Crearemos la entidad usuario
- Crear fichero mapping
- Creación de repositorio
- Creación de UserProvider, clase que se va a usar por la libreria instalada
- Migración de contraseñas
  - Desde la v4.4 en funcion de la pass del usuario se aplica un algoritmo u otro
- Security.yml
- Xdebug
- Rutas
- `git checkout -b section4/video2-authentication-logic`
- `make ssh-be`
- `composer require ramsey/uuid-doctrine`
- Crea el fichero: `expenses_api/config/packages/ramsey_uuid_doctrine.yaml`
  - Aqui se le indica que cada vez que se defina un tipo uuid se va a inyectar `Ramsey...\UuidType`
- Se crea la entidad User
- Se configura en **doctrine.yaml** el tipo del mapping, en lugar de anotación se pone **yml**
- El role Role.php
- El mapping (esquema de user) en `Doctrine/Mapping/Entity/User.orm.yml`
- Con esto ya se puede ejecutar la migración (dentro del contenedor be):
  - `sf doctrine:migrations:generate`
  - crea el fichero `src/Migrations/Version<numero>.php`
  ```php
  //expenses_api/src/Migrations/Version20200330212754.php
  declare(strict_types=1);
  namespace DoctrineMigrations;
  use Doctrine\DBAL\Schema\Schema;
  use Doctrine\Migrations\AbstractMigration;
  /**
  * Auto-generated Migration: Please modify to your needs!
  */
  final class Version20200330212754 extends AbstractMigration
  {
      public function getDescription() : string
      {
          return '';
      }

      public function up(Schema $schema) : void
      {
          // this up() migration is auto-generated, please modify it to your needs
      }

      public function down(Schema $schema) : void
      {
          // this down() migration is auto-generated, please modify it to your needs
      }
  }
  ```
  - Configuracion del método up: `public function up(Schema $schema) : void` aqui se configura el esquema, el **CREATE TABLE**
  - Se usa *utf8mb4* ya que es el fix de UTF8 de mysql
  ```php
  //el tipo BINARY la comparación es más rapido y más eficiente tambien
  //permite comparar la contraseña por el cod bin y no su caracter
  $this->addSql("
      CREATE TABLE user (
          id CHAR(36) NOT NULL PRIMARY KEY,
          name VARCHAR(100) NOT NULL,
          email VARCHAR(100) NOT NULL,
          password BINARY(200) NOT NULL,
          roles VARCHAR(100) NOT NULL,
          create_at DATETIME NOT NULL,
          updated_at DATETIME NOT NULL
      )
      DEFAULT CHARACTER SET utf8mb4 
      COLLATE utf8mb4_general_ci
      ENGINE = InnoDB
  ");
  ```
  - Con el esquema definido ahora ejecutamos en el contenedor be: `sf doctrine:migrations:migrate -n` -n salta la pregunta de *si quiero*
  ```js
  ++ migrated (took 218.7ms, used 12M memory)
  ------------------------
  ++ finished in 235.1ms
  ++ used 12M memory
  ++ 1 migrations executed
  ++ 1 sql queries
  ```
  ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/906x380/82134cf4a379ef986253d539464468e8/image.png)
- **Creacion de repositorio**
- Clase obsoleta (izquierda):
  - `use Doctrine\Common\Persistence\ManagerRegistry;`
  - deberia usarse: `use Doctrine\Persistence\ManagerRegistry;`
  - ![](https://trello-attachments.s3.amazonaws.com/5b014dcaf4507eacfc1b4540/5e7777d6cd7def249ee578fb/bddc7a643ca90178c710984c98050b9c/image.png)
  ```php
  //repositorios
  //BaseRepository.php
  <?php

  declare(strict_types=1);

  namespace App\Repository;

  //esta clase aparece como obsoleta pero en esta version de doctrine no esta del todo corregida
  //por lo tanto la seguimos usando
  //use Doctrine\Common\Persistence\ManagerRegistry;
  use Doctrine\DBAL\Connection;
  use Doctrine\DBAL\Query\QueryBuilder;
  use Doctrine\Persistence\ManagerRegistry;
  use Doctrine\Persistence\ObjectManager;
  use Doctrine\Persistence\ObjectRepository;

  abstract class BaseRepository
  {
      protected ManagerRegistry $managerRegistry;
      protected ObjectRepository $objectRepository;
      protected Connection $connection;

      public function __construct(ManagerRegistry $managerRegistry, Connection $connection)
      {
          $this->managerRegistry = $managerRegistry;
          $this->connection = $connection;

          //getRepository devolverá el repositorio del nombre de la entidad que se le inyecta
          $this->objectRepository = $this->getEntityManager()->getRepository($this->entityClass());
      }

      abstract protected static function entityClass(): string;

      protected function saveEntity($entity): void
      {
          $this->getEntityManager()->persist($entity);
          $this->getEntityManager()->flush();
      }

      protected function removeEntity($entity): void
      {
          $this->getEntityManager()->remove($entity);
          $this->getEntityManager()->flush();
      }

      protected function createQueryBuilder(): QueryBuilder
      {
          return $this->getEntityManager()->createQueryBuilder();
      }

      /**
       * @throws DBALException
       *                       para hacer queries abiertas
       */
      protected function executeFetchQuery(string $query, array $params = []): array
      {
          return $this->connection->executeQuery($query, $params)->fetchAll();
      }

      /**
       * @throws DBALException
       */
      protected function executeInsertQuery(string $query, array $params = []): array
      {
          return $this->connection->executeQuery($query, $params);
      }

      private function getEntityManager(): ObjectManager
      {
          $entityManager = $this->managerRegistry->getManager();
          if ($entityManager->isOpen()) {
              return $entityManager;
          }

          return $this->managerRegistry->resetManager();
      }
  }

  //UserRepository.php
  declare(strict_types=1);

  namespace App\Repository;

  use App\Entity\User;

  class UserRepository extends BaseRepository
  {
      protected static function entityClass(): string
      {
          return User::class;
      }

      public function findOneByEmail(string $email): ?User
      {
          $user = $this->objectRepository->findByOne(['email' => $email]);

          return $user;
      }

      public function save(User $user): void
      {
          $this->saveEntity($user);
      }
  }
  ```
- **Creacion del provider**
  ```php
  declare(strict_types=1);

  namespace App\Security\Core\User;

  use App\Repository\UserRepository;
  use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
  use Symfony\Component\Security\Core\User\UserInterface;
  use Symfony\Component\Security\Core\User\UserProviderInterface;

  class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
  {
      private UserRepository $userRepository;

      public function __construct(UserRepository $userRepo)
      {
          $this->userRepository = $userRepo;
      }

      public function loadUserByUsernameAndPayload($usuername, array $payload): UserInterface
      {
          return $this->findUser($username);
      }

      private function findUser(string $username): User
      {
          $user = $this->userRepository->findOneByEmail($username);
          if (null === $user) {
              throw new UsernameNotFoundException(\sprintf('User with email %s not found!', $username));
          }

          return $user;
      }

      public function loadUserByUsername(string $username)
      {
          return $this->findUser($username);
      }

      public function refreshUser(UserInterface $user)
      {
          if (!$user instanceof User) {
              throw new UnsupportedUserException(\sprintf('Instances of %s are not supported', \get_class($user)));
          }

          return $this->loadUserByUsername($user->getUsername());
      }

      public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
      {
          $user->setPassword($newEncodedPassword);
          $this->userRepository($user);
      }

      public function supportsClass(string $class): bool
      {
          return User::class == $class;
      }
  }
  ```
- **Configuracion de YAML security y routes***
  ```yaml
  # config/packages/security.yaml
  # se rellena según lexikjwt guia de instalación
  security:
      encoders:
          App\Entity\User:
              algorithm: "auto"
              
      providers:
          user_provider:
              id: App\Security\Core\User\UserProvider
              
      firewalls:
          login:
              pattern: ^/api/v1/login
              stateless: true
              anonymous: true
              provider: user_provider
              
              # en lugar de json_login que forzaría a enviar los datos de autenticacion en json
              form_login:
                  check_path: /api/v1/login_check
                  username_parameter: _email
                  password_parameter: _password
                  # ruta real: vendor/lexik/jwt-authentication-bundle/Security/Http/Authentication/AuthenticationSuccessHandler.php
                  success_handler: lexik_jwt_authentication.handler.authentication_success
                  # vendor/lexik/jwt-authentication-bundle/Security/Http/Authentication/AuthenticationFailureHandler.php
                  failure_handler: lexik_jwt_authentication.handler.authentication_failure
                  require_previous_session: false
                  
          api:
              pattern: ^/api/v1
              stateless: true
              guard:
                  authenticators:
                      # vendor/lexik/jwt-authentication-bundle/Security/Guard/JWTTokenAuthenticator.php
                      - lexik_jwt_authentication.jwt_token_authenticator
          
      # todo lo que venga despues de /api/v1 tiene que estar autenticado
      access_control:
          - { path: ^/api/v1, roles: IS_AUTHENTICATED_FULLY }

  #routes.yaml
  api_login_check:
    path: /api/v1/login_check
  ```
- **comprobacion de rutas**
```js
appuser@a94a428f574e:/appdata/www$ sf d:r
----------------- -------- -------- ------ -------------------------- 
Name              Method   Scheme   Host   Path                      
----------------- -------- -------- ------ -------------------------- 
_preview_error    ANY      ANY      ANY    /_error/{code}.{_format}  
api_login_check   ANY      ANY      ANY    /api/v1/login_check       
----------------- -------- -------- ------ -------------------------- 
```
- **configuración xdebug (phpstorm)**
  - Preferencias &gt; php &gt;
  - Se donfigura el CLI Interpreters. La version de php y donde está. En nuestro caso docker-mac
  - ![](https://trello-attachments.s3.amazonaws.com/5b014dcaf4507eacfc1b4540/5e7777d6cd7def249ee578fb/7e5589651dfcd5379da6254720be7cc4/image.png)
  - ![](https://trello-attachments.s3.amazonaws.com/5b014dcaf4507eacfc1b4540/5e7777d6cd7def249ee578fb/42f98f6d082a529e32a22af3eb59bf1d/image.png)
  - Configuracion de la raiz del proyecto (path mappings).
    - ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/1152x236/1571589bfd6d1507b165b837b5571800/image.png)
    - Volume binding
    - ![](https://trello-attachments.s3.amazonaws.com/5b014dcaf4507eacfc1b4540/5e7777d6cd7def249ee578fb/8f157930bf55afa208f0e736c3923e28/image.png)
  - En php &gt; debug, hay que configurar el puerto 9005
    ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/1182x296/6807a2d337bbdc7d4d0530619c0b9441/image.png)
    - Hay que activar el **start listening**
    ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/741x533/bfb0488ac683b7a77de58e3095b393c4/image.png)
  - Servers
    - El "Name: Docker" es que se crea en el docker-compose **be** serverName ^^
    ![](https://trello-attachments.s3.amazonaws.com/5b014dcaf4507eacfc1b4540/5e7777d6cd7def249ee578fb/14ab7510a1fdae190851d95d565b9b9b/image.png)
- **make code-style**
  - `brew upgrade php-fixer`
  - Formatea el codigo al estilo de symfony: `make code-style`
  ```js
  make code-style
  php-cs-fixer fix src --rules=@Symfony
  Loaded config default.
   1) src/Migrations/Version20200330212754.php
   2) src/Repository/BaseRepository.php
   3) src/Repository/UserRepository.php
   4) src/Security/Core/User/UserProvider.php
   5) src/Security/Role.php
   6) src/Entity/User.php
  ```

### [9. Custom endpoint para registrar usuarios 38 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451550#questions/9295602)
- `git checkout -b section4/video3-create-custom-action-register-users`
- Hay que configurar la app para crear ese "custom actions"
- Renombramos la carpeta **Controller** a **Api**
- Hacemos refactor en: 
  - annotations.yaml, services.yaml
  ```yaml
  # annotations.yaml
  controllers:
     # resource: ../../src/Controller/
     resource: ../../src/Api/
     type: annotation
     prefix: /api/v1
  
  # services.yaml
  #App\Controller\:
  App\Api\:
      #resource: '../src/Controller'
      resource: "../src/Api"
      tags: ['controller.service_arguments']
  ```
- Con esto todas las clases dentro de `Api/` serán consideradas como **custom endpoints**
- Se crea la clase: `src/Api/Listener/JsonExceptionResponseTransformerListener`
  - La intención es evitar que la gestión de excepciones la haga por defecto symfony, la típca web en color rojo.
  - Al estar trabajando con una API esto no nos vale. Por lo tanto hay que devolver un JSON
  - Una vez creado, hay que darla de alta como servicio **services.yaml**
  ```yaml
  App\Api\Listener\JsonExceptionResponseTransformerListener:
     tags:
        - { name: kernel.event_listner, event: kernel.exception, method: onKernelException, priority: 100}
  ```
- Probando: 
   - [localhost:200/api/v1/users/register](http://localhost:200/api/v1/users/register)
  ```json
  {
      "code": 401,
      "message": "JWT Token not found"
  }
  ```
  - Esto sucede porque esta ACL se esta ejecutando:
  ```
  # security
  access_control:
      - { path: ^/api/v1, roles: IS_AUTHENTICATED_FULLY }
  ```
  - Agregamos esta linea:
  ```yaml
  firewalls:
     register:
        pattern: ^/api/v1/users/register
        methods: [POST]
        security: false  
  ```
  - Ahora ya contesta con 200
- Definiendo una excepción personalizada:
  - En el archivo: `src/Api/Action/User/Register.php` debemos validar si ya existe el email si es así hay que lanzar una excepción.
  - Creamos un fichero en `src/Exceptions/User/UserAlreadyExistException.php`
- Con lo anterior ya podemos definir nuestro custom endpoint:
```php
//src/Api/Action/User/Register.php
class Register
{
  private UserRepository $userRepository;
  private JWTTokenManagerInterface $JWTTokenManager;
  private EncoderFactoryInterface $encoderFactory;

  public function __construct(UserRepository $userRepository, JWTTokenManagerInterface $JWTTokenManager, EncoderFactoryInterface $encoderFactory)
  {
      $this->userRepository = $userRepository;
      $this->JWTTokenManager = $JWTTokenManager;
      $this->encoderFactory = $encoderFactory;
  }

  /**
   * @Route("/users/register", methods={"POST"})
   * @throws \Exception
   */
  public function __invoke(Request $request): JsonResponse
  {
      $name = RequestTransformer::getRequiredField($request,"name");
      $email = RequestTransformer::getRequiredField($request,"email");
      $password = RequestTransformer::getRequiredField($request,"password");

      //hay que ver si existe el usuario
      $existUser = $this->userRepository->findOneByEmail($email);
      if( null !== $existUser)
      {
          throw UserAlreadyExistException::fromUserEmail($email);
          //throw new BadRequestHttpException(\sprintf("User with email % already exist",$email));
      }

      $user = new User($name,$email);
      $encoder = $this->encoderFactory->getEncoder($user);
      $user->setPassword($encoder->encodePassword($password,null));
      $this->userRepository->save($user);
      $jwt = $this->JWTTokenManager->create($user);
      //se podría hacer un push en Rabbit MQ para que despues del alta se haga un envio al usuario
      return new JsonResponse(["token"=>$jwt]);
  }
}  
```
- Me está dando este **error** al llamar a esta url: [localhost:200/api/v1/users/register](http://localhost:200/api/v1/users/register): 
  - A Juan no le pasa esto. He reinstalado todo y me sigue pasando :S
  - ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/743x501/59508c49d3ac360722c70e2c85363245/image.png)
  ```php
  Object of class DateTime could not be converted to string

  in vendor/doctrine/dbal/lib/Doctrine/DBAL/Driver/PDOStatement.php (line 81)
  public function bindValue($param, $value, $type = ParameterType::STRING){
    $type = $this->convertParamType($type);        
    try {            
      return parent::bindValue($param, $value, $type);        
    } catch (\PDOException $exception) { 
      throw new PDOException($exception);        
    }
  }
  ```
  - Tenía mal configurado el mappings
  ```yaml
  # Doctrine/Mapping/Entity/User.orm.yml
  App\Entity\User:
    type: entity
    table: user
  createdAt:
    type: datetime
    nullable: false
  updatedAt:
    type: datetime
    nullable: false
  ```
 - Estaba entrando por la excepción pero no hacia caso al listener.
   - Tenía el listener mal configurado en services.yaml
   - Las importaciones estaban mal hechas, usaba clases nativas de php y debían ser las de Symfony
- Si ahora probamos la llamada a la url: `localhost:200/api/v1/login_check`
  - ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/762x610/b2feb20b92625761ce3cedd3e7fbac71/image.png)
  - Vemos que devuelve el token
  - Para saber la info que se guarda en este podemos acceder a:
    - [http://jwt.io](http://jwt.io)
  - ![](https://trello-attachments.s3.amazonaws.com/5b014dcaf4507eacfc1b4540/5e7777d6cd7def249ee578fb/f9a10291c86e3cf0da61d988df700434/image.png)
  - Con esta información ya podemos saber quien es el que está haciendo la petición
  
### Sección 6: Instalar y configurar API Platform 0 / 3|3 h 4 min
### [10. Instalar y configurar API Platform y recurso para usuarios 1 h 17 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451554#questions/9295602)
- [`git checkout -b section5/video1-install-and-setup-api-platform`](https://bitbucket.org/juanwilde/sf5-expenses-api/src/6d713bba882f82522c5606a3030d1f27a8c2cde7/?at=section5%2Fvideo1-install-and-setup-api-platform)
- Instalación de [**api-platform.com**](https://api-platform.com/docs/core/getting-started/#getting-started)
  - Iniciamos sesion en el contenedor `make ssh-be`
  - `composer require api`
  - Si estamos en mac tambien hay que hacer esto en la maquina host.
  - Despues de la instación se han tocado/creado estos ficheros:
  ```js
  modified:   .env //CORS_ALLOW_ORIGIN=^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$
  modified:   composer.json //"api-platform/api-pack": "^1.2",
  modified:   composer.lock
  modified:   config/bundles.php
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true]
    Nelmio\CorsBundle\NelmioCorsBundle::class => ['all' => true]
    ApiPlatform\Core\Bridge\Symfony\Bundle\ApiPlatformBundle::class => ['all' => true]
  modified:   symfony.lock

  config/packages/api_platform.yaml
  config/packages/nelmio_cors.yaml
  config/packages/test/twig.yaml  ha instalado twig???
  config/packages/test/validator.yaml
  config/packages/twig.yaml
  config/packages/validator.yaml
  config/routes/api_platform.yaml
  src/Entity/.gitignore
  templates/
  ``` 
- **configuración de api_plattform.yaml**
```yaml
# config/packages/api_platform.yaml

# manual de configuración: 
# https://api-platform.com/docs/core/configuration/#configuration
api_platform:
    mapping:
        # repositorio de mappings
        # paths: ['%kernel.project_dir%/src/Entity']
        paths: 
            - '%kernel.project_dir%/src/Doctrine/Mapping/Entity'
            # zona de los recursos, se encarga de habilitar endpoints y la seguridad 
            - '%kernel.project_dir%/config/api_platform/resources'
    patch_formats:
        json: ['application/merge-patch+json']
    swagger:
        versions: [3]
        # se habilita swager para poder introducir un token valido en la doc de swagger y poder consumir
        # los endpoints tanto con postman como con swagger
        api_keys:
            apiKey:
                name: Authorization
                type: header
    
    title: 'SF5 Expenses API'
    description: 'An awesome API built Symfony 5.0 PHP 7.4 and API Platform'
    version: 1.0
    show_webby: false #araña en la interfaz
```
- **configuracion de rutas config/routes/api_platform.yaml**
```yaml
# config/routes/api_platform.yaml
api_platform:
    resource: .
    type: api_platform
    prefix: /api/v1
```
- comprobamos doctrine routes: `appuser@a37554db531b:/appdata/www$ sf d:r`
```js
 -------------------------------------- -------- -------- ------ ---------------------------------------- 
  Name                                   Method   Scheme   Host   Path                                    
 -------------------------------------- -------- -------- ------ ---------------------------------------- 
  _preview_error                         ANY      ANY      ANY    /_error/{code}.{_format}                
  app_api_action_user_register__invoke   POST     ANY      ANY    /api/v1/users/register                  
  api_entrypoint                         ANY      ANY      ANY    /api/v1/{index}.{_format}               
  api_doc                                ANY      ANY      ANY    /api/v1/docs.{_format}                  
  api_jsonld_context                     ANY      ANY      ANY    /api/v1/contexts/{shortName}.{_format}  
  api_users_get_collection               GET      ANY      ANY    /api/v1/users.{_format}                 
  api_users_post_collection              POST     ANY      ANY    /api/v1/users.{_format}                 
  api_users_get_item                     GET      ANY      ANY    /api/v1/users/{id}.{_format}            
  api_users_delete_item                  DELETE   ANY      ANY    /api/v1/users/{id}.{_format}            
  api_users_put_item                     PUT      ANY      ANY    /api/v1/users/{id}.{_format}            
  api_users_patch_item                   PATCH    ANY      ANY    /api/v1/users/{id}.{_format}            
  api_login_check                        ANY      ANY      ANY    /api/v1/login_check                     
 -------------------------------------- -------- -------- ------ ---------------------------------------- 
```
- No sha creado varios endpoints y la mayoria tienen que ver con *users*
- Tenemos que *abrir* `/api/v1/docs.{_format}`
  - Sin permisos:
  ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/678x344/1396f4dea6b35b661a6fb417cabded70/image.png)
  ```js
  InvalidConfigurationException
  HTTP 500 Internal Server Error
  No authentication listener registered for firewall "docs".
  ```
  - con **security.yaml** configurado
  ```yaml
  # config/packages/security.yaml
  #alias
  docs:
      pattern: ^/api/v1/docs
      methods: [GET]
      security: false
  ```
  - ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/597x581/ec2664708b7555fac7cd8b050b258020/image.png)
- Configuramos el **grupo de serialización** para indicar que (recrusos) es lo que queremos exponer del usuario
  - Por ejemplo `/api/v1/users/{id}` devuelve todos los campos de user
  - Vamos a exponer solo cierta info
  - Nueva carpeta: `config/api_platform/serialization`
  - En lugar de utilizar los grupos de seralización directamente en nuestras entidades configuramos **framework.yaml**
  ```yaml
  # config/packages/framework.yaml
  framework:
      ...
      serializer:
          mapping:
              path: ['%kernel.project_dir%/config/api_platform/serialization']
  ```
- Creamos el recurso usuario **config/api_platform/resources/User.yaml**:
  - Normalizacion = Leer un recurso
  - Denormalizacion = Escribir
  - [info serializaer](https://symfony.com/doc/current/components/serializer.html)
  ```yaml
  # config/api_platform/resources/User.yaml
  App\Entity\User:
    attributes:
      # estos son como decoradores que identificarán que campos admiten lectura/escritura
      normalization_context:
        groups: ["user_read"]
      denormalization_context:
        groups: ["user_write"]
    
    # para obtener todos los usuarios el usr que pide el recuros debe tener USER_READ
    # listado
    collectionOperations:
      get:
        method: "GET"
        security: "is_granted('USER_READ')" #se configura como voter

    # registro
    itemOperations:
      get:
        method: "GET"
        security: "is_granted('USER_READ',object)"
      put:
        method: "PUT"
        security: "is_granted('USER_UPDATE',object)"
        swagger_context:
          parameters:
            - in: body
              name: user
              description: The user to update
              schema:
                type: object
                required:
                  - name
                  - email
                  - roles
                properties:
                  name:
                    type: string
                  email:
                    type: string
                  roles:
                    type: array
                    items:
                      type: string
      delete:
        method: "DELETE"
        security: "is_granted('USER_DELETE',object)"
  ```
- Creamos el recurso de serializacion usuario **config/api_platform/serialization/User.yaml**:
  ```yaml
  # config/api_platform/serialization/User.yaml
  App\Entity\User:
    attributes:
      id:
        groups: ["user_read"]
      name:
        groups: ["user_read","user_write"]
      email:
        groups: ["user_read"]
      password:
        groups: ["user_write"]
      roles:
        groups: ["user_read","user_write"]
  ```
  - Ya no hay endpoint con **POST** `/api/v1/users Creates a User Resource`
  - Comprobamos nuevamente la respuesta de: `/api/v1/users/{id}`
  ```json
  {
    "@context": "string",
    "@id": "string",
    "@type": "string",
    "id": "string",
    "name": "string",
    "email": "string",
    "roles": [
      "string"
    ]
  }
  ```
  - ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/568x249/9646eb78efc52375f9d1deabeac9319a/image.png)
- En **put**
  - Vemos que el json solo devuelve **name, password** no permite la lectura de roles.
  - ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/481x159/b3a6bd3f7bd8f4f28a565892f4fdbb97/image.png)
  - Porque estos se pueden cambiar **user_write** pero en la entidad no hay el setter del roles (setRoles(array $roles)), lo agregamos.
  - ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/502x211/f35acada786c3e5b873bccf0382a41b0/image.png)

- configuracion de **voters**
  - [Symfony - Voters](https://symfony.com/doc/current/security/voters.html)
  - Son un conjunto de interfaces que nos permiten evaluar una operación
  - Un caso de uso es verificar el perfil del usuario y en base a esto discrimina el CRUD que se puede realizar sobre el recurso
  - Los **voters** tienen relación con los recursos
  - crear carpeta: **src/Security/Authorization/Voter**
  - crear clases:
  - `expenses_api/src/Security/Authorization/Voter/BaseVoter.php`
  ```php
  //src/Security/Authorization/Voter/BaseVoter.php
  declare(strict_types=1);
  namespace App\Security\Authorization\Voter;

  use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
  use Symfony\Component\Security\Core\Authorization\Voter\Voter;
  use Symfony\Component\Security\Core\Security;

  /*
  * La clase abstracta nos va a permitir que los hijos hereden de BaseVoter y que
  * puedan customizar los metodos obligatorios y la interfaz no obliga a la clase abstracta
  * a implementar los metodos de la interfaz
  * */
  abstract class BaseVoter extends Voter //Voter implementa: VoterInterface
  {
      protected Security $security;

      public function __construct(Security $security)
      {
          $this->security = $security;
      }
  }  
  ```
  - `expenses_api/src/Security/Authorization/Voter/UserVoter.php`
  ```php
  //src/Security/Authorization/Voter/UserVoter.php
  declare(strict_types=1);
  namespace App\Security\Authorization\Voter;

  use App\Entity\User;
  use App\Security\Roles;
  use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

  /**
  * Class UserVoter
  * Cuando se intente hacer una operación sobre un recurso se van a lanzar todos los metodos del Voter
  * y se verá cual de ellos soporta el atributo (operacion R,U,D) que se está pasando
  */
  class UserVoter extends BaseVoter
  {
      public const USER_READ = "USER_READ";
      public const USER_UPDATE = "USER_UPDATE";
      public const USER_DELETE = "USER_DELETE";

      /**
      * @inheritDoc
      */
      protected function supports(string $attribute, $subject): bool
      {
          //en base a un atributo READ,UPDATE,DELETE y la entidad devolverá si es valido o no
          return \in_array($attribute,$this->getSupportedAttributes(),true);
      }

      /**
      * @inheritDoc
      * @param string $attribute se obtiene de is_granted('USER_READ',object) y es USER_READ
      * @param User | null $subject es el "object" que es un objeto usuario, null para casos is_granted(ACCION)
      */
      protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
      {
          /** @var User $tokenUser  **/
          $tokenUser = $token->getUser();
          if(self::USER_READ === $attribute){
              //si no hay entidad la lectura escritura solo se permitirá a los admin
              if(null === $subject){
                  return $this->security->isGranted(Roles::ROLE_ADMIN);
              }

              //si el token tiene ROLE_ADMIN o el registro a modificar es igual a la entidad que está moodificando
              return ($this->security->isGranted(Roles::ROLE_ADMIN) || $subject->equals($tokenUser));
          }

          //si se pretende actualizar o borrar
          if(\in_array($attribute, [self::USER_UPDATE, self::USER_DELETE]))
          {
              //el token (usuario en sesion) debe ser admin o debe ser el mismo
              return ($this->security->isGranted(Roles::ROLE_ADMIN) || $subject->equals($tokenUser));
          }

          return false;
      }

      private function getSupportedAttributes():array
      {
          return [
              self::USER_READ,
              self::USER_UPDATE,
              self::USER_DELETE
          ];
      }
  }

  /*
  vendor/symfony/security-core/Authorization/AuthorizationChecker.php

  final public function isGranted($attribute, $subject = null): bool
  {
      if (null === ($token = $this->tokenStorage->getToken())) {
          throw new AuthenticationCredentialsNotFoundException('The token storage contains no authentication token. One possible reason may be that there is no firewall configured for this URL.');
      }

      if ($this->alwaysAuthenticate || !$token->isAuthenticated()) {
          $this->tokenStorage->setToken($token = $this->authenticationManager->authenticate($token));
      }

      //hay varios metodos de decision y cada uno compruega algo, el atributo y el token pasan por los voters
      //vendor/symfony/security-core/Authorization/AccessDecisionManager.php
      return $this->accessDecisionManager->decide($token, [$attribute], $subject);
  }
  */

  //Entity/User.php
  //para acceder desde voter a tus propios datos
  public function equals(User $user): bool
  {
      return $this->getId() == $user->getId();
  }
  
  //expenses_api/src/Security/Roles.php
  nuevo ROLE_ADMIN
  ```
- En la bd cambiamos a mano el perfil del usuario: **ROLE_ADMIN**
- Con todo configurado, como trabajamos con Swagger?
- Con **postman** entramos en *login_check* que nos devolverá un token
- Con dicho token vamos a swagger y presionamos en **Authorize** insertamos: `Bearer <el token>`
- Me ha dado un error (pq no tenía ROLE_ADMIN en la tabla user):
```
Code	Details
403
Undocumented
Error: Forbidden
Response body
Download
{
  "@context": "/api/v1/contexts/Error",
  "@type": "hydra:Error",
  "hydra:title": "An error occurred",
  "hydra:description": "Access Denied.",

Faltaba aplicar los literales a las constantes en UserVoter
```
- Sobre **hydra** [Estandard para creación de APIS](http://www.hydra-cg.com/#specifications)
- Otros:
```
"@context": "/api/v1/contexts/Error",
  "@type": "hydra:Error",
  "hydra:title": "An error occurred",
  "hydra:description": "Call to a member function equals() on null",

Faltaba aplicar return en UserVoter
```
```
the namespace prefix used with the resource in /appdata/www/config/services.yaml (which is loaded in resource /appdata/www/config/services.yaml). (500 Internal Server Error)

Faltaba limpiar la cache pq habia borrado un fichero
```
- Ya contesta correctamente:
```json
{
  "@context": "/api/v1/contexts/User",
  "@id": "/api/v1/users",
  "@type": "hydra:Collection",
  "hydra:member": [
    {
      "@id": "/api/v1/users/403169b5-2ed4-42f5-84bd-848b76e6a548",
      "@type": "User",
      "id": "403169b5-2ed4-42f5-84bd-848b76e6a548",
      "name": "juan",
      "email": "juan@api.com",
      "roles": [
        "ROLE_ADMIN"
      ]
    }
  ],
  "hydra:totalItems": 1
}
```
- Probando `GET: /api/v1/users/{id}` devuelve:
```json
{
  "@context": "/api/v1/contexts/User",
  "@id": "/api/v1/users/403169b5-2ed4-42f5-84bd-848b76e6a548",
  "@type": "User",
  "id": "403169b5-2ed4-42f5-84bd-848b76e6a548",
  "name": "juan",
  "email": "juan@api.com",
  "roles": [
    "ROLE_ADMIN"
  ]
}
```
- Probando `DELETE: http://localhost:200/api/v1/users/403169b5-2ed4-42f5-84bd-848b76e6a548`
![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/1143x495/511ee31647ddd90bc527909e1a1f0b33/image.png)
- La tabla user ahora está vacia
- Trabajar con **PUT** y listeners del kernel de symfony
- En [api-platform/listeners](https://api-platform.com/docs/core/events/#built-in-event-listeners)
- En **pre y post** hooks vemos los puntos de inyección
- Creamos intefaz: **src/Api/Listener/PreWriteListener.php**
```php
# src/Api/Listener/PreWriteListener.php
declare(strict_types=1);
namespace App\Api\Listener;

use Symfony\Component\HttpKernel\Event\ViewEvent;

interface PreWriteListener
{
    //este método se ejecutará siempre antes de escribir en la bd
    //justo antes de que doctrine lo guarde en la bd
    public function onKernelView(ViewEvent $event):void;
}
//PreWriteListener.onKernelView(ViewEvent):void
```
- Creamos **expenses_api/src/Api/Listener/User/UserPreWriteListener.php**
```php
//src/Api/Listener/User/UserPreWriteListener.php
declare(strict_types=1);
namespace App\Api\Listener\User;

use App\Api\Action\RequestTransformer;
use App\Entity\User;
use App\Api\Listener\PreWriteListener;
use App\Security\Validator\Role\RoleValidator;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserPreWriteListener implements PreWriteListener
{
    //ruta put
    private const PUT_USER = "api_users_put_item";
    private EncoderFactoryInterface $encoderFactory;

    /**
     * @var iterable | RoleValidator[]
     */
    private iterable $roleValidators;

    public function __construct(EncoderFactoryInterface $encoderFactory, iterable $roleValidators)
    {
        $this->encoderFactory = $encoderFactory;
        //aqui vendrian instancias de AreValidRoles y CanAddRoleAdmin
        $this->roleValidators = $roleValidators;
    }

    /**
     * @param ViewEvent $event Tiene toda la información que tiene que ver con la actualización del usuario
     */
    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();

        //si conincide la ruta, es PUT de user
        if(self::PUT_USER === $request->get("_route")){
            /**
             * @var User $user  Usuario con la info ya actualizada (nuevos datos) todavia no está en bd
             */
            $user = $event->getControllerResult();

            $roles = [];
            foreach ($this->roleValidators as $roleValidator){
                // validate lanzará una excepción si no cumple la validación
                $roles = $roleValidator->validate($request);
            }

            //si todo ha ido bien se aplica esos roles al usuario
            $user->setRoles($roles);

            $plainTextPassword = RequestTransformer::getRequiredField($request,"password");

            $encoder = $this->encoderFactory->getEncoder($user);
            $user->setPassword($encoder->encodePassword($plainTextPassword,null));
        }
    }
}

/*
sf d:r
-------------------------------------- -------- -------- ------ ---------------------------------------- 
  Name                                   Method   Scheme   Host   Path                                    
 -------------------------------------- -------- -------- ------ ---------------------------------------- 
  _preview_error                         ANY      ANY      ANY    /_error/{code}.{_format}                
  app_api_action_user_register__invoke   POST     ANY      ANY    /api/v1/users/register                  
  api_entrypoint                         ANY      ANY      ANY    /api/v1/{index}.{_format}               
  api_doc                                ANY      ANY      ANY    /api/v1/docs.{_format}                  
  api_jsonld_context                     ANY      ANY      ANY    /api/v1/contexts/{shortName}.{_format}  
  api_users_get_collection               GET      ANY      ANY    /api/v1/users.{_format}                 
  api_users_get_item                     GET      ANY      ANY    /api/v1/users/{id}.{_format}            
  api_users_put_item                     PUT      ANY      ANY    /api/v1/users/{id}.{_format}            
  api_users_delete_item                  DELETE   ANY      ANY    /api/v1/users/{id}.{_format}            
  api_login_check                        ANY      ANY      ANY    /api/v1/login_check                     
 -------------------------------------- -------- -------- ------ ----------------------------------------
*/
```
- Hay que configurar la inyección de validadores y listeners **services.yaml**
```yaml
# config/services.yaml
  _defaults:
    ...
    bind:
        # cuando inyectemos una variable que se llame $roleValidators va a estar etiquetada
        # con app.role_validator
        # esto le permitirá a symfony inyectar un iterable $roleValidators siempre que se pase a un constructor
        # esta variable
        $roleValidators: !tagged app.role_validator

  # todas las clases que sean instancia de una determinada clase o interface tengan un comportamiento
  _instanceof:
    App\Api\Listener\PreWriteListener: # Interfaz
        tags:
            - { name: kernel.event_listener, event: kernel.view, method: onKernelView, priority: 33 }
    # todas las clases que implementen esta interfaz van a tener este tag: app.role_validator
      App\Security\Validator\Role\RoleValidator:
          tags: ["app.role_validator"]              
```
- Se crean los validadores en **src/Security/Validator/Role**
- Se crean las excepciones para las validaciones **src/Exceptions/Role**
```php
//src/Security/Validator/Role/AreValidRoles.php
declare(strict_types=1);
namespace App\Security\Validator\Role;

use App\Api\Action\RequestTransformer;
use App\Exceptions\Role\UnsupportedRoleException;
use App\Security\Roles;
use Symfony\Component\HttpFoundation\Request;

class AreValidRoles implements RoleValidator
{

    public function validate(Request $request): array
    {
        $roles = \array_unique(RequestTransformer::getRequiredField($request,"roles"));

        \array_map(function (string $role): void {
            if(!\in_array($role,Roles::getSupportedRoles(), true)){
                //lanza una excepcion: BadRequestHttpException
                throw UnsupportedRoleException::fromRole($role);
            }
        },$roles);

        return $roles;
    }
}
//src/Security/Validator/Role/CanAddRoleAdmin.php
declare(strict_types=1);
namespace App\Security\Validator\Role;

use App\Api\Action\RequestTransformer;
use App\Exceptions\Role\RequiredRoleToAddRoleAdminNotFoundException;
use App\Exceptions\Role\UnsupportedRoleException;
use App\Security\Roles;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

/**
 * Class CanAddRoleAdmin
 * @package App\Security\Validator\Role
 * Si un usuario es admin puede asignarle rol de admin a otro usuario de lo contrario no
 */
class CanAddRoleAdmin implements RoleValidator
{

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function validate(Request $request): array
    {
        $roles = \array_unique(RequestTransformer::getRequiredField($request,"roles"));

        if( \in_array(Roles::ROLE_ADMIN, $roles, true))
        {
            if(!$this->security->isGranted(Roles::ROLE_ADMIN))
            {
                throw RequiredRoleToAddRoleAdminNotFoundException::fromRole(Roles::ROLE_ADMIN);
            }
        }

        return $roles;
    }
}
```
- Me está dando este eror:
![](https://trello-attachments.s3.amazonaws.com/5b014dcaf4507eacfc1b4540/5e7777d6cd7def249ee578fb/4f214ae77038a03befe14f82794ae9d1/image.png)
```
403 Error: Forbidden
  "@context": "/api/v1/contexts/Error",
  "@type": "hydra:Error",
  "hydra:title": "An error occurred",
  "hydra:description": "Access Denied.",
  "trace": [
    namespace": "",
      "short_class": "",
      "class": "",
      "type": "",
      "function": "",
      "file": "/appdata/www/vendor/symfony/security-http/Firewall/ExceptionListener.php",

solucion:
  Tenía mal configurado el metodo de retorno voteOnAttribute en src/Security/Authorization/Voter/UserVoter.php
```
- Al haber funcionado la actualización
![](https://trello.com/1/cards/5e7777d6cd7def249ee578fb/attachments/5e88bd7f54aec15117da9d95/previews/download?backingUrl=https%3A%2F%2Ftrello-attachments.s3.amazonaws.com%2F5b014dcaf4507eacfc1b4540%2F5e7777d6cd7def249ee578fb%2F06e6657e43cfa08ae3342c749965d212%2Fimage.png)
![](https://trello.com/1/cards/5e7777d6cd7def249ee578fb/attachments/5e8889fa3c3d4c266c33f1f3/previews/download?backingUrl=https%3A%2F%2Ftrello-attachments.s3.amazonaws.com%2F5e7777d6cd7def249ee578fb%2F1034x93%2Fd5dc228352dd6ca01209c93ba862d712%2Fimage.png)

- [Symfony - Autowiring doc](https://symfony.com/doc/current/service_container/autowiring.html)
- **CORS**
- Permite el consumo de una API desde un dominio diferente
```env
.env
.env.local
###> nelmio/cors-bundle ###
CORS_ALLOW_ORIGIN=^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$
###< nelmio/cors-bundle ###
```

### [11. Configurar recurso y seguridad de User y tests funcionales 1 h 9 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451568#questions/9295602)
- [`git checkout -b section5/video2-functional-test-for-user`](https://bitbucket.org/juanwilde/sf5-expenses-api/src/11b29705a81ec66bc706f18c863e5ee6864551c1/?at=section5%2Fvideo2-functional-test-for-user)
- Dentro del container:
  - **`composer require --dev symfony/phpunit-bridge`**
  - crea ficheros:
    - expenses_api/.env.test
    - expenses_api/phpunit.xml.dist
    - expenses_api/bin/phpunit
    - expenses_api/tests/bootstrap.php
  - **`composer require --dev symfony/browser-kit`**
    - Nos proveera de un cliente http que nos permitirá hacer peticiones http
  - **`composer require --dev doctrine/doctrine-fixtures-bundle` (faker)**
    - Nos permitira crear datos falsos para hacer pruebas (Faker?)
  - crea ficheros:
    - src/DataFixtures/AppFixtures.php
  - modifica:
    - config/bundles.php
      - Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['dev' => true, 'test' => true], 
  - **`composer require --dev liip/test-fixtures-bundle:^1.0.0`**
    - Nos dara cierta funcionalidad para carga de datos que podemos ejecutar antes lanzar los tests
  - modifica
    - config/bundles.php
      - Liip\TestFixturesBundle\LiipTestFixturesBundle::class => ['dev' => true, 'test' => true],
  - **`composer require symfony/proxy-manager-bridge`**
    - Es para evitar que el **entityManager** nos de un error "tu repositorio no está en modo lazy"
- Hay dos formas de ejecución:
  - Dentro del contenedor
  - En nuestra maquina local
- Juan usa su interprete local porque es mucho más rápido. En contenedor puede tardar casi un minuto en iniciar y otro minuto en devolver el resultado
- **Config PHPSTORM con interprete local**
- ![](https://trello.com/1/cards/5e7777d6cd7def249ee578fb/attachments/5e88ceac6b9e06143f4a80a6/previews/download?backingUrl=https%3A%2F%2Ftrello-attachments.s3.amazonaws.com%2F5e7777d6cd7def249ee578fb%2F973x219%2F5c1d94388183b8c2519cb8c5fd9e5ac4%2Fimage.png)
- local: `vendor/bin/simple-phpunit` antes se hacia con **phpunit** ahora se recomienda este.
  - Al instalar este comando da un error:
  ```js
  vendor/bin/simple-phpunit
  sh: composer: command not found
  ```
  - ![](https://trello.com/1/cards/5e7777d6cd7def249ee578fb/attachments/5e88f723df69c051328b5241/previews/download?backingUrl=https%3A%2F%2Ftrello-attachments.s3.amazonaws.com%2F5b014dcaf4507eacfc1b4540%2F5e7777d6cd7def249ee578fb%2F19c44865b55108082d314804a6d009ee%2Fimage.png)
  - El problema se da porque el script **/vendor/symfony/phpunit-bridge/bin/simple-phpunit.php** intenta ejecutar comandos con **composer.phar** y no lo encuentra ya que lo tengo instalado en mi carpeta projects (`/Users/<user>/projects/composer.phar`) y el alias esta en **.zshrc** mas no en **.bash_profile**. La solución rápida ha sido copiar el **.phar** dentro de la raíz del proyecto. Ha funcionado!.
  - ![](https://trello.com/1/cards/5e7777d6cd7def249ee578fb/attachments/5e88f9a4c649cc541e09c2b0/previews/download?backingUrl=https%3A%2F%2Ftrello-attachments.s3.amazonaws.com%2F5e7777d6cd7def249ee578fb%2F769x239%2F83a5aa9311d4318daf2e4e9410dca397%2Fimage.png)
  - He instalado la versión de phpunit-8.3 (se indica aqui: expenses_api/phpunit.xml.dist)
  - Se puede probar con: **`bin/phpunit`** puede que actualice algunas librerias
  - Esto crea la estructura:
  ```s
  # tree -d -a  -L 4
  expenses_api/bin/
  └── .phpunit
    └── phpunit-8.3-0
        ├── .github
        │   └── workflows
        ├── .psalm
        ├── bin
        ├── src
        │   ├── Framework
        │   ├── Runner
        │   ├── TextUI
        │   └── Util
        ├── tests
        │   ├── _files
        │   ├── basic
        │   ├── end-to-end
        │   ├── fail
        │   ├── static-analysis
        │   └── unit
        └── vendor
            ├── composer
            ├── doctrine
            ├── myclabs
            ├── phar-io
            ├── phpdocumentor
            ├── phpspec
            ├── phpunit
            ├── sebastian
            ├── symfony
            ├── theseer
            └── webmozart
  ```
- ![](https://trello.com/1/cards/5e7777d6cd7def249ee578fb/attachments/5e89014289b8ec5b3e880de7/previews/download?backingUrl=https%3A%2F%2Ftrello-attachments.s3.amazonaws.com%2F5e7777d6cd7def249ee578fb%2F931x311%2F9470e4b20adc152295f8ace84e9e41d4%2Fimage.png)
- Vamos a proceder con los **tests funcionales**.  Estos consumirán la API y comprobaremos los resultados.
- Se crean archivos:
  - `tests/Functional/Api/TestBase.php`
  - `tests/Functional/Api/User/UserTestBase.php`
- Se ha creado el fichero `.env.test` variables necesarias para ejecutar nuestros tests, se crea una bd de pruebas **sf5-expenses-api_api-test**
```js
# s5_udemy/expenses_api/.env.test
# define your env variables for the test env here
KERNEL_CLASS='App\Kernel'
APP_SECRET='$ecretf0rt3st'
SYMFONY_DEPRECATIONS_HELPER=999999
PANTHER_APP_ENV=panther

###> doctrine/doctrine-bundle ###
# estos datos se obtienen de docker-compose.macos.yml - sf5-expenses-api-db
# DATABASE_URL=mysql://root:root@s127.0.0.1:3350/sf5-expenses-api_api-test?serverVersion=5.7 con esta ip no va
DATABASE_URL=mysql://root:root@0.0.0.0:3350/sf5-expenses-api_api-test?serverVersion=5.7
###< doctrine/doctrine-bundle ###
```
- Se retoca la conf de bd, se usa la ip de localhost pq se va a conectar desde la maquina local (como dijimos antes, por rendimiento los tests no se harán en el contenedor)
- Hay que hacer un pequeño refactor llevando la lógica de alta y modificación a un **servicio**
```php
//servicio
//src/Service/Password/EncoderService.php
declare(strict_types=1);
namespace App\Service\Password;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class EncoderService
{
    private EncoderFactoryInterface $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function generateEncodedPasswordForUser(UserInterface $user, string $password, string $salt = null): string
    {
        $encoder = $this->encoderFactory->getEncoder($user);
        return $encoder->encodePassword($password,$salt);
    }
}
//register
//src/Api/Action/User/Register.php
class Register
{
    private UserRepository $userRepository;
    private JWTTokenManagerInterface $JWTTokenManager;
    private EncoderService $encoderService;

    public function __construct(UserRepository $userRepository, JWTTokenManagerInterface $JWTTokenManager, EncoderService $encoderService)
    {
        $this->userRepository = $userRepository;
        $this->JWTTokenManager = $JWTTokenManager;
        $this->encoderService = $encoderService;
    }

    /**
     * @Route("/users/register", methods={"POST"})
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $name = RequestTransformer::getRequiredField($request, 'name');
        $email = RequestTransformer::getRequiredField($request, 'email');
        $password = RequestTransformer::getRequiredField($request, 'password');

        //hay que ver si existe el usuario
        $existUser = $this->userRepository->findOneByEmail($email);
        if (null !== $existUser) {
            throw UserAlreadyExistException::fromUserEmail($email);
            //throw new BadRequestHttpException(\sprintf("User with email % already exist",$email));
        }

        $user = new User($name, $email);
        $user->setPassword($this->encoderService->generateEncodedPasswordForUser($user,$password));
        $this->userRepository->save($user);
        $jwt = $this->JWTTokenManager->create($user);
        //se podría hacer un push en Rabbit MQ para que despues del alta se haga un envio al usuario
        return new JsonResponse(['token' => $jwt]);
    }
}

//listener
//src/Api/Listener/User/UserPreWriteListener.php
class UserPreWriteListener implements PreWriteListener
{
    //ruta put sf d:r
    private const PUT_USER = "api_users_put_item";

    private EncoderService $encoderService;

    /**
     * @var iterable | RoleValidator[]
     */
    private iterable $roleValidators;
    //$roleValidators se configura en services.yaml

    public function __construct(EncoderService $encoderService, iterable $roleValidators)
    {
        $this->encoderService = $encoderService;
        //aqui vendrian instancias de AreValidRoles y CanAddRoleAdmin
        $this->roleValidators = $roleValidators;
    }

    /**
     * @param ViewEvent $event Tiene toda la información que tiene que ver con la actualización del usuario
     */
    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();

        //si conincide la ruta, es PUT de user
        if(self::PUT_USER === $request->get("_route")){
            /**
             * @var User $user  Usuario con la info ya actualizada (nuevos datos) todavia no está en bd
             */
            $user = $event->getControllerResult();

            $roles = [];
            foreach ($this->roleValidators as $roleValidator){
                // validate lanzará una excepción si no cumple la validación
                $roles = $roleValidator->validate($request);
            }

            //si todo ha ido bien se aplica esos roles al usuario
            $user->setRoles($roles);

            $user->setPassword(
                $this->encoderService->generateEncodedPasswordForUser(
                    $user,
                    RequestTransformer::getRequiredField($request,"password"),
                    null
                )
            );
        }
    }

}//UserPreWriteListener
```
- Generamos ids de prueba en [uuidgenerator.net/](https://www.uuidgenerator.net/) 
```js
eeebd294-7737-11ea-bc55-0242ac130003
```
- Tocamos `AppFixtures`
```php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\User;
use App\Service\Password\EncoderService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Security\Roles;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 * Aqui se crearán los datos falsos para los tests de la app
 */
class AppFixtures extends Fixture
{
    private EncoderService $encoderService;

    public function __construct(EncoderService $encoderService)
    {
        $this->encoderService = $encoderService;
    }

    public function load(ObjectManager $manager)
    {
        $users = $this->getUsers();
        foreach ($users as $userData){
            $user = new User($userData["name"],$userData["email"]);
            $user->setPassword($this->encoderService->generateEncodedPasswordForUser($user,$userData["password"]));
            $user->setRoles($userData["roles"]);
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getUsers(): array
    {
        return [
            [
                "id" => "eeebd294-7737-11ea-bc55-0242ac130001",
                "name" => "Admin",
                "email" => "admin@api.com",
                "password" => "password",
                "roles" => [
                    Roles::ROLE_ADMIN,
                    Roles::ROLE_USER,
                ]
            ],
            [
                "id" => "eeebd294-7737-11ea-bc55-0242ac130002",
                "name" => "User",
                "email" => "user@api.com",
                "password" => "password",
                "roles" => [
                    Roles::ROLE_USER,
                ]
            ],
        ];
    }
}
```
- Ejecutamos las migraciones en la bd de test
  - doctrine migrations migrate no preguntar usa fichero env.test
  - `sf d:m:m -n --env=test`
  - A mi no me encuentra el comando ^^
  - `php bin/console d:m:m -n --env=test` con este ha ido ok
- Ejecutamos el *faker*
  - doctrine:fixtures:load 
  - `php bin/console d:f:l -n --env=test`
  ```s
  # ejecutamos el faker
  > purging database
  > loading App\DataFixtures\AppFixtures
  ```
- **configuración de clases de test**
```php
// tests/Functional/Api/TestBase.php
namespace App\Tests\Functional\Api;

use App\DataFixtures\AppFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Aqui irá toda la lógica como ids, configuraciones, creación de usuarios
 * que es lo necesario para ejecutar los tests
 */
class TestBase extends WebTestCase
{
    //trait helper para hacer operaciones contra la bd
    use FixturesTrait;

    protected const FORMAT = "jasonld";
    protected const IDS = [
        "admin_id" => "eeebd294-7737-11ea-bc55-0242ac130001",
        "user_id" => "eeebd294-7737-11ea-bc55-0242ac130002",
    ];

    protected static ?KernelBrowser $client = null;
    protected static ?KernelBrowser $admin = null;
    protected static ?KernelBrowser $user = null;

    public function setUp(): void
    {
        if(null === self::$client){
            self::$client = static::createClient();
        }

        if(null === self::$admin){
            self::$admin = clone self::$client;
            $this->createAuthenticatedUser(self::$admin,"admin@api.com","password");
        }

        if(null === self::$user){
            self::$user = clone self::$client;
            $this->createAuthenticatedUser(self::$user, "user@api.com", "password");
        }
    }

    private function createAuthenticatedUser(KernelBrowser &$client, string $username, string $password): void
    {
        $client->request(
            "POST",
            "http://localhost:200/api/v1/login_check",
            [
                "_email" => $username,
                "_password" => $password,
            ],
        );

        $data = json_decode($client->getResponse()->getContent(),true);
        $client->setServerParameters([
            "HTTP_Authorization" => sprintf("Bearer %s",$data["token"]),
            "CONTENT_TYPE" => "application/json",
        ]);
    }

    protected function getResponseData(Response $response): array
    {
        return \json_decode($response->getContent(), true);
    }

    private function resetDatabase():void
    {
        /**
         * @var EntityManagerInterface
         */
        $em = $this->getContainer()->get("doctrine")->getManager();

        if(!isset($metadata)){
            $metadata = $em->getMetadataFactory()->getAllMetadata();
        }

        $schemaTool = new SchemaTool($em);
        $schemaTool->dropDatabase();

        if(!empty($metadata)){
            $schemaTool->createSchema($metadata);
        }

        $this->postFixtureSetup();
        $this->loadFixtures([AppFixtures::class]);
    }
}

//tests/Functional/Api/User/UserTestBase.php
namespace App\Tests\Functional\Api\User;
use App\Tests\Functional\Api\TestBase;

/**
 * Aqui el test está orientado a casos de uso
 * atributos con endponts por ejemplo
 */
class UserTestBase extends TestBase
{
    protected string $endpoint;

    public function setUp(): void
    {
        //crea los clientes y guarda los tokens
        parent::setUp();
        $this->endpoint = "http://localhost:200/api/v1/users";
    }
}

// tests/Functional/Api/User/GetUserTest.php
namespace App\Tests\Functional\Api\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class GetUserTest extends UserTestBase
{
    public function testGetUsersForAdmin(): void
    {
        // /api/v1/users.jasonld
        $url = \sprintf("%s.%s",$this->endpoint,self::FORMAT);
        $url = \sprintf("%s",$this->endpoint);
        //print_r($url);//die;
        self::$admin->request("GET", $url);
        $response = self::$admin->getResponse();
        //print_r($response);die;
        $responseData = $this->getResponseData($response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }
}
```
- Ejecución del test:
  - `bin/phpunit`
  ```s
  # lo que hace por detras
  /usr/local/Cellar/php/7.4.1/bin/php /Users/ioedu/projects/prj_symfony/s5_udemy/expenses_api/vendor/symfony/phpunit-bridge/bin/simple-phpunit --configuration 
  /Users/ioedu/projects/prj_symfony/s5_udemy/expenses_api/phpunit.xml.dist --filter "/(::testGetUsersForAdmin)( .*)?$/" App\Tests\Functional\Api\User\GetUserTest 
  /Users/ioedu/projects/prj_symfony/s5_udemy/expenses_api/tests/Functional/Api/User/GetUserTest.php 
  --teamcity --cache-result-file=/Users/ioedu/projects/prj_symfony/s5_udemy/expenses_api/.phpunit.result.cache
  ```
- **ERROR**
  - El test va a medias, me indica que no se soporta ".ldjson" (forzando localhost:200)
  - symfony 404 Format jasonld is not supported
  - Cuando quito este formato, ya va, pero la respuesta es un HTML ¬¬!
  - No me van los puntos de interrupción.
    ```s
    php -i | grep "Xdebug"
    Xdebug requires Zend Engine API version 320180731.
    The Zend Engine API version 320190902 which is installed, is newer.
    Contact Derick Rethans at https://xdebug.org/docs/faq#api for a later version of Xdebug.

    solución:
    He instalado xdebug en mac con: sudo pecl install xdebug
    esto daba error porque no podía crear la carpeta "pecl" en /usr/local/Cellar/php/7.4.1/
    la creé manualmente y volví a ejecutar la instalación, se realizo correctamente. 
    Ahora xdebug va en phpstorm 

    Cuando se lanza el test desde phpstorm este ejecuta esta linea:
    Testing started at 22:12 ...
    /usr/local/Cellar/php/7.4.1/bin/php -dxdebug.remote_enable=1 -dxdebug.remote_mode=req -dxdebug.
    remote_port=9005 -dxdebug.remote_host=127.0.0.1

    Es decir, configura xdebug en el momento de la ejecución, por eso no es necesario configurar
    xdebug en php.ini (/usr/local/etc/php/7.4/php.ini)
    ```
  - **solución**
    - En **TestBase** estaba mal esto: ` protected const FORMAT = "jsonld";` Tenía: "jasonld".
```php
// tests/Functional/Api/TestBase.php
protected static ?KernelBrowser $client = null;
protected static ?KernelBrowser $admin = null;
protected static ?KernelBrowser $user = null;

/*
  * Este método se ejecuta para cada test (clase individual de test) que se ejecute
  * por lo tanto se crean clientes estaticos que se inicializen una sola vez, la primera
  * es como una emulación singleton
  */
public function setUp(): void
{
    if(null === self::$client){
        self::$client = static::createClient();
    }

    if(null === self::$admin){
        self::$admin = clone self::$client;
        $this->createAuthenticatedUser(self::$admin,"admin@api.com","password");
    }

    if(null === self::$user){
        self::$user = clone self::$client;
        $this->createAuthenticatedUser(self::$user, "user@api.com", "password");
    }
}
```
- Tengo un **error** con el test: **PutUserTest.testPutUserWithAdmin()**
  - Bd purgada
  - ![](https://trello.com/1/cards/5e7777d6cd7def249ee578fb/attachments/5e8c4c2fade01a67c475d280/previews/download?backingUrl=https%3A%2F%2Ftrello-attachments.s3.amazonaws.com%2F5e7777d6cd7def249ee578fb%2F1179x111%2Fd9510a39bc052931f6ceade24df8c2a2%2Fimage.png)
  - Bd despues del test, que va OK
  - ![](https://trello.com/1/cards/5e7777d6cd7def249ee578fb/attachments/5e8c4e27366a641c52991394/previews/download?backingUrl=https%3A%2F%2Ftrello-attachments.s3.amazonaws.com%2F5e7777d6cd7def249ee578fb%2F1200x96%2F39b6f2f9fbe48b9c5d5a64268d94d981%2Fimage.png)
    - El usuario tiene "New name" y los dos roles
  - Aqui está el error cuando ejecuto el test **PutUserTest.testPutAdminWithUser**
  ```s
  Testing App\Tests\Functional\Api\User\PutUserTest
  Undefined index: token
  /Users/ioedu/projects/prj_symfony/s5_udemy/expenses_api/tests/Functional/Api/TestBase.php:72
  /Users/ioedu/projects/prj_symfony/s5_udemy/expenses_api/tests/Functional/Api/TestBase.php:54
  /Users/ioedu/projects/prj_symfony/s5_udemy/expenses_api/tests/Functional/Api/User/UserTestBase.php:20

  testPutAdminWithUser: uri: /api/v1/users/eeebd294-7737-11ea-bc55-0242ac130001.jsonld
  
  Failed asserting that 401 matches expected 403.
  Expected :403
  Actual   :401
  /Users/ioedu/projects/prj_symfony/s5_udemy/expenses_api/tests/Functional/Api/User/PutUserTest.php:60
  ```
  - **solución** Faltaba `$this->resetDatabase() en este método`
  ```php
  //tests/Functional/Api/TestBase.php
  public function setUp(): void
  {
      $this->resetDatabase();

      if (null === self::$client) {
          self::$client = static::createClient();
      }
  ```
- Archivos php implicados:
```php
// src/DataFixtures/AppFixtures.php
public function load(ObjectManager $manager)
{
    $users = $this->getUsers();
    foreach ($users as $userData){
        $user = new User($userData["name"],$userData["email"],$userData["id"]);
        $user->setPassword($this->encoderService->generateEncodedPasswordForUser($user,$userData["password"]));
        $user->setRoles($userData["roles"]);
        $manager->persist($user);
    }

    $manager->flush();
}

//tests/Functional/Api/User/DeleteUserTest.php
namespace App\Tests\Functional\Api\User;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteUserTest extends UserTestBase
{
    public function testDeleteUserWithAdmin(): void
    {
        $uri = \sprintf("%s/%s.%s",$this->endpoint,self::IDS["user_id"], self::FORMAT);
        self::$admin->request("DELETE", $uri);
        $response = self::$admin->getResponse();
        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteAdminWithUser(): void
    {
        $uri = \sprintf("%s/%s.%s",$this->endpoint,self::IDS["admin_id"], self::FORMAT);
        self::$user->request("DELETE", $uri);
        $response = self::$user->getResponse();
        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}

// tests/Functional/Api/User/GetUserTest.php
class GetUserTest extends UserTestBase
{
    public function testGetUsersForAdmin(): void
    {
        $uri = \sprintf("%s.%s",$this->endpoint,self::FORMAT);
        self::$admin->request("GET", $uri);
        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(2,$responseData["hydra:member"]);
    }

    /**
     * Si el usuario (que no es admin) puede obtener a todos los usuarios
     */
    public function testGetUsersForUser(): void
    {
        $uri = \sprintf("%s.%s",$this->endpoint,self::FORMAT);
        self::$user->request("GET", $uri);
        $response = self::$user->getResponse();
        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * Endpoints para obtener un solo usuario
     */
    public function testGetUserWithAdmin():void
    {
        $uri = \sprintf("%s/%s.%s", $this->endpoint, self::IDS["user_id"], self::FORMAT);
        self::$admin->request("GET", $uri);
        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(self::IDS["user_id"],$responseData["id"]);
    }

    /**
     * Endpoints para obtener el admin con un usuario común
     */
    public function testGetAdminWithUser():void
    {
        $uri = \sprintf("%s/%s.%s", $this->endpoint, self::IDS["admin_id"], self::FORMAT);
        self::$user->request("GET", $uri);
        $response = self::$user->getResponse();
        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}//GetUserTest

//tests/Functional/Api/User/PutUserTest.php
namespace App\Tests\Functional\Api\User;

use App\Security\Roles;
use Symfony\Component\HttpFoundation\JsonResponse;

class PutUserTest extends UserTestBase
{
    /**
     * Al usuario simple se le actualiza el Rol a ADMIN con un Admin
     */
    public function testPutUserWithAdmin():void
    {
        $payload = [
          "name" => "New name",
          "password" => "password2",
          "roles" => [
              Roles::ROLE_ADMIN,
              Roles::ROLE_USER,
          ],
        ];

        $uri = \sprintf("%s/%s.%s",$this->endpoint,self::IDS["user_id"], self::FORMAT);
        self::$admin->request(
            "PUT",
            $uri,
            [],[],[], json_encode($payload)
        );

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(self::IDS["user_id"], $responseData["id"]);
        $this->assertEquals($payload["name"], $responseData["name"]);
        $this->assertEquals($payload["roles"], $responseData["roles"]);
    }

    /**
     * se intenta modificar un Admin con un User comun
     */
    public function testPutAdminWithUser():void
    {

        $payload = [
            "name" => "New name",
            "password" => "password2",
            "roles" => [
                Roles::ROLE_ADMIN,
                Roles::ROLE_USER,
            ],
        ];

        $uri = \sprintf("%s/%s.%s",$this->endpoint,self::IDS["admin_id"], self::FORMAT);
        self::$user->request(
            "PUT",
            $uri,
            [],[],[], json_encode($payload)
        );

        $response = self::$user->getResponse();
        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testPutUserWithAdminAndFakeRole():void
    {
        $payload = [
            "name" => "New name",
            "password" => "password2",
            "roles" => [
                Roles::ROLE_ADMIN,
                "ROLE_FAKE",
            ],
        ];

        $uri = \sprintf("%s/%s.%s",$this->endpoint,self::IDS["user_id"], self::FORMAT);
        self::$admin->request(
            "PUT",
            $uri,
            [],[],[], json_encode($payload)
        );

        $response = self::$admin->getResponse();
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testAddAdminRoleWithUser():void
    {
        $payload = [
            "name" => "New name",
            "password" => "password2",
            "roles" => [
                Roles::ROLE_ADMIN,
                Roles::ROLE_USER,
            ],
        ];


        $uri = \sprintf("%s/%s.%s",$this->endpoint,self::IDS["user_id"], self::FORMAT);
        self::$user->request(
            "PUT",
            $uri,
            [],[],[], json_encode($payload)
        );

        $response = self::$user->getResponse();
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
```

### [12. Tests unitarios para Register y Validators 38 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451578#questions/9295602)
- [`git checkout -b section5/video3-unit-testing`](https://bitbucket.org/juanwilde/sf5-expenses-api/src/975ba33f27c06b53ce2a27e6fa390edf7bc5d8de/tests/Unit/?at=section5%2Fvideo3-unit-testing)
- Nuevo archivo: `tests/Unit/Api/Action/User/RegisterTest.php`
  - No extiende de webtest ya que es un test unitario, extiende de TestCase
- [Mocker - Prophecy](https://github.com/phpspec/prophecy)
```php
//tests/Unit/Api/Action/User/RegisterTest.php
declare(strict_types=1);
namespace App\Tests\Unit\Api\Action\User;

use App\Api\Action\User\Register;
use App\Entity\User;
use App\Exceptions\User\UserAlreadyExistException;
use App\Repository\UserRepository;
use App\Service\Password\EncoderService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class RegisterTest extends TestCase
{
    /** @var ObjectProphecy|UserRepository */
    private $userRepositoryProphecy;

    private UserRepository $userRepository;

    /** @var ObjectProphecy|JWTTokenManagerInterface */
    private $JWTTokenManagerProphecy;

    private JWTTokenManagerInterface $JWTTokenManager;

    /** @var ObjectProphecy|EncoderService */
    private $encoderServiceProphecy;

    private EncoderService $encoderService;

    private Register $action;

    public function setUp(): void
    {
        //la promesa
        $this->userRepositoryProphecy = $this->prophesize(UserRepository::class);
        //la revelamos
        $this->userRepository = $this->userRepositoryProphecy->reveal();

        $this->JWTTokenManagerProphecy = $this->prophesize(JWTTokenManagerInterface::class);
        $this->JWTTokenManager = $this->JWTTokenManagerProphecy->reveal();

        $this->encoderServiceProphecy = $this->prophesize(EncoderService::class);
        $this->encoderService = $this->encoderServiceProphecy->reveal();

        $this->action = new Register($this->userRepository,$this->JWTTokenManager,$this->encoderService);

    }

    /*
     * En los tests unitarios se valida que lo que debería pasar realmente ocurre
     * @throws \Exception
     * */
    public function testCreateUser(): void
    {
        $payload = [
            "name" => "Username",
            "email" => "username@api.com",
            "password" => "random_password",
        ];

        $request = new Request([],[],[],[],[],[],\json_encode($payload));
        //hay que emular el comportamiento de lo que va a pasar en la clase
        //comprobar happy pass

        $this->userRepositoryProphecy->findOneByEmail($payload["email"])->willReturn(null);
        $this->encoderServiceProphecy->generateEncodedPasswordForUser(
            //Este argumento es dinámico, y el usuario lo inyecta la clase Register
            Argument::that(function(User $user):bool{
                return true;
            }),
            //$payload["password"]
            Argument::type("string")
        )->shouldBeCalledOnce();

        $this->userRepositoryProphecy->save(
            Argument::that(function(User $user):bool{
                return true;
            })
        )->shouldBeCalledOnce();

        $this->JWTTokenManagerProphecy->create(
            Argument::that(function(User $user):bool{
                return true;
            })
        )->shouldBeCalledOnce();

        //action es una instancia de Register
        $response = $this->action->__invoke($request);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }//testCreateUser

    public function testCreateUserForExistingEmail(): void
    {
        $payload = [
            "name" => "Username",
            "email" => "username@api.com",
            "password" => "random_password",
        ];

        $request = new Request([],[],[],[],[],[],\json_encode($payload));

        $user = new User($payload["name"],$payload["email"]);

        $this->userRepositoryProphecy->findOneByEmail($payload["email"])->willReturn($user);

        $this->expectException(UserAlreadyExistException::class);
        //action es una instancia de Register
        $this->action->__invoke($request);

    }//testCreateUserForExistingEmail
}

//tests/Unit/Security/Validator/Role/AreValidRolesTest.php
declare(strict_types=1);
namespace App\Tests\Unit\Security\Validator\Role;

use App\Exceptions\Role\UnsupportedRoleException;
use App\Security\Roles;
use App\Security\Validator\Role\AreValidRoles;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

class AreValidRolesTest extends TestCase
{
    private AreValidRoles $validator;

    public function setUp(): void
    {
        $this->validator = new AreValidRoles();
    }

    public function testRolesAreValid(): void
    {
        $payload = [
            "roles" => [
                Roles::ROLE_ADMIN,
                Roles::ROLE_USER
            ]
        ];
        $request = new Request([],[],[],[],[],[], \json_encode($payload));
        $response = $this->validator->validate($request);
        $this->assertIsArray($response);
    }

    public function testInvalidRoles(): void
    {
        $payload = [
            "roles" => [
                Roles::ROLE_ADMIN,
                "ROLE_FAKE",
            ]
        ];

        $request = new Request([],[],[],[],[],[], \json_encode($payload));
        $this->expectException(UnsupportedRoleException::class);
        $this->getExpectedExceptionMessage("Unsupported role ROLE_FAKE");
        //validator instancia de AreValidRoles
        $this->validator->validate($request);
    }
}

//tests/Unit/Security/Validator/Role/CanAddRoleAdminTest.php
declare(strict_types=1);
namespace App\Tests\Unit\Security\Validator\Role;

use App\Exceptions\Role\RequiredRoleToAddRoleAdminNotFoundException;
use App\Security\Roles;
use App\Security\Validator\Role\CanAddRoleAdmin;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class CanAddRoleAdminTest extends TestCase
{
    /** @var ObjectProphecy|Security */
    private  $securityProphecy;
    private Security $security;


    private CanAddRoleAdmin $validator;

    public function setUp(): void
    {
        $this->securityProphecy = $this->prophesize(Security::class);
        $this->security = $this->securityProphecy->reveal();
        $this->validator = new CanAddRoleAdmin($this->security);
    }

    public function testCanAddRoleAdmin():void
    {
        $payload = [
            "roles" => [
                Roles::ROLE_ADMIN,
                Roles::ROLE_USER
            ]
        ];
        $request = new Request([],[],[],[],[],[], \json_encode($payload));
        $this->securityProphecy->isGranted(Roles::ROLE_ADMIN)->willReturn(true);
        $response = $this->validator->validate($request);
        $this->assertIsArray($response);
    }

    public function testCannotAddRoleAdmin():void
    {
        $payload = [
            "roles" => [
                Roles::ROLE_ADMIN,
                Roles::ROLE_USER
            ]
        ];
        $request = new Request([],[],[],[],[],[], \json_encode($payload));
        $this->securityProphecy->isGranted(Roles::ROLE_ADMIN)->willReturn(false);
        $this->expectException(RequiredRoleToAddRoleAdminNotFoundException::class);
        $this->expectExceptionMessage("ROLE_ADMIN required to perform this operation");
        $this->validator->validate($request);
    }
}
```
- Activamos code style para tests en `s5_udemy/expenses_api/makefile`
- Hay que tratar minimizar el numero de inyecciones en el contstructor ya que esto obliga a crear un mock por cada instancia

### Sección 7: Grupos 0 / 5|3 h 20 min
### [13. Entidad para grupos y actualización de la configuración de API Platform 20 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451596#questions/9295602)
- Un usuario puede estar en varios grupos
- Un grupo puede contener varios usuarios
- ![](https://trello.com/1/cards/5e7777d6cd7def249ee578fb/attachments/5e8df948454b1a6a6be29ec8/previews/download?backingUrl=https%3A%2F%2Ftrello-attachments.s3.amazonaws.com%2F5e7777d6cd7def249ee578fb%2F807x108%2F1636db981ac37b99c60db0bf0d3594b3%2Fimage.png)
- **entidades**
```php
//src/Entity/User.php
/**
 * @return Group[]|Collection
 */
public function getGroups():Collection
{
    return $this->groups;
}

public function addGroup(Group $group): void
{
    $this->groups->add($group);
}

//src/Entity/Group.php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use phpDocumentor\Reflection\Types\Collection;
use Ramsey\Uuid\Uuid;

class Group
{
    private ?string $id;
    private string $name;
    private User $owner;
    protected \DateTime $createdAt;
    protected \DateTime $updatedAt;

    /** @var Collection|User[  */
    private Collection $users;

    /**
     * @throws \Exception
     */
    public function __construct(string $name, User $owner, string $id = null)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->name = $name;
        $this->owner = $owner;
        $this->createdAt = new \DateTime();
        $this->users = new ArrayCollection();

    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    public function getCreatedAt(): \Datetime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \Datetime
    {
        return $this->updatedAt;
    }

    public function markAsUpdated(): void
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return Collection|User[
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): void
    {
        //relación n:m
        $this->users->add($user);
        $user->addGroup($this);
    }
}
```
- **config/api_platform (nuevos)**
```yaml
# config/api_platform/resources/Group.yaml
App\Entity\Group:
  attributes:
    normalization_context:
      groups: ['group_read']

  collectionOperations:
    get:
      method: 'GET'
      security: 'is_granted("GROUP_READ")'
    post:
      method: "POST"
      security: 'is_granted("GROUP_CREATE")'
      denormalization_context:
        groups: ['group_post']
      swagger_context:
        parameters:
          - in: body
            name: group
            description: The group to create
            schema:
              type: object
              required:
                - name
                - owner
              properties:
                name:
                  type: string
                owner:
                  type: string

  itemOperations:
    get:
      method: 'GET'
      security: 'is_granted("GROUP_READ", object)'
    put:
      method: 'PUT'
      security: 'is_granted("GROUP_UPDATE", object)'
      denormalization_context:
        groups: ['group_put']
      swagger_context:
        parameters:
          - in: body
            name: group
            description: The group to update
            schema:
              type: object
              required:
                - name
              properties:
                name:
                  type: string

    delete:
      method: 'DELETE'
      security: 'is_granted("GROUP_DELETE", object)'

# config/api_platform/serialization/Group.yaml
App\Entity\Group:
  attributes:
    id:
      groups: ['group_read']
    name:
      groups: ['group_read', 'group_post',"group_put"]
    owner:
      groups: ['group_read',"group_post"]
    createdAt:
      groups: ["group_read"]
    updateedAt:
      groups: ["group_read"]

# Doctrine/Mapping/Entity/Group.orm.yml
App\Entity\Group:
  type: entity
  table: user_group #group está reservado (group by)

  id:
    id:
      type: string

  manyToOne:
    owner:
      targetEntity: User

  manyToMany:
    users:
      targetEntity: User
      mappedBy: groups

  fields:
    name:
      type: string
      nullable: false
    createdAt:
      type: datetime
      nullable: false
    updatedAt:
      type: datetime
      nullable: false

  lifecycleCallbacks:
    preUpdate: [markAsUpdated]
```
- **modificados**
```yaml
# Doctrine/Mapping/Entity/User.orm.yml
  manyToMany:
    groups:
      targetEntity: Group
      inversedBy: users
      joinTable:
        # si no pusieramos esto crearia la tabla users_groups
        name: user_group_user 
# config/api_platform/serialization/User.yaml
    createdAt:
      groups: ["user_read"]
    updateedAt:
      groups: ["user_read"]
```

- Error:
  ```s
  Symfony\Component\ErrorHandler\Error\
  UndefinedMethodError
  Attempted to call an undefined method named "getDescription" of class "phpDocumentor\Reflection\DocBlock\Tags\InvalidTag".
  ```
  - **solución**
  > Se estaba definiendo una variable con un comentario incompleto: `User[` `/** @var Collection|User[ */private Collection $users;` le falta el cierre del corchete
- Con todo esto configurado y swagger funcionando se ejecuta:
  - [git checkout -b section6/video1-create-group-and-update-api-platform](https://bitbucket.org/juanwilde/sf5-expenses-api/src/ae46ecf5fd9172454cc2a678c8c205d057476adc/src/Entity/Group.php?at=section6%2Fvideo1-create-group-and-update-api-platform)

### [14. Configurar seguridad para grupos 52 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451602#questions/9295602)
- Grupos y su relación con usuarios
- [`git checkout -b section/6/video2-configure-group-resource`](https://bitbucket.org/juanwilde/sf5-expenses-api/src/929512cf9a43cfa522a1ce90fe8a433714101df3/?at=section6%2Fvideo2-configure-group-resource)
- Hay que agregar una capa de seguridad para que un usuario solo pueda crear grupos para si mismo
- Esto evitaría que un usuario con conocimiento del id de otro usuario pueda crear grupos para el segundo
- Para esto hay que crear un listener de escritura. ***`src/Api/Listener/User/GroupPreWriteListener.php`**
  - El listener depende de una URI con lo cual hay que revisar el rep de rutas: `sf d:r`
  - Se selecciona: `api_users_get_collection  GET ANY ANY /api/v1/users.{_format}`
- El listener necesita lanzar una excepción. Hay que crear esta: **`src/Exceptions/Group/CannotAddAnotherOwnerException.php`**
- Con el listener configurado pasamos a lanzar las migraciones **ya contamos con la entidad Group y el mapping**
  - `sf d:m:g` - doctrine:migrations:generate 
  - crea el fichero
  ```php
  <?php
  //expenses_api/src/Migrations/Version20200414192436.php 
  public function up(Schema $schema) : void
  {
      // this up() migration is auto-generated, please modify it to your needs
      $this->addSql(
          "
          CREATE TABLE user_group (
              id CHAR(36) NOT NULL PRIMARY KEY,
              name VARCHAR(100) NOT NULL,
              owner_id CHAR(36) NOT NULL,
              created_at DATETIME NOT NULL,
              updated_at DATETIME NOT NULL,
              INDEX idx_user_group_user_id (owner_id),
              CONSTRAINT fk_user_group_user_id FOREIGN KEY (owner_id) REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE
          )
          DEFAULT CHARACTER SET utf8mb4 
          COLLATE utf8mb4_general_ci
          ENGINE = InnoDB            
          "
      );

      $this->addSql(
          "
          CREATE TABLE user_group_user (
              user_id CHAR(36) NOT NULL,
              group_id CHAR(36) NOT NULL,
              INDEX idx_user_group_user_user_id (user_id),
              INDEX idx_user_group_user_group_id (group_id),
              CONSTRAINT fk_user_group_user_user_id FOREIGN KEY (user_id) REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE,
              CONSTRAINT fk_user_group_user_group_id FOREIGN KEY (group_id) REFERENCES user_group(id) ON UPDATE CASCADE ON DELETE CASCADE
          )
          DEFAULT CHARACTER SET utf8mb4 
          COLLATE utf8mb4_general_ci
          ENGINE = InnoDB            
          "
      );
  }

  public function down(Schema $schema) : void
  {
      $this->addSql("DROP TABLE user_group_user");
      $this->addSql("DROP TABLE user_group");
  }
  ```
  - En el contenedor **be**:`sf d:m:m -n`
  ```s
   ++ migrated (took 297.9ms, used 26M memory)
  ------------------------
  ++ finished in 316ms
  ++ used 26M memory
  ++ 1 migrations executed
  ++ 2 sql queries
  ```
  - ![](https://trello-attachments.s3.amazonaws.com/5b014dcaf4507eacfc1b4540/5e7777d6cd7def249ee578fb/7394de7a06a73080970e8906810d18e6/image.png)
- Cuando (Juan) intenta crear un usuario con postman le da un error: **Typed property App\Entity\User::$groups must not be accessed before initialization**
  - **solución**
    - En `App\Entity\User` hay que retocar esta linea `protected ?Collection $groups = null;` permitiendo inicialización con null
- Tambien habia otro error por el tipo de Collection ya que estaba usando otro namespace
- **crear grupo**
-![](https://trello-attachments.s3.amazonaws.com/5b014dcaf4507eacfc1b4540/5e7777d6cd7def249ee578fb/f8b7cd2f075b866593f075c2b70feb60/image.png)
```js
{
  "name": "Grupo de Juan",
  "owner": "/api/v1/users/3628ca48-ad5b-4bb1-9bfc-4a7aa95e1998"  //hay que pasar el endpoint que mapea la entidad usuario
}

//con esta llamada da error 400:
{
  "name": "prueba con error",
  "owner": "3628ca48-ad5b-4bb1-9bfc-4a7aa95e1998"
}
{
  "@context": "/api/v1/contexts/Error",
  "@type": "hydra:Error",
  "hydra:title": "An error occurred",
  "hydra:description": "Invalid IRI \"3628ca48-ad5b-4bb1-9bfc-4a7aa95e1998\".",
  ...
}
```
- Si da este error:
  - > Typed property App\Entity\Group::$updatedAt must not be accessed before initialization
  - Se debe a que el valor inicial de `$createdAt` y `$updatedAt` puede ser null entonces hay que cambiar la definición con **elvis** 
  - `private ?\Datetime $createdAt = null;`
- Da otra excepción en insert, y es porque en la entidad Group faltaba ejecutar en el constructor **$this->markAsUpdated();**
- **Error:** No me esta guardando en **user_group_user**
  - He probado copiar todo del repo original y sigue sin funcionar.
  - He comprobado las relaciones **.orm.yml** y nada que destacar, tambien he aplicado los cambios de las migraciones y nada.
  - El problema estaba en el listener **GroupPreWriteListener** que tenía mal puesta la ruta en `private const POST_GROUP=api_users_get_collection` y debía ser `api_groups_post_collection`.  Al no estar bien la ruta, no se agragaba el usuario en sesion a la relación y por lo tanto no se guardaba
- Sigo sin entender para que es **inversedBy y mappedBy** en el caso **Usuarios - Grupos** el inverso es: `User inversed Group.users` y mapped en `Group mapped User.groups`
  - `user_group_user(user_id,group_id)`
  - **inversedBy** parece que inversedBy indica quien es el padre (pais inversedBy ciudad.paises)
  - Más info sobre doctrine [nicio proyecto con Doctrine ORM by miw-upm](https://www.youtube.com/watch?v=ELIrOvAtiQY&t=1795s)
- La excepción **403** se debe a que el id del owner no coincide con el del grupo
  - ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/1107x695/0aac1dfbb8a513f733eb6a7ab1c0e2b4/image.png)
  - Registros en la tabla pivote:
    - ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/696x130/26957a4e71510e70f7aab78196cf6a1c/image.png)
- Crear un endpoint para obtener todos los grupos de un usuario
  - **config/api_platform/resources/User.yaml**
  ```yaml
  #definiiendo los subrecursos de cada entidad
  properties:
    groups:
      subresource:
        subresourceClass: 'App\Entity\User'
        collection: true
        maxDepth: 1

  # si da este error es porque tenía esta linea: subresourceClass: "App\Entity\User" con dobles comillas
  Found unknown escape character "\E" in config/api_platform/resources/User.yaml at line 47 (near "subresourceClass: "App\Entity\User"") in . 
  (which is being imported from "/appdata/www/config/routes/api_platform.yaml"). Make sure there is a loader supporting the "api_platform" type.      
  ```
  - Crea este endpoint: **/api/v1/users/{id}/groups**
  - ![](https://trello-attachments.s3.amazonaws.com/5b014dcaf4507eacfc1b4540/5e7777d6cd7def249ee578fb/9e5062a846456338cef23270c30f9f77/image.png)
  - **doctrine extensions**
  - [sobre extensiones: https://api-platform.com/docs/core/extensions](https://api-platform.com/docs/core/extensions/)
  - Se presenta un fallo de seguridad en este endpoint. 
    - Con Pepe en sesion se puede pedir todos los grupos de Juan. Como corregimos esto?. No se puede hacer en el GropuPreWriteListener y tampoco en el Voter
  - Se crea una extensión que permitirá customizar la consulta generada por el endpoint
  ```php
  //src/Doctrine/Extension/DoctrineUserExtension.php
  declare(strict_types=1);

  namespace App\Doctrine\Extension;

  use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
  use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
  use App\Entity\Group;
  use App\Entity\User;
  use App\Repository\GroupRepository;
  use App\Security\Roles;
  use Doctrine\ORM\QueryBuilder;
  use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
  use Symfony\Component\Security\Core\Security;

  class DoctrineUserExtension implements QueryCollectionExtensionInterface
  {
      private TokenStorageInterface $tokenStorage;

      private Security $security;

      private GroupRepository $groupRepository;

      public function __construct(
          TokenStorageInterface $tokenStorage,
          Security $security,
          GroupRepository $groupRepository
      ) {
          $this->tokenStorage = $tokenStorage;
          $this->security = $security;
          $this->groupRepository = $groupRepository;
      }

      public function applyToCollection(
          QueryBuilder $queryBuilder,
          QueryNameGeneratorInterface $queryNameGenerator,
          string $resourceClass,
          string $operationName = null
      ) {
          $this->addWhere($queryBuilder, $resourceClass);
      }

      private function addWhere(QueryBuilder $qb, string $resourceClass): void
      {
          if ($this->security->isGranted(Roles::ROLE_ADMIN)) {
              return;
          }

          /** @var User $user */
          $user = $this->tokenStorage->getToken()->getUser();

          $rootAlias = $qb->getRootAliases()[0];

          if (Group::class === $resourceClass) {
              $qb->andWhere(\sprintf('%s.%s = :currentUser', $rootAlias, $this->getResources()[$resourceClass]));
              $qb->setParameter(':currentUser', $user);
          }
      }

      private function getResources(): array
      {
          return [Group::class => 'owner'];
      }

  }//DoctrineUserExtension
  ```
- Para filtrar una colección de un **subrecurso** se usa una extensión
- Todo lo que tiene que ver con seguridad se puede resolver en estas tres estructuras:
  - Listeners
  - Voters
  - Extensions
- **Archivos tocados**
```php
//src/Entity/Group.php
public function isOwnedBy(User $user): bool
{
    return $this->getOwner()->getId() === $user->getId();
}

//src/Exceptions/Group/CannotAddAnotherOwnerException.php
declare(strict_types=1);
namespace App\Exceptions\Group;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotAddAnotherOwnerException extends AccessDeniedHttpException
{
    private const MESSAGE = 'You cannot add another user as owner';

    public static function create(): self
    {
        throw new self(self::MESSAGE);
    }
}

//src/Repository/GroupRepository.php
namespace App\Repository;
use App\Entity\Group;
use App\Entity\User;

class GroupRepository extends BaseRepository
{
    protected static function entityClass(): string
    {
        return Group::class;
    }

    public function findOneById(string $id): ?Group
    {
        /** @var Group $group */
        $group = $this->objectRepository->find($id);

        return $group;
    }

    public function userIsMember(Group $group, User $user): bool
    {
        foreach ($group->getUsers() as $userGroup) {
            if ($userGroup->getId() === $user->getId()) {
                return  true;
            }
        }

        return false;
    }
}
//src/Security/Authorization/Voter/BaseVoter.php
namespace App\Security\Authorization\Voter;
use App\Repository\GroupRepository;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

abstract class BaseVoter extends Voter
{
    protected Security $security;
    protected GroupRepository $groupRepository;

    public function __construct(Security $security, GroupRepository $groupRepository)
    {
        $this->security = $security;
        $this->groupRepository = $groupRepository;
    }
}
// src/Security/Authorization/Voter/GroupVoter.php
namespace App\Security\Authorization\Voter;
use App\Entity\Group;
use App\Entity\User;
use App\Security\Roles;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class GroupVoter extends BaseVoter
{
    private const GROUP_READ = "GROUP_READ";
    private const GROUP_CREATE = "GROUP_CREATE";
    private const GROUP_UPDATE = "GROUP_UPDATE";
    private const GROUP_DELETE = "GROUP_DELETE";

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, $subject):bool
    {
        return \in_array($attribute,$this->getSupportedAttributes(),true);
    }

    /**
     * @param Group|null $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token):bool
    {
        /** @var User $tokenUser  **/
        $tokenUser = $token->getUser();

        if(self::GROUP_READ === $attribute)
        {
            //si no hay grupo
            if(null === $subject)
            {
                //comprueba si el usuario en sesion (token) es admin
                return $this->security->isGranted(Roles::ROLE_ADMIN);
            }

            return $this->security->isGranted(Roles::ROLE_ADMIN) || $this->groupRepository->userIsMember($subject, $tokenUser);
        }

        //si llega a este punto de ejecución es porque el usuario tiene piermisos y puede crear un grupo
        if( self::GROUP_CREATE === $attribute )
        {
            return true;
        }

        //cualquier administrador o cualquier miembro del grupo
        if(self::GROUP_UPDATE === $attribute)
        {
            return $this->security->isGranted(Roles::ROLE_ADMIN) || $this->groupRepository->userIsMember($subject, $tokenUser);
        }

        //para eliminarlo solo lo puede hacer un ADMIN o el propietario (creador)
        if(self::GROUP_DELETE === $attribute)
        {
            return $this->security->isGranted(Roles::ROLE_ADMIN) || $subject->isOwnerBy($tokenUser);
        }

        return false;

    }//voteOnAttribute

    private function getSupportedAttributes():array
    {
        return [
            self::GROUP_READ,
            self::GROUP_CREATE,
            self::GROUP_UPDATE,
            self::GROUP_DELETE
        ];
    }

}// GroupVoter
```
- [Como inyecta symfony las extensiones](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451610#questions/10185068/)

### [15. Crear endpoint para añadir usuarios a un grupo 1 h 8 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451610#questions/9295602)
- Nos falta añadir y eliminar usuarios al grupo
- Esto tiene que ver con Subrecursos de Api Platform [subresources](https://api-platform.com/docs/core/subresources/#subresources)
  - Se puede definir endpoints tipo: `questions/<id>/<subrecurso>/<otro-subrecurso>`. Tiene relación con el maxdepth, en nuestro caso nos interesa acceder hasta el primer subrecurso
- Esta caracteristica solo está disponible para **GET**
- Si desearamos hacer algo como: `/api/v1/users/<id-user>/groups/<id-grupo>` usando **POST** no se podría por lo tanto habria que definir un **CUSTOM ACTION** (src/api/action)
- [`git checkout -b section6/video3-custom-actions-to-manage-users`](https://bitbucket.org/juanwilde/sf5-expenses-api/src/93106d6ec07ba5b587406577be677db59d16616f/src/Doctrine/Extension/DoctrineUserExtension.php?at=section6%2Fvideo3-custom-actions-to-manage-users)
- Solo el administrador puede agregar usuarios al grupo
- Para quitar usuarios de un grupo lo puede hacer el admin o el mismo usuario del grupo. Si me añaden yo me puedo salir
- **crear** 
  - ~`src/Api/Action/Group.php`~ no era un archivo sino una carpeta
  - `src/Api/Action/Group/AddUser.php`
- Tecnica:[Symfony argument value resolver](https://symfony.com/doc/current/controller/argument_value_resolver.html)
  - Podemos pasar un recurso en concreto a un controller
  - caso de uso: si deseo agregar un usuario a un grupo pero quiero saber que viene en el token si inyecto el **TokenStorageInterface** en un custom action va a dar un Error porque Symfony no permite hacer ese tipo de inyección de dependencias en este tipo de controllers. 
  - Utilizando un **argument resolver** a partir del argumento id de la ruta vamos a obtener al usuario usando el repositorio. Una vez obtenido el usuario lo vamos a poder pasar dentro de **`SomeController->invoke($objResolved)`**
- Para que symfony y su autowiring sea capaz de identificar que la clase es un Resolver hay que implementar **ArgumentValueResolverInterface**
- Me da un error la anotación:
  ```s
  src/Api/Action/Group/AddUser.php
  /**
  * @Route("/groups/add_user", methods={"POST"})
  */
  sf d:r
  [Semantical Error] The annotation "@Route" in method App\Api\Action\Group\AddUser::__invoke() was never imported. 
  Did you maybe forget to add a "use" statement for this annotation? in /appdata/www/config/routes/../../src/Api/ 
  (which is being imported from "/appdata/www/config/routes/annotations.yaml"). Make sure annotations are installed and enabled.
  **solución**
  Faltaba importar: use Symfony\Component\Routing\Annotation\Route;

  appuser@daf72e73a8a5:/appdata/www$ sf d:r
  -------------------------------------- -------- -------- ------ ---------------------------------------- 
    Name                                   Method   Scheme   Host   Path                                    
  -------------------------------------- -------- -------- ------ ---------------------------------------- 
  _preview_error                         ANY      ANY      ANY    /_error/{code}.{_format}                
  app_api_action_group_adduser__invoke   POST     ANY      ANY    /api/v1/groups/add_user     -->nueva
  app_api_action_user_register__invoke   POST     ANY      ANY    /api/v1/users/register                  
  ...                   
  -------------------------------------- -------- -------- ------ ----------------------------------------
  ```
- Siempre que se quiera atacar a un endpoint que esté protegido tras el firewall se necesita un usuario autenticado y para esto se necesita el argument resolver
- Una vez configurado **AddUser.invoke** todo va bien, excepto que se permite guardar duplicados en **user_group_user** esto debería de validarse o controlarlo por bd.  En este caso se hará por definición de bd, aplicando unique en el archivo de migración.
  ```sql
  -- expenses_api/src/Migrations/Version20200414192436.php
  -- archivo de migración
  CREATE TABLE user_group_user (
  user_id CHAR(36) NOT NULL,
  group_id CHAR(36) NOT NULL,
  UNIQUE (user_id, group_id), -- Linea agregada
  INDEX idx_user_group_user_user_id (user_id),
  INDEX idx_user_group_user_group_id (group_id),
  ```
  - Se ejecuta **`sf d:m:e 20200414192436 -n --down`** 
  ```s
  appuser@daf72e73a8a5:/appdata/www$ sf d:m:e 20200414192436 -n --down
  -- reverting 20200414192436 (Creates `user_group` and `user_group_user` tables)
  -> DROP TABLE user_group_user
  -> DROP TABLE user_group
  -- reverted (took 352.7ms, used 26M memory)

  appuser@daf72e73a8a5:/appdata/www$ sf d:m:m -n
  Migrating up to 20200414192436 from 20200330212754
  ++ migrating 20200414192436 (Creates `user_group` and `user_group_user` tables)
  ++ migrated (took 338.5ms, used 26M memory)
  ------------------------
  ++ finished in 357.3ms
  ++ used 26M memory
  ++ 1 migrations executed
  ++ 2 sql queries
  ```
  - **antes migracion**
    - ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/387x169/9a5d87df5873d467f0d3a7928ad2b0a6/image.png)
  - **despues migracion**
    - ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/376x183/bb448dfad842cba67a7af6ef3e5326c8/image.png)
  - Ahora da este error en caso de duplicados
  ```s
  An exception occurred while executing 'INSERT INTO user_group_user (user_id, group_id) VALUES (?, ?)' 
  with params ["3628ca48-ad5b-4bb1-9bfc-4a7aa95e1998", "d9640aea-6b2c-4d5e-a4ab-37be525e377f"]:
  SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '3628ca48-ad5b-4bb1-9bfc-4a7aa95e1998-d9640aea-6b2c-4d5e-a4ab-37b' for key 'user_id'
  ```
```php
// src/Api/Action/Group/AddUser.php

/**
 * @Route("/groups/add_user", methods={"POST"})
 */
public function __invoke(Request $request, User $user): JsonResponse
{
    $groupId = RequestTransformer::getRequiredField($request, 'group_id');
    $userId = RequestTransformer::getRequiredField($request, 'user_id');

    $group = $this->groupRepository->findOneById($groupId);
    if(null === $group){
        throw new BadRequestHttpException("Group not found");
    }

    if(!$this->groupRepository->userIsMember($group,$user))
    {
        throw new BadRequestHttpException("You cannot add users to this group");
    }

    $newUser = $this->userRepository->findOneById($userId);
    if(null === $newUser){
        throw new BadRequestHttpException("User not found");
    }

    if($this->groupRepository->userIsMember($group,$newUser)) {
        throw new ConflictHttpException("This user is already member of this group");
    }

    $group->addUser($newUser);
    //aqui ya se guarda la relacion usuario:grupo
    $this->groupRepository->save($group);

    return new JsonResponse([
        "message"=>\sprintf(
            "User with id %s has been added to group with id %s",
            $newUser->getId(),
            $group->getId()),
    ], JsonResponse::HTTP_CREATED);
}// __invoke

//src/Api/ArgumentResolver/UserValueResolver.php
class UserValueResolver implements ArgumentValueResolverInterface
{
    private TokenStorageInterface $tokenStorage;

    private UserRepository $userRepository;

    public function __construct(TokenStorageInterface $tokenStorage, UserRepository $userRepository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (User::class !== $argument->getType()) {
            return false;
        }

        $token = $this->tokenStorage->getToken();
        if (!$token instanceof TokenInterface) {
            return false;
        }

        return $token->getUser() instanceof User;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        yield $this->userRepository->findOneByEmail($this->tokenStorage->getToken()->getUser()->getEmail());
    }
}//UserValueResolver

//src/Repository/GroupRepository.php
public function save(Group $group)
{
    $this->saveEntity($group);
}

// src/Repository/UserRepository.php
public function findOneById(string $id): ?User
{
    /**
     * @var User $user
     */
    $user = $this->objectRepository->find($id);
    return $user;
}
```
```yaml
# services.yaml
App\Api\ArgumentResolver\UserValueResolver:
    tags:
        - {name:  controller.argument_value_resolver, priority: 50 }
```
- Con el test **AddUserTest.php** configurado, lanzamos la prueba y salta el **error**:
  - No se encuentra el método add() de un null
  - `Error: call to a member function add() on null`
  - Faltaba inicializar en el constructor de User.php la variable `$this->groups = new ArrayCollection()`
- Corregido lo anterior a mi me da error y a Juan no:
  ```s
  # min  1:07:45
  Testing App\Tests\Unit\Api\Action\Group\AddUserTest
  Failed asserting that 201 matches expected 200.
  Expected :200
  Actual   :201
  
  solución:
  Tenía la respuesta en la accion (AddUser) con 201 y Juan no aplica este código
  ```
- Ya pasa el test:
```php
//src/Api/Action/Group/AddUser.php
if(null === $group = $this->groupRepository->findOneById($groupId)){
    throw new BadRequestHttpException("Group not found");
}

//src/Entity/User.php
...
use Doctrine\Common\Collections\ArrayCollection;
public function __construct(string $name, string $email, string $id = null)
{
    ...
    $this->groups = new ArrayCollection();
    ...
}

//tests/Unit/Api/Action/TestBase.php
class TestBase extends TestCase
{
    /** @var ObjectProphecy|UserRepository */
    protected $userRepositoryProphecy;
    protected UserRepository $userRepository;

    /** @var ObjectProphecy|GroupRepository */
    protected $groupRepositoryProphecy;
    protected GroupRepository $groupRepository;

    public function setUp(): void
    {
        $this->userRepositoryProphecy = $this->prophesize(UserRepository::class);
        $this->userRepository = $this->userRepositoryProphecy->reveal();

        $this->groupRepositoryProphecy = $this->prophesize(GroupRepository::class);
        $this->groupRepository = $this->groupRepositoryProphecy->reveal();
    }
}

//tests/Unit/Api/Action/Group/AddUserTest.php
public function testCanAddUserToGroup(): void
{
  $payload = [
      "group_id" => "group_id_123",
      "user_id" => "user_id_456",
  ];

  $request = new Request([],[],[],[],[],[], \json_encode($payload));

  $user = new User("name","name@api.com");
  $newUser = new User("new","new@api.com");
  $group = new Group("group",$user);

  $this->groupRepositoryProphecy->findOneById($payload["group_id"])->willReturn($group);
  $this->groupRepositoryProphecy->userIsMember($group,$user)->willReturn(true);
  $this->userRepositoryProphecy->findOneById($payload["user_id"])->willReturn($newUser);
  $this->groupRepositoryProphecy->userIsMember($group,$newUser)->willReturn(false);

  //se llama al método save una sola vez con una instancia de grupo
  $this->groupRepositoryProphecy->save(
      Argument::that(
          function(Group $group): bool {
              return true;
          }
      )
  )->shouldBeCalledOnce();

  //Todo lo anterior son los requisitos necesarios para lanzar AddUser.invoke()
  // /groups/add_user  methods=POST
  $response = $this->action->__invoke($request, $user);

  $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
}
```
- Sobre los **tests** una vez que se tiene definido el método **accion._invoke()** hay que tratar todas las excepciones por separado en los tests. Ejemplo `AddUser.php->invoke() => AddUserTest.php->test<Métodos>`
```php
//src/Api/Action/Group/AddUser.php
use App\Exceptions\Group\CannotAddUsersToGroupException;
use App\Exceptions\Group\GroupDoesNotExistException;
use App\Exceptions\User\UserDoesNotExistException;
use App\Exceptions\User\UserAlredyMemberOfGroupException;
...
  if(null === $group = $this->groupRepository->findOneById($groupId)){
      throw GroupDoesNotExistException::fromGroupId($groupId);
  }

  if(!$this->groupRepository->userIsMember($group,$user)){
      throw CannotAddUsersToGroupException::create();
  }

  if(null === $newUser = $this->userRepository->findOneById($userId)){
      throw UserDoesNotExistException::fromUserId($userId);
  }

  if($this->groupRepository->userIsMember($group,$newUser)) {
      throw UserAlredyMemberOfGroupException::fromUserId($userId);
  }
...

// src/Exceptions/Group/CannotAddUsersToGroupException.php
// src/Exceptions/Group/GroupDoesNotExist.php
// src/Exceptions/User/UserAlredyMemberOfGroupException.php
// src/Exceptions/User/UserDoesNotExistException.php

//tests/Unit/Api/Action/Group/AddUserTest.php
class AddUserTest extends TestBase
{
  private User $user;
  private User $newUser;

  private array $payload;
  private Request $request;

  private AddUser $action;
  
  public function setUp(): void
  {
      parent::setUp();
      $this->payload = [
          "group_id" => "group_id_123",
          "user_id" => "user_id_456",
      ];
      $this->request = new Request([],[],[],[],[],[], \json_encode($this->payload));
      $this->user = new User("name","name@api.com");
      $this->newUser = new User("new","new.user@api.com");
      $this->action = new AddUser($this->userRepository,$this->groupRepository);
  }  

  public function testForNonExistingGroup(): void
  {
      $this->groupRepositoryProphecy->findOneById($this->payload["group_id"])->willReturn(null);
      $this->expectException(GroupDoesNotExistException::class);
      $this->action->__invoke($this->request, $this->user);
  }//testForNonExistingGroup

  /**
   * @throws \Exception
   */
  public function testAddUserToAnotherGroup(): void
  {
      $group = new Group("group",$this->user);
      $this->groupRepositoryProphecy->findOneById($this->payload["group_id"])->willReturn($group);
      $this->groupRepositoryProphecy->userIsMember($group,$this->user)->willReturn(false);
      $this->expectException(CannotAddUsersToGroupException::class);
      $this->action->__invoke($this->request, $this->user);
  }//testAddUserToAnotherGroup

  /**
   * @throws \Exception
   */
  public function testNewUserDoesNotExist(): void
  {
      $group = new Group("group",$this->user);
      $this->groupRepositoryProphecy->findOneById($this->payload["group_id"])->willReturn($group);
      $this->groupRepositoryProphecy->userIsMember($group,$this->user)->willReturn(true);
      $this->userRepositoryProphecy->findOneById($this->payload["user_id"])->willReturn(null);
      $this->expectException(UserDoesNotExistException::class);
      $this->action->__invoke($this->request, $this->user);
  }//testAddUserToAnotherGroup

  /**
   * @throws \Exception
   */
  public function testNewUserAlreadyMemberOfGroup(): void
  {
      $group = new Group("group",$this->user);
      $this->groupRepositoryProphecy->findOneById($this->payload["group_id"])->willReturn($group);
      $this->groupRepositoryProphecy->userIsMember($group,$this->user)->willReturn(true);
      $this->userRepositoryProphecy->findOneById($this->payload["user_id"])->willReturn($this->newUser);
      $this->groupRepositoryProphecy->userIsMember($group,$this->newUser)->willReturn(true);

      $this->expectException(UserAlredyMemberOfGroupException::class);
      $this->action->__invoke($this->request, $this->user);
  }//testAddUserToAnotherGroup
```

### [16. Crear endpoint para eliminar usuarios de un grupo 34 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451618#questions/9295602)
- [`git checkout -b section6/video4-custom-action-to-remove-users-from-group`](https://bitbucket.org/juanwilde/sf5-expenses-api/src/89febb12d442eec2476ac3b837b27e9b6613bbe4/?at=section6%2Fvideo5-create-functional-tests-for-group)
- Refactor endpoint en AddUser.php se pasará a un servicio
- Creacion de servicio: `GroupService`
```php
//src/Api/Action/Group/AddUser.php
class AddUser
{
    private GroupService $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    /**
     * @Route("/groups/add_user", methods={"POST"})
     */
    public function __invoke(Request $request, User $user): JsonResponse
    {
        $this->groupService->addUserToGroup(
            RequestTransformer::getRequiredField($request, "group_id"),
            RequestTransformer::getRequiredField($request, "user_id"),
            $user
        );

        //404 sin contenido pero que se ha borrado el recurso
        return new JsonResponse(null,JsonResponse::HTTP_NO_CONTENT);
    }// __invoke

}//AddUser

//src/Api/Action/Group/RemoveUser.php
class RemoveUser
{
    private GroupService $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    /**
     * @Route("/groups/remove_user", methods={"POST"})
     */
    public function __invoke(Request $request, User $user): JsonResponse
    {
        $this->groupService->removeUserFromGroup(
            RequestTransformer::getRequiredField($request, 'group_id'),
            RequestTransformer::getRequiredField($request, 'user_id'),
            $user
        );

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }//__invoke

}//RemoveUser

//Entity/Group.php
public function removeUser(User $user):void
{
    $this->users->removeElement($user);
    $user->removeGroup($this);
}

//Entity/User.php
public function removeGroup(Group $group): void
{
    $this->groups->removeElement($group);
}

//RefactorExcepcion:  CannotAddUsersToGroupException a CannotManageGroupException
//nuevo: // src/Exceptions/Group/UserNotMemberOfGroupException.php

// src/Service/Group/GroupService.php
class GroupService
{
    private GroupRepository $groupRepository;
    private UserRepository $userRepository;

    public function __construct(GroupRepository $groupRepository, UserRepository $userRepository)
    {

        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
    }

    public function addUserToGroup(string $groupId, string $userId, User $user): void
    {
        $group = $this->getGroupFromId($groupId);

        $this->userCanManageGroup($user, $group);

        $userToAdd = $this->getUserFromId($userId);

        if ($this->groupRepository->userIsMember($group, $userToAdd)) {
            throw UserAlreadyMemberOfGroupException::fromUserId($userId);
        }

        $group->addUser($userToAdd);

        $this->groupRepository->save($group);
    }

    public function removeUserFromGroup(string $groupId, string $userId, User $user): void
    {
        $group = $this->getGroupFromId($groupId);

        $this->userCanManageGroup($user, $group);

        $userToRemove = $this->getUserFromId($userId);

        if (!$this->groupRepository->userIsMember($group, $userToRemove)) {
            throw UserNotMemberOfGroupException::create();
        }

        $group->removeUser($userToRemove);

        $this->groupRepository->save($group);
    }

    private function getGroupFromId(string $groupId): Group
    {
        if( null !== $group = $this->groupRepository->findOneById($groupId))
        {
            return $group;
        }

        throw GroupDoesNotExistException::fromGroupId($groupId);
    }

    private function userCanManageGroup(User $user, Group $group): void
    {
        if(!$this->groupRepository->userIsMember($group, $user)){
            throw CannotManageGroupException::create();
        }
    }

    private function getUserFromId(string $userId): User
    {
        if(null !== $user = $this->userRepository->findOneById($userId))
        {
            return $user;
        }
        throw UserDoesNotExistException::fromUserId($userId);
    }

}// GroupService
```
- Prueba de endpoint borrado: **POST** [http://localhost:200/api/v1/groups/remove_user](localhost:200/api/v1/groups/remove_user)
```js
{
	"group_id": "d9640aea-6b2c-4d5e-a4ab-37be525e377f",
	"user_id": "3628ca48-ad5b-4bb1-9bfc-4a7aa95e1998"
}
```
- Se elimina correctamente el usuario siempre y cuando el usuario en sesion este en el grupo
```js
{
	"group_id": "2836395b-55b3-4f4f-94c4-df8403967f56",
	"user_id": "50a51782-4f7f-4534-b319-396d6cdb4d4d"
}
//respuesta:
{
    "class": "App\\Exceptions\\Group\\CannotManageGroupException",
    "code": 400,
    "message": "You cannot manage this group"
}
```
- **tests**
  - Se elimina **`tests/Unit/Api/Action/Group`** ya no vale
  - Se mueve **TestBase** a: `tests/Unit/TestBase.php`
  - Hay que crear un test para el servicio: `tests\Unit\Service\Group\GroupServiceTest.php`
  - Los test se dejan hechos para agregar y borrar. Queda pendiente cubrir los casos de uso 
  ```php
  //GroupService.php
  throw UserNotMemberOfGroupException::create();
  throw UserDoesNotExistException::fromUserId($userId);

  //habría que jugar con estas combinaciones
  $this->groupRepositoryProphecy->findOneById($groupId)->willReturn($group);
  $this->groupRepositoryProphecy->userIsMember($group, $user)->willReturn(true);
  $this->userRepositoryProphecy->findOneById($userId)->willReturn($newUser);
  $this->groupRepositoryProphecy->userIsMember($group, $newUser)->willReturn(true);

  // tests/Unit/Service/Group/GroupServiceTest.php
  class GroupServiceTest extends TestBase
  {
      private GroupService $groupService;

      public function setUp(): void
      {
          parent::setUp(); // TODO: Change the autogenerated stub
          $this->groupService = new GroupService($this->groupRepository, $this->userRepository);
      }

      public function testAddUserToGroup(): void
      {
          $groupId = 'group_id_123';
          $userId = 'user_id_456';

          $user = new User('user', 'user@api.com');
          $newUser = new User('new', 'new.user@api.com');
          $group = new Group('group', $user);

          $this->groupRepositoryProphecy->findOneById($groupId)->willReturn($group);
          $this->groupRepositoryProphecy->userIsMember($group, $user)->willReturn(true);
          $this->userRepositoryProphecy->findOneById($userId)->willReturn($newUser);
          $this->groupRepositoryProphecy->userIsMember($group, $newUser)->willReturn(false);

          $this->groupRepositoryProphecy->save(
              Argument::that(
                  function (Group $group): bool {
                      return true;
                  }
              )
          )->shouldBeCalledOnce();

          $this->groupService->addUserToGroup($groupId, $userId, $user);
      }//testAddUserToGroup

      public function testRemoveUserFromGroup(): void
      {
          $groupId = 'group_id_123';
          $userId = 'user_id_456';

          $user = new User('user', 'user@api.com');
          $newUser = new User('new', 'new.user@api.com');
          $group = new Group('group', $user);

              $this->groupRepositoryProphecy->findOneById($groupId)->willReturn($group);
              $this->groupRepositoryProphecy->userIsMember($group, $user)->willReturn(true);
              $this->userRepositoryProphecy->findOneById($userId)->willReturn($newUser);
              $this->groupRepositoryProphecy->userIsMember($group, $newUser)->willReturn(true);

          $this->groupRepositoryProphecy->save(
              Argument::that(
                  function (Group $group): bool {
                      return true;
                  }
              )
          )->shouldBeCalledOnce();

          $this->groupService->removeUserFromGroup($groupId, $userId, $user);
      }//testRemoveUserFromGroup

  }// GroupServiceTest
  ```

### [17. Tests funcionales para grupo 26 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451626#questions/9295602)
- [`git checkout -b section6/video5-create-functional-tests-for-group`](https://bitbucket.org/juanwilde/sf5-expenses-api/src/89febb12d442eec2476ac3b837b27e9b6613bbe4/tests/Unit/Service/Group/GroupServiceTest.php?at=section6%2Fvideo5-create-functional-tests-for-group)
- Actualización de **fixtures** (fake data):
```php
// src/DataFixtures/AppFixtures.php
public function load(ObjectManager $manager)
{
    $users = $this->getUsers();
    foreach ($users as $userData){
        $user = new User($userData["name"],$userData["email"],$userData["id"]);
        $user->setPassword($this->encoderService->generateEncodedPasswordForUser($user,$userData["password"]));
        $user->setRoles($userData["roles"]);
        $manager->persist($user);

        foreach ($userData["groups"] as $groupData){
            $group = new Group($groupData["name"],$user,$groupData["id"]);
            $group->addUser($user);
            $manager->persist($group);
        }
    }

    $manager->flush();
}

private function getUsers(): array
{
    return [
        [
            "id" => "eeebd294-7737-11ea-bc55-0242ac130001",
            "name" => "Admin",
            "email" => "admin@api.com",
            "password" => "password",
            "roles" => [
                Roles::ROLE_ADMIN,
                Roles::ROLE_USER,
            ],
            "groups" => [
                [
                    "id" => "eeebd294-7737-11ea-bc55-0242ac130003",
                    "name" => "Admin\'s Group"
                ]
            ],
            
        ],
        [
            "id" => "eeebd294-7737-11ea-bc55-0242ac130002",
            "name" => "User",
            "email" => "user@api.com",
            "password" => "password",
            "roles" => [
                Roles::ROLE_USER,
            ],
            "groups" => [
                [
                    "id" => "eeebd294-7737-11ea-bc55-0242ac130004",
                    "name" => "User\'s Group"
                ]
            ],
        ],
    ];
}//getUsers()
```
- Ejecutamos: 
```js
appuser@6cb41baaf32c:/appdata/www$ sf d:f:l -n --env=test
//error:
An exception occurred in driver: SQLSTATE[HY000] [2002] Connection refused
//solución:
Hay que ejecutarlo desde la maquina host y no desde el contendor:
php bin/console d:f:l -n --env=test

//resultado:
> purging database
> loading App\DataFixtures\AppFixtures
```
- ![](https://trello-attachments.s3.amazonaws.com/5b014dcaf4507eacfc1b4540/5e7777d6cd7def249ee578fb/1b9970e8ee29f947e5b0c8a09e8e194e/image.png)
- En la bd de pruebas se insertan correctamente los datos fake.
- **tests/Functional/Api/TestBase.php** 
  - Se configuran los nuevos ids: Grupo Admin y Grupo User
```php
// tests/Functional/Api/Group/GroupTestBase.php
class GroupTestBase extends TestBase
{
    protected string $endpoint;

    public function setUp(): void
    {
        parent::setUp();
        $this->endpoint = "/api/v1/groups";
    }
}

//tests/Functional/Api/Group/GetGroupTest.php
class GetGroupTest extends GroupTestBase
{
    public function testGetGroupsForAdmin():void
    {
        self::$admin->request("GET", \sprintf("%s.%s",$this->endpoint,self::FORMAT));
        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(2, $responseData["hydra:member"]);
    }

    ... se crea resto de metodos test
}

//estos test van ok
```
- Se crea clase de tests para POST: **tests/Functional/Api/Group/PostGroupTest.php**
  - Este test va ok.
- Se crea clase de tests para PUT: **tests/Functional/Api/Group/PutGroupTest.php**
  - Este test va ok.  
- Se crea test DELETE: **tests/Functional/Api/Group/DeleteGroupTest.php**
  - Me da un error ^^
    - `Error : Call to undefined method App\Entity\Group::isOwnerBy()`
  - En **GroupVoter** habia que cambiar a **$subject->isOwnedBy**
  - Test ahora ok
- Queda cubrir los tests que tratan los casos de uso de obtener los grupos para un usuario
- Se crea clase GET: **tests/Functional/Api/User/GetUserGroupsTest.php**
  - Test ok
- Lanzo el test general y me da error:
  ```s
  Testing App\Tests\Functional\Api\User\DeleteUserTest
  Failed asserting that 500 matches expected 204.
  "An exception occurred while executing 'DELETE FROM user WHERE id = ?' with params ["eeebd294-7737-11ea-bc55-0242ac130002"]: 
  SQLSTATE[23000]: Integrity constraint violation: 1451 Cannot delete or update a parent row: a foreign key constraint fails 
  (`sf5-expenses-api_api-test`.`user_group`, CONSTRAINT `FK_8F02BF9D7E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`)
  ```
  - Revisando este error veía que la bd de pruebas cambiaba su estructura al ejecutar el test. Creaba indices extraños y los tipos tampoco eran los originales.
  - **corregido**
  ```yaml
  # Doctrine/Mapping/Entity/User.orm.yml
    manyToMany:
    groups:
      targetEntity: Group
      inversedBy: users
      cascade: [remove]  -> faltaba esta config
  ```
  - ![](https://trello-attachments.s3.amazonaws.com/5e7777d6cd7def249ee578fb/1194x253/543f3ba53600d5037818ccca60512a39/image.png)
  - Este error se resuelve más adelante en el video. Min: 24:39
- Hay que corregir el `parent::setUp()` en **tests/Unit/Api/Action/User/RegisterTest.php**
- Todos los tests **OK**


### Sección 8: Categorías 0 / 2|49 min
### [18. Crear Categorías y migración 15 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451640#questions/9295602)
- Entidad categorias. (tabla array)
- [`git checkout -b section7/video1-create-category`](https://bitbucket.org/juanwilde/sf5-expenses-api/src/05f81192b083f986c5daa0b192a69f031a23d81e/?at=section7%2Fvideo1-create-category)
- 


### [19. Seguridad y tests para categorías 34 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451648#questions/9295602)
- 

### Sección 9: Gastos 0 / 3|1 h 14 min
### [20. Crear entidades para gastos y migración 19 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451664#questions/9295602)
- 
### [21. Configurar seguridad para gastos 21 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451672#questions/9295602)
- 
### [22. Tests funcionales para gastos 33 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451678#questions/9295602)
- 

### Sección 10: Refactoring y bugfix 0 / 1|4 min
### [23. Refactoring y bugfix 4 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451694#questions/9295602)
- 

### Sección 11: Filtros 0 / 2|45 min
### [24. Filtros por defecto de API Platform 29 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451702#questions/9295602)
- 
### [25. Filtros avanzados de API Platform 16 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451712#questions/9295602)
- 
### Sección 12: Personalizar respuestas de la API 0 / 1|9 min
### [26. Personalizar respuestas de la API 9 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451718#questions/9295602)
- 