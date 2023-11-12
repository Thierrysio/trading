<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ActionRepository;
use App\Entity\Action;

class ActionController extends AbstractController
{
    #[Route('/action', name: 'app_action')]
    public function index(): Response
    {
        return $this->render('action/index.html.twig', [
            'controller_name' => 'ActionController',
        ]);
    }
    // src/Controller/ActionController.php


 #[Route('/actions', name: 'actions_list')]
    public function listActions(ActionRepository $actionRepository): Response
    {
        $actions = $actionRepository->findAll();

        return $this->render('action/list.html.twig', [
            'actions' => $actions,
        ]);
    }

    #[Route('/action/{id}/cours-moyen', name: 'action_cours_moyen')]
public function coursMoyen(Action $action): Response 
{
    $moyenne = $action->calculerCoursMoyen();

    return $this->render('action/cours_moyen.html.twig', [
        'action' => $action,
        'moyenne' => $moyenne,
    ]);
}


}
