<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230331071757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, street VARCHAR(255) DEFAULT NULL, number VARCHAR(10) DEFAULT NULL, box VARCHAR(10) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(15) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE complain (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, object VARCHAR(255) DEFAULT NULL, message LONGTEXT DEFAULT NULL, date_complain DATE DEFAULT NULL, INDEX IDX_2DD0CD6BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE detail_reservation (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, reservation_id INT NOT NULL, begin_date DATE NOT NULL, end_date DATE NOT NULL, INDEX IDX_2DC32404ED5CA9E6 (service_id), INDEX IDX_2DC32404B83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disponibilite (id INT AUTO_INCREMENT NOT NULL, host_id INT NOT NULL, service_id INT NOT NULL, begin_date_dispo DATE DEFAULT NULL, end_date_dispo DATE DEFAULT NULL, INDEX IDX_2CBACE2F1FB8D185 (host_id), INDEX IDX_2CBACE2FED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, refugee_id INT NOT NULL, host_id INT NOT NULL, reservation_id INT NOT NULL, stars INT NOT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_D22944587ECD7344 (refugee_id), INDEX IDX_D22944581FB8D185 (host_id), INDEX IDX_D2294458B83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language_user (language_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_BF9A3C0582F1BAF4 (language_id), INDEX IDX_BF9A3C05A76ED395 (user_id), PRIMARY KEY(language_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, name VARCHAR(255) NOT NULL, updated_at DATE NOT NULL, INDEX IDX_16DB4F89ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, host_id INT NOT NULL, refugee_id INT NOT NULL, date_reservation DATE DEFAULT NULL, code_reservation VARCHAR(255) DEFAULT NULL, resume LONGTEXT DEFAULT NULL, INDEX IDX_42C849551FB8D185 (host_id), INDEX IDX_42C849557ECD7344 (refugee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, address_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_E19D9AD2BCF5E72D (categorie_id), INDEX IDX_E19D9AD2F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, last_name VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, birth_date DATE DEFAULT NULL, presentation LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, gender VARCHAR(30) DEFAULT NULL, discr VARCHAR(255) NOT NULL, national_number_id VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE host_address (host_id INT NOT NULL, address_id INT NOT NULL, INDEX IDX_3AB243B01FB8D185 (host_id), INDEX IDX_3AB243B0F5B7AF75 (address_id), PRIMARY KEY(host_id, address_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE complain ADD CONSTRAINT FK_2DD0CD6BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE detail_reservation ADD CONSTRAINT FK_2DC32404ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE detail_reservation ADD CONSTRAINT FK_2DC32404B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2F1FB8D185 FOREIGN KEY (host_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2FED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944587ECD7344 FOREIGN KEY (refugee_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944581FB8D185 FOREIGN KEY (host_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE language_user ADD CONSTRAINT FK_BF9A3C0582F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE language_user ADD CONSTRAINT FK_BF9A3C05A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F89ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849551FB8D185 FOREIGN KEY (host_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849557ECD7344 FOREIGN KEY (refugee_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE host_address ADD CONSTRAINT FK_3AB243B01FB8D185 FOREIGN KEY (host_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE host_address ADD CONSTRAINT FK_3AB243B0F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE complain DROP FOREIGN KEY FK_2DD0CD6BA76ED395');
        $this->addSql('ALTER TABLE detail_reservation DROP FOREIGN KEY FK_2DC32404ED5CA9E6');
        $this->addSql('ALTER TABLE detail_reservation DROP FOREIGN KEY FK_2DC32404B83297E7');
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2F1FB8D185');
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2FED5CA9E6');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944587ECD7344');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944581FB8D185');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458B83297E7');
        $this->addSql('ALTER TABLE language_user DROP FOREIGN KEY FK_BF9A3C0582F1BAF4');
        $this->addSql('ALTER TABLE language_user DROP FOREIGN KEY FK_BF9A3C05A76ED395');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F89ED5CA9E6');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849551FB8D185');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849557ECD7344');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2BCF5E72D');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2F5B7AF75');
        $this->addSql('ALTER TABLE host_address DROP FOREIGN KEY FK_3AB243B01FB8D185');
        $this->addSql('ALTER TABLE host_address DROP FOREIGN KEY FK_3AB243B0F5B7AF75');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE complain');
        $this->addSql('DROP TABLE detail_reservation');
        $this->addSql('DROP TABLE disponibilite');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE language_user');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE host_address');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
