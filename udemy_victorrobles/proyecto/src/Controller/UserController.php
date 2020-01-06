<?php
//proyecto\src\Controller\UserController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{    
    public function register(Request $request,UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        //mapea el formulario con la entidad
        $form = $this->createForm(RegisterType::class,$user);
        //rellena la entidad con los datos del formulario
        $form->handleRequest($request);
        //si hay datos en POST|GET
        if($form->isSubmitted() && $form->isValid())
        {
            $user->setRole("ROLE_USER");
            $user->setCreatedAt(new \DateTime("now"));
            //cifrando la contraseña
            $encoded = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($encoded);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            return $this->redirectToRoute("tasks");
        }
        
        return $this->render('user/register.html.twig', [
            "form" => $form->createView()
        ]);
    }
    
    public function login(AuthenticationUtils $authentication)
    {
        $error = $authentication->getLastAuthenticationError();
        $lastUsername = $authentication->getLastUsername();
        return $this->render("user/login.html.twig",[
            "error" => $error,
            "_last_username"=>$lastUsername
        ]);
    }
}//UserController
