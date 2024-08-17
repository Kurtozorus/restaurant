<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
{
    public function testApiDocUrlIsSuccessful(): void
    {
        $client = self::createClient();
        $client->request('GET', 'api/doc');

        self::assertResponseIsSuccessful();
    }
    public function testApiAccountUrlIsSecure(): void
    {
        $client = self::createClient();
        // $client->request('GET', 'api/account/me');
        $client->request('PUT', 'api/account/edit');

        self::assertResponseStatusCodeSame(401);
    }

    public function testLoginRouteCanConnectValideUser(): void
    {
        $client = self::createClient();
        $client->followRedirects(false);

        //  $client->request('POST', '/api/registration', [], [], [
        //      'Content-Type' => 'application/json',
        //  ], json_encode([
        //      "email"=> "adresse@email.com",
        //      "password"=> "Mot de passe",
        //      "firstName" => "Nom d'utilisateur",
        //      "lastName" => "prenom d'utilisateur",
        //      "guestNumber" => 50,
        //      "allergy" => "Cacahuètes"
        //  ], JSON_THROW_ON_ERROR));

         $client->request('POST', '/api/login', [], [], [
             'CONTENT_TYPE' => 'application/json',
         ], json_encode([
              "username" => "adresse@email.com",
              "password" => "Mot de passe"
         ], JSON_THROW_ON_ERROR));

        $statusCode = $client->getResponse()->getStatusCode();
        dd($statusCode);
    }
    
}