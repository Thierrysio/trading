<?php

namespace App\Entity;

use App\Repository\HabilitationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabilitationRepository::class)]
class Habilitation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    function ajouterMois($dateDebut, $mois) {
        // Créer une instance de DateTime à partir de la date de début
        $date = new DateTime($dateDebut);
    
        // Ajouter le nombre de mois à la date
        $date->modify("+$mois months");
    
        // Retourner la date mise à jour sous forme de chaîne
        return $date->format('Y-m-d');
    }
}
