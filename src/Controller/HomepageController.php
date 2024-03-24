<?php

namespace App\Controller;

use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomepageController extends AbstractController
{
    #[Route('/', methods: ['GET', 'HEAD'], name: 'homepage')]
    public function index(TricksRepository $repository): Response
    {
        $tricks = $repository->findAll();

        return $this->render('homepage/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }
} 
