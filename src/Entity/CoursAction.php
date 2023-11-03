<?php

namespace App\Entity;

use App\Repository\CoursActionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursActionRepository::class)]
class CoursAction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datecoursaction = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\ManyToOne(inversedBy: 'lescoursaction')]
    private ?Action $laaction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatecoursaction(): ?\DateTimeInterface
    {
        return $this->datecoursaction;
    }

    public function setDatecoursaction(\DateTimeInterface $datecoursaction): static
    {
        $this->datecoursaction = $datecoursaction;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getLaaction(): ?Action
    {
        return $this->laaction;
    }

    public function setLaaction(?Action $laaction): static
    {
        $this->laaction = $laaction;

        return $this;
    }
}
