<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116090606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE professeur CHANGE matricule matricule VARCHAR(6) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_17A5529912B2DC9C ON professeur (matricule)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_17A55299E7927C74 ON professeur (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_17A5529912B2DC9C ON professeur');
        $this->addSql('DROP INDEX UNIQ_17A55299E7927C74 ON professeur');
        $this->addSql('ALTER TABLE professeur CHANGE matricule matricule VARCHAR(60) NOT NULL');
    }
}
