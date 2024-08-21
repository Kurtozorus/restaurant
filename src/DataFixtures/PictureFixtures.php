<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;


class PictureFixtures extends Fixture implements DependentFixtureInterface
{
    public const PICTURE_NB_TUPLES = 20;
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1; $i <= self::PICTURE_NB_TUPLES; $i++) {
            $restaurant = $this->getReference("restaurant" . random_int(1, 20));
            $picture = (new Picture())
                // ->setTitle("Titre$i")
                ->setTitle($faker->word())
                // ->setSlug("Slug$i")
                ->setSlug($faker->slug())
                ->setRestaurant($restaurant)
                ->setCreatedAt(new DateTimeImmutable());

            $manager->persist($picture);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [RestaurantFixtures::class];
    }
}