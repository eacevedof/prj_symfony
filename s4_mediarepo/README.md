# prj_mediarepo
Microframework para gestión de imágenes

## Documentation
> [About proxies - ProxyManager](https://ocramius.github.io/ProxyManager/docs/lazy-loading-value-holder.html) <br/>
> [Repo pattern](https://www.thinktocode.com/2018/03/05/repository-pattern-symfony/)  <br/>
> [Apirest 1 fosrestbundle using patterns](https://www.thinktocode.com/2018/03/26/symfony-4-rest-api-part-1-fosrestbundle/) <br/>
> [Repo Apirest 1](https://github.com/JeffreyVerreckt/Symfony4-REST-API/tree/FinishPart1)

### [Tutorial Symfony API](http://codeforcodes.com/rest-api-using-symfony-4/)
### [The author repo](https://github.com/achreftlili/REST-API-SY4/tree/master/src)

### [127.0.0.1:8000/v1/media](http://127.0.0.1:8000/v1/media)
### [mediarepoapi.theframework.es/v1/media](http://mediarepoapi.theframework.es/v1/media)

###[Api Rest Symfony Parte 2 - Instalación (en español)](https://www.franciscougalde.com/2018/02/19/construir-restful-api-symfony-4-jwt-parte-2/)

**php -S 127.0.0.1:8000 -t public**

- `composer create-project "symfony/skeleton:^4.0"`
```js
  * Run your application:
    1. Go to the project directory
    2. Create your code repository with the git init command
    3. Download the Symfony CLI at https://symfony.com/download to install a development web server

  * Read the documentation at https://symfony.com/doc
```
- `composer require sensio/framework-extra-bundle`
```js
Restricting packages listed in "symfony/symfony" to "4.3.*"
Using version ^5.3 for sensio/framework-extra-bundle
./composer.json has been updated
Restricting packages listed in "symfony/symfony" to "4.3.*"
Loading composer repositories with package information
Updating dependencies (including require-dev)
Nothing to install or update
Generating autoload files
Executing script cache:clear [OK]
Executing script assets:install public [OK]
```

- `composer require friendsofsymfony/rest-bundle` 
```js
$ composer require friendsofsymfony/rest-bundle
Restricting packages listed in "symfony/symfony" to "4.3.*"
Using version ^2.5 for friendsofsymfony/rest-bundle
./composer.json has been updated
Restricting packages listed in "symfony/symfony" to "4.3.*"
Loading composer repositories with package information
Updating dependencies (including require-dev)
Nothing to install or update
Generating autoload files
Executing script cache:clear [OK]
Executing script assets:install public [OK]
```
- `composer require symfony/orm-pack`
```js
Restricting packages listed in "symfony/symfony" to "4.3.*"
Using version ^1.0 for symfony/orm-pack
./composer.json has been updated
Restricting packages listed in "symfony/symfony" to "4.3.*"
Loading composer repositories with package information
Updating dependencies (including require-dev)

Prefetching 16 packages
  - Downloading (100%)

Package operations: 16 installs, 0 updates, 0 removals
  - Installing ocramius/package-versions (1.4.0): Loading from cache
  - Installing symfony/stopwatch (v4.3.1): Loading from cache
  - Installing zendframework/zend-eventmanager (3.2.1): Loading from cache
  - Installing zendframework/zend-code (3.3.1): Loading from cache
  - Installing ocramius/proxy-manager (2.2.2): Loading from cache
  - Installing doctrine/dbal (v2.9.2): Loading from cache
  - Installing doctrine/migrations (v2.1.0): Loading from cache
  - Installing doctrine/common (v2.10.0): Loading from cache
  - Installing symfony/doctrine-bridge (v4.3.1): Loading from cache
  - Installing doctrine/doctrine-cache-bundle (1.3.5): Loading from cache
  - Installing jdorn/sql-formatter (v1.2.17): Loading from cache
  - Installing doctrine/doctrine-bundle (1.11.2): Loading from cache
  - Installing doctrine/doctrine-migrations-bundle (v2.0.0): Loading from cache
  - Installing doctrine/instantiator (1.2.0): Loading from cache
  - Installing doctrine/orm (v2.6.3): Loading from cache
  - Installing symfony/orm-pack (v1.0.6): Loading from cache
Writing lock file
Generating autoload files
Symfony operations: 3 recipes (c027d291d7319379cf5d6d22082826c2)
  - Configuring doctrine/doctrine-cache-bundle (>=1.3.5): From auto-generated recipe
  - Configuring doctrine/doctrine-bundle (>=1.6): From github.com/symfony/recipes:master
  - Configuring doctrine/doctrine-migrations-bundle (>=1.2): From github.com/symfony/recipes:master
ocramius/package-versions:  Generating version class...
ocramius/package-versions: ...done generating version class
Executing script cache:clear [OK]
Executing script assets:install public [OK]

Some files may have been created or updated to configure your new packages.
Please review, edit and commit them: these files are yours.

Database Configuration
  * Modify your DATABASE_URL config in .env
  * Configure the driver (mysql) and
    server_version (5.7) in config/packages/doctrine.yaml
```

#### database configuration
```js
-- <project>.env

###> doctrine/doctrine-bundle ###
# Format described at http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
# For an SQLite database, use: "sqlite:///%kernel.project_dir%/var/data.db"
# Configure your db driver and server_version in config/packages/doctrine.yaml
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
###< doctrine/doctrine-bundle ###
```
- `php bin/console doctrine:mapping:import "App\Entity" annotation --path=src/Entity`
```js
> writing src/Entity/AppMedia.php
> writing src/Entity/AppMediaArray.php
> writing src/Entity/AppMediaTags.php
> writing src/Entity/AppSearch.php
> writing src/Entity/AppTag.php
> writing src/Entity/AppTagArray.php
> writing src/Entity/AppTagArrayLang.php
> writing src/Entity/AppTagLang.php
```
- ` bin/console doctrine:schema:create`
```js
! [CAUTION] This operation should not be executed in a production environment! 
Creating database schema...
[OK] Database schema created successfully!                                     
```

- `composer require symfony/maker-bundle --dev`
    - Permite generar repositorios
```js
# para poder usar el comando anterior necesito maker-bundle
$ composer require symfony/maker-bundle --dev
Restricting packages listed in "symfony/symfony" to "4.3.*"
Using version ^1.11 for symfony/maker-bundle
./composer.json has been updated
Restricting packages listed in "symfony/symfony" to "4.3.*"
Loading composer repositories with package information
Updating dependencies (including require-dev)
Nothing to install or update
Generating autoload files
ocramius/package-versions:  Generating version class...
ocramius/package-versions: ...done generating version class
Executing script cache:clear [OK]
Executing script assets:install public [OK]
```
- `php bin/console make:entity --regenerate`
```js
$ php bin/console make:entity --regenerate
This command will generate any missing methods (e.g. getters & setters) for a class or all classes in a namespace.
To overwrite any existing methods, re-run this command with the --overwrite flag
Enter a class or namespace to regenerate [App\Entity]:

created: src/Repository/AppMediaRepository.php
created: src/Repository/AppTagRepository.php
updated: src/Entity/AppMedia.php
updated: src/Entity/AppMediaArray.php
updated: src/Entity/AppMediaTags.php
updated: src/Entity/AppSearch.php
updated: src/Entity/AppTag.php
updated: src/Entity/AppTagArray.php
updated: src/Entity/AppTagArrayLang.php
updated: src/Entity/AppTagLang.php
```

- Este comando tambien agrega getters y setters a las entidades
- ~~No ha servido. Lo genera, pero lo genera mal~~
- funciona pero hay que añadir **@ORM\Entity(repositoryClass="App\Repository\AppMediaRepository")** en el comentario de la entidad
- ~~`php bin/console make:repositories --regenerate`~~
- ~~`php bin/console orm:generate-repositories`~~
- Estos comandos no existen

27/06/2019 19:55 Despues de haber intentado el refactor sin éxito y antes de usar el proyecto del ejemplo

## Errors
- ControllerDoesNotReturnResponseException
```js
The controller must return a "Symfony\Component\HttpFoundation\Response" object but it returned an object of type FOS\RestBundle\View\View.
```
    - Ya funciona, faltaban estos archivos:
    - fos_rest.yaml (https://www.adcisolutions.com/knowledge/getting-started-rest-api-symfony-4)


