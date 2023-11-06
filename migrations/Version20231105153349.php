<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231105153349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_29a5ec27f7a2c2fc');
        $this->addSql('CREATE INDEX IDX_29A5EC27F7A2C2FC ON produit (badge_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_29A5EC27F7A2C2FC');
        $this->addSql('CREATE UNIQUE INDEX uniq_29a5ec27f7a2c2fc ON produit (badge_id)');
    }
}
