<?php
//tests/Unit/Api/Action/Group/AddUserTest.php
declare(strict_types=1);
namespace App\Tests\Unit\Api\Action\Group;

use App\Api\Action\Group\AddUser;
use App\Entity\Group;
use App\Entity\User;
use App\Exceptions\Group\CannotAddUsersToGroupException;
use App\Exceptions\Group\GroupDoesNotExist;
use App\Exceptions\Group\GroupDoesNotExistException;
use App\Exceptions\User\UserAlredyMemberOfGroupException;
use App\Exceptions\User\UserDoesNotExistException;
use App\Tests\Unit\Api\Action\TestBase;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AddUserTest extends TestBase
{
    //tenemos un UserRepository y GroupRepository
    private User $user;
    private User $newUser;

    private array $payload;
    private Request $request;

    private AddUser $action;


    public function setUp(): void
    {
        parent::setUp();
        $this->payload = [
            "group_id" => "group_id_123",
            "user_id" => "user_id_456",
        ];
        $this->request = new Request([],[],[],[],[],[], \json_encode($this->payload));
        $this->user = new User("name","name@api.com");
        $this->newUser = new User("new","new.user@api.com");
        $this->action = new AddUser($this->userRepository,$this->groupRepository);
    }

    /**
     * @throws \Exception
     */
    public function testCanAddUserToGroup(): void
    {
        $group = new Group("group",$this->user);

        $this->groupRepositoryProphecy->findOneById($this->payload["group_id"])->willReturn($group);
        $this->groupRepositoryProphecy->userIsMember($group,$this->user)->willReturn(true);
        $this->userRepositoryProphecy->findOneById($this->payload["user_id"])->willReturn($this->newUser);
        $this->groupRepositoryProphecy->userIsMember($group,$this->newUser)->willReturn(false);

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
        $response = $this->action->__invoke($this->request, $this->user);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }//testCanAddUserToGroup

    public function testForNonExistingGroup(): void
    {
        $this->groupRepositoryProphecy->findOneById($this->payload["group_id"])->willReturn(null);
        $this->expectException(GroupDoesNotExistException::class);
        $this->action->__invoke($this->request, $this->user);
    }//testForNonExistingGroup

    /**
     * @throws \Exception
     */
    public function testAddUserToAnotherGroup(): void
    {
        $group = new Group("group",$this->user);
        $this->groupRepositoryProphecy->findOneById($this->payload["group_id"])->willReturn($group);
        $this->groupRepositoryProphecy->userIsMember($group,$this->user)->willReturn(false);
        $this->expectException(CannotAddUsersToGroupException::class);
        $this->action->__invoke($this->request, $this->user);
    }//testAddUserToAnotherGroup

    /**
     * @throws \Exception
     */
    public function testNewUserDoesNotExist(): void
    {
        $group = new Group("group",$this->user);
        $this->groupRepositoryProphecy->findOneById($this->payload["group_id"])->willReturn($group);
        $this->groupRepositoryProphecy->userIsMember($group,$this->user)->willReturn(true);
        $this->userRepositoryProphecy->findOneById($this->payload["user_id"])->willReturn(null);
        $this->expectException(UserDoesNotExistException::class);
        $this->action->__invoke($this->request, $this->user);
    }//testAddUserToAnotherGroup

    /**
     * @throws \Exception
     */
    public function testNewUserAlreadyMemberOfGroup(): void
    {
        $group = new Group("group",$this->user);
        $this->groupRepositoryProphecy->findOneById($this->payload["group_id"])->willReturn($group);
        $this->groupRepositoryProphecy->userIsMember($group,$this->user)->willReturn(true);
        $this->userRepositoryProphecy->findOneById($this->payload["user_id"])->willReturn($this->newUser);
        $this->groupRepositoryProphecy->userIsMember($group,$this->newUser)->willReturn(true);

        $this->expectException(UserAlredyMemberOfGroupException::class);
        $this->action->__invoke($this->request, $this->user);
    }//testAddUserToAnotherGroup
}// AddUserTest