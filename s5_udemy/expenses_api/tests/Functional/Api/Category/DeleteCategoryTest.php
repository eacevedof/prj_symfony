<?php
// tests/Functional/Api/Category/DeleteCategoryTest.php
declare(strict_types=1);

namespace App\Tests\Functional\Api\Category;

use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteCategoryTest extends CategoryTestBase
{
    public function testDeleteCategoryForUser(): void
    {
        self::$user->request('DELETE', \sprintf('%s/%s.%s', $this->endpoint, self::IDS['user_category_id'], self::FORMAT));

        $response = self::$user->getResponse();

        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteGroupCategory(): void
    {
        self::$user->request('DELETE', \sprintf('%s/%s.%s', $this->endpoint, self::IDS['user_group_category_id'], self::FORMAT));

        $response = self::$user->getResponse();

        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteAnotherUserCategory(): void
    {
        self::$user->request('DELETE', \sprintf('%s/%s.%s', $this->endpoint, self::IDS['admin_category_id'], self::FORMAT));

        $response = self::$user->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testDeleteAnotherGroupCategory(): void
    {
        self::$user->request('DELETE', \sprintf('%s/%s.%s', $this->endpoint, self::IDS['admin_group_category_id'], self::FORMAT));

        $response = self::$user->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}