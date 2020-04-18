<?php
//src/Api/Action/Group/AddUser.php
declare(strict_types=1);
namespace App\Api\Action\Group;

use App\Entity\User;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\Annotation\Route;

class AddUser
{
    private UserRepository $userRepository;
    private GroupRepository $groupRepository;
    //private TokenStorageInterface $tokenStorage;

    public function __construct(UserRepository $userRepository, GroupRepository $groupRepository)
    {
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
        //$this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route("/groups/add_user", methods={"POST"})
     */
    public function __invoke(Request $request, User $user):JsonResponse
    {
        //esto daría un error, pq tokenstorage en este punto de la ejecución no estaría definido, habria que inyectar
        //el usuario en __invoke, con el User también daria error porque solo sería posible inyectar Request es por eso que
        //se necesita crear un Resolver
        //$user = $this->tokenStorage->getToken()->getUser();
        return new JsonResponse();
    }

}//AddUser