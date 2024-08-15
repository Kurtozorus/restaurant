<?php

namespace App\Tests\Entity;

use App\Entity\Booking;
use DateTime;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;

class BookingTest extends TestCase
{
    public function provideGuestNumber(): \Generator
    {
        yield [5];
        yield [10];
        yield [8];
    }

    /** @dataProvider provideGuestNumber */
    public function testGuestNumberSetter(int $guestNumber): void
    {
        $booking = new Booking();
        $booking->setGuestNumber($guestNumber);

        $this->assertSame($guestNumber, $booking->getGuestNumber());
    }

    public function provideOrderDate(): \Generator
    {
        yield [new DateTime('2024-08-14')];
        yield [new DateTime('2026-05-24')];
        yield [new DateTime('2023-12-6')];
    }
    /** @dataProvider provideOrderDate */
    public function testOrderDateSetter(DateTimeInterface $orderDate): void
    {
        $booking = new Booking();
        $booking->setOrderDate($orderDate);

        $this->assertSame($orderDate, $booking->getOrderDate());
    }

    public function provideOrderHour(): \Generator
    {
        yield [new DateTime('21:00')];
        yield [new DateTime('12:45')];
        yield [new DateTime('19:30')];
    }
     /** @dataProvider provideOrderHour */
    public function testOrderHourSetter(DateTimeInterface $orderHour): void
    {
        $booking = new Booking();
        $booking->setOrderHour($orderHour);

        $this->assertSame($orderHour, $booking->getOrderHour());
    }
    public function provideAllergy(): \Generator
    {
        yield ['Fruit de mer'];
        yield ['Arachide'];
        yield ['Oeuf'];
    }

    /** @dataProvider provideAllergy */
    public function testAllergySetter(string $allergy): void
    {
        $booking = new Booking();
        $booking->setAllergy($allergy);

        $this->assertSame($allergy, $booking->getAllergy());
    }
}