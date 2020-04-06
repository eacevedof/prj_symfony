<?php
//src/Api/Listener/User/UserPreWriteListener.php
declare(strict_types=1);
namespace App\Api\Listener\User;

use App\Api\Action\RequestTransformer;
use App\Entity\User;
use App\Api\Listener\PreWriteListener;
use App\Security\Validator\Role\RoleValidator;
use App\Service\Password\EncoderService;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class UserPreWriteListener implements PreWriteListener
{
    //ruta put sf d:r
    private const PUT_USER = "api_users_put_item";

    private EncoderService $encoderService;

    /**
     * @var iterable | RoleValidator[]
     */
    private iterable $roleValidators;
    //$roleValidators se configura en services.yaml

    public function __construct(EncoderService $encoderService, iterable $roleValidators)
    {
        $this->encoderService = $encoderService;
        //aqui vendrian instancias de AreValidRoles y CanAddRoleAdmin
        $this->roleValidators = $roleValidators;
    }

    /**
     * @param ViewEvent $event Tiene toda la información que tiene que ver con la actualización del usuario
     */
    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();

        //si conincide la ruta, es PUT de user
        if(self::PUT_USER === $request->get("_route")){
            /**
             * @var User $user  Usuario con la info ya actualizada (nuevos datos) todavia no está en bd
             */
            $user = $event->getControllerResult();

            $roles = [];
            foreach ($this->roleValidators as $roleValidator){
                // validate lanzará una excepción si no cumple la validación
                $roles = $roleValidator->validate($request);
            }

            //si todo ha ido bien se aplica esos roles al usuario
            $user->setRoles($roles);

            $user->setPassword(
                $this->encoderService->generateEncodedPasswordForUser(
                    $user,
                    RequestTransformer::getRequiredField($request,"password"),
                    null
                )
            );
        }
    }

}//UserPreWriteListener

/*
sf d:r
-------------------------------------- -------- -------- ------ ---------------------------------------- 
  Name                                   Method   Scheme   Host   Path                                    
 -------------------------------------- -------- -------- ------ ---------------------------------------- 
  _preview_error                         ANY      ANY      ANY    /_error/{code}.{_format}                
  app_api_action_user_register__invoke   POST     ANY      ANY    /api/v1/users/register                  
  api_entrypoint                         ANY      ANY      ANY    /api/v1/{index}.{_format}               
  api_doc                                ANY      ANY      ANY    /api/v1/docs.{_format}                  
  api_jsonld_context                     ANY      ANY      ANY    /api/v1/contexts/{shortName}.{_format}  
  api_users_get_collection               GET      ANY      ANY    /api/v1/users.{_format}                 
  api_users_get_item                     GET      ANY      ANY    /api/v1/users/{id}.{_format}            
  api_users_put_item                     PUT      ANY      ANY    /api/v1/users/{id}.{_format}            
  api_users_delete_item                  DELETE   ANY      ANY    /api/v1/users/{id}.{_format}            
  api_login_check                        ANY      ANY      ANY    /api/v1/login_check                     
 -------------------------------------- -------- -------- ------ ----------------------------------------
*/