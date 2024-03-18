<?php

namespace App\Entity;

use App\Repository\MediasRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediasRepository::class)]
class Medias
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\ManyToOne(inversedBy: 'medias')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tricks $trick = null;

    #[ORM\ManyToOne(inversedBy: 'medias')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypesMedia $type_media = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getTrick(): ?tricks
    {
        return $this->trick;
    }

    public function setTrick(?tricks $trick): static
    {
        $this->trick = $trick;

        return $this;
    }

    public function getTypeMedia(): ?typesMedia
    {
        return $this->type_media;
    }

    public function setTypeMedia(?typesMedia $type_media): static
    {
        $this->type_media = $type_media;

        return $this;
    }
}
