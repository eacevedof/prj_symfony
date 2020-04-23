<?php
//tests/Functional/Api/Group/DeleteGroupTest.php
declare(strict_types=1);
namespace App\Tests\Functional\Api\Group;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteGroupTest extends GroupTestBase
{
    public function testDeleteGroup(): void
    {
        self::$user->request("DELETE", \sprintf("%s/%s.%s",$this->endpoint, self::IDS["user_group_id"], self::FORMAT));
        $response = self::$user->getResponse();
        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
    }//testDeleteGroup

    //intentar borrar con un usuario el grupo del administrador debe dar error
    public function testDeleteAnotherUserGroup(): void
    {
        self::$user->request("DELETE", \sprintf("%s/%s.%s",$this->endpoint, self::IDS["admin_group_id"], self::FORMAT));
        $response = self::$user->getResponse();
        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }//testDeleteAnotherUserGroup

}// DeleteGroupTest