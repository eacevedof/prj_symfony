<?php
//tests/Functional/Api/User/DeleteUserTest.php
declare(strict_types=1);
namespace App\Tests\Functional\Api\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteUserTest extends UserTestBase
{
    public function testDeleteUserWithAdmin(): void
    {
        /*
        Este test me da error de fk, intenta borrar un usuario que existe en user_group
        SQLSTATE[23000]: Integrity constraint violation: 1451 Cannot delete or update a parent row: a foreign key constraint fails
        (`sf5-expenses-api_api-test`.`user_group`, CONSTRAINT `FK_8F02BF9D7E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`)
        */
        $uri = \sprintf("%s/%s.%s",$this->endpoint,self::IDS["user_id"], self::FORMAT);
        self::$admin->request("DELETE", $uri);
        $response = self::$admin->getResponse();
        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteAdminWithUser(): void
    {
        $uri = \sprintf("%s/%s.%s",$this->endpoint,self::IDS["admin_id"], self::FORMAT);
        self::$user->request("DELETE", $uri);
        $response = self::$user->getResponse();
        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

}// DeleteUserTest

/*
public function testDeleteUserWithAdmin(): void
{
    self::$admin->request('DELETE', \sprintf('%s/%s.%s', $this->endpoint, self::IDS['user_id'], self::FORMAT));

    $response = self::$admin->getResponse();

    $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
}

public function testDeleteAdminWithUser(): void
{
    self::$user->request('DELETE', \sprintf('%s/%s.%s', $this->endpoint, self::IDS['admin_id'], self::FORMAT));

    $response = self::$user->getResponse();

    $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
}
*/