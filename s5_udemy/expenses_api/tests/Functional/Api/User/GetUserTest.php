<?php
// tests/Functional/Api/User/GetUserTest.php
declare(strict_types=1);
namespace App\Tests\Functional\Api\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class GetUserTest extends UserTestBase
{
    public function testGetUsersForAdmin(): void
    {
        // /api/v1/users.jasonld
        $uri = \sprintf("%s.%s",$this->endpoint,self::FORMAT);
        //$uri = \sprintf("%s",$this->endpoint);
        //print_r($url);//die;
        self::$admin->request("GET", $uri);
        $response = self::$admin->getResponse();
        //print_r($response);die;
        $responseData = $this->getResponseData($response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }
}