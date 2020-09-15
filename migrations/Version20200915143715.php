<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200915143715 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE web_form (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, button_name VARCHAR(255) NOT NULL, button_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE web_form_element (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, linked_entity VARCHAR(255) NOT NULL, required TINYINT(1) NOT NULL, Parent_id INT DEFAULT NULL, INDEX IDX_F72DAAFCF08B48D3 (Parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE web_form_element ADD CONSTRAINT FK_F72DAAFCF08B48D3 FOREIGN KEY (Parent_id) REFERENCES web_form (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE web_form_element DROP FOREIGN KEY FK_F72DAAFCF08B48D3');
        $this->addSql('DROP TABLE web_form');
        $this->addSql('DROP TABLE web_form_element');
    }
}
