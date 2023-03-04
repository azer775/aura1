<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221235156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affectations DROP FOREIGN KEY FK_420910415CF394A');
        $this->addSql('ALTER TABLE affectations DROP FOREIGN KEY FK_42091048AB13FB8');
        $this->addSql('DROP INDEX IDX_42091048AB13FB8 ON affectations');
        $this->addSql('DROP INDEX IDX_420910415CF394A ON affectations');
        $this->addSql('ALTER TABLE affectations ADD technicien_id INT DEFAULT NULL, ADD terrain_id INT DEFAULT NULL, DROP technicien_id_id, DROP terrain_id_id');
        $this->addSql('ALTER TABLE affectations ADD CONSTRAINT FK_420910413457256 FOREIGN KEY (technicien_id) REFERENCES technicien (id)');
        $this->addSql('ALTER TABLE affectations ADD CONSTRAINT FK_42091048A2D8B41 FOREIGN KEY (terrain_id) REFERENCES terrain (id)');
        $this->addSql('CREATE INDEX IDX_420910413457256 ON affectations (technicien_id)');
        $this->addSql('CREATE INDEX IDX_42091048A2D8B41 ON affectations (terrain_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affectations DROP FOREIGN KEY FK_420910413457256');
        $this->addSql('ALTER TABLE affectations DROP FOREIGN KEY FK_42091048A2D8B41');
        $this->addSql('DROP INDEX IDX_420910413457256 ON affectations');
        $this->addSql('DROP INDEX IDX_42091048A2D8B41 ON affectations');
        $this->addSql('ALTER TABLE affectations ADD technicien_id_id INT DEFAULT NULL, ADD terrain_id_id INT DEFAULT NULL, DROP technicien_id, DROP terrain_id');
        $this->addSql('ALTER TABLE affectations ADD CONSTRAINT FK_420910415CF394A FOREIGN KEY (technicien_id_id) REFERENCES technicien (id)');
        $this->addSql('ALTER TABLE affectations ADD CONSTRAINT FK_42091048AB13FB8 FOREIGN KEY (terrain_id_id) REFERENCES terrain (id)');
        $this->addSql('CREATE INDEX IDX_42091048AB13FB8 ON affectations (terrain_id_id)');
        $this->addSql('CREATE INDEX IDX_420910415CF394A ON affectations (technicien_id_id)');
    }
}
