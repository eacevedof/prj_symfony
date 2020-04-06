<?php
// tests/Functional/Api/TestBase.php
declare(strict_types=1);
namespace App\Tests\Functional\Api;

use App\DataFixtures\AppFixtures;
use Doctrine\ORM\EntityManagerInterface;
//use Doctrine\ORM\Tools\ToolsException;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Aqui irá toda la lógica como ids, configuraciones, creación de usuarios
 * que es lo necesario para ejecutar los tests
 */
class TestBase extends WebTestCase
{
    //trait helper para hacer operaciones contra la bd
    use FixturesTrait;

    protected const FORMAT = "jasonld";
    protected const IDS = [
        "admin_id" => "eeebd294-7737-11ea-bc55-0242ac130001",
        "user_id" => "eeebd294-7737-11ea-bc55-0242ac130002",
    ];

    protected static ?KernelBrowser $client = null;
    protected static ?KernelBrowser $admin = null;
    protected static ?KernelBrowser $user = null;

    public function setUp(): void
    {
        if(null === self::$client){
            self::$client = static::createClient();
        }

        if(null === self::$admin){
            self::$admin = clone self::$client;
            $this->createAuthenticatedUser(self::$admin,"admin@api.com","password");
        }

        if(null === self::$user){
            self::$user = clone self::$client;
            $this->createAuthenticatedUser(self::$user, "user@api.com", "password");
        }

    }

    private function createAuthenticatedUser(KernelBrowser &$client, string $username, string $password): void
    {
        $client->request(
            "POST",
            "http://localhost:200/api/v1/login_check",
            [
                "_email" => $username,
                "_password" => $password,
            ],
        );

        $data = json_decode($client->getResponse()->getContent(),true);
//print_r($data);die;
        $client->setServerParameters([
            "HTTP_Authorization" => sprintf("Bearer %s",$data["token"]),
            "CONTENT_TYPE" => "application/json",
        ]);
    }

    protected function getResponseData(Response $response): array
    {
        return \json_decode($response->getContent(), true);
    }

    private function resetDatabase():void
    {
        /**
         * @var EntityManagerInterface
         */
        $em = $this->getContainer()->get("doctrine")->getManager();

        if(!isset($metadata)){
            $metadata = $em->getMetadataFactory()->getAllMetadata();
        }

        $schemaTool = new SchemaTool($em);
        $schemaTool->dropDatabase();

        if(!empty($metadata)){
            $schemaTool->createSchema($metadata);
        }

        $this->postFixtureSetup();

        $this->loadFixtures([AppFixtures::class]);
    }

}