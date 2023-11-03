<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TraderRepository;
use App\Entity\Trader;

class TraderController extends AbstractController
{
    #[Route('/trader', name: 'app_trader')]
    public function index(): Response
    {
        return $this->render('trader/index.html.twig', [
            'controller_name' => 'TraderController',
        ]);
    }
    
    #[Route('/trader/{id}', name: 'trader_show')]
public function showTrader(Trader $trader): Response
{
    // Pas besoin de vérifier si le trader existe, Symfony s'en occupe
    return $this->render('trader/show.html.twig', [
        'trader' => $trader,
    ]);
}

#[Route('/traders', name: 'traders_list')]
public function listTraders(TraderRepository $traderRepository): Response
{
    $traders = $traderRepository->findAll();

    return $this->render('trader/list.html.twig', [
        'traders' => $traders,
    ]);
}
}
