<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Tricks;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentsRepository;
use App\Repository\TricksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{
    #[Route('/tricks/{id}-{slug}', name: 'tricks')]
    public function show(Tricks $trick, CommentsRepository $repository, Request $resquest, EntityManagerInterface $em): Response
    {
        // Comments recovery
        $comments = $repository->findByRecentComments($trick);

        // dd($comments);

        // Add a comment form area
        $comment = new Comments();

        $commentForm = $this->createForm(CommentType::class, $comment, ['allow_extra_fields' => true]);
        $commentForm->handleRequest($resquest);
        if ($commentForm->isSubmitted() && $commentForm->isValid())
        {
            $comment->setTrick($trick);
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Votre commentaire a bien été ajouté !');
            return $this->redirectToRoute('homepage');
        }

        // dd($trick);

        return $this->render('trick/index.html.twig', [
            'trick' => $trick,
            'comments' => $comments,
            'commentForm' => $commentForm 
        ]);
    }

    #[Route('/tricks/ajouter', name: 'tricks.create')]
    #[IsGranted('ROLE_USER')]
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

    #[Route('/tricks/{id}-{slug}/modifier', name: 'tricks.edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(Tricks $trick, Request $resquest, EntityManagerInterface $em)
    {
        $form = $this->createForm(TrickType::class, $trick, ['allow_extra_fields' => true]);
        $form->handleRequest($resquest);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            $this->addFlash('success', 'Le trick a bien été modifié !');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'hello' => 'WIP',
            'form' => $form,
        ]);
    }

    #[Route('/tricks/{id}-{slug}/supprimer', name: 'tricks.remove', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function remove(Tricks $trick, EntityManagerInterface $em)
    {
        $em->remove($trick);
        $em->flush();
        $this->addFlash('success', 'Le trick a bien été supprimé !');
        return $this->redirectToRoute('homepage');
    }
}
