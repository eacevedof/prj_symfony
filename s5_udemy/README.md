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
- [Rama](https://bitbucket.org/juanwilde/sf5-expenses-api/src/b953a0090139df2d90c8eea1e7f8e11912315136/?at=section2%2Fvideo1-docker-config)
- Para usuarios de linux hay que instalar vbox
- Maq virtual con version 18.04 ubuntu, con git, docker y todos los paquetes relacionados con el curso
- También explicará como hacerlo en Windows
- Por cada sección creará una rama, de modo que se pueda cambiar de rama para consulta del código
  - `git checkout -b section1/vieo1-create-project; git push origin section1/video1-create-project`
  - Vemos que ahora tenemos esta rama en Bitbucket
- `git checkout -b section2/video1-docker-config`
- ![Bitbucket rama: section2/video1-docker-config](https://bitbucket.org/juanwilde/sf5-expenses-api/src/5d07e74988543272786a6fc859836162e79bab3c/?at=section2%2Fvideo1-docker-config)
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

### [4. Configuración de Docker en Linux 8 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451450#questions/9295602)
-
### [5. Configuración de Docker en Windows (Bonus)](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451456#questions/9295602)
- 
### [6. Instalación de librerías adicionales 5 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451528#questions/9295602)
- 
### Sección 4: Instalación de librerías adicionales 1 / 1|5 min
### [6. Instalación de librerías adicionales 5 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17450464#questions)
- 

### Sección 5: Autenticación y registro 0 / 3|1 h 41 min
### [7. Instalar librería para usar JSON Web Tokens 7 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451540#questions/9295602)
- 
### [8. Configurar sistema de autenticación 56 min](https://www.udemy.com/course/crear-api-con-symfony-4-y-api-platform/learn/lecture/17451544#questions/9295602)
- 
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