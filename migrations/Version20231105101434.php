<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231105101434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE badge_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE badge (id INT NOT NULL, etiquette VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE produit ADD badge_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit DROP image_name');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27F7A2C2FC FOREIGN KEY (badge_id) REFERENCES badge (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_29A5EC27F7A2C2FC ON produit (badge_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT FK_29A5EC27F7A2C2FC');
        $this->addSql('DROP SEQUENCE badge_id_seq CASCADE');
        $this->addSql('DROP TABLE badge');
        $this->addSql('DROP INDEX UNIQ_29A5EC27F7A2C2FC');
        $this->addSql('ALTER TABLE produit ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit DROP badge_id');
    }
}
