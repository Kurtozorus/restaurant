<?php

namespace App\Tests\Entity;

use App\Entity\Restaurant;
use PHPUnit\Framework\TestCase;

class RestaurantTest extends TestCase
{
    public function testThePictureSettingWhenAnRestaurantIsCreated(): void
    {
        $restaurant = new Restaurant();
        $this->assertNotNull($restaurant->getPictures());
    }

    public function testTheBookingSettingWhenAnRestaurantIsCreated(): void
    {
        $restaurant = new Restaurant();
        $this->assertNotNull($restaurant->getBookings());
    }

    public function testTheMenueSettingWhenAnRestaurantIsCreated(): void
    {
        $restaurant = new Restaurant();
        $this->assertNotNull($restaurant->getMenus());
    }

    public function provideName(): \Generator
    {
        yield ['Le grillon'];
        yield ['Pizza Marcel'];
        yield ['Mc Donald'];
    }

    /** @dataProvider provideName */
    public function testNameSetter(string $name): void
    {
        $restaurant = new Restaurant();
        $restaurant->setName($name);

        $this->assertSame($name, $restaurant->getName());
    }
    
    public function provideDescription(): \Generator
    {
        yield ['Portion de frites 400gr'];
        yield ['400gr Streak cru'];
        yield ['Coupelle de mousse au chocolat maison'];
    }
    /** @dataProvider provideDescription */
    public function testDescriptionSetter(string $description): void
    {
        $restaurant = new Restaurant();
        $restaurant->setDescription($description);

        $this->assertSame($description, $restaurant->getDescription());
    }
    
    public function provideAmOpeningTime(): \Generator
    {
        yield [["07:30", "14:30"]];
        yield [["07:00", "13:30"]];
        yield [["07:30", "13:30"]];
    }
    /** @dataProvider provideAmOpeningTime */
    public function testAmOpeningTimeSetter(array $amOpeningTime): void
    {
        $restaurant = new Restaurant();
        $restaurant->setAmOpeningTime($amOpeningTime);

        $this->assertSame($amOpeningTime, $restaurant->getAmOpeningTime());
    }

    public function providePmOpeningTime(): \Generator
    {
        yield [["19:30", "22:30"]];
        yield [["20:00", "23:30"]];
        yield [["20:30", "23:30"]];
    }
    /** @dataProvider providePmOpeningTime */
    public function testPmOpeningTimeSetter(array $pmOpeningTime): void
    {
        $restaurant = new Restaurant();
        $restaurant->setPmOpeningTime($pmOpeningTime);

        $this->assertSame($pmOpeningTime, $restaurant->getPmOpeningTime());
    }

    public function provideMaxGuest(): \Generator
    {
        yield [50];
        yield [25];
        yield [100];
    }
    /** @dataProvider provideMaxGuest */
    public function testMaxGuest(Int $maxGuest): void
    {
        $restaurant = new Restaurant;
        $restaurant->setMaxGuest($maxGuest);

        $this->assertSame($maxGuest, $restaurant->getMaxGuest());
    }
}