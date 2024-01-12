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
    public function calculerVariationJournaliere(): ?float
{
    // 1 1 - retouver le cours d'hier
    //$dateHier = (clone $this->datecoursaction)->sub(new \DateInterval('P1D'));
    $dateHier = (clone $this->datecoursaction)->modify('-1 day');

   
    $coursHier = $this->laaction->getCoursActionValide($dateHier);
    
    if ($coursHier === null || $coursHier == 0) {
        return null; // Retourne null si les données de la veille ne sont pas disponibles ou si le cours est O
    }

    return (($this->prix - $coursHier) / $coursHier) * 100;

}

public function getPlusHautPrixHistorique() : bool
{
    return $this->laaction->getCoursMax() == $this->prix;
}

public function comparerAvecMoyenneMobile(int $param) : bool
{

    return $this->prix >= $this->laaction->getMoyenneMobile($this->datecoursaction,
    (clone $this->datecoursaction)->modify('-'.$param.' day'));

}

}
