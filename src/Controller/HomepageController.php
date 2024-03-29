<?php

namespace App\Controller;

use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomepageController extends AbstractController
{
    #[Route('/{limit}', methods: ['GET', 'HEAD'], name: 'homepage')]
    public function index(TricksRepository $repository, int $limit = 15): Response
    {
        // $limit = 15;
        $tricks = $repository->paginateTricks($limit);

        $countEntity = $tricks->count();

        return $this->render('homepage/index.html.twig', [
            'tricks' => $tricks,
            'limit' => $limit,
            'countEntity' => $countEntity
        ]);
    }
} 
