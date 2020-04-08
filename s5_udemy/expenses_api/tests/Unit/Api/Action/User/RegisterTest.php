<?php
//tests/Unit/Api/Action/User/RegisterTest.php
declare(strict_types=1);
namespace App\Tests\Unit\Api\Action\User;

use App\Api\Action\User\Register;
use App\Entity\User;
use App\Exceptions\User\UserAlreadyExistException;
use App\Repository\UserRepository;
use App\Service\Password\EncoderService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class RegisterTest extends TestCase
{
    /** @var ObjectProphecy|UserRepository */
    private $userRepositoryProphecy;

    private UserRepository $userRepository;

    /** @var ObjectProphecy|JWTTokenManagerInterface */
    private $JWTTokenManagerProphecy;

    private JWTTokenManagerInterface $JWTTokenManager;

    /** @var ObjectProphecy|EncoderService */
    private $encoderServiceProphecy;

    private EncoderService $encoderService;

    private Register $action;

    public function setUp(): void
    {
        //la promesa
        $this->userRepositoryProphecy = $this->prophesize(UserRepository::class);
        //la revelamos
        $this->userRepository = $this->userRepositoryProphecy->reveal();

        $this->JWTTokenManagerProphecy = $this->prophesize(JWTTokenManagerInterface::class);
        $this->JWTTokenManager = $this->JWTTokenManagerProphecy->reveal();

        $this->encoderServiceProphecy = $this->prophesize(EncoderService::class);
        $this->encoderService = $this->encoderServiceProphecy->reveal();

        $this->action = new Register($this->userRepository,$this->JWTTokenManager,$this->encoderService);

    }

    /*
     * En los tests unitarios se valida que lo que debería pasar realmente ocurre
     * @throws \Exception
     * */
    public function testCreateUser(): void
    {
        $payload = [
            "name" => "Username",
            "email" => "username@api.com",
            "password" => "random_password",
        ];

        $request = new Request([],[],[],[],[],[],\json_encode($payload));
        //hay que emular el comportamiento de lo que va a pasar en la clase
        //comprobar happy pass

        $this->userRepositoryProphecy->findOneByEmail($payload["email"])->willReturn(null);
        $this->encoderServiceProphecy->generateEncodedPasswordForUser(
            //Este argumento es dinámico, y el usuario lo inyecta la clase Register
            Argument::that(function(User $user):bool{
                return true;
            }),
            //$payload["password"]
            Argument::type("string")
        )->shouldBeCalledOnce();

        $this->userRepositoryProphecy->save(
            Argument::that(function(User $user):bool{
                return true;
            })
        )->shouldBeCalledOnce();

        $this->JWTTokenManagerProphecy->create(
            Argument::that(function(User $user):bool{
                return true;
            })
        )->shouldBeCalledOnce();

        //action es una instancia de Register
        $response = $this->action->__invoke($request);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }//testCreateUser

    public function testCreateUserForExistingEmail(): void
    {
        $payload = [
            "name" => "Username",
            "email" => "username@api.com",
            "password" => "random_password",
        ];

        $request = new Request([],[],[],[],[],[],\json_encode($payload));

        $user = new User($payload["name"],$payload["email"]);

        $this->userRepositoryProphecy->findOneByEmail($payload["email"])->willReturn($user);

        $this->expectException(UserAlreadyExistException::class);
        //action es una instancia de Register
        $this->action->__invoke($request);

    }//testCreateUserForExistingEmail
}