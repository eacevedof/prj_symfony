<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            "hello" => "Hola mundo"
        ]);
    }

    public function animales($nombre,$apellidos)
    {
        $animales = ["perro","gato","paloma","rata"];
        $aves = [
            "tipo" => "palomo",
            "color" => "gris",
            "edad" => 4,
            "raza" => "colillano"
        ];
        
        $vars = [
            "title"=>"Bienvenido a la página de animáles",
            "nombre"=>$nombre,
            "apellidos"=>$apellidos,
            "animales" => $animales,
            "aves" => $aves,
        ];
        return $this->render('home/animales.html.twig',$vars);
    }

    public function redirigir()
    {
        //return $this->redirectToRoute("index");

        //esto para seo puede ser conveniente para evitar su indexación
        //return $this->redirectToRoute("index",[],301);

        //cuidado con la cache
        return $this->redirectToRoute("animales",["nombre"=>"Juan Pedro","apellidos"=>"Lopez"]);

        //return $this->redirect("http://eduardoaf.com");
    }

}//HomeController
