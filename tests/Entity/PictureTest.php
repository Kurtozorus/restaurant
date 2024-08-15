<?php

namespace App\Tests\Entity;

use App\Entity\Menu;
use App\Entity\Picture;
use PHPUnit\Framework\TestCase;

class PictureTest extends TestCase
{
    public function testTheRestaurantSettingWhenAnPictureIsCreated(): void
    {
        $menu = new Menu();
        $this->assertNotNull($menu->getRestaurant());
    }

    public function provideTitle(): \Generator
    {
        yield ['Image du restaurant'];
        yield ['Image de dessert'];
        yield ['Image paela'];
    }
    /** @dataProvider provideTitle */
    public function testTitleSetter(string $title): void
    {
        $picture = new Picture();
        $picture->setTitle($title);

        $this->assertSame($title, $picture->getTitle());
    }
    
    public function provideSlug(): \Generator
    {
        yield ['Portion de frites'];
        yield ['Streak cru'];
        yield ['Coupelle de mousse au chocolat maison'];
    }
    /** @dataProvider provideTitle */
    public function testSlugSetter(string $slug): void
    {
        $picture = new Picture();
        $picture->setSlug($slug);

        $this->assertSame($slug, $picture->getSlug());
    }
}