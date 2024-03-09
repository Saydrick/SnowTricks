<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomepageController extends AbstractController
{
    #[Route('/', methods: ['GET', 'HEAD'], name: 'homepage')]
    public function index(): Response
    {

        return $this->render('homepage/index.html.twig', [
            'test' => 'Ceci est la page d\'accueil !',
        ]);
    }
}
