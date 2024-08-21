<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;


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

    public const BOOKING_NB_TUPLES = 20;

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1; $i <= self::BOOKING_NB_TUPLES; $i++) {
            $restaurant = $this->getReference(RestaurantFixtures::RESTAURANT_REFERENCE . random_int(1, 20));
            $client = $this->getReference(UserFixtures::USER_REFERENCE . random_int(1, 20));
            $booking = (new Booking())
                // ->setGuestNumber(random_int(0, 10))
                ->setGuestNumber($faker->numberBetween(0, 10))
                // ->setOrderDate($this->getRandomDate())
                ->setOrderDate($faker->dateTimeBetween('now', '+1 year'))
                // ->setOrderHour($this->getRandomHour())
                ->setOrderHour($faker->dateTimeBetween('09:00:00', '20:00:00'))
                ->setRestaurant($restaurant)
                ->setClient($client)
                // ->setAllergy("allergy$i")
                ->setAllergy($faker->word())
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