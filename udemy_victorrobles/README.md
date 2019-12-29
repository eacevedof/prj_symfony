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
{# trabjar con arrays #}
  
{{ dump(animales) }}

{{ animales[0] }}

{{ aves.tipo ~ " - " ~ aves.raza}}
```
### [422. Estructuras de control en Twig 6 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/11990200#questions)
```php
```
### [423. Starts Ends 2 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/11990202#questions)
```php
```
### [424. Funciones predefinidas Twig 5 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12041194#questions)
```php
```
### [425. Includes 4 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12047378#questions)
```php
```
### [426. Filtros por defecto 3 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12047380#questions)
```php
```
### [427. Crear extensiones 10 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12052868#questions)
```php
```
### [428. Listar rutas 2 min](https://www.udemy.com/course/master-en-php-sql-poo-mvc-laravel-symfony-4-wordpress/learn/lecture/12052870#questions)
```php
```
