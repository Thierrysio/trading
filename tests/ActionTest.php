<?php

namespace App\Tests;
use App\Entity\Action;
use App\Entity\Transaction;
use PHPUnit\Framework\TestCase;

class ActionTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }
    public function testGetVolumeTotalTransactionsParDate()
    {
        $action = new Action();
        $date = new \DateTime('2023-01-01');

        // Création de transactions fictives
        $transactionAchat = $this->createMock(Transaction::class);
        $transactionAchat->method('getDatetransaction')->willReturn($date);
        $transactionAchat->method('getQuantite')->willReturn(10);
        $transactionAchat->method('getOperation')->willReturn('achat');
        $action->addLestransaction($transactionAchat);

        $transactionVente = $this->createMock(Transaction::class);
        $transactionVente->method('getDatetransaction')->willReturn($date);
        $transactionVente->method('getQuantite')->willReturn(5);
        $transactionVente->method('getOperation')->willReturn('vente');
        $action->addLestransaction($transactionVente);

        // Vérification que le volume total est correct
        $this->assertEquals(5, $action->getVolumeTotalTransactionsParDate($date));
    }
}
