<?php
// tests/Functional/Api/Category/PostCategoryTest.php
declare(strict_types=1);

namespace App\Tests\Functional\Api\Category;

use Symfony\Component\HttpFoundation\JsonResponse;

class PostCategoryTest extends CategoryTestBase
{
    public function testCreateCategory(): void
    {
        $payload = [
            'name' => 'Admin\'s Category 2',
            'user' => \sprintf('/api/v1/users/%s', self::IDS['admin_id']),
        ];

        self::$admin->request(
            'POST',
            \sprintf('%s.%s', $this->endpoint, self::FORMAT),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($payload['name'], $responseData['name']);
    }

    public function testCreateCategoryForGroup(): void
    {
        $payload = [
            'name' => 'Admin\'s Category 2',
            'user' => \sprintf('/api/v1/users/%s', self::IDS['admin_id']),
            'group' => \sprintf('/api/v1/groups/%s', self::IDS['admin_group_id']),
        ];

        self::$admin->request(
            'POST',
            \sprintf('%s.%s', $this->endpoint, self::FORMAT),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($payload['name'], $responseData['name']);
        $this->assertEquals($payload['group'], $responseData['group']);
    }

    public function testCreateCategoryForAnotherUser(): void
    {
        $payload = [
            'name' => 'Admin\'s Category 2',
            'user' => \sprintf('/api/v1/users/%s', self::IDS['user_id']),
        ];

        self::$admin->request(
            'POST',
            \sprintf('%s.%s', $this->endpoint, self::FORMAT),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$admin->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testCreateCategoryForAnotherGroup(): void
    {
        $payload = [
            'name' => 'Admin\'s Category 2',
            'user' => \sprintf('/api/v1/users/%s', self::IDS['admin_id']),
            'group' => \sprintf('/api/v1/groups/%s', self::IDS['user_group_id']),
        ];

        self::$admin->request(
            'POST',
            \sprintf('%s.%s', $this->endpoint, self::FORMAT),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$admin->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}