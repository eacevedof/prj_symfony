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
- Las rutas las trabajeremos en routes.yml 
### [414. Rutas y acciones 5 min]()
-
```php
```
### [415. Parámetros opcionales 4 min]()
-
```php
```
### [416. Rutas avanzadas 5 min]()
-
```php
```
### [417. Redirecciones]()
-
```php
```