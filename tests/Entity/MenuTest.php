<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Entity\Menu;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{
    public function testTheCategorySettingWhenAnMenuIsCreated(): void
    {
        $menu = new Menu();
        $this->assertNotNull($menu->getCategories());
    }

    public function testTheRestaurantSettingWhenAnMenuIsCreated(): void
    {
        $menu = new Menu();
        $this->assertNotNull($menu->getRestaurant());
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
        $category = new Category();
        $category->setUuid($uuid);

        $this->assertSame($uuid, $category->getUuid());
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
        $menu = new Menu();
        $menu->setTitle($title);

        $this->assertSame($title, $menu->getTitle());
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
        $menu = new Menu();
        $menu->setDescription($description);

        $this->assertSame($description, $menu->getDescription());
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
        $menu = new Menu();
        $menu->setPrice($price);

        $this->assertSame($price, $menu->getPrice());
    }
}