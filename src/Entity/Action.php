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

    
}




