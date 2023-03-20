<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230320140239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address_service (address_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_BA786D4AF5B7AF75 (address_id), INDEX IDX_BA786D4AED5CA9E6 (service_id), PRIMARY KEY(address_id, service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE complain (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, object VARCHAR(255) DEFAULT NULL, message LONGTEXT DEFAULT NULL, date_complain DATE DEFAULT NULL, INDEX IDX_2DD0CD6BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, refugee_id INT NOT NULL, host_id INT NOT NULL, reservation_id INT NOT NULL, stars INT NOT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_D22944587ECD7344 (refugee_id), INDEX IDX_D22944581FB8D185 (host_id), INDEX IDX_D2294458B83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_D4DB71B5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE host_address (host_id INT NOT NULL, address_id INT NOT NULL, INDEX IDX_3AB243B01FB8D185 (host_id), INDEX IDX_3AB243B0F5B7AF75 (address_id), PRIMARY KEY(host_id, address_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address_service ADD CONSTRAINT FK_BA786D4AF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE address_service ADD CONSTRAINT FK_BA786D4AED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE complain ADD CONSTRAINT FK_2DD0CD6BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944587ECD7344 FOREIGN KEY (refugee_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944581FB8D185 FOREIGN KEY (host_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE language ADD CONSTRAINT FK_D4DB71B5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE host_address ADD CONSTRAINT FK_3AB243B01FB8D185 FOREIGN KEY (host_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE host_address ADD CONSTRAINT FK_3AB243B0F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE detail_reservation ADD service_id INT NOT NULL, ADD reservation_id INT NOT NULL');
        $this->addSql('ALTER TABLE detail_reservation ADD CONSTRAINT FK_2DC32404ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE detail_reservation ADD CONSTRAINT FK_2DC32404B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('CREATE INDEX IDX_2DC32404ED5CA9E6 ON detail_reservation (service_id)');
        $this->addSql('CREATE INDEX IDX_2DC32404B83297E7 ON detail_reservation (reservation_id)');
        $this->addSql('ALTER TABLE disponibilite ADD service_id INT NOT NULL');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2FED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('CREATE INDEX IDX_2CBACE2FED5CA9E6 ON disponibilite (service_id)');
        $this->addSql('ALTER TABLE reservation ADD host_id INT NOT NULL, ADD refugee_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849551FB8D185 FOREIGN KEY (host_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849557ECD7344 FOREIGN KEY (refugee_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_42C849551FB8D185 ON reservation (host_id)');
        $this->addSql('CREATE INDEX IDX_42C849557ECD7344 ON reservation (refugee_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address_service DROP FOREIGN KEY FK_BA786D4AF5B7AF75');
        $this->addSql('ALTER TABLE address_service DROP FOREIGN KEY FK_BA786D4AED5CA9E6');
        $this->addSql('ALTER TABLE complain DROP FOREIGN KEY FK_2DD0CD6BA76ED395');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944587ECD7344');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944581FB8D185');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458B83297E7');
        $this->addSql('ALTER TABLE language DROP FOREIGN KEY FK_D4DB71B5A76ED395');
        $this->addSql('ALTER TABLE host_address DROP FOREIGN KEY FK_3AB243B01FB8D185');
        $this->addSql('ALTER TABLE host_address DROP FOREIGN KEY FK_3AB243B0F5B7AF75');
        $this->addSql('DROP TABLE address_service');
        $this->addSql('DROP TABLE complain');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE host_address');
        $this->addSql('ALTER TABLE detail_reservation DROP FOREIGN KEY FK_2DC32404ED5CA9E6');
        $this->addSql('ALTER TABLE detail_reservation DROP FOREIGN KEY FK_2DC32404B83297E7');
        $this->addSql('DROP INDEX IDX_2DC32404ED5CA9E6 ON detail_reservation');
        $this->addSql('DROP INDEX IDX_2DC32404B83297E7 ON detail_reservation');
        $this->addSql('ALTER TABLE detail_reservation DROP service_id, DROP reservation_id');
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2FED5CA9E6');
        $this->addSql('DROP INDEX IDX_2CBACE2FED5CA9E6 ON disponibilite');
        $this->addSql('ALTER TABLE disponibilite DROP service_id');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849551FB8D185');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849557ECD7344');
        $this->addSql('DROP INDEX IDX_42C849551FB8D185 ON reservation');
        $this->addSql('DROP INDEX IDX_42C849557ECD7344 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP host_id, DROP refugee_id');
    }
}
