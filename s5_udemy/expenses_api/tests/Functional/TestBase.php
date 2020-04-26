<?php
// tests/Functional/TestBase.php
declare(strict_types=1);

namespace App\Tests\Functional;

use App\DataFixtures\AppFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TestBase extends WebTestCase
{
    use FixturesTrait;

    protected const FORMAT = 'jsonld';

    protected const IDS = [
        //deben existir en src/DataFixtures/AppFixtures.php -> getUsers
        'admin_id' => 'eeebd294-7737-11ea-bc55-0242ac130001',
        'user_id' => 'eeebd294-7737-11ea-bc55-0242ac130002',
        'admin_group_id' => 'eeebd294-7737-11ea-bc55-0242ac130003',
        'user_group_id' => 'eeebd294-7737-11ea-bc55-0242ac130004',
        'admin_category_id' => 'eeebd294-7737-11ea-bc55-0242ac130005',
        'user_category_id' => 'eeebd294-7737-11ea-bc55-0242ac130006',
        'admin_group_category_id' => 'eeebd294-7737-11ea-bc55-0242ac130007',
        'user_group_category_id' => 'eeebd294-7737-11ea-bc55-0242ac130008',
    ];

    protected static ?KernelBrowser $client = null;
    protected static ?KernelBrowser $admin = null;
    protected static ?KernelBrowser $user = null;

    /**
     * @throws ToolsException
     */
    public function setUp():void
    {
        $this->resetDatabase();

        if (null === self::$client) {
            self::$client = static::createClient();
        }

        if (null === self::$admin) {
            self::$admin = clone self::$client;
            $this->createAuthenticatedUser(self::$admin, 'admin@api.com', 'password');
        }

        if (null === self::$user) {
            self::$user = clone self::$client;
            $this->createAuthenticatedUser(self::$user, 'user@api.com', 'password');
        }
    }

    private function createAuthenticatedUser(KernelBrowser &$client, string $username, string $password): void
    {
        $client->request(
            'POST',
            '/api/v1/login_check',
            [
                '_email' => $username,
                '_password' => $password,
            ]
        );

        $data = \json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameters(
            [
                'HTTP_Authorization' => \sprintf('Bearer %s', $data['token']),
                'CONTENT_TYPE' => 'application/json',
            ]
        );
    }

    protected function getResponseData(Response $response): array
    {
        return \json_decode($response->getContent(), true);
    }

    /**
     * @throws ToolsException
     */
    private function resetDatabase(): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine')->getManager();
        if (!isset($metadata)) {
            $metadata = $em->getMetadataFactory()->getAllMetadata();
        }

        $schemaTool = new SchemaTool($em);
        $schemaTool->dropDatabase();

        if (!empty($metadata)) {
            $schemaTool->createSchema($metadata);
        }

        $this->postFixtureSetup();

        $this->loadFixtures([AppFixtures::class]);
    }
}