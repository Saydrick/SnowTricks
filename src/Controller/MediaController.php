<?php

namespace App\Controller;

use App\Form\NewMediaType;
use App\Repository\MediasRepository;
use App\Repository\TricksRepository;
use App\Repository\TypesMediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MediaController extends AbstractController
{
    #[Route('/media-modifier/{id}', name: 'app_media_edit')]
    public function edit(
        int $id,
        MediasRepository $mediasRepository,
        TricksRepository $tricksRepository,
        TypesMediaRepository $tmrepository,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $media = $mediasRepository->findOneByID($id);
        
        $trick = $tricksRepository->findOneByID($media->getTrick());
        $trickID = $trick->getId();
        $trickSlug = $trick->getSlug();

        $mediaName = explode('/', $media->getPath());
        $mediaName = explode('.', $mediaName[2]);

        $mediaName = $mediaName[0];

        $form = $this->createForm(NewMediaType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $mediaFile */
            $mediaFiles = $form->get('mediaFile')->getData();

            foreach ($mediaFiles as $mediaFile) {
                if ($mediaFile instanceof UploadedFile && !empty($mediaFile)) {
                    $ext = $mediaFile->getClientOriginalExtension();
                    $mediaPath = 'img/tricks/' . $mediaName . '.' . $ext;

                    if ($ext === 'png' || $ext === 'jpg' || $ext === 'gif') {
                        $mediaType = $tmrepository->findOneByLabel('photo');
                    } else {
                        $mediaType = $tmrepository->findOneByLabel('vidéo');
                    }

                    // Remove existing file
                    $existing_files = glob($this->getParameter('kernel.project_dir') . '/public/img/tricks/' . $mediaName . '.*');
                    foreach ($existing_files as $existing_file) {
                        if (is_file($existing_file)) {
                            unlink($existing_file);
                        }
                    }
                    
                    $mediaFile->move($this->getParameter('kernel.project_dir') . '/public/img/tricks', $mediaName . '.' . $ext);

                    $media->setPath($mediaPath);
                    $media->setTypeMedia($mediaType);

                    $em->flush();        

                    $this->addFlash('success', 'Le média a bien été modifié !');    
                    return $this->redirectToRoute('tricks', ['id' => $trickID, 'slug' => $trickSlug]);
                }
            }
        }
        

        return $this->render('media/edit.html.twig', [
            'mediaForm' => $form,
        ]);
    }


    #[Route('/media-supprimer/{id}', name: 'app_media_delete')]
    public function delete(
        int $id,
        MediasRepository $mediasRepository,
        TricksRepository $tricksRepository,
        EntityManagerInterface $em
    ): Response
    {
        $media = $mediasRepository->findOneByID($id);
        $trick = $tricksRepository->findOneByID($media->getTrick());
        $trickID = $trick->getId();
        $trickSlug = $trick->getSlug();

        $mediaName = explode('/', $media->getPath());
        $mediaName = explode('.', $mediaName[2]);
        $mediaName = $mediaName[0];

        // Remove existing file from BDD
        $em->remove($media);
        $em->flush();

        // Remove existing file
        $existing_files = glob($this->getParameter('kernel.project_dir') . '/public/img/tricks/' . $mediaName . '.*');
        foreach ($existing_files as $existing_file) {
            if (is_file($existing_file)) {
                unlink($existing_file);
            }
        }

        $this->addFlash('success', 'Le média a bien été supprimé !');    
        return $this->redirectToRoute('tricks', ['id' => $trickID, 'slug' => $trickSlug]);
    }
}
