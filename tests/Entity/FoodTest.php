<?php

namespace App\Tests\Entity;

use App\Entity\Food;
use PHPUnit\Framework\TestCase;

class FoodTest extends TestCase
{
    public function testTheCategorySettingWhenAnCategoryIsCreated(): void
    {
        $food= new Food();
        $this->assertNotNull($food->getCategory());
    }

    public function provideUuid(): \Generator
    {
        yield ['500'];
        yield ['125'];
        yield ['829'];
    }

    /** @dataProvider provideUuid */
    public function testUuidSetter(string $uuid): void
    {
        $food = new Food();
        $food->setUuid($uuid);

        $this->assertSame($uuid, $food->getUuid());
    }

    public function provideTitle(): \Generator
    {
        yield ['Barquette de frite'];
        yield ['Steak tartare'];
        yield ['Mousse au chocolat'];
    }
    /** @dataProvider provideTitle */
    public function testTitleSetter(string $title): void
    {
        $food = new Food();
        $food->setTitle($title);

        $this->assertSame($title, $food->getTitle());
    }
    
    public function provideDescription(): \Generator
    {
        yield ['Portion de frites 400gr'];
        yield ['400gr Streak cru'];
        yield ['Coupelle de mousse au chocolat maison'];
    }
    /** @dataProvider provideTitle */
    public function testDescriptionSetter(string $description): void
    {
        $food = new Food();
        $food->setDescription($description);

        $this->assertSame($description, $food->getDescription());
    }
    
    public function providePrice(): \Generator
    {
        yield [7];
        yield [5];
        yield [4];
    }
    /** @dataProvider providePrice */
    public function testPriceSetter(Int $price)
    {
        $food = new Food();
        $food->setPrice($price);

        $this->assertSame($price, $food->getPrice());
    }
}