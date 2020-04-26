<?php
//src/Api/Listener/User/CategoryPreWriteListener.php
declare(strict_types=1);

namespace App\Api\Listener\Category;

use App\Api\Listener\PreWriteListener;
use App\Entity\Category;
use App\Entity\User;
use App\Exception\Common\CannotAddAnotherUserAsOwnerException;
use App\Exception\Group\UserNotMemberOfGroupException;
use App\Repository\GroupRepository;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CategoryPreWriteListener implements PreWriteListener
{
    private const POST_CATEGORY = 'api_categories_post_collection';

    private TokenStorageInterface $tokenStorage;
    private GroupRepository $groupRepository;

    public function __construct(TokenStorageInterface $tokenStorage, GroupRepository $groupRepository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->groupRepository = $groupRepository;
    }

    public function onKernelView(ViewEvent $event): void
    {
        /** @var User $tokenUser */
        $tokenUser = $this->tokenStorage->getToken()->getUser();
        $request = $event->getRequest();

        if (self::POST_CATEGORY === $request->get('_route')) {
            //comprobamos que el usuario que viene es el mismo que hace la petición
            /** @var Category $category */
            $category = $event->getControllerResult();

            if (null !== $category->getGroup()) {
                if (!$this->groupRepository->userIsMember($category->getGroup(), $tokenUser)) {
                    throw UserNotMemberOfGroupException::create();
                }

                //si intento agregar un usuario que no soy yo
                if($category->getUser()->getId()!==$tokenUser->getId()){
                    throw CannotAddAnotherUserAsOwnerException::create();
                }

                return;
            }//if(haygrupo)

            if ($category->getUser()->getId() !== $tokenUser->getId()) {
                throw CannotAddAnotherUserAsOwnerException::create();
            }

        }//if(uricategory)
    }// onKernelView

}// CategoryWriteListener
