<?php

namespace App\DataFixtures;

use App\Entity\Menu;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class MenuFixtures extends Fixture implements DependentFixtureInterface
{
    public const MENU_NB_TUPLES = 20;
    
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= self::MENU_NB_TUPLES; $i++) {
            $restaurant = $this->getReference("restaurant" . random_int(1, 20));
            $menu = (new Menu())
                ->setTitle("Title$i")
                ->setDescription("Description$i")
                ->setPrice(random_int(15, 60))
                ->setRestaurant($restaurant)
                ->setCreatedAt(new DateTimeImmutable());
            
            //associer des catégories au menu
            //Ici, on associe 1 catégorie aléatoire à chaque menu pour l'exemple
            for ($j = 1; $j <= 1; $j++) {
                $category = $this->getReference(CategoryFixtures::CATEGORY_REFERENCE . random_int(1, 20));
                $menu->addCategory($category);
            }

            $manager->persist($menu);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [RestaurantFixtures::class,
                CategoryFixtures::class,];
    }
}