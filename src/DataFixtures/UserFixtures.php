<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        
    }
    public const USER_NB_TUPLES = 20;
    public const USER_REFERENCE = "client";
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1; $i <= self::USER_NB_TUPLES; $i++) {
            $user = (new User())
                // ->setFirstName( "Firstname $i")
                ->setFirstName($faker->firstName())
                // ->setLastName( "Lastname $i")
                ->setLastName($faker->lastName())
                // ->setGuestNumber(random_int(0, 10))
                ->setGuestNumber($faker->numberBetween(0, 10))
                // ->setEmail(" email.$i@studi.fr")
                ->setEmail($faker->email())
                ->setCreatedAt(new DateTimeImmutable());

            $user->setPassword($this->passwordHasher->hashPassword($user, "password$i" ));
            
            $manager->persist($user);
            $this->addReference(self::USER_REFERENCE . $i, $user);
        }
        $manager->flush();
    }
}
