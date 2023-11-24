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
            'controller_name' => 'katia',
        ]);
    }
    // src/Controller/ActionController.php
    #[Route('/action/prixmoyens/{id}', name: 'action_prix_moyens')]
    public function prixMoyens(ActionRepository $actionRepository, int $id): Response
    {
        // Récupérer l'entité Action
        $action = $actionRepository->find($id);

        // Vérifier si l'action existe
        if (!$action) {
            throw $this->createNotFoundException('Aucune action trouvée pour l\'id ' . $id);
        }

        // Calculer les prix moyens
        $prixMoyens = $action->getPrixMoyens();

        // Envoyer les données à Twig
        return $this->render('action/PrixMoyen.html.twig', [
            'prixMoyens' => $prixMoyens,
        ]);
    }

    #[Route('/action/getVolume', name:'app_volume')] 
    public function getVolume(ActionRepository $actionRepository): Response
    {
        $action = $actionRepository->find(1);
        $volume = $action->getVolumeTransaction();

        return $this->render('action/volume.html.twig', ['leVolume' => $volume]);
    }
}