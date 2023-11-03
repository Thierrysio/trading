<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027141740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE action (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours_action (id INT AUTO_INCREMENT NOT NULL, laaction_id INT DEFAULT NULL, datecoursaction DATETIME NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_284C9DFA74573EC6 (laaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trader (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, letrader_id INT DEFAULT NULL, laaction_id INT DEFAULT NULL, datetransaction DATETIME NOT NULL, quantite INT NOT NULL, INDEX IDX_723705D1F2FDF806 (letrader_id), INDEX IDX_723705D174573EC6 (laaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cours_action ADD CONSTRAINT FK_284C9DFA74573EC6 FOREIGN KEY (laaction_id) REFERENCES action (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1F2FDF806 FOREIGN KEY (letrader_id) REFERENCES trader (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D174573EC6 FOREIGN KEY (laaction_id) REFERENCES action (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours_action DROP FOREIGN KEY FK_284C9DFA74573EC6');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1F2FDF806');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D174573EC6');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE cours_action');
        $this->addSql('DROP TABLE trader');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
