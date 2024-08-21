<?php

namespace App\DataFixtures;

use App\Entity\Food;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class FoodFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $food = (new Food())
                ->setUuid(random_int(100,200))
                ->setTitle("Title$i")
                ->setDescription("Description$i")
                ->setPrice(random_int(5,20))
                ->setCreatedAt(new DateTimeImmutable());

            //associer des catégories au menu
            //Ici, on associe 1 catégorie aléatoire à chaque menu pour l'exemple
            for ($j = 1; $j <= 1; $j++) {
                $category = $this->getReference(CategoryFixtures::CATEGORY_REFERENCE . random_int(1, 20));
                $food->addCategory($category);
            }
            
            $manager->persist($food);
    }
        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }
}