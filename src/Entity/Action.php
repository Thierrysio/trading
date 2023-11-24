<?php

namespace App\Entity;
use App\Entity\CoursAction;
use App\Repository\ActionRepository;
use App\Repository\TraderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActionRepository::class)]
class Action
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\OneToMany(mappedBy: 'laaction', targetEntity: Transaction::class)]
    private Collection $lestransactions;

    #[ORM\OneToMany(mappedBy: 'laaction', targetEntity: CoursAction::class)]
    private Collection $lescoursaction;

    public function __construct()
    {
        $this->lestransactions = new ArrayCollection();
        $this->lescoursaction = new ArrayCollection();
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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

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
            $lestransaction->setLaaction($this);
        }

        return $this;
    }

    public function removeLestransaction(Transaction $lestransaction): static
    {
        if ($this->lestransactions->removeElement($lestransaction)) {
            // set the owning side to null (unless already changed)
            if ($lestransaction->getLaaction() === $this) {
                $lestransaction->setLaaction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CoursAction>
     */
    public function getLescoursaction(): Collection
    {
        return $this->lescoursaction;
    }

    public function addLescoursaction(CoursAction $lescoursaction): static
    {
        if (!$this->lescoursaction->contains($lescoursaction)) {
            $this->lescoursaction->add($lescoursaction);
            $lescoursaction->setLaaction($this);
        }

        return $this;
    }

    public function removeLescoursaction(CoursAction $lescoursaction): static
    {
        if ($this->lescoursaction->removeElement($lescoursaction)) {
            // set the owning side to null (unless already changed)
            if ($lescoursaction->getLaaction() === $this) {
                $lescoursaction->setLaaction(null);
            }
        }

        return $this;
    }

    /**
     * Calcule le volume total des transactions pour cette action.
     *
     * @return int Le volume total des transactions.
     */
    public function getVolumeTotalTransactions(): int
    {
        $volumeTotal = 0;

        foreach ($this->lestransactions as $transaction) {
            $volumeTotal += $transaction->getQuantite();
        }

        return $volumeTotal;
    }

    public function getVolumeTotalTransactionsParDate(\DateTimeInterface $date): int
    {
        $volumeTotal = 0;

        foreach ($this->lestransactions as $transaction) {
            if ($transaction->getDatetransaction()->format('Y-m-d') === $date->format('Y-m-d')) {
                $quantite = $transaction->getQuantite();
                $operation = $transaction->getOperation();

                if ($operation === 'achat') {
                    $volumeTotal += $quantite;
                } elseif ($operation === 'vente') {
                    $volumeTotal -= $quantite;
                }
            }
        }

        return $volumeTotal;
    }
    
    /**
     * Calcule le prix moyen d'achat pour cette action.
     *
     * @return float Le prix moyen d'achat ou 0 si aucune transaction d'achat n'est enregistrée.
     */
    public function getPrixMoyenAchat(): float
    {
        $totalPrix = 0;
        $totalQuantite = 0;

        foreach ($this->lestransactions as $transaction) {
            if ($transaction->getOperation() === 'achat') {
                // Trouver le cours de l'action pour la date de la transaction
                $coursAction = $this->trouverCoursPourDate($transaction->getDatetransaction());
                if ($coursAction) {
                    $totalPrix += $coursAction->getPrix() * $transaction->getQuantite();
                    $totalQuantite += $transaction->getQuantite();
                }
            }
        }

        return $totalQuantite > 0 ? $totalPrix / $totalQuantite : 0;
    }

    public function getPrixMoyenVente(): float
    {
        $totalPrix = 0;
        $totalQuantite = 0;

        foreach ($this->lestransactions as $transaction) {
            if ($transaction->getOperation() === 'vente') {
                // Trouver le cours de l'action pour la date de la transaction
                $coursAction = $this->trouverCoursPourDate($transaction->getDatetransaction());
                if ($coursAction) {
                    $totalPrix += $coursAction->getPrix() * $transaction->getQuantite();
                    $totalQuantite += $transaction->getQuantite();
                }
            }
        }

        return $totalQuantite > 0 ? $totalPrix / $totalQuantite : 0;
    }

    public function getPrixMoyenAchatOuVente(string $monOperation): float
    {
        $totalPrix = 0;
        $totalQuantite = 0;

        foreach ($this->lestransactions as $transaction) {
            if ($transaction->getOperation() === $monOperation) {
                // Trouver le cours de l'action pour la date de la transaction
                $coursAction = $this->trouverCoursPourDate($transaction->getDatetransaction());
                if ($coursAction) {
                    $totalPrix += $coursAction->getPrix() * $transaction->getQuantite();
                    $totalQuantite += $transaction->getQuantite();
                }
            }
        }

        return $totalQuantite > 0 ? $totalPrix / $totalQuantite : 0;
    }

    /**
     * Trouve le cours de l'action pour une date donnée.
     *
     * @param \\DateTimeInterface $date La date de la transaction.
     * @return CoursAction|null Le cours de l'action pour cette date, ou null si aucun cours n'est trouvé.
     */
    private function trouverCoursPourDate(\DateTimeInterface $date): ?CoursAction
    {
        foreach ($this->lescoursaction as $coursAction) {
            if ($coursAction->getDatecoursaction()->format('Y-m-d') === $date->format('Y-m-d')) {
                return $coursAction;
            }
        }

        return null;
    }

    private function getPrixMoyenParOperation(string $typeOperation): float
    {
        $totalPrix = 0;
        $totalQuantite = 0;

        foreach ($this->lestransactions as $transaction) {
            if ($transaction->getOperation() === $typeOperation) {
                $coursAction = $this->trouverCoursPourDate($transaction->getDatetransaction());
                if ($coursAction) {
                    $totalPrix += $coursAction->getPrix() * $transaction->getQuantite();
                    $totalQuantite += $transaction->getQuantite();
                }
            }
        }

        return $totalQuantite > 0 ? $totalPrix / $totalQuantite : 0;
    }

    /**
     * Renvoie un tableau associatif avec le prix moyen des achats et des ventes.
     *
     * @return array Un tableau associatif contenant le prix moyen des achats et des ventes.
     */
    public function getPrixMoyens(): array
    {
        $prixMoyenAchats = $this->getPrixMoyenParOperation('achat');
        $prixMoyenVentes = $this->getPrixMoyenParOperation('vente');

        return [
            'prix_moyen_achats' => $prixMoyenAchats,
            'prix_moyen_ventes' => $prixMoyenVentes
        ];
    }
    public function GetDernierPrixAction() : float
    {
        $resultat = 0.0;

            foreach($this->lescoursaction as $leCoursAction)
            {
                $resultat = $leCoursAction->getPrix();
            }

        return $resultat;

    }

   /* public function GetDernierPrixActionOneLine(): float
    {
        return $this->lescoursaction[count($this->lescoursaction) - 1]->getPrix();
        //    return $this->lescoursaction[x - 1]->getPrix();
        //    return end($this->lescoursaction)->getPrix();


    }*/

    public function calculerVolumeAchats(\DateTimeInterface $debut, \DateTimeInterface $fin): int
    {
        $volumeTotal = 0;

        foreach ($this->lestransactions as $transaction) {
            // Vérifier si la transaction est un achat et si elle est dans la période donnée
            if ($transaction->getOperation() === 'achat' &&
                $transaction->getDatetransaction() >= $debut &&
                $transaction->getDatetransaction() <= $fin) 
                {
                $volumeTotal += $transaction->getQuantite();
            }
        }

        return $volumeTotal;
    }

    public function getPrixMoyen(string $operation):float
    {
        $resultat = 0.0;
        $montant = 0;
        $sommeDesQuantites = 0;

        foreach($this->lestransactions as $laTransaction)
        {
            if($laTransaction->getOperation() === $operation)
                {
                    $montant += $laTransaction->getCoursTransaction() *  $laTransaction->getQuantite() ;
                    $sommeDesQuantites += $laTransaction->getQuantite();

                }
        }

        if ($sommeDesQuantites>0)
        {
            $resultat = $montant/$sommeDesQuantites;
        }

        return $resultat;
    }

    public function getVolumeTransaction(string $param = null) :int
    {
        $volume = 0;
        //$volume = count($this->$lestransactions);

        foreach($this->lestransactions as $uneTransaction)
        {
           if($uneTransaction->getOperation() === $param || !$param )
           {
            $volume += $uneTransaction->getQuantite();
           }        
        }


        return $volume;
    }

    public function getBilan(Trader $trader) :float
    { 
        $bilan = 0.00;

        foreach($this->lestransactions as $uneTransaction)
        {
            if($uneTransaction->getLetrader() === $trader)
            {
                if($uneTransaction->getOperation() === "achat")
                {
                    $bilan += $uneTransaction->getQuantite()* $this->GetDernierPrixAction();
                }
                else
                {
                    $bilan -= $uneTransaction->getQuantite()* $this->GetDernierPrixAction();

                }
            }
        }

        return $bilan;

    }

    public function getBilanGeneral(Trader $trader) :float
    { 
        $bilan = 0.00;

        foreach($this->lestransactions as $uneTransaction)
        {
            if($uneTransaction->getLetrader() === $trader)
            {
                if($uneTransaction->getOperation() === "achat")
                {

                    $bilan -= $uneTransaction->getQuantite()*$uneTransaction->getCoursTransactionAuPlusProche();
                }
                else
                {
                    $bilan += $uneTransaction->getQuantite()*$uneTransaction->getCoursTransactionAuPlusProche();

                }
            }
        }
        return $bilan;
    }



}





