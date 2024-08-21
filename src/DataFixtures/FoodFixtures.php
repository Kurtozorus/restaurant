<?php

namespace App\DataFixtures;

use App\Entity\Food;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class FoodFixtures extends Fixture implements DependentFixtureInterface
{
    public const FOOD_NB_TUPLES = 20;

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= self::FOOD_NB_TUPLES; $i++) {
            $food = (new Food())
                // ->setUuid(random_int(100,200))
                ->setUuid($faker->uuid())
                ->setTitle($faker->word())
                ->setDescription($faker->text())
                // ->setPrice(random_int(5,20))
                ->setPrice($faker->numberBetween(5,20))
                ->setCreatedAt(new DateTimeImmutable());

            //associer des catégories au menu
            //Ici, on associe 1 catégorie aléatoire à chaque menu pour l'exemple
            // for ($j = 1; $j <= 1; $j++) {
                $category = $this->getReference(CategoryFixtures::CATEGORY_REFERENCE . random_int(1, 20));
                $food->addCategory($category);
            // }

            $manager->persist($food);
    }
        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }
}