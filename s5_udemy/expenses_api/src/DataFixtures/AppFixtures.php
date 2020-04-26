<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Group;
use App\Entity\User;
use App\Security\Roles;
use App\Service\Password\EncoderService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
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
        foreach ($users as $userData) {
            $user = new User($userData['name'], $userData['email'], $userData['id']);
            $user->setPassword($this->encoderService->generateEncodedPasswordForUser($user, $userData['password']));
            $user->setRoles($userData['roles']);

            $manager->persist($user);

            foreach ($userData['categories'] as $categoryData) {
                $category = new Category($categoryData['name'], $user, null, $categoryData['id']);

                $manager->persist($category);
            }

            foreach ($userData['groups'] as $groupData) {
                $group = new Group($groupData['name'], $user, $groupData['id']);
                $group->addUser($user);

                $manager->persist($group);

                foreach ($groupData['categories'] as $categoryData) {
                    $category = new Category($categoryData['name'], $user, $group, $categoryData['id']);

                    $manager->persist($category);
                }
            }
        }
        $manager->flush();
    }

    private function getUsers(): array
    {
        //se usan en: tests/Functional/TestBase.php const IDS
        /*
        'admin_id' => 'eeebd294-7737-11ea-bc55-0242ac130001',
        'user_id' => 'eeebd294-7737-11ea-bc55-0242ac130002',
        'admin_group_id' => 'eeebd294-7737-11ea-bc55-0242ac130003',
        'user_group_id' => 'eeebd294-7737-11ea-bc55-0242ac130004',
        'admin_category_id' => 'eeebd294-7737-11ea-bc55-0242ac130005',
        'user_category_id' => 'eeebd294-7737-11ea-bc55-0242ac130006',
        'admin_group_category_id' => 'eeebd294-7737-11ea-bc55-0242ac130007',
        'user_group_category_id' => 'eeebd294-7737-11ea-bc55-0242ac130008',
        */
        return [
            [
                'id' => 'eeebd294-7737-11ea-bc55-0242ac130001',
                'name' => 'Admin',
                'email' => 'admin@api.com',
                'password' => 'password',
                'roles' => [
                    Roles::ROLE_ADMIN,
                    Roles::ROLE_USER,
                ],
                'groups' => [
                    [
                        'id' => 'eeebd294-7737-11ea-bc55-0242ac130003',
                        'name' => 'Admin\'s Group',
                        'categories' => [
                            [
                                'id' => 'eeebd294-7737-11ea-bc55-0242ac130006',
                                'name' => 'Admin\'s Group category',
                            ],
                        ],
                    ],
                ],
                'categories' => [
                    [
                        'id' => 'eeebd294-7737-11ea-bc55-0242ac130005',
                        'name' => 'Admin\'s category',
                    ],
                ],
            ],
            [
                'id' => 'eeebd294-7737-11ea-bc55-0242ac130002',
                'name' => 'User',
                'email' => 'user@api.com',
                'password' => 'password',
                'roles' => [Roles::ROLE_USER],
                'groups' => [
                    [
                        'id' => 'eeebd294-7737-11ea-bc55-0242ac130004',
                        'name' => 'User\'s Group',
                        'categories' => [
                            [
                                'id' => 'eeebd294-7737-11ea-bc55-0242ac130008',
                                'name' => 'Admin\'s Group category',
                            ],
                        ],
                    ],
                ],
                'categories' => [
                    [
                        'id' => 'eeebd294-7737-11ea-bc55-0242ac130007',
                        'name' => 'User\'s category',
                    ],
                ],
            ],
        ];
    }//getUsers()

}//AppFixtures
