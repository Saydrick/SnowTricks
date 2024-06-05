<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Form\TrickType;
use App\Entity\Comments;
use App\Entity\Medias;
use App\Form\CommentType;
use App\Repository\MediasRepository;
use App\Repository\TricksRepository;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{
    #[Route('/tricks/{id}-{slug}', name: 'tricks')]
    public function show(
        Tricks $trick, 
        CommentsRepository $commentsRepository, 
        MediasRepository $mediasRepository, 
        Request $request, 
        EntityManagerInterface $em
        ): Response
    {
        $trickID = $trick->getId();
        $trickSlug = $trick->getSlug();
        // Comments recovery
        $comments = $commentsRepository->findByRecentComments($trick);

        // Comments pagination
        $commentsPerPage = 5;
        $currentPage = $request->query->getInt('page', 1);
        $totalPages = ceil(count($comments) / $commentsPerPage);
        $startIndex = ($currentPage - 1) * $commentsPerPage;
        $currentComments = array_slice($comments, $startIndex, $commentsPerPage);

        // Medias recovery
        $firstMedia = $mediasRepository->findOneByTrick($trick);
        $medias = $mediasRepository->findAllMediasForTrickExceptFirst($trick);

        // dd($comments);

        // Add a comment form area
        $comment = new Comments();

        $commentForm = $this->createForm(CommentType::class, $comment, ['allow_extra_fields' => true]);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid())
        {
            $comment->setTrick($trick);
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Votre commentaire a bien été ajouté !');
            return $this->redirectToRoute('tricks', ['id' => $trickID, 'slug' => $trickSlug]);
        }

        // dd($firstMedia);

        return $this->render('trick/index.html.twig', [
            'trick' => $trick,
            'firstMedia' => $firstMedia,
            'medias' => $medias,
            'comments' => $currentComments,
            'commentForm' => $commentForm,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/tricks/ajouter', name: 'tricks.create')]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $trick = new Tricks();
        $form = $this->createForm(TrickType::class, $trick, ['allow_extra_fields' => true]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $mediaForms = $form->get('medias');

            $trickSlug = $trick->getSlug();

            // dd($trickSlug);

            foreach($mediaForms as $index => $mediaForm)
            {
                $mediaEntity = $mediaForm->getData();
                // dd($mediaEntity);
                /** @var UploadedFile $mediaFiles */
                $mediaFiles = $mediaForm->get('mediaFile')->getData();

                foreach($mediaFiles as $mediaFile)
                {
                    if ($mediaFile instanceof UploadedFile) 
                    {
                        $ext = $mediaFile->getClientOriginalExtension();
                        $trickName = $trick->getName();
                        $mediaName = $this->GetNextAvailableFilename($trickName, $this->getParameter('kernel.project_dir') . '/public/img/tricks') . '.' . $ext;

                        $mediaFile->move($this->getParameter('kernel.project_dir') . '/public/img/tricks', $mediaName);

                        $mediaPath = 'img/tricks/' . $mediaName;
                        $mediaEntity->setPath($mediaPath);
                        $mediaEntity->setTrick($trick);
                    }
                }
            }

            $em->persist($trick);
            $em->flush();

            $trickID = $trick->getId();
            $this->addFlash('success', 'Le trick a bien été créé !');
            return $this->redirectToRoute('tricks', ['id' => $trickID, 'slug' => $trickSlug]);
        }

        return $this->render('trick/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/tricks/{id}-{slug}/modifier', name: 'tricks.edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(
        Tricks $trick, 
        MediasRepository $mediasRepository, 
        Request $request, 
        EntityManagerInterface $em
        )
    {

        // Medias recovery
        $firstMedia = $mediasRepository->findOneByTrick($trick);
        $medias = $mediasRepository->findAllMediasForTrickExceptFirst($trick);

        // Display form
        $form = $this->createForm(TrickType::class, $trick, ['allow_extra_fields' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $trickName = $trick->getName();
            $trickID = $trick->getId();
            $trickSlug = $trick->getSlug();
            
            // Update medias
            // if($trickName !== $form->get('name')->getData())
            // {

            // }      

            // dd($form);
            $mediaForms = $form->get('medias');

            dd($mediaForms);

            foreach($mediaForms as $index => $mediaForm)
            {
                $media = new Medias;
                $mediaEntity = $mediaForm->getData();
                // dd($mediaEntity);

                /** @var UploadedFile $mediaFiles */
                $mediaFiles = $mediaForm->get('mediaFile')->getData();
                // dd($mediaFiles);
                
                foreach($mediaFiles as $mediaFile)
                {
                    // dd($mediaForm);
                    if ($mediaFile instanceof UploadedFile && !empty($mediaFile)) 
                    {
                        $mediaType = $mediaForm->get('type_media')->getData();
                        // dd($mediaType);

                        $ext = $mediaFile->getClientOriginalExtension();
                        $mediaName = $this->GetNextAvailableFilename($trickName, $this->getParameter('kernel.project_dir') . '/public/img/tricks') . '.' . $ext;

                        $mediaFile->move($this->getParameter('kernel.project_dir') . '/public/img/tricks', $mediaName);

                        $mediaPath = '/img/tricks/' . $mediaName;
                        $media->setTypeMedia($mediaType);
                        $media->setTrick($trick);
                        $media->setPath($mediaPath);
                        
                        
                        if($mediaPath !== null)
                        {
                            $em->persist($media);        
                            $em->flush();                
                        }
                        // dd($mediaPath);
                    }
                }
            }
            // die();

            // $em->flush();
            $this->addFlash('success', 'Le trick a bien été modifié !');
            return $this->redirectToRoute('tricks', ['id' => $trickID, 'slug' => $trickSlug]);
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'firstMedia' => $firstMedia,
            'medias' => $medias,
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

    private function getNextAvailableFilename($trickName, $mediaDirectory)
    {
        $index = 1;
        $trickName = strtolower(str_replace([' ', '\''], ['_', ''], $trickName));

        do {
            $filename = sprintf('%s_%d', $trickName, $index);
            $filePath = $mediaDirectory . '/' . $filename;
            $index++;
        } while (glob($filePath . '.*')); 

        return $filename;
    }
}
