<?php
//src/Api/Listener/User/CategoryWriteListener.php
declare(strict_types=1);

namespace App\Api\Listener\Group;

use App\Api\Listener\PreWriteListener;
use App\Entity\Group;
use App\Entity\User;
use App\Exception\Common\CannotAddAnotherUserAsOwnerException;
use App\Exceptions\Group\CannotAddAnotherOwnerException;
use App\Exceptions\Group\UserNotMemberOfGroupException;
use App\Repository\GroupRepository;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CategoryWriteListener implements PreWriteListener
{
    private const POST_CATEGORY = 'api_groups_post_collection';

    private TokenStorageInterface $tokenStorage;
    private GroupRepository $groupRepository;

    public function __construct(TokenStorageInterface $tokenStorage, GroupRepository $groupRepository)
    {
        $this->tokenStorage = $tokenStorage;
        //necesitamos el grouprepository para saber si se crea por un usuario o un grupo
        $this->groupRepository = $groupRepository;
    }

    public function onKernelView(ViewEvent $event): void
    {
        /** @var User $tokenUser */
        $tokenUser = $this->tokenStorage->getToken()->getUser();
        $request = $event->getRequest();

        if (self::POST_CATEGORY === $request->get('_route')) {
            //comprobamos que el usuario que viene es el mismo que hace la petición
            /** @var Group $group */
            $category = $event->getControllerResult();
            if (null !== $category->getGroup()){
                if(!$this->groupRepository->userIsMember($category->getGroup(),$tokenUser)) {
                    throw UserNotMemberOfGroupException::create();
                }//!category no es grupo de userSess

                //si intento agregar un usuario que no soy yo
                if($category->getUser()->getId()!==$tokenUser->getId()){
                    throw CannotAddAnotherUserAsOwnerException::create();
                }

                return;
            }//if(haygrupo)

            if($category->getUser()->getId() !== $tokenUser->getId()){
                throw CannotAddAnotherUserAsOwnerException::create();
            }

        }//if(uricategory)
    }// onKernelView

}// CategoryWriteListener
