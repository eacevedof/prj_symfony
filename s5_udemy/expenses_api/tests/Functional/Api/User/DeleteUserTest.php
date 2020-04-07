<?php
//tests/Functional/Api/User/DeleteUserTest.php
declare(strict_types=1);
namespace App\Tests\Functional\Api\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteUserTest extends UserTestBase
{
    public function testDeleteUserWithAdmin(): void
    {
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
}