<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230222191559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY FK_F8F081D9EAAC4B6D');
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY FK_F8F081D9D27F8253');
        $this->addSql('DROP INDEX IDX_F8F081D9EAAC4B6D ON don');
        $this->addSql('DROP INDEX IDX_F8F081D9D27F8253 ON don');
        $this->addSql('ALTER TABLE don ADD membre_id INT DEFAULT NULL, ADD association_id INT DEFAULT NULL, DROP id_membre_id, DROP id_assoc_id');
        $this->addSql('ALTER TABLE don ADD CONSTRAINT FK_F8F081D96A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id)');
        $this->addSql('ALTER TABLE don ADD CONSTRAINT FK_F8F081D9EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('CREATE INDEX IDX_F8F081D96A99F74A ON don (membre_id)');
        $this->addSql('CREATE INDEX IDX_F8F081D9EFB9C8A5 ON don (association_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY FK_F8F081D96A99F74A');
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY FK_F8F081D9EFB9C8A5');
        $this->addSql('DROP INDEX IDX_F8F081D96A99F74A ON don');
        $this->addSql('DROP INDEX IDX_F8F081D9EFB9C8A5 ON don');
        $this->addSql('ALTER TABLE don ADD id_membre_id INT DEFAULT NULL, ADD id_assoc_id INT DEFAULT NULL, DROP membre_id, DROP association_id');
        $this->addSql('ALTER TABLE don ADD CONSTRAINT FK_F8F081D9EAAC4B6D FOREIGN KEY (id_membre_id) REFERENCES membre (id)');
        $this->addSql('ALTER TABLE don ADD CONSTRAINT FK_F8F081D9D27F8253 FOREIGN KEY (id_assoc_id) REFERENCES association (id)');
        $this->addSql('CREATE INDEX IDX_F8F081D9EAAC4B6D ON don (id_membre_id)');
        $this->addSql('CREATE INDEX IDX_F8F081D9D27F8253 ON don (id_assoc_id)');
    }
}
