<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    #[Route('/profil/{username}', name: 'app_profil')]
    public function index(
        Users $user,
    ): Response {
        return $this->render('profil/index.html.twig', [
            'user' => $user
        ]);
    }


    #[Route('/profil/modifie/{username}', name: 'app_profil_edit')]
    public function edit(
        Users $user,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(UserType::class, $user, ['allow_extra_fields' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $mediaFiles */
            $file = $form->get('mediaFile')->getData();

            if (!empty($file)) {
                $fileExt = $file->getClientOriginalExtension();
                $fileName = $user->getId() . '.' . $fileExt;
                $filePath = 'img/users/' . $fileName;

                // Remove existing file
                $existing_files = glob(
                    $this->getParameter('kernel.project_dir')
                    . '/public/img/users/'
                    . $user->getId()
                    . '.*'
                );
                foreach ($existing_files as $existing_file) {
                    if (is_file($existing_file)) {
                        unlink($existing_file);
                    }
                }

                // Save new file
                $file->move($this->getParameter('kernel.project_dir') . '/public/img/users/', $fileName);
                $user->setPhoto($filePath);
                $em->flush();
                $this->addFlash('success', 'Votre photo de profil a été mise à jour');

                return $this->redirectToRoute('app_profil', ['username' => $user->getUsername()]);
            } else {
                $this->addFlash('danger', 'Aucune image n\'a été envoyée');
                return $this->redirectToRoute('app_profil', ['username' => $user->getUsername()]);
            }
        }

        return $this->render('profil/edit.html.twig', [
            'user' => $user,
            'user_form' => $form
        ]);
    }
}
