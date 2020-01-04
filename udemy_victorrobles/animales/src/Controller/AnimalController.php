<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Animal;

use Symfony\Component\HttpFoundation\Session\Session;

use App\Form\AnimalType;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Email;


class AnimalController extends AbstractController
{
    public function validarEmail($email)
    {
        $validator = Validation::createValidator();
        $errores = $validator->validate($email,[
            new Email()
        ]);
        if(count($errores)!=0)
        {
            echo "El email NO se ha validado correctamente <br/>";
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
    
    public function crearAnimal(Request $request)
    {
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class,$animal);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
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
        
        return $this->render("animal/crear-animal.html.twig",[
            "form" => $form->createView()
        ]);
    }
    
    /**
     * @Route("/animal", name="animal")
     */
    public function index()
    {
        $repanimal = $this->getDoctrine()->getRepository(Animal::class);
        $animales = $repanimal->findAll();
        //var_dump($animales);
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
        //var_dump($result);
        
        //dql
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT a FROM App\Entity\Animal a WHERE a.raza='africana' ORDER BY a.id DESC";
        $query = $em->createQuery($dql);
        $result= $query->execute();
        //var_dump($result);
        
        //sql
        $conx = $this->getDoctrine()->getConnection();
        $sql = "SELECT * FROM animales ORDER BY id DESC";
        $objprepare = $conx->prepare($sql);
        $result = $objprepare->execute();
        //var_dump($result);//boolean true | false
        //echo "<br/>";
        $result = $objprepare->fetchAll();
        //var_dump($result);
        
        //no va!
        //$repo= new AnimalRepository(Animal::class);
        $result = $repanimal->findAllAnimals(); //error: BadMethodCallException in vendor/doctrine/orm/lib/Doctrine/ORM/EntityRepository.php (line 235)
        var_dump($result);
        echo "<hr/>";
        $result = $repanimal->findByRaza();
        var_dump($result);
        
        return $this->render('animal/index.html.twig', [
            'controller_name' => 'AnimalController',
            "animales" => $animales
        ]);
    }//index
    
    public function save(Request $request)
    {
        $tipo = $request->get("form");
        var_dump($tipo);die;
//        //cargo el entity manager
//        $em = $this->getDoctrine()->getManager();
//        //creo el objeto y le doy valores
//        $animal = new Animal();
//        $animal->setTipo("Avestruz");
//        $animal->setColor("verde");
//        $animal->setRaza("africana");
//        //persist indica la accion futura que se ejecutará con flush
//        $em->persist($animal);
//        //volcar datos en la tabla
//        $em->flush();
//        
//        return new Response("El animal guardado tiene el id: ".$animal->getId());
    }
    
    //http://localhost:1000/animal/2
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
   
}//class AnimalController
