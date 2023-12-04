<?php

namespace App\Entity;

use App\Repository\TraderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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

    public function __construct()
    {
        $this->lestransactions = new ArrayCollection();
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
public function GetProportionAction(Action $action): float
{

    
    return 0.00;
}

}
