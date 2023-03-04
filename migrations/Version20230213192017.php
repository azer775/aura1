<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213192017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achat (id INT AUTO_INCREMENT NOT NULL, facture_id INT DEFAULT NULL, membre_id INT DEFAULT NULL, produit_id INT DEFAULT NULL, nbr_piece INT NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_26A984567F2DEE08 (facture_id), INDEX IDX_26A984566A99F74A (membre_id), INDEX IDX_26A98456F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A984567F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A984566A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id)');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A984567F2DEE08');
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A984566A99F74A');
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456F347EFB');
        $this->addSql('DROP TABLE achat');
    }
}
