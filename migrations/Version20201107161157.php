<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201107161157 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE web_form_email_type (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, front_label VARCHAR(255) NOT NULL, end_label VARCHAR(255) NOT NULL, required TINYINT(1) NOT NULL, INDEX IDX_C75F1AED727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE web_form_int_type (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, front_label VARCHAR(255) NOT NULL, end_label VARCHAR(255) NOT NULL, required TINYINT(1) NOT NULL, INDEX IDX_1DDD9772727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE web_form_radio_type (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, front_label VARCHAR(255) NOT NULL, end_label VARCHAR(255) NOT NULL, required TINYINT(1) NOT NULL, choices JSON NOT NULL, INDEX IDX_2A6D0FA2727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE web_form_email_type ADD CONSTRAINT FK_C75F1AED727ACA70 FOREIGN KEY (parent_id) REFERENCES web_form (id)');
        $this->addSql('ALTER TABLE web_form_int_type ADD CONSTRAINT FK_1DDD9772727ACA70 FOREIGN KEY (parent_id) REFERENCES web_form (id)');
        $this->addSql('ALTER TABLE web_form_radio_type ADD CONSTRAINT FK_2A6D0FA2727ACA70 FOREIGN KEY (parent_id) REFERENCES web_form (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE web_form_email_type');
        $this->addSql('DROP TABLE web_form_int_type');
        $this->addSql('DROP TABLE web_form_radio_type');
    }
}
