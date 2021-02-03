<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201012171131 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calender_item (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT NOT NULL, calender_type INT NOT NULL, description TINYTEXT NOT NULL, end_date DATETIME NOT NULL, start_date DATETIME NOT NULL, submit_date DATETIME NOT NULL, author TINYTEXT NOT NULL, details LONGTEXT NOT NULL, max_subscriptions INT NOT NULL, subscription_end_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nieuws_item (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT NOT NULL, picture_path LONGTEXT NOT NULL, description LONGTEXT NOT NULL, submit_date DATETIME NOT NULL, author TINYTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, body VARCHAR(6000) DEFAULT NULL, published TINYINT(1) NOT NULL, homepage_enabled TINYINT(1) NOT NULL, navigation_enabled TINYINT(1) NOT NULL, navigation_text VARCHAR(255) DEFAULT NULL, submit_date DATETIME NOT NULL, picture_path TINYTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_subscription (id INT AUTO_INCREMENT NOT NULL, subscription_date DATE NOT NULL, CalenderItem_id INT DEFAULT NULL, INDEX IDX_230A18D15BAEFB8F (CalenderItem_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE web_form (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, open TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE web_form_element (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, front_label VARCHAR(255) NOT NULL, end_label VARCHAR(255) NOT NULL, required TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE web_form_string_type (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, front_label VARCHAR(255) NOT NULL, end_label VARCHAR(255) NOT NULL, required TINYINT(1) NOT NULL, multiline SMALLINT NOT NULL, Parent_id INT DEFAULT NULL, INDEX IDX_3ABAEE21F08B48D3 (Parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_subscription ADD CONSTRAINT FK_230A18D15BAEFB8F FOREIGN KEY (CalenderItem_id) REFERENCES calender_item (id)');
        $this->addSql('ALTER TABLE web_form_string_type ADD CONSTRAINT FK_3ABAEE21F08B48D3 FOREIGN KEY (Parent_id) REFERENCES web_form (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_subscription DROP FOREIGN KEY FK_230A18D15BAEFB8F');
        $this->addSql('ALTER TABLE web_form_string_type DROP FOREIGN KEY FK_3ABAEE21F08B48D3');
        $this->addSql('DROP TABLE calender_item');
        $this->addSql('DROP TABLE nieuws_item');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_subscription');
        $this->addSql('DROP TABLE web_form');
        $this->addSql('DROP TABLE web_form_element');
        $this->addSql('DROP TABLE web_form_string_type');
    }
}
