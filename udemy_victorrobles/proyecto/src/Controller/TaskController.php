<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;

class TaskController extends AbstractController
{
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $repotask = $this->getDoctrine()->getRepository(Task::class);
        //$tasks = $repotask->findAll();
        $tasks = $repotask->findBy([],["id"=>"DESC"]);

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    public function detail(Task $task){
        if(!$task){
            return $this->redirectToRoute("tasks");
        }

        return $this->render("task/detail.html.twig",["task"=>$task]);
    }

    public function creation(Request $request, UserInterface $user)
    {
        //var_dump($user);die;
        $task = new Task();
        $form = $this->createForm(TaskType::class,$task);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $task->setCreatedAt(new \Datetime("now"));
            $task->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            
            return $this->redirect($this->generateUrl("task_detail",["id"=>$task->getId()]));

        }
        return $this->render("task/creation.html.twig",[
            "form"=>$form->createView()
        ]);
    }

    //mis-tareas
    public function myTasks(UserInterface $user){
        $tasks = $user->getTasks();
        return $this->render("task/my-tasks.html.twig",["tasks"=>$tasks]);
    }
    
    //editar-tarea/{id}
    public function edit(Request $request,UserInterface $user, Task $task){
        if(!$user || $user->getId() != $task->getUser()->getId())
            return $this->redirectToRoute("tasks");
        
        $form = $this->createForm(TaskType::class,$task);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $task->setCreatedAt(new \Datetime("now"));
            $task->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            
            return $this->redirect($this->generateUrl("task_detail",["id"=>$task->getId()]));

        }
        return $this->render("task/creation.html.twig",["edit"=>true,
            "form"=>$form->createView()
        ]);
    }

    //tarea/delete/{id}
    public function delete(UserInterface $user, Task $task)
    {
        if(!$task)
            return $this->redirectToRoute("tasks");        
        
        if(!$user || $user->getId() != $task->getUser()->getId())
            return $this->redirectToRoute("tasks");
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();
        return $this->redirectToRoute("tasks");
    }

}//TaskController
