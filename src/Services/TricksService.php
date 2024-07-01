<?php

namespace App\Services;

use App\Entity\Medias;
use App\Entity\Tricks;
use App\Repository\MediasRepository;
use App\Repository\TypesMediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TricksService extends AbstractController
{
    public function __construct(
        public TypesMediaRepository $tmrepository,
        public GetNextAvailableFilename $getNextAvailableFilename,
        public MediasRepository $mediasRepository,
        public EntityManagerInterface $em
    ) {
    }


    public function saveMedias(
        $mediaForm,
        Tricks $trick,
        $trickID,
        $index
    ): void {
        $mediaEntity = $mediaForm->getData();

        if (!empty($mediaEntity->getPath())) {
            $newMedia = new Medias();
        } else {
            $newMedia = null;
        }

        /** @var UploadedFile $mediaFiles */
        $mediaFiles = $mediaForm->get('mediaFile')->getData();

        foreach ($mediaFiles as $mediaFile) {
            if ($mediaFile instanceof UploadedFile && !empty($mediaFile)) {
                $ext = $mediaFile->getClientOriginalExtension();

                if ($ext === 'png' || $ext === 'jpg' || $ext === 'gif') {
                    $mediaType = $this->tmrepository->findOneByLabel('photo');
                } else {
                    $mediaType = $this->tmrepository->findOneByLabel('vidéo');
                }

                $mediaName = $this->getNextAvailableFilename->getNextAvailableFilename(
                    $trickID,
                    $ext,
                    $this->mediasRepository,
                    $index
                );

                $mediaFile->move($this->getParameter('kernel.project_dir') . '/public/img/tricks', $mediaName);

                $mediaPath = $mediaName;
                if ($mediaPath != null && !empty($mediaPath)) {
                    if ($newMedia) {
                        $newMedia->setTypeMedia($mediaType);
                        $newMedia->setTrick($trick);
                        $newMedia->setPath($mediaPath);

                        $this->em->persist($newMedia);
                    } else {
                        $mediaEntity->setTypeMedia($mediaType);
                        $mediaEntity->setTrick($trick);
                        $mediaEntity->setPath($mediaPath);

                        $this->em->persist($mediaEntity);
                    }
                }
            }
        }
    }
}
