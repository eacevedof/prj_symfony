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
  ````

### [9. Custom endpoint para registrar usuarios 38 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451550#questions/9295602)
- 

### Sección 6: Instalar y configurar API Platform 0 / 3|3 h 4 min
### [10. Instalar y configurar API Platform y recurso para usuarios 1 h 17 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451554#questions/9295602)
- 
### [11. Configurar recurso y seguridad de User y tests funcionales 1 h 9 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451568#questions/9295602)
- 
### [12. Tests unitarios para Register y Validators 38 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451578#questions/9295602)
- 

### Sección 7: Grupos 0 / 5|3 h 20 min
### [13. Entidad para grupos y actualización de la configuración de API Platform 20 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451596#questions/9295602)
- 
### [14. Configurar seguridad para grupos 52 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451602#questions/9295602)
- 
### [15. Crear endpoint para añadir usuarios a un grupo 1 h 8 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451610#questions/9295602)
- 
### [16. Crear endpoint para eliminar usuarios de un grupo 34 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451618#questions/9295602)
- 
### [17. Tests funcionales para grupo 26 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451626#questions/9295602)
- 

### Sección 8: Categorías 0 / 2|49 min
### [18. Crear Categorías y migración 15 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451640#questions/9295602)
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