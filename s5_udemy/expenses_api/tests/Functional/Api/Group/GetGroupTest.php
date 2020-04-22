<?php
//tests/Functional/Api/Group/GetGroupTest.php
declare(strict_types=1);

namespace App\Tests\Functional\Api\Group;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetGroupTest extends GroupTestBase
{
    public function testGetGroupsForAdmin():void
    {
        self::$admin->request("GET", \sprintf("%s.%s",$this->endpoint,self::FORMAT));
        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(2, $responseData["hydra:member"]);
    }
}