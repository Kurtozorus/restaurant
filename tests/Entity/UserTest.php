<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testTheAutomaticApiTokenSettingWhenAnUserIsCreated(): void
    {
        $user = new User();
        $this->assertNotNull($user->getApiToken());
    }

    public function testThanAnUserHasAtLeastOneRoleUser(): void
    {
        $user = new User();
        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public function testAnException(): void
    {
        $this->expectException(\TypeError::class);

        $user = new User();
        $user->setFirstName("10");
    }
    public function provideFirstName(): \Generator
    {
        yield ['Thomas'];
        yield ['Eric'];
        yield ['Marie'];
    }

    /** @dataProvider provideFirstName */
    public function testFirstNameSetter(string $firstName): void
    {
        $user = new User();
        $user->setFirstName($firstName);

        $this->assertSame($firstName, $user->getFirstName());
    }

    public function provideLastName(): \Generator
    {
        yield ['Moda'];
        yield ['Lore'];
        yield ['Dupont'];
    }

    /** @dataProvider provideLastName */
    public function testLastNameSetter(string $lastName): void
    {
        $user = new User();
        $user->setLastName($lastName);

        $this->assertSame($lastName, $user->getLastName());
    }
    public function provideEmail(): \Generator
    {
        yield ['Thomas@hotmail.fr'];
        yield ['Eric@hotmail.fr'];
        yield ['Marie@hotmail.fr'];
    }

    /** @dataProvider provideEmail */
    public function testEmailSetter(string $email): void
    {
        $user = new User();
        $user->setEmail($email);

        $this->assertSame($email, $user->getEmail());
    }
    public function provideGuestNumber(): \Generator
    {
        yield [5];
        yield [10];
        yield [8];
    }

    /** @dataProvider provideGuestNumber */
    public function testGuestNumberSetter(int $guestNumber): void
    {
        $user = new User();
        $user->setGuestNumber($guestNumber);

        $this->assertSame($guestNumber, $user->getGuestNumber());
    }

    public function providePassword(): \Generator
    {
        yield ['ejakmrallmkj'];
        yield ['aejkmrazkmek'];
        yield ['azerazjklmaz'];
    }

    /** @dataProvider providePassword */
    public function testPasswordSetter(string $password): void
    {
        $user = new User();
        $user->setPassword($password);

        $this->assertSame($password, $user->getPassword());
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
        $user = new User();
        $user->setAllergy($allergy);

        $this->assertSame($allergy, $user->getAllergy());
    }

}