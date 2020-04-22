<?php
// tests/Functional/Api/Group/GroupTestBase.php
declare(strict_types=1);
namespace App\Tests\Functional\Api\Group;
use App\Tests\Functional\Api\TestBase;

class GroupTestBase extends TestBase
{
    protected string $endpoint;

    public function setUp(): void
    {
        parent::setUp();
        $this->endpoint = "/api/v1/groups";
    }
}