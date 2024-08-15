<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testTheMenuSettingWhenAnCategoryIsCreated(): void
    {
        $category = new Category();
        $this->assertNotNull($category->getMenu());
    }
    public function testTheFoodSettingWhenAnCategoryIsCreated(): void
    {
        $category = new Category();
        $this->assertNotNull($category->getFood());
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
        yield ['Hamburger'];
        yield ['Soupe'];
        yield ['Dessert'];
    }
    /** @dataProvider provideTitle */
    public function testTitleSetter(string $title): void
    {
        $category = new Category();
        $category->setTitle($title);

        $this->assertSame($title, $category->getTitle());
    }
}