<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\User;
use App\Service\Password\EncoderService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Security\Roles;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 * Aqui se crearán los datos falsos para los tests de la app
 */
class AppFixtures extends Fixture
{
    private EncoderService $encoderService;

    public function __construct(EncoderService $encoderService)
    {
        $this->encoderService = $encoderService;
    }

    public function load(ObjectManager $manager)
    {
        $users = $this->getUsers();
        foreach ($users as $userData){
            $user = new User($userData["name"],$userData["email"],$userData["id"]);
            $user->setPassword($this->encoderService->generateEncodedPasswordForUser($user,$userData["password"]));
            $user->setRoles($userData["roles"]);
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getUsers(): array
    {
        return [
            [
                "id" => "eeebd294-7737-11ea-bc55-0242ac130001",
                "name" => "Admin",
                "email" => "admin@api.com",
                "password" => "password",
                "roles" => [
                    Roles::ROLE_ADMIN,
                    Roles::ROLE_USER,
                ]
            ],
            [
                "id" => "eeebd294-7737-11ea-bc55-0242ac130002",
                "name" => "User",
                "email" => "user@api.com",
                "password" => "password",
                "roles" => [
                    Roles::ROLE_USER,
                ]
            ],
        ];
    }//getUsers()

}//AppFixtures
