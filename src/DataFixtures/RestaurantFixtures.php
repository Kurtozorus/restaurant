<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class RestaurantFixtures extends Fixture implements DependentFixtureInterface
{
    public const RESTAURANT_REFERENCE = "restaurant";
    public const RESTAURANT_NB_TUPLES = 20;

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= self::RESTAURANT_NB_TUPLES; $i++) {
            $client = $this->getReference(UserFixtures::USER_REFERENCE . $i);
            $restaurant = (new Restaurant())
                // ->setName("Nomexmeple$i")
                ->setName($faker->word())
                // ->setDescription("Description$i")
                ->setDescription($faker->text())
                // ->setAmOpeningTime(['06h30-14h'])
                ->setAmOpeningTime([$faker->time()])
                // ->setPmOpeningTime(['19h30-23-00'])
                ->setPmOpeningTime([$faker->time()])
                // ->setMaxGuest(random_int(1, 20))
                ->setMaxGuest($faker->numberBetween(1, 20))
                ->setOwner($client)
                ->setCreatedAt(new DateTimeImmutable());

            $manager->persist($restaurant);
            $this->addReference(self::RESTAURANT_REFERENCE .$i, $restaurant);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}