<?php
// tests/Functional/Api/Category/PutCategoryTest.php
declare(strict_types=1);

namespace App\Tests\Functional\Api\Category;

use Symfony\Component\HttpFoundation\JsonResponse;

class PutCategoryTest extends CategoryTestBase
{
    public function testPutCategory(): void
    {
        $payload = ['name' => 'new name'];

        self::$user->request(
            'PUT',
            \sprintf('%s/%s.%s', $this->endpoint, self::IDS['user_category_id'], self::FORMAT),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$user->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($payload['name'], $responseData['name']);
    }

    public function testPutAnotherGroup(): void
    {
        $payload = ['name' => 'new name'];

        self::$user->request(
            'PUT',
            \sprintf('%s/%s.%s', $this->endpoint, self::IDS['admin_category_id'], self::FORMAT),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$user->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}