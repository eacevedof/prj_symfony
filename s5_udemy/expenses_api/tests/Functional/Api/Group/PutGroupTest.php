<?php
//tests/Functional/Api/Group/PutGroupTest.php
declare(strict_types=1);
namespace App\Tests\Functional\Api\Group;
use Symfony\Component\HttpFoundation\JsonResponse;

class PutGroupTest extends GroupTestBase
{
    public function testCreateGroup(): void
    {
        $payload = [
            "name" => "new name",
        ];

        self::$user->request(
            "PUT",
            \sprintf("%s/%s.%s", $this->endpoint, self::IDS["user_group_id"], self::FORMAT),
            [],[],[],
            \json_encode($payload)
        );

        $response = self::$user->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($payload["name"], $responseData["name"]);

    }//testCreateGroup

    public function testPutAnotherGroup(): void
    {
        $payload = [
            "name" => "new name",
        ];

        self::$user->request(
            "PUT",
            \sprintf("%s/%s.%s", $this->endpoint, self::IDS["user_group_id"], self::FORMAT),
            [],[],[],
            \json_encode($payload)
        );

        $response = self::$user->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($payload["name"], $responseData["name"]);

    }//testCreateGroup

}// PutGroupTest