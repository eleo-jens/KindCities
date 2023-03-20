<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230320130728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE disponibilite ADD host_id INT NOT NULL');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2F1FB8D185 FOREIGN KEY (host_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2CBACE2F1FB8D185 ON disponibilite (host_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2F1FB8D185');
        $this->addSql('DROP INDEX IDX_2CBACE2F1FB8D185 ON disponibilite');
        $this->addSql('ALTER TABLE disponibilite DROP host_id');
    }
}
