<?php

namespace App\Entity;

use App\Repository\MotDePasseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MotDePasseRepository::class)]
class MotDePasse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'lesMotsDePasse')]
    private ?Trader $leTrader = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getLeTrader(): ?Trader
    {
        return $this->leTrader;
    }

    public function setLeTrader(?Trader $leTrader): static
    {
        $this->leTrader = $leTrader;

        return $this;
    }

    private function verifierMdp() :bool
    {
        $resultat =  false;
// Calculer la longueur du mot de passe
$longueur = strlen($motDePasse);

// Compter le nombre de majuscules en utilisant une expression régulière
// [A-Z] correspond à n'importe quelle lettre majuscule de l'alphabet anglais
$majuscules = preg_match_all('/[A-Z]/', $motDePasse);

// Compter le nombre de minuscules
// [a-z] correspond à n'importe quelle lettre minuscule de l'alphabet anglais
$minuscules = preg_match_all('/[a-z]/', $motDePasse);

// Compter le nombre de chiffres
// \d est un raccourci pour [0-9], qui correspond à n'importe quel chiffre
$chiffres = preg_match_all('/\d/', $motDePasse);

// Compter le nombre de caractères spéciaux
// \W correspond à tout caractère qui n'est pas un mot (non-lettres, non-chiffres et non-underscore)
$caracteresSpeciaux = preg_match_all('/\W/', $motDePasse);

// Vérifier si tous les critères sont remplis
if ($longueur >= 12 && $majuscules >= 1 && $minuscules >= 3 && $chiffres >= 4 && $caracteresSpeciaux >= 1) {
    $resultat =  true;
} else {
$resultat = false;
}
        return $resultat;
    }

}
