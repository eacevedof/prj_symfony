<?php
//tests/Unit/Api/Action/Group/AddUserTest.php
declare(strict_types=1);
namespace App\Tests\Unit\Api\Action\Group;

use App\Api\Action\Group\AddUser;
use App\Entity\Group;
use App\Entity\User;
use App\Tests\Unit\Api\Action\TestBase;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AddUserTest extends TestBase
{
    //tenemos un UserRepository y GroupRepository

    private AddUser $action;

    public function setUp(): void
    {
        parent::setUp();
        $this->action = new AddUser($this->userRepository,$this->groupRepository);
    }

    /**
     * @throws \Exception
     */
    public function testCanAddUserToGroup(): void
    {
        $payload = [
            "group_id" => "group_id_123",
            "user_id" => "user_id_456",
        ];

        $request = new Request([],[],[],[],[],[], \json_encode($payload));

        $user = new User("name","name@api.com");
        $newUser = new User("new","new@api.com");
        $group = new Group("group",$user);

        $this->groupRepositoryProphecy->findOneById($payload["group_id"])->willReturn($group);
        $this->groupRepositoryProphecy->userIsMember($group,$user)->willReturn(true);
        $this->userRepositoryProphecy->findOneById($payload["user_id"])->willReturn($newUser);
        $this->groupRepositoryProphecy->userIsMember($group,$newUser)->willReturn(false);

        //se llama al método save una sola vez con una instancia de grupo
        $this->groupRepositoryProphecy->save(
            Argument::that(
                function(Group $group): bool {
                    return true;
                }
            )
        )->shouldBeCalledOnce();

        //Todo lo anterior son los requisitos necesarios para lanzar AddUser.invoke()
        // /groups/add_user  methods=POST
        $response = $this->action->__invoke($request, $user);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }


}// AddUserTest