<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231102200050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD date_publication TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE article ADD date_mise_ajour TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE article ADD nombre_de_vues INT NOT NULL');
        $this->addSql('ALTER TABLE article RENAME COLUMN nom TO titre');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE article DROP date_publication');
        $this->addSql('ALTER TABLE article DROP date_mise_ajour');
        $this->addSql('ALTER TABLE article DROP nombre_de_vues');
        $this->addSql('ALTER TABLE article RENAME COLUMN titre TO nom');
    }
}
