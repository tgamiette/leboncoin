<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherAwareInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

class AppFixtures extends Fixture {

    public function __construct(private UserFactory $userFactory) {
    }

    public function load(ObjectManager $manager): void {
        $faker = Factory::create('fr_FR');


        $this->userFactory::createMany(10,)

        for ($i = 0; $i < 10; $i++) {
            $user = (new User())->setEmail($faker->email())->setPassword($password);

            $password = $this->hasher->hashPassword();
        }
        // $product = new Product();
        $manager->persist($user);
        $manager->flush();
    }
}
