<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Form\TrickType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TrickController extends AbstractController
{
    #[Route('/tricks', name: 'tricks')]
    public function show(): Response
    {
        return $this->render('trick/index.html.twig', [
            'controller_name' => 'TrickController'
        ]);
    }

    #[Route('/tricks/ajouter', name: 'tricks.create')]
    public function create(Request $resquest, EntityManagerInterface $em): Response
    {
        $trick = new Tricks();
        $form = $this->createForm(TrickType::class, $trick, ['allow_extra_fields' => true]);
        $form->handleRequest($resquest);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($trick);
            $em->flush();
            $this->addFlash('success', 'Le trick a bien été créé !');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('trick/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('tricks/{id}/modifier', name: 'tricks.edit')]
    public function edit(Tricks $trick, Request $resquest, EntityManagerInterface $em)
    {

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'hello' => 'WIP',
        ]);
    }

    #[Route('tricks/{id}/supprimer', name: 'tricks.remove', methods: ['DELETE'])]
    public function remove(Tricks $trick, EntityManagerInterface $em)
    {
        $em->remove($trick);
        $em->flush();
        $this->addFlash('success', 'Le trick a bien été supprimé !');
        return $this->redirectToRoute('homepage');
    }
}
