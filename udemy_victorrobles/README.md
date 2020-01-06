## Curso symfony udemy

## Sección 96: Controladores y rutas en Symfony
### [413. Crear controladores](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/11988574#questions)
- Dentro de: `/symsite/bin`
- `php console help`
- `php console list`
	-![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/978x442/3c9e3b7064e95bd9db188850a7f8bcb0/image.png)
- Generar un controlador
	```
	# en symsite
	$ php bin/console make:controller HomeController
	created: src/Controller/HomeController.php
	created: templates/home/index.html.twig
	Success!
	Next: Open your new controller class and add some pages!
	```
	- ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/705x280/4e4cd19fa9028a448184abfe031037b7/image.png)
- Twig es más estricto que Blade no deja usar sintaxis de php
```php
return $this->render('home/index.html.twig', [
    'controller_name' => 'HomeController',
    "hello" => "Hola mundo"
]);
```
```tpl
{% extends 'base.html.twig' %}

{% block title %}Hello HomeController!{% endblock %}

{% block body %}
<style>
    .example-wrapper { margin: 1em auto; max-width: 800px; width: 95%; font: 18px/1.5 sans-serif; }
    .example-wrapper code { background: #F5F5F5; padding: 2px 6px; }
</style>

<div class="example-wrapper">
    <h1>{{ hello }}!</h1>
</div>
{% endblock %}
```
- Las rutas las trabajeremos en **symsite\config\routes.yaml**

### [414. Rutas y acciones 5 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/11988578#questions)
- Vamos a: `symsite\config\routes.yaml`
```yaml
index:
  path: /
  controller: App\Controller\HomeController::index
animales:
  path: /animales
  controller: App\Controller\HomeController::animales
```
```php
public function animales()
{
    $vars = [
        "title"=>"Bienvenido a la página de animáles"
    ];
    return $this->render('home/animales.html.twig',$vars);
}
```
```tpl
<div class="example-wrapper">
    <h1>{{ title }}!</h1>
</div>
```

### [415. Parámetros opcionales 4 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/11988588#questions)
- Parametros opcionales en ruta con valor por defecto
```yml
animales:
  path: /animales/{nombre}
  controller: App\Controller\HomeController::animales
  defaults: {nombre: "Sin Nombre"}
```
```php
public function animales($nombre)
{
    $vars = [
        "title"=>"Bienvenido a la página de animáles",
        "nombre"=>$nombre
```
```tpl
<div class="example-wrapper">
    <h1>{{ title }}</h1>
    <h2>Tu nombre es: {{ nombre }}</h2>
</div>
```

### [416. Rutas avanzadas 5 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/11990012#questions)
- Aplicando expresiones regulares a las rutas
- Definiendo metodos http 
```yml
animales:
  path: /animales/{nombre}/{apellidos}
  controller: App\Controller\HomeController::animales
  defaults: {nombre: "Sin Nombre", apellidos: "Sin apellidos"}
  methods: [POST,GET]
  requirements:
    nombre: "[A-Z,a-z]+"  # solo letras
    apellidos: "[0-9]+"   # solo numeros
```
```php
public function animales($nombre,$apellidos)
{
  $vars = [
      "title"=>"Bienvenido a la página de animáles",
      "nombre"=>$nombre,
      "apellidos"=>$apellidos
  ];
  return $this->render('home/animales.html.twig',$vars);
}
```
```tpl
<div class="example-wrapper">
    <h1>{{ title }}</h1>
    <h2>Tu nombre es: {{ nombre }}</h2>
    <h2>Tus apellidos: {{ apellidos }}</h2>
</div>
```
### [417. Redirecciones](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/11990082#questions)
- Varios tipos de redirecciones
- Se puede hacer una redirección con paso de variables
```yml
...
  requirements:
    nombre: "[A-Z,a-z ]+"     # se puede incluir un espacio
    apellidos: "[A-Z,a-z ]+"  # lo interpreta como un caracter más
redirigir:
  path: /redirigir
  controller: App\Controller\HomeController::redirigir    
```
```php
public function redirigir()
{
  return $this->redirectToRoute("index");

  //esto para seo puede ser conveniente para evitar su indexación
  return $this->redirectToRoute("index",[],301);

  //cuidado con la cache
  //va a: http://localhost:1000/animales/Juan%20Pedro/Lopez
  return $this->redirectToRoute("animales",["nombre"=>"Juan Pedro","apellidos"=>"Lopez"]);

  return $this->redirect("http://eduardoaf.com");
}
```

## Sección 97: Vistas, plantillas y Twig
### [418. Introducción a twig 2 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/11990086#questions)
- Incluye sistema de herencia y bloques
- Veremos estructuras de control dentro de Twig

### [419. Plantillas y bloques 11 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/11990134#questions)
- creamos una nueva carpeta dentro de templates (symsite\templates) llamada **layouts**
```php
<!--master.html.twig-->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title> {% block blktitulo %} Inicio {% endblock %} - Master en PHP de VR</title>
  </head>
  <body>
    <div id="header">
      {% block blkcabecera %}
        <h1>Cabecera de la plantilla</h1>
      {% endblock %}
    </div>
    <section id="content">
      {% block blkcontenido %}
        <p>Contenido por defcto</p>
      {% endblock %}
    </section>
    <footer>
      eduardoaf.com
    </footer>
  </body>
</html>

<!--animales.html.twig -->
{% extends "layouts/master.html.twig" %}

//remplaza el titulo
{% block blktitulo %} Animales {% endblock %}

//remplaza cabecera
{% block blkcabecera %}
  {{parent()}} //hereda y agrega a lo que ya tiene el padre
  <h1>Animales</h1>
{% endblock %}

//remplaza contenido
{% block blkcontenido %}
  <div class="example-wrapper">
    <h1>{{ title }}</h1>
    <h2>Tu nombre es: {{ nombre }}</h2>
    <h2>Tus apellidos: {{ apellidos }}</h2>
  </div>
{% endblock %}
```
- Los bloques son trozos dinámicos de contenido que pueden ir cambiando si la "tpl" del controlador lo rescribe
```s
A template that extends another one cannot include content outside Twig blocks.
Did you forget to put the content inside a {% block %} tag?
```
- El error pasa porque he definido bloques en el el master layout que no estoy rescribiendo en animales

### [420. Comentarios y variables 3 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/11990184#questions)
- comentarios: {##}
- definir variables: `{% set <mivariable> = <valor> %}`
```php
{% block blkcontenido %}
  {# 
  <!--animales.html.twig blkcontenido-->
  <div class="example-wrapper">
    <h1>{{ title }}</h1>
    <h2>Tu nombre es: {{ nombre }}</h2>
    <h2>Tus apellidos: {{ apellidos }}</h2>
  </div> 
  #}
  {# crear variable #}
  {% set perro = "pastor aleman" %}
  <h4>{{ perro }}</h4>
{% endblock %}
```
### [421. Definir y mostrar arrays 6 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/11990190#questions)
- hacer prints de variables 
- depuracion de variables 
- impresion de arrays por indice
```php
$animales = ["perro","gato","paloma","rata"];
$aves = [
    "tipo" => "palomo",
    "color" => "gris",
    "edad" => 4,
    "raza" => "colillano"
];
```
```php
{# trabjar con arrays #}
  
{{ dump(animales) }}

{{ animales[0] }}

{{ aves.tipo ~ " - " ~ aves.raza}}
```
### [422. Estructuras de control en Twig 6 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/11990200#questions)
- bucles
- filtros
- condicionales
- longitud
```php
 {# condicional #}
  {% if aves.tipo == "palomo" %}
    <h1>Cuidado un palomo volador</h1>
  {% else %}
    <h2>No hay palomos a la vista</h2>
  {% endif %}
  
  {# bucle con filtro #}
  {% if animales|length >= 0  %}
    <ul>
      {% for animal in animales %}
        <li>{{animal}}</li>
      {% endfor %}
    </ul>
  {% endif %}
  
  //{% for i in 0..10 %} 
  {% for i in 0..animales|length %}
    {{i}}<br/>
  {% endfor %}

{% endblock %}
```
### [423. Starts Ends 2 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/11990202#questions)
- Comprobaciones de cadenas
- condicionales con: Empieza por o acaba en
```php
{% if aves.color starts with "g" %}
  <h1>Empieza por G</h1>
{% endif %}

{% if aves.color ends with "su" %}
  <h1>Termina en su</h1>
{% endif %}
```
### [424. Funciones predefinidas Twig 5 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12041194#questions)
- [twig.symfony.com/doc/3.x](https://twig.symfony.com/doc/3.x/)
- obtener el minimo
- obtener el maximo
- item aleatorio
- rango de n en n, rango con saltos, imprimir con saltos entre numeros
```php
<h1>Funciones</h1>
{# funciones predefinidas #}
{{ min([9,11,6,99,2]) }}
{{ max([9,11,6,99,2]) }}
{{ random(animales) }}

{% for i in range(0,100,5) %}
  {{i}} <br/>
{% endfor %}
```
### [425. Includes 4 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12047378#questions)
- Supongamos que deseamos separar todo lo que hay en animales.html.twig
- Sirve para incluir archivos e incluso pasarle valores
```php
//fichero: partials/funciones.twig
<h1>Funciones</h1>
<h3> del include: {{nombre}}</h3>
{# funciones predefinidas #}
{{ min([9,11,6,99,2]) }}
{{ max([9,11,6,99,2]) }}
{{ random(animales) }}

{% for i in range(0,100,5) %}
  {{i}} <br/>
{% endfor %}

//fichero: animales.html.twig
  {% if aves.color ends with "su" %}
    <h1>Termina en su</h1>
  {% endif %}

  <hr/>
  //aqui se hace el include pasando a funciones.twig el nombre
  {{ include("partials/funciones.twig", {"nombre":"Eduardo A.F."}) }}
{% endblock %}
```
### [426. Filtros por defecto 3 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12047380#questions)
- Son tuberias que nos permiten modificar una variable
- Aplicar filtros en cadena
- Concatenar filtros con tuberia
- Multiples filtros para limpiar texto, trim, upper, lower
```php
//funciones.twig
<h1>Filtros</h1>
{{ animales|length }}

{% set email = "  email@email.com    " %}

{{ dump(email|trim|upper|lower) }}

{{ email }}
```
### [427. Crear extensiones 10 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12052868#questions)
- Usamos **bin/console** para crear una extensión de Twig
- `php bin/console make:twig-extension Mifiltro`
```php
//Twig/MifiltroExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MifiltroExtension extends AbstractExtension
{
  public function getFilters(): array
  {
    return [
      // If your filter generates SAFE HTML, you should add a third
      // parameter: ['is_safe' => ['html']]
      // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
      new TwigFilter('multiplicar', [$this, 'multiplicar']),
    ];
  }

  public function getFunctions(): array
  {
    return [
      new TwigFunction('multiplicar', [$this, 'multiplicar']),
    ];
  }

  public function multiplicar($numero)
  {
    $tabla = "<h1>Tabla del $numero</h1>";
    for($i=0; $i<=10; $i++){
      $tabla .= "$i x $numero = ".($i * $numero)."<br/>";
    }
    return $tabla;
  }

}//MifiltroExtension

//services.yaml
App\Twig\:
    resource: '../src/Twig'
    tags: ['twig.extension']

//funciones.twig
<h1>Extensiones Personalizadas</h1>
{{ multiplicar(2)|raw }}  //imprime la tabla del 2 (funcion)

{{ 12|multiplicar|raw}}   //imprime la tabla del 12 (filtro)
```
### [428. Listar rutas 2 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12052870#questions)
```s
$ php bin/console debug:router
-------------------------- ---------- -------- ------ -----------------------------------
Name                       Method     Scheme   Host   Path
-------------------------- ---------- -------- ------ -----------------------------------
_preview_error             ANY        ANY      ANY    /_error/{code}.{_format}
_wdt                       ANY        ANY      ANY    /_wdt/{token}
//el profiler es el debugger
_profiler_home             ANY        ANY      ANY    /_profiler/
_profiler_search           ANY        ANY      ANY    /_profiler/search
_profiler_search_bar       ANY        ANY      ANY    /_profiler/search_bar
_profiler_phpinfo          ANY        ANY      ANY    /_profiler/phpinfo
_profiler_search_results   ANY        ANY      ANY    /_profiler/{token}/search/results
_profiler_open_file        ANY        ANY      ANY    /_profiler/open
_profiler                  ANY        ANY      ANY    /_profiler/{token}
_profiler_router           ANY        ANY      ANY    /_profiler/{token}/router
_profiler_exception        ANY        ANY      ANY    /_profiler/{token}/exception
_profiler_exception_css    ANY        ANY      ANY    /_profiler/{token}/exception.css
home                       ANY        ANY      ANY    /home
index                      ANY        ANY      ANY    /inicio
animales                   POST|GET   ANY      ANY    /animales/{nombre}/{apellidos}
redirigir                  ANY        ANY      ANY    /redirigir
-------------------------- ---------- -------- ------ -----------------------------------
```

## Sección 98: Bases de datos y Doctrine 1 / 18|1 h 27 min

### [429. Conexión a la base de datos en Symfony 3 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12052872#questions)
- Archivo **.env**
- Configurar base de datos en: **symsite/.env**
```js
//se le puede configurar el entorno
APP_ENV=dev //o prod
APP_SECRET=721e4e44d545fb60603f1aa4f8e8d070

//configurar la bd
DATABASE_URL=mysql://root:db_password@172.30.0.2:3306/db_symf?serverVersion=5.7
//funciona tambien sin serverVersion con doctrine, queda finalmente así:
DATABASE_URL=mysql://root:1234@172.30.0.2:3306/db_symf2?serverVersion=mariadb-10.4.11
```
- Podemos crear la bd "db_symf" manualmente en el motor o tambien existe un comando que nos creara la bd.
  - Y como no, dará un error ¬¬!
  ```s
  $ php bin/console doctrine:database:create
  In AbstractMySQLDriver.php line 106:
    An exception occurred in driver: could not find driver
  In PDOConnection.php line 31:
    could not find driver
  In PDOConnection.php line 27:
    could not find driver
  doctrine:database:create [--shard SHARD] [--connection [CONNECTION]] [--if-not-exists] [-h|--help] [-q|--quiet] [-v|vv|vvv|--verbose] [-V|--version] [--ansi] [--no-ansi] [-n|--no-interaction] [-e|--env ENV] [--no-debug] [--] <command>
  ```
  - Version mariadb:
  ```s
  root@hmari01:/# mariadb -V
  mariadb  Ver 15.1 Distrib 10.4.11-MariaDB, for debian-linux-gnu (x86_64) using readline 5.2
  ```
  - >[If you are running a MariaDB database, you must prefix the server_version value with mariadb- (e.g. server_version: mariadb-10.2.12).](https://symfony.com/doc/current/reference/configuration/doctrine.html)
  ```php
  //con esto conecta
  function test_mysql()
  {
      //$host = "127.0.0.1";
      $host = "cmari01"; //nombre del contenedor (docker ps -> NAMES)
      $host = "172.30.0.2";
      $db   = "mysql";
      $user = "root";
      $pass = "1234";
      $charset = "utf8";
      $options = [
          \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
          \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
          \PDO::ATTR_EMULATE_PREPARES   => false,
      ];
      $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
      echo "<pre>";
      echo "$dsn\n";
      try 
      {
          $pdo = new \PDO($dsn, $user, $pass, $options);
          $sql = "SELECT * FROM help_topic WHERE 1 LIMIT 2";
          echo "<b>$sql</b>\n";
          $stmt = $pdo->query($sql);
          while ($row = $stmt->fetch()) 
          {
              print_r($row);
              die;
          }
          echo "</pre>";
      } 
      catch (\PDOException $e) 
      {
          throw new \PDOException($e->getMessage(), (int)$e->getCode());
      }
  }//test_mysql()
  ```
  - Esta entrando por aqui: `symsite\vendor\doctrine\dbal\lib\Doctrine\DBAL\Driver\PDOMySql\Driver.php`
  - El problema era que estaba ejecutando el comando desde la consola de windows y claro, ese php no tiene acceso al contenedor.
  - La solución pasa por lanzar ese comando en el contenedor de php, así:
    - ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/645x114/7b66bf254be1545b99e48012dfcd5d9d/image.png)

### [430. Generar entidades desde la base de datos 8 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12063202#questions)
- Pasar de tabla a entidad
  - La ventaja de tener los datos en yml es que se separa la definición (meta datos) de la clase 
  - **comando:** `php bin/console doctrine:mapping:convert --from-database yml ./src/Entity`
  - ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/887x94/b1326390b1ae6a0d208b361657bd2b46/image.png)
  ```yml
  # symsite\src\Entity\Animales.orm.yml
  Animales:
    type: entity
    table: animales
    id:
      id:
        type: integer
        nullable: false
        options:
            unsigned: false
        id: true
        generator:
            strategy: IDENTITY
    fields:
      tipo:
        type: string
        nullable: true
        length: 255
        options:
            fixed: false
      color:
        type: string
        ...
      raza:
        type: string
        ...
    lifecycleCallbacks: {  }
  ```
  - **comando:** `php bin/console doctrine:mapping:import App\\Entity yml --path=src/Entity`
  - Al yml anterior le agrega esta linea al principio: `App\Entity\Animales:`
  - ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/886x75/7959ab0bdb6477fdc261d947ea7ca75b/image.png)
  - **comando:** `php bin/console doctrine:mapping:import App\\Entity annotation --path=src/Entity`
  - ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/933x79/2a46140921deb6dd0ffc648dcde6fa0c/image.png)
  - Crea la clase entidad sin getters ni setters
  ```php
  namespace App\Entity;
  use Doctrine\ORM\Mapping as ORM;

  /**
  * Animales
  * @ORM\Table(name="animales")
  * @ORM\Entity
  */
  class Animales
  {
    /**
    * @var int
    * @ORM\Column(name="id", type="integer", nullable=false)
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="IDENTITY")
    */
    private $id;

    /**
    * @var string|null
    * @ORM\Column(name="tipo", type="string", length=255, nullable=true)
    */
    private $tipo;

    /**
    * @var string|null
    * @ORM\Column(name="color", type="string", length=255, nullable=true)
    */
    private $color;

    /**
    * @var string|null
    * @ORM\Column(name="raza", type="string", length=255, nullable=true)
    */
    private $raza;
  }
  ```
  - **comando:** `php bin/console make:entity --regenerate App`
  - ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/635x143/86e254441da45acd9d9c3d989574d00d/image.png)
  - Agrega getters y setters a la clase Animales
  ```php
  //fichero:symsite\src\Entity\Animales.php
  ...
    /**
    * @var string|null
    *
    * @ORM\Column(name="raza", type="string", length=255, nullable=true)
    */
    private $raza;

    public function getId(): ?int
    {
      return $this->id;
    }

    public function getTipo(): ?string
    {
      return $this->tipo;
    }

    public function setTipo(?string $tipo): self
    {
      $this->tipo = $tipo;
      return $this;
    }

    public function getColor(): ?string
    {
      return $this->color;
    }

    public function setColor(?string $color): self
    {
      $this->color = $color;
      return $this;
    }

    public function getRaza(): ?string
    {
      return $this->raza;
    }

    public function setRaza(?string $raza): self
    {
      $this->raza = $raza;
      return $this;
    }
  }
  ```
  - En este punto ya tenemos la entidad configurada pero esta en formato anotación por legibilidad es mejor llevar las anotaciones a YML
  - Quitamos las anotaciones, más adelante las usaremos por practicidad
  - Vamos a generar la metadata (el YML)
  - **comando** `php bin/console doctrine:mapping:import App\\Entity yml --path=src/Entity`
  - ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/882x58/9394c9f3e182cde46677ed89924528f6/image.png)
- Cambiamos en nombre, el plural a singular ya que una entidad se refiere a un único registro.
### [431. Generar entidades con Symfony 4 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12063204#questions)
- Generar la entidad "Usuario" de forma común. (sin tabla)
- **comando** `php bin/console make:entity Usuario`
  - Crea la entidad y un repositorio en *sr/Repository*, esta es una clase donde podemos guardar las distintas consultas
- ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/637x163/50823c1782f58724aa298c3490d077aa/image.png)
```php
namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Usuario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usuario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Usuario[]    findAll()
 * @method Usuario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Usuario::class);
  }

  // /**
  //  * @return Usuario[] Returns an array of Usuario objects
  //  */
  /*
  public function findByExampleField($value)
  {
    return $this->createQueryBuilder('u')
      ->andWhere('u.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('u.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
    ;
  }
  */

  /*
  public function findOneBySomeField($value): ?Usuario
  {
    return $this->createQueryBuilder('u')
      ->andWhere('u.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
    ;
  }
  */
}//class UsuarioRepository
```
- En la entidad Usuario.php vamos a trabajar con las anotaciones
- `@ORM\GeneratedValue()` indica que es incremental
- Vamos a crear nuevos campos, usamos anotaciones ya que al parecer estan dejando de lado los YML con estas
- Despues de configurar los campos hay que regenerar todo
- **comando** `php bin/console make:entity --regenerate`
- ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/924x216/796fa6415245d21257f510c1c01b68be/image.png)
- Ha creado el resto de anotaciones, los getters y setters
- ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/581x384/2bebcce564674838f88cf6bc2a9c9f46/image.png)

### [432. Generar tablas desde entidades 4 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12063206#questions)
- Vamos a crear las tabla a partir de la entidad Usuario
- **comando** `php bin/console doctrine:migrations:diff`
- ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/812x111/2ecfd5d16dfcd386ce90be0b49c8b706/image.png)
- Vera las diferencias entre la entidad y la tabla si hay algo extra en la entidad se creará
- Ha creado el fichero: **Version20191231193326**
  - ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/257x213/09ad2862d0006c524e36184eff75afdd/image.png)
  - ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/1028x480/f57eb5c0d9fb1e9735c5a96a70746a1d/image.png)
- Según la opinion de Victor es más sencillo crear toda la bd y despues pasarla a entidades
- Con los ficheros de migración creados hay que lanzar la migración para que se escriba en la bd
- **comando** `php bin/console doctrine:migrations:migrate`
  - ![](https://trello-attachments.s3.amazonaws.com/5b014dcaf4507eacfc1b4540/5e08af454987ac63c8dd78d7/f1139c7d409dc4185f86989ba8833ec7/image.png)
  - ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/599x360/4dac7600aaed5f60a49526a0ac07e1a4/image.png)
  - Ha creado la tabla pero ha eliminado **animales** ^^
  - Al no tener ninguna migración de **animales** no la ha creado pq siempre borra toda la bd.
  - Quitamos los restos de animales.

### [433. Hacer cambios en entidades 7 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12080892#questions)
- **comando** `php bin/console doctrine:mapping:import App\\Entity annotation --path=src/Entity`
  - ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/945x112/2b72ef98452e0fecd8c4cb2b606bff8e/image.png)
  - ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/969x204/844b31a92314b1f95bd53393c1b16bb2/image.png)
- Regeneramos los metodos que puedan faltar
  - **comando** `php bin/console make:entity --regenerate App`
    - ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/650x172/ec020d60ea3991b3ef91365d7d026edd/image.png)
- Si quisieramos regenerar una entidad en concreto lo hariamos así:
  - **comando** `php bin/console make:entity --regenerate App\\Entity\\<MiEntidad>`
- Volvemos a cambiar de Animales a Animal
- Vamos a replicar cambios de la entidad a la bd. Modificamos la config de campos de Animal 
  - `php bin/console doctrine:migrations:diff` Generamos el archivo **Migrations/Version<>.php**
  - `php bin/console doctrine:migrations:migrate` Replicamos los cambios en la bd

### [434. Guardar en la base de datos 10 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12083792#questions)
- Creamos un nuevo contolador
```s
root@hphp01:/var/www/html/symsite# php bin/console make:controller AnimalController
created: src/Controller/AnimalController.php
created: templates/animal/index.html.twig
Next: Open your new controller class and add some pages!
Success!
```
```php
class AnimalController extends AbstractController
{
  /**
  * @Route("/animal", name="animal")
  */
  public function index()
  {
    return $this->render('animal/index.html.twig', [
      'controller_name' => 'AnimalController',
    ]);
  }
}

//symsite\templates\animal\index.html.twig
{% extends 'base.html.twig' %}

{% block title %}Hello AnimalController!{% endblock %}

{% block body %}
<style>
    .example-wrapper { margin: 1em auto; max-width: 800px; width: 95%; font: 18px/1.5 sans-serif; }
    .example-wrapper code { background: #F5F5F5; padding: 2px 6px; }
</style>

<div class="example-wrapper">
    <h1>Hello {{ controller_name }}! ✅</h1>

    This friendly message is coming from:
    <ul>
        <li>Your controller at 
          <code><a href="{{ '/var/www/html/symsite/src/Controller/AnimalController.php'|file_link(0) }}">src/Controller/AnimalController.php</a>
          </code>
        </li>
        <li>Your template at 
          <code><a href="{{ '/var/www/html/symsite/templates/animal/index.html.twig'|file_link(0) }}">templates/animal/index.html.twig</a>
          </code>
        </li>
    </ul>
</div>
{% endblock %}
```
- Creamos la ruta en **routes.yml**
```yml
animal_index:
  path: /animal/index
  controller: App\Controller\AnimalController::index  
```
- ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/783x274/c7117f30b7af0b018f4c92d647acdcce/image.png)
- importamos la clase response `use Symfony\Component\HttpFoundation\Response;`
- Doctrine tiene una memoria temporal que actua como un pool de datos que se van almacenando ahi para que despues se guarden en una sola ejecución
- Insertar un registro en la bd
```php
public function save()
{
  //cargo el entity manager
  $em = $this->getDoctrine()->getManager();
  //creo el objeto y le doy valores
  $animal = new Animal();
  $animal->setTipo("Avestruz");
  $animal->setColor("verde");
  $animal->setRaza("africana");
  //persist indica la accion futura que se ejecutará con flush
  $em->persist($animal);
  //volcar datos en la tabla
  $em->flush();
  
  return new Response("El animal guardado tiene el id: ".$animal->getId());
}
```

### [435. Comando SQL 1 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12083794#questions)
- Para comprobar si se hemos añadido los regs a la bd por consola podriamos comprobarlo
- **comando** `php bin/console doctrine:query:sql "SELECT * FROM animales"`
- ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/766x263/4b71c670ccda19e0c9aac653912584e8/image.png)

### [436. Find 6 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12084102#questions)
- Vamos a obtener un registro de la bd
```php
//routes.yml
animal_detail:
  path: /animal/{id}
  controller: App\Controller\AnimalController::animal

//http://localhost:1000/animal/2
public function animal($id)
{
  //cargar repositorio
  $repanimal = $this->getDoctrine()->getRepository(Animal::class);
  //consulta
  $animal = $repanimal->find($id);
  
  if(!$animal)
  {
    $message = "El animal no existe";
  }
  else
  {
    $message = "Tu animal elegido es: {$animal->getTipo()} - {$animal->getRaza()}";
  }
  return new Response($message);
}//animal(id)
```
### [437. Find all 5 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12086624#questions)
```php
//symsite\src\Controller\AnimalController.php
public function index()
{
  $repanimal = $this->getDoctrine()->getRepository(Animal::class);
  $animales = $repanimal->findAll();
  
  return $this->render('animal/index.html.twig', [
      'controller_name' => 'AnimalController',
      "animales" => $animales
  ]);
}

//symsite\templates\animal\index.html.twig
<div class="example-wrapper">
  <h1>Hello {{ controller_name }}! ✅</h1>
  {# {{dump(animales)}} #}
  <ul>
  {% for animal in animales %}
    <li>
      <ul>
        <li>{{animal.id}}</li>
        <li>{{animal.tipo}}</li>
        <li>{{animal.color}}</li>
        <li>{{animal.raza}}</li>
      </ul>
    </li>
  {% endfor %}
  </ul>
</div>
```
```
array(5) { 
[0]=> object(App\Entity\Animal)#770 (4) 
{ 
  ["id":"App\Entity\Animal":private]=> int(1) 
  ["tipo":"App\Entity\Animal":private]=> string(8) "Avestruz" 
  ["color":"App\Entity\Animal":private]=> string(5) "verde" 
  ["raza":"App\Entity\Animal":private]=> string(8) "africana" 
} 
[1]=> object(App\Entity\Animal)#772 (4) 
{ 
  ["id":"App\Entity\Animal":private]=> int(2) 
  ["tipo":"App\Entity\Animal":private]=> string(8) "Avestruz" 
  ["color":"App\Entity\Animal":private]=> string(5) "verde" 
  ["raza":"App\Entity\Animal":private]=> string(8) "africana" 
}
... 
}
```
### [438. Tipos de Find 4 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12086630#questions)
```php
public function index()
{
  $repanimal = $this->getDoctrine()->getRepository(Animal::class);
  $animales = $repanimal->findAll();
  $animal = $repanimal->findOneBy(
      ["raza"=>"africana"], //where or and
      ["id"=>"ASC"] //order by 
  );
  dump($animal);die;

  return $this->render('animal/index.html.twig', [
  'controller_name' => 'AnimalController',
  "animales" => $animales
  ]);
}
```

### [439. Conseguir objeto automático 1 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12086638#questions)
- Atajo con inyección de dependencias para el **find($id)**
- Hay que usar el "type hinting"
```php
animal_detail:
  path: /animal/{id}
  controller: App\Controller\AnimalController::animal  

public function animal(Animal $animal)
{
  /*
  //cargar repositorio
  $repanimal = $this->getDoctrine()->getRepository(Animal::class);
  //consulta
  $animal = $repanimal->find($id);
  */
  if(!$animal)
  {
    $message = "El animal no existe";
  }
  else
  {
    $message = "Tu animal elegido es: {$animal->getTipo()} - {$animal->getRaza()}";
  }
  return new Response($message);
}//animal(id)
```

### [440. Actualizar registros 8 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12086644#questions)
```php
//yaml
animal_update:
  path: /animal/update/{id}
  controller: App\Controller\AnimalController::update

//http://localhost:1000/animal/update/3
public function update($id)
{
  //cargar doctrine
  $doctrine = $this->getDoctrine();
  //cargar em
  $em = $doctrine->getManager();
  //cargar repo animal
  $repanimal = $em->getRepository(Animal::class);
  //find para conseguir objeto
  $animal = $repanimal->find($id);
  //comprobar si el objeto me llega
  if(!$id)
  {
    $message = "El animal no existe en la bbdd";
  }
  else
  {
    //asignarle los valores al objeto
    $animal->setTipo("Perro $id");
    $animal->setColor("rojo");
  }

  //persistir en doctrine
  $em->persist($animal);
  //hacer el flush
  $em->flush();

  $message = "El animal {$animal->getId()} se ha actualizado";
  //respuesta
  return new Response($message);
}
```

### [441. Borrar elementos de la base de datos 4 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12086650#questions)
```php
//routes.yml
animal_delete:
  path: /animal/delete/{id}
  controller: App\Controller\AnimalController::delete  

//http://localhost:1000/animal/delete/5
public function delete(Animal $animal)
{
  if($animal && is_object($animal))
  {
    $em = $this->getDoctrine()->getManager();
    $em->remove($animal);
    $em->flush();
    $message="Animal borrado correctamente {$animal->getId()}";
  }
  else
  {
    $message="Animal no encontrado";
  }
  return new Response($message);
}//delete
```
### [442. Query Builder 5 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12088422#questions)
- Nos permite hacer consultas complejas basandonos en el repositorio
```php
public function index()
{
  $repanimal = $this->getDoctrine()->getRepository(Animal::class);
  $animales = $repanimal->findAll();
  $animal = $repanimal->findOneBy(
        ["raza"=>"africana"], //where or and
        ["id"=>"ASC"] //order by 
    );
  //dump($animal);die;
  $qb = $repanimal->createQueryBuilder("a")
        //->andWhere("a.raza = 'africana' ")
        ->andWhere("a.raza = :raza")
        ->setParameter("raza","africana")
        ->orderBy("a.id","DESC")
        ->getQuery();
  $result = $qb->execute();
  var_dump($result);

  return $this->render('animal/index.html.twig', [
    'controller_name' => 'AnimalController',
    "animales" => $animales
  ]);
}//index
```
### [443. DQL 3 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12088424#questions)
- Doctrine Query Language
```php
$em = $this->getDoctrine()->getManager();
$dql = "SELECT a FROM App\Entity\Animal a WHERE a.raza='africana' ORDER BY a.id DESC";
$query = $em->createQuery($dql);
$result= $query->execute();
var_dump($result);
```
```
array(4) { 
  [0]=> object(App\Entity\Animal)#775 (4) { 
    ["id":"App\Entity\Animal":private]=> int(7) 
    ["tipo":"App\Entity\Animal":private]=> string(8) "Avestruz" 
    ["color":"App\Entity\Animal":private]=> string(5) "verde" 
    ["raza":"App\Entity\Animal":private]=> string(8) "africana" 
  } 
  [1]=> object(App\Entity\Animal)#773 (4) { 
    ["id":"App\Entity\Animal":private]=> int(3) 
    ["tipo":"App\Entity\Animal":private]=> string(7) "Perro 3" 
    ["color":"App\Entity\Animal":private]=> string(4) "rojo" 
    ["raza":"App\Entity\Animal":private]=> string(8) "africana" 
  } 
  ...
}
```
### [444. SQL en Symfony 4 y Symfony 5 4 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12088426#questions)
```php
//symsite\src\Controller\AnimalController.php
public function index()
{
  ...
  //sql
  $conx = $this->getDoctrine()->getConnection();
  $sql = "SELECT * FROM animales ORDER BY id DESC";
  $objprepare = $conx->prepare($sql);
  $result = $objprepare->execute();
  var_dump($result);//boolean true | false
  echo "<br/>";
  $result = $objprepare->fetchAll();
  var_dump($result);
  
  return $this->render('animal/index.html.twig', [
      'controller_name' => 'AnimalController',
      "animales" => $animales
  ]);
}//index
```
```s
#$result = $objprepare->execute();
bool(true)

#$result = $objprepare->fetchAll();
array(5) { 
  [0]=> array(4) { 
    ["id"]=> string(1) "7" 
    ["tipo"]=> string(8) "Avestruz" 
    ["color"]=> string(5) "verde" 
    ["raza"]=> string(8) "africana" 
  } 
  [1]=> array(4) { 
    ["id"]=> string(1) "4" 
    ["tipo"]=> string(4) "Vaca" 
    ["color"]=> string(7) "purpura" 
    ["raza"]=> string(6) "danesa" 
  } 
  [2]=> array(4) { 
    ["id"]=> string(1) "3" 
    ["tipo"]=> string(7) "Perro 3" 
    ["color"]=> string(4) "rojo" 
    ["raza"]=> string(8) "africana" 
  } 
}
```
### [445. Crear repositorios 7 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12088428#questions)
- Si deseamos hacer cualquier tipo de consulta muy compleja y muy larga
- Lo ideal es moverla del controlador y llevarla al repositorio
- El repositorio forma parte del modelo
- El modelo esta formado por las entidades y el repositorio
- Hay una diferencia entre symf 4 y symf 5
- **symfony 4**
  - ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/1179x596/b88af61cc489cac90b7e59fdcaf6b6f3/image.png)
- **symfony 5**
```php
//symfony 5
//symsite\src\Repository\UsuarioRepository.php
namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry; //v4 Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Usuario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usuario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Usuario[]    findAll()
 * @method Usuario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioRepository extends ServiceEntityRepository
{
  //v4 public function __construct(RegistryInterface $registry)
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Usuario::class);
  }
```
- Creo el repositorio Animal
```php
//symsite\src\Repository\AnimalRepository.php
class AnimalRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Animal::class);
  }

  public function findByRaza($raza)
  {
    $qb = $this->createQueryBuilder("a")
            ->andWhere("a.raza",$raza)
            ->orderBy("a.id","DESC")
            ->getQuery();
    $result = $qb->execute();
    return $result;        
  }

//error: BadMethodCallException in vendor/doctrine/orm/lib/Doctrine/ORM/EntityRepository.php
//      public function findAllAnimals(){
//        $qb = $this->createQueryBuilder("a")
//                ->orderBy("a.id","DESC")
//                ->getQuery();
//        $result = $qb->execute();
//        return $result;
//    }
    
}//class AnimalRepository

//\symsite\src\Controller\AnimalController.php
...
  //no va!
  //$repo= new AnimalRepository(Animal::class);
  //error: BadMethodCallException in vendor/doctrine/orm/lib/Doctrine/ORM/EntityRepository.php 
  //(line 235)
  //$result = $repanimal->findAllAnimals(); 
  
  $result = $repanimal->findByRaza("danesa");
  var_dump($result);
  
  return $this->render('animal/index.html.twig', [
    'controller_name' => 'AnimalController',
    "animales" => $animales
  ]);
}//index
```
```s
array(1) { 
  [0]=> object(App\Entity\Animal)
  #5386 (4) { 
    ["id":"App\Entity\Animal":private]=> int(4) 
    ["tipo":"App\Entity\Animal":private]=> string(4) "Vaca" 
    ["color":"App\Entity\Animal":private]=> string(7) "purpura" 
    ["raza":"App\Entity\Animal":private]=> string(6) "danesa" } 
  }
```
### [446. Métodos en repositorios](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12088432#questions)
- Vincular la clase entity con su repositorio. Sirve para obligar a que el repositorio busque los métodos (con cualquier nombre) en su cuerpo antes de "autocrearlos" usando los atributos de la entidad (los metodos findBy,findCount,etc)
- Por defecto tenemos la clase Animal así:
```php
//symsite\src\Entity\Animal.php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Animales
 * @ORM\Table(name="animales")
 * @ORM\Entity
 */
class Animal
{
  ...
```
- Interesante. Si defino y método **`public function findByRazax($raza)`** en el repositorio, obtendría un error:
```s
Entity 'App\Entity\Animal' has no field 'razax'. You can therefore not call 'findByRazax' on the entities' repository
```
- Esto quiere decir que los métodos **findBy** deben tener esta sintaxis: **findBy<nombre-campo>**
- Si intento ejecutar el método del repositorio `findByRaza()` me daría un error *falta argumento* ya que por defecto el repositorio busca el atributo Raza de la entidad que tiene configurada y le asigna el valor del argumento. La entidad se configura en `parent::__construct($registry, Animal::class);` para forzar que el repositorio busque un método en su cuerpo (y no lo asuma como parte de la entidad) hay que indicar en la entidad asociada cual es su **repositoryClass**
- Vamos con el refactor:
```php
//symsite\src\Entity\Animal.php
/**
 * Animales
 * @ORM\Table(name="animales")
 * @ORM\Entity(repositoryClass="App\Repository\AnimalRepository")
 */
class Animal
```
- Ahora en el controlador, la llamada a los siguientes metodos ya no daria error:
```php
//symsite\src\Controller\AnimalController.php
public function index()
{
  $repanimal = $this->getDoctrine()->getRepository(Animal::class);
  ...
  $result = $repanimal->findAllAnimals(); //error: BadMethodCallException in vendor/doctrine/orm/lib/Doctrine/ORM/EntityRepository.php (line 235)
  var_dump($result);
  echo "<hr/>";
  $result = $repanimal->findByRaza();
  var_dump($result);
  die;
  ...
}//index
```
- ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/557x193/ab23c9f11553ea9b732f37d9d6b05a8b/image.png)

### Sección 99: Formularios en Symfony 0 / 8|39 min
### [447. Introducción a los formularios en Symfony4 2 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12088436#questions/8965684)
- Se puede hacer de la forma clásica de toda la vida apuntando a una ruta de symfony (no recomendada)
- Podemos usar createFormBuilder
  - definimos en el controlador una funcion create que devolvera una vista con un formulario
  - El formulario estará vinculado a una clase
  - Dentro de la vista solo imprimiremos el formulario con una etiqueta de impresion
  - Los formularios están vinculados a entidades
- Podriamos crear una clase especifica para cada formulario

### [448. Crear formularios 7 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12094830#questions/8965684)
```php
crear_animal:
  path: /crear-animal
  controller: App\Controller\AnimalController::crearAnimal


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Animal;

//objetos para dentro del form
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AnimalController extends AbstractController
{
  
  public function crearAnimal()
  {
      $animal = new Animal();
      $form = $this->createFormBuilder($animal)
                  //despues de hacer la acción
                  ->setAction($this->generateUrl("animal_save"))
                  ->setMethod("POST")
                  ->add("tipo", TextType::class)
                  ->add("color", TextType::class)
                  ->add("raza", TextType::class)
                  ->add("submit", SubmitType::class)
                  ->getForm()
                  ;
      return $this->render("animal/crear-animal.html.twig",[
          "form" => $form->createView()
      ]);
  }
//\symsite\templates\animal\crear-animal.html.twig
<h1>Formulario con S4</h1>
{{ form_start(form) }}
{{ form_widget(form) }}
{{ form_end(form) }}
```
- ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/334x190/b44f38dc82784b29eb275d5501689455/image.png)

### [449. Personalizar atributos 2 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12096908#questions/8965684)
```php
->setAction($this->generateUrl("animal_save"))
->setMethod("POST")
->add("tipo", TextType::class,[
    "label"=>"Tipo animal",
    "attr"=>[
        "class"=>"input"
    ]
])
->add("color", TextType::class)
->add("raza", TextType::class)
->add("submit", SubmitType::class, [
    "label"=>"Crear Animal",
    "attr"=>[
        "class"=>"btn"
    ]
])
->getForm()
;
```
- ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/914x168/3ab49c602f56a3d9dc54f1e100a80710/image.png)

### [450. Recibir datos del formulario 11 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12098910#questions/8965684)

```php
animal_save:
  path: /animal/save
  controller: App\Controller\AnimalController::save
  methods: [post]

public function crearAnimal(Request $request)
{
  $animal = new Animal();
  $form = $this->createFormBuilder($animal)
              //despues de hacer la acción
              //->setAction($this->generateUrl("animal_save"))
              ->setMethod("POST")
  ...
              ->getForm()
              ;
  $form->handleRequest($request);
  if($form->isSubmitted())
  {
      $em = $this->getDoctrine()->getManager();
      $em->persist($animal);
      $em->flush();
      $session = new Session();
      //$session->start(); no hace falta iniciar sesion
      //arrastra el mensaje hasta la ruta "crear_animal" ^^
      $session->getFlashBag()->add("message","Animal creado");
      
      //reseteo el formulario:
      //return $this->redirect($request->getUri()); //funciona ok
      return $this->redirectToRoute("crear_animal");
  }

<h1>Formulario con S4</h1>

{% for message in app.session.flashbag().get("message") %}
    <strong>{{ message }}</strong>
{% endfor %}

{{ form_start(form) }}  
```
### [451. Validar formulario 4 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12098912#questions/8965684)
- Para las validaciones se recomienda agregar las anotaciones
- Tengo que importar Constraints as Assert
```php
//symsite\src\Entity\Animal.php
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Animales
 *
 * @ORM\Table(name="animales")
 * @ORM\Entity(repositoryClass="App\Repository\AnimalRepository")
 */
class Animal
{
  /**
    * @var int
    *
    * @ORM\Column(name="id", type="integer", nullable=false)
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="IDENTITY")
    */
  private $id;

  /**
    * @var string|null
    *
    * @ORM\Column(name="tipo", type="string", length=255, nullable=true)
//validaciones:
    * @Assert\NotBlank
    * @Assert\Regex("/[a-zA-Z]+/")
    */
  private $tipo;
...
```
### [452. Personalizar mensajes 1 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12098914#questions/8965684)
```php
/**
  * @var string|null
  *
  * @ORM\Column(name="tipo", type="string", length=255, nullable=true)
  * @Assert\NotBlank
  * @Assert\Regex(
  *  pattern="/[a-zA-Z]+/",
  *  message="La raza debe estar formada por letras"
  * )
  */
private $tipo;
```
### [453. Formularios separados en clases 5 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12098916#questions/8965684)
- Creamos una carpeta "src/Form" sera el namespace "App/Form"
- Creamos la clase "AnimalType" extiende de "AbstractType" por lo tanto se le llama **<Entity>Type** por convención
- Hacemos refactor y extraemos el form 
```php
//symsite\src\Form\AnimalType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AnimalType extends AbstractType
{
    
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add("tipo", TextType::class,[
            "label"=>"Tipo animal",
            "attr"=>[
                "class"=>"input"
            ]
        ])
        ->add("color", TextType::class)
        ->add("raza", TextType::class)
        ->add("submit", SubmitType::class, [
            "label"=>"Crear Animal",
            "attr"=>[
                "class"=>"btn"
            ]
        ])
        ->getForm()
        ;
  }
}//AnimalType

//symsite\src\Controller\AnimalController.php
use App\Form\AnimalType;

class AnimalController extends AbstractController
{
    public function crearAnimal(Request $request)
    {
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class,$animal);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
          ...
```

### [454. Validar datos aislados 7 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12098920#questions/8965684)
- **comando** `php bin/console cache:clear -e dev` limpiar cache
- [Symfony constraints](https://symfony.com/doc/current/reference/constraints.html)
```php
//symsite\src\Controller\AnimalController.php
public function validarEmail($email)
{
    $validator = Validation::createValidator();
    $errores = $validator->validate($email,[
        new Email()
    ]);
    if(count($errores)!=0)
    {
        echo "El email NO se ha validado correctamente";
        foreach($errores as $error)
        {
            echo $error->getMessage()."<br/>";
        }
    }
    else
    {
        echo "El email ha sido validado correctametne";
    }
    die;
}//validarEmail
```
- Me da un error:
  - >Not Found The requested resource /validar-email/xxagua.a.com was not found on this server.
  - ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/632x169/5046f8a27ae1e3110e668e018badab6b/image.png)

## Sección 100: Empezando el proyecto completo de Symfony 0 / 2|4 min
### [455. Introducción al proyecto de Symfony 1 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12098926#questions)
- Proyecto de tareas
- diseño y creacion de la bd
- Entidades con sus relaciones
- Login de usuario
### [456. Instalar Symfony para el proyecto completo 3 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12098928#questions)
- **comando:** crear el proyecto
  - `composer create-project symfony/website-skeleton proyecto`
- configurar un host virutal
- configurar el etc

## Sección 101: La base de datos del proyecto 0 / 3|10 min
### [457. Diseñar la base de datos 3 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12109360#questions)
- Usa el programa dia para diseñar en UML
- Tablas:
  - users
  - tasks

### [458. Crear la base de datos 6 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12109362#questions)
- Se configura database.sql
- Se ejecuta en la bd

### [459. Conectar a la base de datos 1 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12109888#questions)
- Configuramos el fichero .env
- `DATABASE_URL=mysql://root:1234@cmari03:3306/symf_tasks?serverVersion=5.7`

### Sección 102: Modelos y entidades 0 / 4|22 min
### [460. Generar entidades 5 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12109892#questions)
- Creo las entidades
- `root@hphp03:/var/www/html# php bin/console doctrine:mapping:import App\\Entity annotation --path=src/Entity` Ok
- Muestra un aviso doctrine:
  ```s
  User Deprecated: Doctrine\ORM\Tools\Export\ClassMetadataExporter is deprecated and will be removed in Doctrine ORM 3.0

  2020-01-04T17:22:34+00:00 [info] User Deprecated: Doctrine\ORM\Tools\Export\Driver\AnnotationExporter is deprecated 
  and will be removed in Doctrine ORM 3.0

  2020-01-04T17:22:34+00:00 [info] User Deprecated: Doctrine\ORM\Tools\EntityGenerator is deprecated 
  and will be removed in Doctrine ORM 3.0

  2020-01-04T17:22:34+00:00 [info] User Deprecated: The Doctrine\Common\Persistence\ObjectRepository class is deprecated 
  since doctrine/persistence 1.3 and will be removed in 2.0. 
  Use \Doctrine\Persistence\ObjectRepository instead.
  ```
  ```php
  //Ha creado esta relación
  /**
  * Task
  *
  * @ORM\Table(name="tasks", indexes={@ORM\Index(name="fk_task_user", columns={"user_id"})})
  * @ORM\Entity
  */
  class Task
  {
    /**
      * @var \Users
      * @ORM\ManyToOne(targetEntity="Users")
      * @ORM\JoinColumns({
      *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
      * })
      */
    private $user;
  ```
- Creamos getters y setters:
  - `root@hphp03:/var/www/html# php bin/console make:entity --regenerate App` Ok
- Cambio el nombre la entidad de Users a User
  - `@ORM\ManyToOne(targetEntity="Users")`
  - Muchas tareas pueden estar relacionadas con un usuario

### [461. Relaciones ORM 6 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12110678#questions)
```php
//importamos estas colecciones, son iterables de objetos
//hay que agregar App\Entity\User
//User.php
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
  *
  * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="user")
  */
private $tasks; 

/**
 * @return Collection|Task[]
 */
public function getTasks():Collection
{
    return $this->tasks;
}

//Task.php
/**
  * @var \User
  *
  * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tasks")
  * @ORM\JoinColumns({
  *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
  * })
  */
private $user;
```
- Se configura la relación bidireccional

### [462. Rellenar la base de datos 3 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12110766#questions)
- Genero queries insert into

### [463. Probando entidades relacionadas 8 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12129072#questions)
- `/var/www/html# php bin/console make:controller TaskController`
- `/var/www/html# php bin/console make:controller UserController`
- En el video instala un paquete de apache symfony
  - `composer require symfony/apache-pack`
- Se definen rutas para estos controladores
  - /tasks
  - /users
- Listar los usuarios y sus tareas
```php
//User.php
public function index()
{
  $em = $this->getDoctrine()->getManager();
  $repotask = $this->getDoctrine()->getRepository(Task::class);
  $tasks = $repotask->findAll();

  //        foreach($tasks as $task)
  //        {
  //            //echo $task->getUser()->getEmail()." - ".$task->getTitle();
  //        }

  $repouser = $this->getDoctrine()->getRepository(User::class);
  $users = $repouser->findAll();
  foreach($users as $user)
  {
    echo "<h1>{$user->getName()} {$user->getSurname()}</h1>";
    foreach($user->getTasks() as $task)
    {
        echo $task->getUser()->getEmail()." - ".$task->getTitle()."<br/>";
    }            
  }

  return $this->render('task/index.html.twig', [
    'controller_name' => 'TaskController',
  ]);
}
```

## Sección 103: Registro de usuarios con Symfony 0 / 6|47 min
### [464. Formulario de Registro 10 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12139616#questions)
```php
//proyecto\src\Form\RegisterType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegisterType extends AbstractType {
    
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add("name", TextType::class, ["label"=>"Nombre"])
            ->add("surname", TextType::class, ["label"=>"Apellidos"])
            ->add("email", EmailType::class, ["label"=>"Correo electronico"])
            ->add("password", PasswordType::class, ["label"=>"Contraseña"])
            ->add("submit", SubmitType::class, ["label"=>"Registrarese"])
            ;
  }
}

//proyecto\src\Controller\UserController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

Use App\Entity\User;
use App\Form\RegisterType;

class UserController extends AbstractController
{    
  public function register(Request $request)
  {
    $user = new User();
    $form = $this->createForm(RegisterType::class,$user);
    return $this->render('user/register.html.twig', [
        "form" => $form->createView()
    ]);
  }
}//UserController

{% extends 'base.html.twig' %}

{% block title %}Registro!{% endblock %}

{% block body %}
    <h1>Registro usuarios</h1>
    {{ form_start(form) }}
    {{ form_widget(form) }}
    {{ form_end(form) }}
{% endblock %}
```
- ![](https://trello-attachments.s3.amazonaws.com/5e08af454987ac63c8dd78d7/328x272/8272fc6174b66c609dbd3e1ff0630b5d/image.png)

### [465. Guardar el usuario registrado 13 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12140094#questions)
- cifrado de contraseña
- trabajar con security.yaml
```
# error
Could not convert PHP value '06-01-2020 11:14:38' of type 'string' to type 'datetime'. 
Expected one of the following types: null, DateTime

Esto es porque la entidad User espera un objeto DateTime

/**
* @var \DateTime|null
* @ORM\Column(name="created_at", type="datetime", nullable=true, options={"default"="NULL"})
*/
private $createdAt = 'NULL';
```
```php
#proyecto\config\packages\security.yaml
security:
    encoders:
        App\Entity\User:
            algorithm: bcrypt
            # lo cifra 4 veces
            cost: 4
//proyecto\src\Entity\User.php
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * User
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User implements UserInterface
{
  //
  public function getUsername(): string {
    return $this->email;
  }

  public function getSalt()
  {
    return null;
  }

  public function getRoles()
  {
    //$this->getRole();
    return ["ROLE_USER"];
  }

  public function eraseCredentials() {
    ;
  }

//proyecto\src\Controller\UserController.php
if($form->isSubmitted())
{
    $user->setRole("ROLE_USER");
    $user->setCreatedAt(new \DateTime("now"));
    //cifrando la contraseña
    $encoded = $encoder->encodePassword($user,$user->getPassword());
    $user->setPassword($encoded);
    
    $em = $this->getDoctrine()->getManager();
    $em->persist($user);
    $em->flush();
```

### [466. Validar formulario de registro 4 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12140098#questions)
-
### [467. Cargar estilos 3 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12141124#questions)
-
### [468. Maquetar formulario 8 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12141126#questions)
-
### [469. Maquetar la cabecera y el menú 8 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12142262#questions)
-
## Sección 104: Login en Symfony 0 / 2|21 min
### [470. Login de usuarios 14 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12143718#questions)
-
### [471. Cerrar sesión (logout symfony) 7 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12143720#questions)
-
## Sección 105: Gestión de tareas 0 / 9|1 h 7 min
### [472. Listado de tareas 9 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12143726#questions)
-
### [473. Mejoras en el listado 6 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12144738#questions)
-
### [474. Detalle de la tarea 8 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12144740#questions)
-
### [475. Método crear tareas 3 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12144842#questions)
-
### [476. Crear tareas 14 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12150698#questions)
-
### [477. Mejorar estilos 1 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12150704#questions)
-
### [478. Mis tareas 9 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12150706#questions)
-
### [479. Edición de tareas 9 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12150710#questions)
-
### [480. Borrado de tareas 6 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12150712#questions)
-
## Sección 106: Control de Acceso 0 / 1|5 min
### [481. Control de acceso 5 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12150720#questions)
-

### Notas
- Pruebas de rendimiento:
- Cargar un formulario ronda unos 4 segundos
  ```php
  //carga sin cuello de botella
  Array (
  [0] => /var/www/html/public/index.php
  [1] => /var/www/html/config/bootstrap.php
  [2] => /var/www/html/vendor/autoload.php
  ...
  [34] => /var/www/html/vendor/symfony/http-foundation/HeaderBag.php
  [35] => /var/www/html/vendor/symfony/http-foundation/HeaderUtils.php
  Total Execution Time: 0.0041566133499146 Mins (0.2 segundos)

  //cuello de botella, cuando ejecuta esta linea
  $response = $kernel->handle($request);
  [36] => /var/www/html/config/bundles.php
  [37] => /var/www/html/vendor/symfony/framework-bundle/FrameworkBundle.php
  ...
  [679] => /var/www/html/vendor/symfony/security-http/RememberMe/RememberMeServicesInterface.php
  [680] => /var/www/html/vendor/symfony/http-kernel/Event/FinishRequestEvent.php
  //casi un minuto
  Total Execution Time: 0.074351918697357 Mins (4.5 segundos)
  ```
- Despues de instalar **intl** y **opcache**
  - Se reduce el tiempo de respuesta a la mitad
  ```php
  //carga sin cuello de botella
  Time: 0.11794185638428 secs
  //con cuello de botella
  Time: 2.0286891460419 secs
  //con profiler
  Time: 2.6077029705048 secs 703 archivos ^^
  ```