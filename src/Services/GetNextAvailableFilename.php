<?php

namespace App\Services;

use App\Entity\Tricks;
use App\Repository\MediasRepository;

class GetNextAvailableFilename
{
    public function getNextAvailableFilename(
        int $trickID,
        string $ext,
        MediasRepository $repository,
        int $index
    ): string {
        $lastMedia = $repository->findLastPathByTrick($trickID);

        if ($lastMedia !== 'none') {
            // Get next number
            $nextNumber = explode("_", $lastMedia);
            $nextNumber = explode(".", $nextNumber[1]);

            $sequence = (int)$nextNumber[0];
            $sequence++;

            // Cast number to string with 2 digits
            $number = sprintf('%03d', $sequence);

            $nextName = 'img/tricks/' . $trickID . "_" . $number . "." . $ext;
        } else {
            $index = sprintf('%03d', $index);
            $nextName = 'img/tricks/' . $trickID . "_" . $index . "." . $ext;
        }

        return $nextName;
    }
}
