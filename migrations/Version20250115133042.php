<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250115133042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, code_matiere VARCHAR(60) NOT NULL, libelle VARCHAR(60) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere_stage (matiere_id INT NOT NULL, stage_id INT NOT NULL, INDEX IDX_4EDC3D1CF46CD258 (matiere_id), INDEX IDX_4EDC3D1C2298D193 (stage_id), PRIMARY KEY(matiere_id, stage_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professeur (id INT AUTO_INCREMENT NOT NULL, matiere_id INT DEFAULT NULL, matricule VARCHAR(60) NOT NULL, nom VARCHAR(60) NOT NULL, prenom VARCHAR(60) NOT NULL, email VARCHAR(60) NOT NULL, UNIQUE INDEX UNIQ_17A55299F46CD258 (matiere_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage (id INT AUTO_INCREMENT NOT NULL, code_stage VARCHAR(60) NOT NULL, libelle VARCHAR(60) NOT NULL, description LONGTEXT DEFAULT NULL, date_debut DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage_stagiaire (stage_id INT NOT NULL, stagiaire_id INT NOT NULL, INDEX IDX_7C690D102298D193 (stage_id), INDEX IDX_7C690D10BBA93DD6 (stagiaire_id), PRIMARY KEY(stage_id, stagiaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stagiaire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(60) NOT NULL, prenom VARCHAR(60) NOT NULL, adresse LONGTEXT DEFAULT NULL, code_postal VARCHAR(10) DEFAULT NULL, ville VARCHAR(60) DEFAULT NULL, date_inscription DATETIME NOT NULL, email VARCHAR(60) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matiere_stage ADD CONSTRAINT FK_4EDC3D1CF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE matiere_stage ADD CONSTRAINT FK_4EDC3D1C2298D193 FOREIGN KEY (stage_id) REFERENCES stage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE professeur ADD CONSTRAINT FK_17A55299F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE stage_stagiaire ADD CONSTRAINT FK_7C690D102298D193 FOREIGN KEY (stage_id) REFERENCES stage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stage_stagiaire ADD CONSTRAINT FK_7C690D10BBA93DD6 FOREIGN KEY (stagiaire_id) REFERENCES stagiaire (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matiere_stage DROP FOREIGN KEY FK_4EDC3D1CF46CD258');
        $this->addSql('ALTER TABLE matiere_stage DROP FOREIGN KEY FK_4EDC3D1C2298D193');
        $this->addSql('ALTER TABLE professeur DROP FOREIGN KEY FK_17A55299F46CD258');
        $this->addSql('ALTER TABLE stage_stagiaire DROP FOREIGN KEY FK_7C690D102298D193');
        $this->addSql('ALTER TABLE stage_stagiaire DROP FOREIGN KEY FK_7C690D10BBA93DD6');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE matiere_stage');
        $this->addSql('DROP TABLE professeur');
        $this->addSql('DROP TABLE stage');
        $this->addSql('DROP TABLE stage_stagiaire');
        $this->addSql('DROP TABLE stagiaire');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
