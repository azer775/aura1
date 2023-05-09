<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230316173745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE technicien CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE tel tel VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE specialite specialite VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE terrain DROP FOREIGN KEY FK_C87653B16A99F74A');
        $this->addSql('DROP INDEX IDX_C87653B16A99F74A ON terrain');
        $this->addSql('ALTER TABLE terrain CHANGE membre_id id_partenaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE terrain ADD CONSTRAINT FK_C87653B126F6C2C9 FOREIGN KEY (id_partenaire_id) REFERENCES partenaire (id)');
        $this->addSql('CREATE INDEX IDX_C87653B126F6C2C9 ON terrain (id_partenaire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE technicien CHANGE nom nom VARCHAR(30) NOT NULL, CHANGE prenom prenom VARCHAR(30) NOT NULL, CHANGE tel tel VARCHAR(8) NOT NULL, CHANGE email email VARCHAR(50) NOT NULL, CHANGE specialite specialite VARCHAR(15) NOT NULL');
        $this->addSql('ALTER TABLE terrain DROP FOREIGN KEY FK_C87653B126F6C2C9');
        $this->addSql('DROP INDEX IDX_C87653B126F6C2C9 ON terrain');
        $this->addSql('ALTER TABLE terrain CHANGE id_partenaire_id membre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE terrain ADD CONSTRAINT FK_C87653B16A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id)');
        $this->addSql('CREATE INDEX IDX_C87653B16A99F74A ON terrain (membre_id)');
    }
}
