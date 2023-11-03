<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GretaController extends AbstractController
{
    #[Route('/greta', name: 'app_greta')]
    public function index(): Response
    {
        return $this->render('greta/index.html.twig', [
            'controller_name' => 'GretaController','prenom' => 'thierry'
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
}
