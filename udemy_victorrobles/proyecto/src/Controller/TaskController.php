<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use App\Entity\User;

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
}
