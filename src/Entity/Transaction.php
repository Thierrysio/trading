<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datetransaction = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'lestransactions')]
    private ?Trader $letrader = null;

    #[ORM\ManyToOne(inversedBy: 'lestransactions')]
    private ?Action $laaction = null;

    #[ORM\Column(length: 255)]
    private ?string $Operation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetransaction(): ?\DateTimeInterface
    {
        return $this->datetransaction;
    }

    public function setDatetransaction(\DateTimeInterface $datetransaction): static
    {
        $this->datetransaction = $datetransaction;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getLetrader(): ?Trader
    {
        return $this->letrader;
    }

    public function setLetrader(?Trader $letrader): static
    {
        $this->letrader = $letrader;

        return $this;
    }

    public function getLaaction(): ?Action
    {
        return $this->laaction;
    }

    public function setLaaction(?Action $laaction): static
    {
        $this->laaction = $laaction;

        return $this;
    }

    public function getOperation(): ?string
    {
        return $this->Operation;
    }

    public function setOperation(string $Operation): static
    {
        $this->Operation = $Operation;

        return $this;
    }
    public function getCoursTransaction(): ?float
    {
        if (!$this->laaction) {
            return null; // Aucune action associée à cette transaction
        }

        foreach ($this->laaction->getLescoursaction() as $coursAction)
         {
 if ($coursAction->getDatecoursaction()->format('Y-m-d') ===  $this->datetransaction->format('Y-m-d')) 
            {
                return $coursAction->getPrix();
            }
        }

        return null; // Aucun cours correspondant trouvé
    }

    public function getCoursTransactionAuPlusProche(): ?float
    {
        $coursAuPlusProche = 0.00;

        if (!$this->laaction) {
            return null; // Aucune action associée à cette transaction
        }
        else{
            $coursAuPlusProche = $this->laaction->getPrix();
        }

        foreach ($this->laaction->getLescoursaction() as $coursAction)
         {
            if ($coursAction->getDatecoursaction()->format('Y-m-d') ===  $this->datetransaction->format('Y-m-d')) 
            {
                return $coursAction->getPrix();
            }
            elseif($coursAction->getDatecoursaction()>$this->datetransaction)
            {
               return  $coursAuPlusProche;
            }
            else
            {
                $coursAuPlusProche = $coursAction->getPrix();
            }

        }

        return $coursAuPlusProche; // Aucun cours correspondant trouvé
    }

    public function calculerRentabilite(): ?float
    {
       

        $prixTransaction = $this->laaction->getCoursActionValide($this->datetransaction);
        
        $coursActuel = $this->laaction->GetDernierPrixAction();

        return $coursActuel - $prixTransaction;
    }

    public function getTypeTransaction(): ?string
    {
        return $this->Operation;
    }

    public function calculerMontantTotal(): ?float
    {
        return $this->laaction->getCoursActionValide($this->datetransaction) * $this->quantite;
    }

    public function verifierConformiteRisque(float $volatilite, int $qmax): ?bool
    {
        if($this->laaction->calculerVolatilite() > $volatilite || $qmax > $this->quantite) 
        return false;

        return true;
    }
    //Cette methode ne doit etre appelée qu'apres la transaction
    public function evaluerImpactDiversification():float
    {
        return $this->letrader->GetProportionAction($this->laaction);
    }

}
