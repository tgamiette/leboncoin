<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\FileFactory;
use App\Factory\OfferFactory;
use App\Factory\QuestionFactory;
use App\Factory\ResponseFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherAwareInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

class AppFixtures extends Fixture {

    public function load(ObjectManager $manager): void {
        UserFactory::createMany(10);
        OfferFactory::createMany(50);
        FileFactory::createMany(150);
        QuestionFactory::createMany(10);
    }
}
