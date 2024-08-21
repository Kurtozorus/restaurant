<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class BookingFixtures extends Fixture implements DependentFixtureInterface
{
    private  function getRandomDate(): DateTimeImmutable
    {
        $timeStamp = random_int(
            strtotime('-30 days'),
            strtotime('now')
        );

        return new DateTimeImmutable(date('Y-m-d', $timeStamp));
    }
    private function getRandomHour(): DateTimeImmutable
    {
        $hour = random_int(8, 20);
        $minute = random_int(0, 59);

        return new DateTimeImmutable(sprintf('%s %02d:%02d:00', date('Y-m-d'), $hour, $minute));
    }
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $restaurant = $this->getReference("restaurant" . random_int(1, 20));
            $client = $this->getReference("client" . random_int(1, 20));
            $booking = (new Booking())
                ->setGuestNumber(random_int(0, 10))
                ->setOrderDate($this->getRandomDate())
                ->setOrderHour($this->getRandomHour())
                ->setRestaurant($restaurant)
                ->setClient($client)
                ->setAllergy("allergy$i")
                ->setCreatedAt(new DateTimeImmutable());

            $manager->persist($booking);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [RestaurantFixtures::class,
        UserFixtures::class,
            ] ;
        
    }
}