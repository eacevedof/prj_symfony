<?php
//tests/Unit/Security/Validator/Role/CanAddRoleAdminTest.php
declare(strict_types=1);
namespace App\Tests\Unit\Security\Validator\Role;

use App\Exceptions\Role\RequiredRoleToAddRoleAdminNotFoundException;
use App\Security\Roles;
use App\Security\Validator\Role\CanAddRoleAdmin;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class CanAddRoleAdminTest extends TestCase
{
    /** @var ObjectProphecy|Security */
    private  $securityProphecy;
    private Security $security;


    private CanAddRoleAdmin $validator;

    public function setUp(): void
    {
        $this->securityProphecy = $this->prophesize(Security::class);
        $this->security = $this->securityProphecy->reveal();
        $this->validator = new CanAddRoleAdmin($this->security);
    }

    public function testCanAddRoleAdmin():void
    {
        $payload = [
            "roles" => [
                Roles::ROLE_ADMIN,
                Roles::ROLE_USER
            ]
        ];
        $request = new Request([],[],[],[],[],[], \json_encode($payload));
        $this->securityProphecy->isGranted(Roles::ROLE_ADMIN)->willReturn(true);
        $response = $this->validator->validate($request);
        $this->assertIsArray($response);
    }

    public function testCannotAddRoleAdmin():void
    {
        $payload = [
            "roles" => [
                Roles::ROLE_ADMIN,
                Roles::ROLE_USER
            ]
        ];
        $request = new Request([],[],[],[],[],[], \json_encode($payload));
        $this->securityProphecy->isGranted(Roles::ROLE_ADMIN)->willReturn(false);
        $this->expectException(RequiredRoleToAddRoleAdminNotFoundException::class);
        $this->expectExceptionMessage("ROLE_ADMIN required to perform this operation");
        $this->validator->validate($request);
    }
}