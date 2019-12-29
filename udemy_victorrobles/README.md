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
### [430. Generar entidades desde la base de datos 8 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12063202#questions)
### [431. Generar entidades con Symfony 4 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12063204#questions)
### [432. Generar tablas desde entidades 4 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12063206#questions)
### [433. Hacer cambios en entidades 7 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12080892#questions)
### [434. Guardar en la base de datos 10 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12083792#questions)
### [435. Comando SQL 1 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12083794#questions)
### [436. Find 6 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12084102#questions)
### [437. Find all 5 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12086624#questions)
### [438. Tipos de Find 4 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12086630#questions)
### [439. Conseguir objeto automático 1 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12086638#questions)
### [440. Actualizar registros 8 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12086644#questions)
### [441. Borrar elementos de la base de datos 4 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12086650#questions)
### [442. Query Builder 5 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12088422#questions)
### [443. DQL 3 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12088424#questions)
### [444. SQL en Symfony 4 y Symfony 5 4 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12088426#questions)
### [445. Crear repositorios 7 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12088428#questions)
### [446. Métodos en repositorios](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12088432#questions)

