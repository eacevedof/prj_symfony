# prj_phpconsole
Gestor de consola en Symfony 

## Primeros pasos
- [https://symfony.com/download](https://symfony.com/download)
- [https://symfony.com/doc/current/console.html](https://symfony.com/doc/current/console.html)
- **composer require symfony/console**

## Despues de ejecutar **composer require symfony/console**
```js
$ composer require symfony/console
Using version ^4.1 for symfony/console
./composer.json has been updated
Loading composer repositories with package information
Updating dependencies (including require-dev)
Package operations: 2 installs, 0 updates, 0 removals
  - Installing symfony/polyfill-mbstring (v1.9.0): Loading from cache
  - Installing symfony/console (v4.1.5): Loading from cache
symfony/console suggests installing psr/log-implementation (For using the console logger)
symfony/console suggests installing symfony/event-dispatcher ()
symfony/console suggests installing symfony/lock ()
symfony/console suggests installing symfony/process ()
Writing lock file
Generating autoload files
```

- [https://symfony.com/doc/current/components/console.html](https://symfony.com/doc/current/components/console.html)
    - Creando **console.php**
- [Creando primer cmd](https://symfony.com/doc/current/console.html)
    - Ruta: `src/Command`
- el archivo **bin/console.php** debe llamarse **bin/console** sin el `.php`
- llamando al comando creado: `./bin/console app:command:create-user`

```ssh
$ ./bin/console --help
Description:
  Lists commands

Usage:
  list [options] [--] [<namespace>]

Arguments:
  namespace            The namespace name

Options:
      --raw            To output raw command list
      --format=FORMAT  The output format (txt, xml, json, or md) [default: "txt"]

Help:
    The list command lists all commands: 
        php ./bin/console list
    You can also display the commands for a specific namespace: 
        php ./bin/console list test
    You can also output the information in other formats by using the --format option: 
        php ./bin/console list --format=xml
    It's also possible to get raw list of commands (useful for embedding command runner):
        php ./bin/console list --raw
```

**`php ./bin/console list`**
```ssh
Available commands:
  help                     Displays help for a command
  list                     Lists commands
 app
  app:command:create-user  Creates a new user.
```
**`command lifecycle`**
```
initialize()
interact()
execute()
```

## Notas
- [Ejemplo en java de la clase Servicio](https://naildrivin5.com/blog/2010/03/08/object-oriented-design.html)
- [Como funcionan los service providers en laravel](https://programacionymas.com/blog/service-providers-en-laravel)
