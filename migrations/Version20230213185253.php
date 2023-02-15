<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213185253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE affectations (id INT AUTO_INCREMENT NOT NULL, technicien_id_id INT DEFAULT NULL, terrain_id_id INT DEFAULT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, INDEX IDX_420910415CF394A (technicien_id_id), INDEX IDX_42091048AB13FB8 (terrain_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE affectations ADD CONSTRAINT FK_420910415CF394A FOREIGN KEY (technicien_id_id) REFERENCES technicien (id)');
        $this->addSql('ALTER TABLE affectations ADD CONSTRAINT FK_42091048AB13FB8 FOREIGN KEY (terrain_id_id) REFERENCES terrain (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affectations DROP FOREIGN KEY FK_420910415CF394A');
        $this->addSql('ALTER TABLE affectations DROP FOREIGN KEY FK_42091048AB13FB8');
        $this->addSql('DROP TABLE affectations');
    }
}
