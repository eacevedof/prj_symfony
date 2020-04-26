<?php
// tests/Functional/Api/User/GetUserTest.php
declare(strict_types=1);
namespace App\Tests\Functional\Api\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class GetUserTest extends UserTestBase
{
    public function testGetUsersForAdmin(): void
    {
        $uri = \sprintf("%s.%s",$this->endpoint,self::FORMAT);
        self::$admin->request("GET", $uri);
        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(2,$responseData["hydra:member"]);
    }

    /**
     * Si el usuario (que no es admin) puede obtener a todos los usuarios
     */
    public function testGetUsersForUser(): void
    {
        $uri = \sprintf("%s.%s",$this->endpoint,self::FORMAT);
        self::$user->request("GET", $uri);
        $response = self::$user->getResponse();
        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * Endpoints para obtener un solo usuario
     */
    public function testGetUserWithAdmin():void
    {
        $uri = \sprintf("%s/%s.%s", $this->endpoint, self::IDS["user_id"], self::FORMAT);
        self::$admin->request("GET", $uri);
        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(self::IDS["user_id"],$responseData["id"]);
    }

    /**
     * Endpoints para obtener el admin con un usuario común
     */
    public function testGetAdminWithUser():void
    {
        $uri = \sprintf("%s/%s.%s", $this->endpoint, self::IDS["admin_id"], self::FORMAT);
        self::$user->request("GET", $uri);
        $response = self::$user->getResponse();
        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

}//GetUserTest
