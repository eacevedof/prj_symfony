<?php
//src/Api/Listener/User/GroupPreWriteListener.php
declare(strict_types=1);

namespace App\Api\Listener\Group;

use App\Api\Listener\PreWriteListener;
use App\Entity\Group;
use App\Entity\User;
use App\Exceptions\Group\CannotAddAnotherOwnerException;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GroupPreWriteListener implements PreWriteListener
{
    private const POST_GROUP = 'api_groups_post_collection';

    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelView(ViewEvent $event): void
    {
        /** @var User $tokenUser */
        $tokenUser = $this->tokenStorage->getToken()->getUser();
        $request = $event->getRequest();

        if (self::POST_GROUP === $request->get('_route')) {
            /** @var Group $group */
            $group = $event->getControllerResult();
            //si el usuario en sesion no es propietario del grupo
            if (!$group->isOwnedBy($tokenUser)) {
                throw CannotAddAnotherOwnerException::create();
            }

            $group->addUser($tokenUser);
        }
    }

}// GroupPreWriteListener

/*
 appuser@d04baa6fe1d4:/appdata/www$ sf d:r
 -------------------------------------- -------- -------- ------ ----------------------------------------
  Name                                   Method   Scheme   Host   Path
 -------------------------------------- -------- -------- ------ ----------------------------------------
  _preview_error                         ANY      ANY      ANY    /_error/{code}.{_format}
  app_api_action_user_register__invoke   POST     ANY      ANY    /api/v1/users/register
  api_entrypoint                         ANY      ANY      ANY    /api/v1/{index}.{_format}
  api_doc                                ANY      ANY      ANY    /api/v1/docs.{_format}
  api_jsonld_context                     ANY      ANY      ANY    /api/v1/contexts/{shortName}.{_format}
  api_groups_get_collection              GET      ANY      ANY    /api/v1/groups.{_format}
  api_groups_post_collection             POST     ANY      ANY    /api/v1/groups.{_format}
  api_groups_get_item                    GET      ANY      ANY    /api/v1/groups/{id}.{_format}
  api_groups_put_item                    PUT      ANY      ANY    /api/v1/groups/{id}.{_format}
  api_groups_delete_item                 DELETE   ANY      ANY    /api/v1/groups/{id}.{_format}
  api_users_get_collection               GET      ANY      ANY    /api/v1/users.{_format}   -> ESTA ES LA QUE VALE!!
  api_users_get_item                     GET      ANY      ANY    /api/v1/users/{id}.{_format}
  api_users_put_item                     PUT      ANY      ANY    /api/v1/users/{id}.{_format}
  api_users_delete_item                  DELETE   ANY      ANY    /api/v1/users/{id}.{_format}
  api_login_check                        ANY      ANY      ANY    /api/v1/login_check
 -------------------------------------- -------- -------- ------ ----------------------------------------
*/