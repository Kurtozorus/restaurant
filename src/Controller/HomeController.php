<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/')]
    public function home() : Response
    {
        // Call la bdd
        // Envoyer un mail
        // créer un rendu $this->render()
	    return new Response('Bienvenue sur votre accueil !');
    }
}