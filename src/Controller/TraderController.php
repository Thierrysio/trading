<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TraderRepository;
use App\Entity\Trader;
use App\Utilitaires\UtilTrader;

class TraderController extends AbstractController
{
    #[Route('/trader', name: 'app_trader')]
    public function index(): Response
    {
        return $this->render('trader/index.html.twig', [
            'controller_name' => 'TraderController',
        ]);
    }

    #[Route('/trader/historique/{id}', name: 'app_trader_historique')]
    public function traderhistorique(Trader $trader): Response
    {
        return $this->render('trader/historique.html.twig', [ 'trader' => $trader,  ]);
    }
   
    #[Route('/trader/diversificationPortfolio/{id}', name: 'app_trader_diversification_Portfolio')]
    public function diversificationPortfolio(Trader $trader): Response
    {
        return $this->render('trader/diversification.html.twig', [ 'trader' => $trader,  ]);
    }

    #[Route('/trader/testmaillon', name: 'app_testmaillon')]
    public function testmaillon(TraderRepository $traderRepository,UtilTrader $leUtilTrader): Response
    {
        $leTrader = $traderRepository->find(1);
        $lemaillonFaible = $leUtilTrader->getMaillonFaible(); 

dd($lemaillonFaible);
        return $this->render('trader/testmaillon.html.twig');
    }
    
}
