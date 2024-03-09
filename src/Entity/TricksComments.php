<?php

namespace App\Entity;

use App\Repository\TricksCommentsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TricksCommentsRepository::class)]
class TricksComments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $trick_id = null;

    #[ORM\Column]
    private ?int $comment_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrickId(): ?int
    {
        return $this->trick_id;
    }

    public function setTrickId(int $trick_id): static
    {
        $this->trick_id = $trick_id;

        return $this;
    }

    public function getCommentId(): ?int
    {
        return $this->comment_id;
    }

    public function setCommentId(int $comment_id): static
    {
        $this->comment_id = $comment_id;

        return $this;
    }
}
