<?php

namespace App\Controller;

use App\Repository\MediasRepository;
use App\Repository\TricksRepository;
use App\Form\NewMediaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MediaController extends AbstractController
{
    #[Route('/media-modifier/{id}', name: 'app_media_edit')]
    public function edit(
        $id,
        MediasRepository $mediasRepository,
        TricksRepository $tricksRepository,
        Request $request
    ) {
        dd($id);
        $media = $mediasRepository->findOneByID($id);

        $trick = $tricksRepository->findOneByID($media->getTrick());  // TODO Single result ?
        $trickID = $trick[0]->getId();
        $trickSlug = $trick[0]->getSlug();

        // dd($trick);

        $mediaName = explode('/', $media->getPath());
        $mediaName = explode('.', $mediaName[2]);

        $mediaName = $mediaName[0];
        $mediaPath = $media->getPath();

        $form = $this->createForm(NewMediaType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $mediaFile */
            $mediaFile = $form->get('mediaFile')->getData();
            $ext = $mediaFile->getClientOriginalExtension();

            // Remove existing file
            $existing_files = glob($this->getParameter('kernel.project_dir') . '/public/img/tricks/' . $mediaPath);
            foreach ($existing_files as $existing_file) {
                if (is_file($existing_file)) {
                    // unlink($existing_file);
                    dd($existing_file);
                }
            }

            // move le nouveau fichier avec le bon nom
            $mediaFile->move($this->getParameter('kernel.project_dir') . '/public/img/tricks', $mediaName . '.' . $ext);

            $this->addFlash('success', 'Le trick a bien été créé !');
            return $this->redirectToRoute('tricks', ['id' => $trickID, 'slug' => $trickSlug]); /* RECUPERER LE TRICKS */
        }


        return $this->render('media/edit.html.twig', [
            'mediaForm' => $form,
        ]);
    }


    #[Route('/media-supprimer/{id}', name: 'app_media_delete')]
    public function delete(): Response
    {




        return $this->render('media/delete.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }
}
