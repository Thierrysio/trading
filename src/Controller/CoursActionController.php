<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CoursAction;

class CoursActionController extends AbstractController
{
    #[Route('/cours/action', name: 'app_cours_action')]
    public function index(): Response
    {
        return $this->render('cours_action/index.html.twig', [
            'controller_name' => 'CoursActionController',
        ]);
    }
    #[Route('/cours/action/variation/{id}', name: 'app_cours_action_variation')]
    public function showVariation(CoursAction $coursAction): Response
    {
        return $this->render('cours_action/showVariation.html.twig', [
            'monCoursAction' => $coursAction,
        ]);
    }

    #[Route('/cours/action/variation2/{id}', name: 'app_cours_action_variation2')]
    public function showVariation2(CoursAction $coursAction): Response
    {
        $variation = $coursAction->calculerVariationJournaliere();
        return $this->render('cours_action/showVariation2.html.twig', [
            'mavariation' => $variation,
        ]);
    }

}
