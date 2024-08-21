<?php

namespace App\DataFixtures;

use App\Entity\Category;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class CategoryFixtures extends Fixture
{
    public const CATEGORY_REFERENCE = 'category';

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $category = (new Category())
                ->setUuid(random_int(100, 200))
                ->setTitle("Title$i")
                ->setCreatedAt(new DateTimeImmutable());

            $this->addReference
            (self::CATEGORY_REFERENCE . $i, $category);
            $manager->persist($category);
        }
        $manager->flush();
    }
}