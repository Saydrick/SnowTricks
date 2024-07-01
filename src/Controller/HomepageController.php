<?php

namespace App\Controller;

use App\Repository\MediasRepository;
use App\Repository\TricksRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomepageController extends AbstractController
{
    #[Route('/', methods: ['GET', 'HEAD'], name: 'default_route')]
    public function default(): Response
    {
        return $this->redirectToRoute('homepage');
    }


    #[Route('/home', methods: ['GET', 'HEAD'], name: 'homepage')]
    public function index(
        TricksRepository $tricksRepository,
        MediasRepository $mediasRepository,
        Request $request
    ): Response {
        $limit = $request->query->getInt('limit', 15);
        $tricks = $tricksRepository->paginateTricks($limit);
        $medias = [];

        foreach ($tricks as $trick) {
            $media = $mediasRepository->findOneByTrick($trick);
            $medias[$trick->getId()] = $media;
        }

        $countEntity = $tricks->count();

        return $this->render('homepage/index.html.twig', [
            'tricks' => $tricks,
            'medias' => $medias,
            'limit' => $limit,
            'countEntity' => $countEntity
        ]);
    }
}
