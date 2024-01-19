<?php

namespace App\Utilitaires;

use App\Entity\Trader;
use App\Repository\TraderRepository;

class UtilTrader
{
    private $traderRepository;

    public function __construct(TraderRepository $TraderRepository) {
        
        $this->traderRepository = $TraderRepository;

    }

    public function getMaillonFaible() : ?Trader 
    {
        // Initialisation du totaliseur au plus grand entier supporté par PHP.
        $Totaliseur = PHP_INT_MAX;
    
        // Création d'un nouvel objet Trader vide.
        $leTraderMaillonFaible = new Trader();
    
        // Récupération de tous les traders à partir du TraderRepository.
        
        $lesTraders = $this->traderRepository->findAll();
    
        // Parcours de chaque trader récupéré.
        foreach($lesTraders as $unTrader)
        {
            // Si le volume total des transactions d'achat de ce trader 
            // est inférieur à la valeur actuelle du totaliseur...
            if($unTrader->getVolumeTotalTransactionsAchat() < $Totaliseur)
            {
                // ...mettre à jour le totaliseur avec ce nouveau volume total inférieur...
                $Totaliseur = $unTrader->getVolumeTotalTransactionsAchat();
    
                // ...et désigner ce trader comme le "maillon faible" actuel.
                $leTraderMaillonFaible = $unTrader;
            }
        }
    
        // Retourner le trader avec le volume total le plus faible de transactions d'achat.
        return $leTraderMaillonFaible;
    }
    
}