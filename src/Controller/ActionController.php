<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ActionRepository;
use App\Entity\Action;
use App\Entity\Trader;
use App\Entity\Journalisation;
use App\Repository\TraderRepository;
use Doctrine\ORM\EntityManagerInterface;

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
    public function prixMoyens(ActionRepository $actionRepository, int $id,EntityManagerInterface $manager): Response
    {
        // Journaliser IP  IDUSER  DATE CIBLE ECHEC
        // $user_ip = $_SERVER['REMOTE_ADDR'];
        // $_SERVER['HTTP_X_FORWARDED_FOR'] - cette methode est falsifiable
        // entity : Journalisation

        // Recuperer les donnees
        $user_ip = $_SERVER['REMOTE_ADDR'];
        $user = $this->getUser();
         if ($user) {
            $userId = $user->getId();
            
        }
        $date_actuelle = date("Y-m-d");
        $cible = "PrixMoyen.html.twig";
        $echec = false;

    // Creer un objet journalisation
    $journalisation = new Journalisation();
    $journalisation->IdUser = $userId;



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

    #[Route('/action/getBilanGeneral/{r2}/{d2}', name:'app_bilan_general')] 
    public function getBilanGeneral(Trader $r2,Action $d2): Response
    {

        return $this->render('action/bilangeneral.html.twig',
         ['trader' =>  $r2,'action' =>  $d2 ]);

    }
}