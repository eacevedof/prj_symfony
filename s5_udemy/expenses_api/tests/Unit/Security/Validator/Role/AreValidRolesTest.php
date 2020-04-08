<?php
//tests/Unit/Security/Validator/Role/AreValidRolesTest.php
declare(strict_types=1);
namespace App\Tests\Unit\Security\Validator\Role;

use App\Exceptions\Role\UnsupportedRoleException;
use App\Security\Roles;
use App\Security\Validator\Role\AreValidRoles;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

class AreValidRolesTest extends TestCase
{
    private AreValidRoles $validator;

    public function setUp(): void
    {
        $this->validator = new AreValidRoles();
    }

    public function testRolesAreValid(): void
    {
        $payload = [
            "roles" => [
                Roles::ROLE_ADMIN,
                Roles::ROLE_USER
            ]
        ];
        $request = new Request([],[],[],[],[],[], \json_encode($payload));
        $response = $this->validator->validate($request);
        $this->assertIsArray($response);
    }

    public function testInvalidRoles(): void
    {
        $payload = [
            "roles" => [
                Roles::ROLE_ADMIN,
                "ROLE_FAKE",
            ]
        ];

        $request = new Request([],[],[],[],[],[], \json_encode($payload));
        $this->expectException(UnsupportedRoleException::class);
        $this->getExpectedExceptionMessage("Unsupported role ROLE_FAKE");
        //validator instancia de AreValidRoles
        $this->validator->validate($request);
    }
}