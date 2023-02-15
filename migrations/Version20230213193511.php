<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213193511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE frais_energie (id INT AUTO_INCREMENT NOT NULL, membre_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, taux_energie DOUBLE PRECISION NOT NULL, date DATE NOT NULL, INDEX IDX_2F78218C6A99F74A (membre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE frais_energie ADD CONSTRAINT FK_2F78218C6A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE frais_energie DROP FOREIGN KEY FK_2F78218C6A99F74A');
        $this->addSql('DROP TABLE frais_energie');
    }
}
