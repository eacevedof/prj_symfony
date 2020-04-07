<?php
//tests/Functional/Api/User/PutUserTest.php
declare(strict_types=1);
namespace App\Tests\Functional\Api\User;

use App\Security\Roles;
use Symfony\Component\HttpFoundation\JsonResponse;

class PutUserTest extends UserTestBase
{
    /**
     * Al usuario simple se le actualiza el Rol a ADMIN con un Admin
     */
    public function testPutUserWithAdmin():void
    {
        $payload = [
          "name" => "New name",
          "password" => "password2",
          "roles" => [
              Roles::ROLE_ADMIN,
              Roles::ROLE_USER,
          ],
        ];

        $uri = \sprintf("%s/%s.%s",$this->endpoint,self::IDS["user_id"], self::FORMAT);
        self::$admin->request(
            "PUT",
            $uri,
            [],[],[], json_encode($payload)
        );

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(self::IDS["user_id"], $responseData["id"]);
        $this->assertEquals($payload["name"], $responseData["name"]);
        $this->assertEquals($payload["roles"], $responseData["roles"]);
    }

    /**
     * se intenta modificar un Admin con un User comun
     */
    public function testPutAdminWithUser():void
    {

        $payload = [
            "name" => "New name",
            "password" => "password2",
            "roles" => [
                Roles::ROLE_ADMIN,
                Roles::ROLE_USER,
            ],
        ];

        $uri = \sprintf("%s/%s.%s",$this->endpoint,self::IDS["admin_id"], self::FORMAT);
        self::$user->request(
            "PUT",
            $uri,
            [],[],[], json_encode($payload)
        );

        $response = self::$user->getResponse();
        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     *
     */
    public function testPutUserWithAdminAndFakeRole():void
    {
        $payload = [
            "name" => "New name",
            "password" => "password2",
            "roles" => [
                Roles::ROLE_ADMIN,
                "ROLE_FAKE",
            ],
        ];

        $uri = \sprintf("%s/%s.%s",$this->endpoint,self::IDS["user_id"], self::FORMAT);
        self::$admin->request(
            "PUT",
            $uri,
            [],[],[], json_encode($payload)
        );

        $response = self::$admin->getResponse();
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }


    public function testAddAdminRoleWithUser():void
    {
        $payload = [
            "name" => "New name",
            "password" => "password2",
            "roles" => [
                Roles::ROLE_ADMIN,
                Roles::ROLE_USER,
            ],
        ];


        $uri = \sprintf("%s/%s.%s",$this->endpoint,self::IDS["user_id"], self::FORMAT);
        self::$user->request(
            "PUT",
            $uri,
            [],[],[], json_encode($payload)
        );

        $response = self::$user->getResponse();
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}