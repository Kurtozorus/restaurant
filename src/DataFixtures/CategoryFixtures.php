<?php

namespace App\DataFixtures;

use App\Entity\Category;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;


class CategoryFixtures extends Fixture
{
    public const CATEGORY_NB_TUPLES = 20;
    public const CATEGORY_REFERENCE = 'category';

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1; $i <= self::CATEGORY_NB_TUPLES; $i++) {
            $category = (new Category())
                // ->setUuid(random_int(100, 200))
                ->setUuid($faker->numberBetween(100, 200))
                ->setTitle($faker->word())
                ->setCreatedAt(new DateTimeImmutable());

            $this->addReference
            (self::CATEGORY_REFERENCE . $i, $category);
            $manager->persist($category);
        }
        $manager->flush();
    }
}