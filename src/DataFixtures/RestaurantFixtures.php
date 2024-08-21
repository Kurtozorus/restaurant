<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class RestaurantFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $client = $this->getReference("client$i");
            $restaurant = (new Restaurant())
                ->setName("Nomexmeple$i")
                ->setDescription("Description$i")
                ->setAmOpeningTime(['06h30-14h'])
                ->setPmOpeningTime(['19h30-23-00'])
                ->setMaxGuest(random_int(1, 20))
                ->setOwner($client)
                ->setCreatedAt(new DateTimeImmutable());

            $manager->persist($restaurant);
            $this->addReference("restaurant$i", $restaurant);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}