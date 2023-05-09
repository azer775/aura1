<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503000247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin ADD photo VARCHAR(255) NOT NULL, ADD is_active INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire CHANGE date date DATE NOT NULL');
        $this->addSql('ALTER TABLE membre ADD photo VARCHAR(255) NOT NULL, ADD is_active INT NOT NULL');
        $this->addSql('ALTER TABLE partenaire ADD photo VARCHAR(255) NOT NULL, ADD is_active INT NOT NULL');
        $this->addSql('ALTER TABLE post CHANGE nbr_vue nbr_vue INT NOT NULL, CHANGE date_creation date_creation DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin DROP photo, DROP is_active');
        $this->addSql('ALTER TABLE commentaire CHANGE date date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE membre DROP photo, DROP is_active');
        $this->addSql('ALTER TABLE partenaire DROP photo, DROP is_active');
        $this->addSql('ALTER TABLE post CHANGE nbr_vue nbr_vue INT DEFAULT NULL, CHANGE date_creation date_creation DATE DEFAULT NULL');
    }
}
