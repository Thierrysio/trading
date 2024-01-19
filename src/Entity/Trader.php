<?php

namespace App\Entity;

use App\Repository\TraderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Utilitaires\UtilTrader;

#[ORM\Entity(repositoryClass: TraderRepository::class)]
class Trader
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\OneToMany(mappedBy: 'letrader', targetEntity: Transaction::class)]
    private Collection $lestransactions;

    #[ORM\OneToMany(mappedBy: 'leTrader', targetEntity: MotDePasse::class)]
    private Collection $lesMotsDePasse;

    public function __construct()
    {
        $this->lestransactions = new ArrayCollection();
        $this->lesMotsDePasse = new ArrayCollection();
    }

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getLestransactions(): Collection
    {
        return $this->lestransactions;
    }

    public function addLestransaction(Transaction $lestransaction): static
    {
        if (!$this->lestransactions->contains($lestransaction)) {
            $this->lestransactions->add($lestransaction);
            $lestransaction->setLetrader($this);
        }

        return $this;
    }

    public function removeLestransaction(Transaction $lestransaction): static
    {
        if ($this->lestransactions->removeElement($lestransaction)) {
            // set the owning side to null (unless already changed)
            if ($lestransaction->getLetrader() === $this) {
                $lestransaction->setLetrader(null);
            }
        }

        return $this;
    }
    // Dans la classe Trader

/**
 * Calcule le montant total investi par le trader.
 *
 * @return float Le montant total investi.
 */
public function calculerMontantTotalInvesti(): float
{
    $montantTotal = 0.0;

    foreach ($this->lestransactions as $transaction) {
        if ($transaction->getOperation() === 'achat') {
            $prixAchat = $transaction->getCoursTransaction(); // Supposons que cette méthode renvoie le prix d'achat par action
            $montantTotal += $prixAchat * $transaction->getQuantite();
        }
    }

    return $montantTotal;
}

public function calculerValeurPortfolio(): float
{
    $soldeActions = [];

    // Calculer le solde pour chaque action (achats - ventes)
    foreach ($this->lestransactions as $transaction) {
        $action = $transaction->getLaaction();
        if (!$action) {
            continue;
        }

        $actionId = $action->getId();
        $quantite = $transaction->getQuantite();
        $operation = $transaction->getOperation();

        if (!isset($soldeActions[$actionId])) {
            $soldeActions[$actionId] = 0;
        }

        if ($operation === 'achat') {
            $soldeActions[$actionId] += $quantite;
        } elseif ($operation === 'vente') {
            $soldeActions[$actionId] -= $quantite;
        }
    }

    // Calculer la valeur totale du portfolio
    $valeurTotale = 0.0;
    foreach ($soldeActions as $actionId => $solde) {
        $action = // récupérer l'entité Action par son id
        $dernierCours = $action->GetDernierPrixAction();
        $valeurTotale += $dernierCours * $solde;
    }

    return $valeurTotale;
}
public function calculerValeurPortfolio2(): float
{
    $soldeActions = [];

    foreach ($this->lestransactions as $transaction) {
        $action = $transaction->getLaaction();
        if (!$action) {
            continue;
        }

        $actionId = $action->getId();
        if (!isset($soldeActions[$actionId])) {
            $soldeActions[$actionId] = ['action' => $action, 'solde' => 0];
        }

        $quantite = $transaction->getQuantite();
        if ($transaction->getOperation() === 'achat') {
            $soldeActions[$actionId]['solde'] += $quantite;
        } elseif ($transaction->getOperation() === 'vente') {
            $soldeActions[$actionId]['solde'] -= $quantite;
        }
    }

    $valeurTotale = 0.0;
    foreach ($soldeActions as $actionInfo) {
        $valeurTotale += $actionInfo['action']->GetDernierPrixAction() * $actionInfo['solde'];
    }

    return $valeurTotale;
}
public function getPerteEtProfit(): ?float
{
    $perteEtProfit = 0;

    

    return $perteEtProfit;
}

public function getHistoriqueTransaction():Collection
{
    return $this->lestransactions;
}

public function getDiversificationPortfolio(): array
{
    $Portfolio = [];

    foreach ($this->lestransactions as $laTransaction)
     {
        if (array_key_exists( $laTransaction->getLaaction()->getNom(), $Portfolio))
        {
            if( $laTransaction->getOperation() === "achat")
            {
                $Portfolio[$laTransaction->getLaaction()->getNom()]+= $laTransaction->getQuantite();
            }
            else
            {
                $Portfolio[$laTransaction->getLaaction()->getNom()]-= $laTransaction->getQuantite();
            }
        }
        else
        {
            $Portfolio[$laTransaction->getLaaction()->getNom()]= $laTransaction->getQuantite();

        }
     }
    
    return $Portfolio;

}
public function getVolumeTotalTransactionsAchat(): int
    {
        $volumeTotal = 0;

        foreach ($this->lestransactions as $transaction)
         {
            if($transaction->getOperation	() === "achat")
            {
                $volumeTotal += $transaction->getQuantite();  
            }
     
        }

        return $volumeTotal;
    }

    public function suisJeLeMaillonFaible(UtilTrader $leUtilTrader):bool
    {
        if($this == $leUtilTrader->getMaillonFaible())
        {
            return true;
        }
        return false;

    }
public function getVolumeTotalTransactions(): int
    {
        $volumeTotal = 0;

        foreach ($this->lestransactions as $transaction)
         {
            if($transaction->getOperation	() === "achat")
            {
                $volumeTotal += $transaction->getQuantite();  
            }
            else
            {
                $volumeTotal -= $transaction->getQuantite();

            }
     
        }

        return $volumeTotal;
    }
    public function getVolumeTotalTransactionsParAction(Action $action): int
    {
        $volumeTotal = 0;

        foreach ($this->lestransactions as $transaction)
         {
                if($transaction->getLaaction()===$action)
                {
                    if($transaction->getOperation	() === "achat")
                        {
                            $volumeTotal += $transaction->getQuantite();  
                        }
                    else
                        {
                            $volumeTotal -= $transaction->getQuantite();
                        }
            }
        }

        return $volumeTotal;
    }
public function GetProportionAction(Action $action): float
{
    return $this->getVolumeTotalTransactionsParAction($action)/$this->getVolumeTotalTransactions();
}

/**
 * @return Collection<int, MotDePasse>
 */
public function getLesMotsDePasse(): Collection
{
    return $this->lesMotsDePasse;
}

public function addLesMotsDePasse(MotDePasse $lesMotsDePasse): static
{
    if (!$this->lesMotsDePasse->contains($lesMotsDePasse)) {
        $this->lesMotsDePasse->add($lesMotsDePasse);
        $lesMotsDePasse->setLeTrader($this);
    }

    return $this;
}

public function removeLesMotsDePasse(MotDePasse $lesMotsDePasse): static
{
    if ($this->lesMotsDePasse->removeElement($lesMotsDePasse)) {
        // set the owning side to null (unless already changed)
        if ($lesMotsDePasse->getLeTrader() === $this) {
            $lesMotsDePasse->setLeTrader(null);
        }
    }

    return $this;
}

public function ExisteMotDePasse(string $motDePasse) : bool
{
    $resultat = false;

    foreach($this->lesMotsDePasse as $leMotDePasse)
    {
        if($leMotDePasse->getNom()==$motDePasse) 
        {
            $resultat = true;
        }

    }
    return $resultat;
}

public function GenererNewMotDePasse($motdepasse) : bool
{
    // Création d'une nouvelle instance de la classe MotDePasse
    $newObjet = new MotDePasse();

    // Définition du nom (dans ce cas, il s'agit du mot de passe) de l'objet
    $newObjet->setNom($motdepasse);

    // Vérification de deux conditions :
    // 1. Le mot de passe répond-il aux critères définis dans la méthode verifierMdp() de l'objet MotDePasse ?
    // 2. Le mot de passe n'existe pas déjà (vérifié par la méthode ExisteMotDePasse).
    if ($newObjet->verifierMdp() && $this->ExisteMotDePasse($motdepasse) == false)
    {
        // Si les deux conditions sont remplies, définir la date actuelle pour l'objet.
        // Ici, on utilise la classe \DateTime de PHP pour obtenir la date et l'heure actuelles.
        $newObjet->setDate(new \DateTime());

        // Retourne true pour indiquer que le nouveau mot de passe a été accepté et généré.
        return true;
    }
    else
    {
        // Si l'une des conditions n'est pas remplie (mot de passe invalide ou déjà existant),
        // détruit l'objet en affectant null à la variable $newObjet.
        $newObjet = null;

        // Retourne false pour indiquer que le mot de passe n'a pas été accepté.
        return false;
    }
}


}
