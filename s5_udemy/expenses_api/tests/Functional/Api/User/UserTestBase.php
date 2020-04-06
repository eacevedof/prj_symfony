<?php
//tests/Functional/Api/User/UserTestBase.php
declare(strict_types=1);
namespace App\Tests\Functional\Api\User;

use App\Tests\Functional\Api\TestBase;

/**
 * Aqui el test está orientado a casos de uso
 * atributos con endponts por ejemplo
 */
class UserTestBase extends TestBase
{
    protected string $endpoint;

    public function setUp(): void
    {
        //crea los clientes y guarda los tokens
        parent::setUp();
        $this->endpoint = "/api/v1/users";
    }

}