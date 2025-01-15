<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250115163423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD id_professeur_id INT DEFAULT NULL, ADD id_stagiaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64949AFF8C FOREIGN KEY (id_professeur_id) REFERENCES professeur (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649986848A4 FOREIGN KEY (id_stagiaire_id) REFERENCES stagiaire (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64949AFF8C ON user (id_professeur_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649986848A4 ON user (id_stagiaire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64949AFF8C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649986848A4');
        $this->addSql('DROP INDEX UNIQ_8D93D64949AFF8C ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649986848A4 ON user');
        $this->addSql('ALTER TABLE user DROP id_professeur_id, DROP id_stagiaire_id');
    }
}
