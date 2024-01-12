<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Action;
use App\Entity\CoursAction;
class CoursActionTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }
    public function testGetPlusHautPrixHistorique()
    {
        $prixMock = 100.0; // Prix pour le test

        // Créer un mock pour l'entité Action
        $actionMock = $this->createMock(Action::class);
        $actionMock->method('getCoursMax')->willReturn($prixMock);

        // Créer une instance de CoursAction et définir ses propriétés
        $coursAction = new CoursAction();
        $coursAction->setLaaction($actionMock);
        $coursAction->setPrix($prixMock);

        // Test lorsque le prix du CoursAction est le plus élevé
        $this->assertTrue($coursAction->getPlusHautPrixHistorique(), "getPlusHautPrixHistorique devrait retourner true lorsque le prix est le plus élevé historiquement");

        // Modifier le prix pour le test du cas où ce n'est pas le plus élevé
        $coursAction->setPrix($prixMock - 10);

        // Test lorsque le prix du CoursAction n'est pas le plus élevé
        $this->assertFalse($coursAction->getPlusHautPrixHistorique(), "getPlusHautPrixHistorique devrait retourner false lorsque le prix n'est pas le plus élevé historiquement");
    }
}
