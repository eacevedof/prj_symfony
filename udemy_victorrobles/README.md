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
- 
### [451. Validar formulario 4 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12098912#questions/8965684)
- 
### [452. Personalizar mensajes 1 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12098914#questions/8965684)
- 
### [453. Formularios separados en clases 5 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12098916#questions/8965684)
- 
### [454. Validar datos aislados 7 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12098920#questions/8965684)
- 