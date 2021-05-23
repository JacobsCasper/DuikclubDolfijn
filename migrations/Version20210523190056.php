<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210523190056 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE album (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, picture_path TINYTEXT NOT NULL, public TINYINT(1) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, answers JSON NOT NULL, INDEX IDX_DADD4A25727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calender_item (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT NOT NULL, calender_type INT NOT NULL, description TINYTEXT NOT NULL, end_date DATETIME NOT NULL, start_date DATETIME NOT NULL, submit_date DATETIME NOT NULL, author TINYTEXT NOT NULL, details LONGTEXT NOT NULL, max_subscriptions INT NOT NULL, subscription_end_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_calenderItems (calender_item_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D2C6191879F389E3 (calender_item_id), INDEX IDX_D2C61918A76ED395 (user_id), PRIMARY KEY(calender_item_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nieuws_item (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT NOT NULL, picture_path LONGTEXT NOT NULL, description LONGTEXT NOT NULL, submit_date DATETIME NOT NULL, author TINYTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, body VARCHAR(6000) DEFAULT NULL, published TINYINT(1) NOT NULL, homepage_enabled TINYINT(1) NOT NULL, navigation_enabled TINYINT(1) NOT NULL, navigation_text VARCHAR(255) DEFAULT NULL, submit_date DATETIME NOT NULL, picture_path TINYTEXT NOT NULL, form_id INT DEFAULT NULL, file_paths JSON NOT NULL, file_names JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE web_form (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, open TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_968AF0BA2B36786B (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE web_form_email_type (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, position INT NOT NULL, price INT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, required TINYINT(1) NOT NULL, INDEX IDX_C75F1AED727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE web_form_int_type (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, position INT NOT NULL, price INT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, required TINYINT(1) NOT NULL, INDEX IDX_1DDD9772727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE web_form_radio_type (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, position INT NOT NULL, price INT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, required TINYINT(1) NOT NULL, choices JSON NOT NULL, INDEX IDX_2A6D0FA2727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE web_form_string_type (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, position INT NOT NULL, price INT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, required TINYINT(1) NOT NULL, multiline SMALLINT NOT NULL, INDEX IDX_3ABAEE21727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25727ACA70 FOREIGN KEY (parent_id) REFERENCES web_form (id)');
        $this->addSql('ALTER TABLE users_calenderItems ADD CONSTRAINT FK_D2C6191879F389E3 FOREIGN KEY (calender_item_id) REFERENCES calender_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_calenderItems ADD CONSTRAINT FK_D2C61918A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE web_form_email_type ADD CONSTRAINT FK_C75F1AED727ACA70 FOREIGN KEY (parent_id) REFERENCES web_form (id)');
        $this->addSql('ALTER TABLE web_form_int_type ADD CONSTRAINT FK_1DDD9772727ACA70 FOREIGN KEY (parent_id) REFERENCES web_form (id)');
        $this->addSql('ALTER TABLE web_form_radio_type ADD CONSTRAINT FK_2A6D0FA2727ACA70 FOREIGN KEY (parent_id) REFERENCES web_form (id)');
        $this->addSql('ALTER TABLE web_form_string_type ADD CONSTRAINT FK_3ABAEE21727ACA70 FOREIGN KEY (parent_id) REFERENCES web_form (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users_calenderItems DROP FOREIGN KEY FK_D2C6191879F389E3');
        $this->addSql('ALTER TABLE users_calenderItems DROP FOREIGN KEY FK_D2C61918A76ED395');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A25727ACA70');
        $this->addSql('ALTER TABLE web_form_email_type DROP FOREIGN KEY FK_C75F1AED727ACA70');
        $this->addSql('ALTER TABLE web_form_int_type DROP FOREIGN KEY FK_1DDD9772727ACA70');
        $this->addSql('ALTER TABLE web_form_radio_type DROP FOREIGN KEY FK_2A6D0FA2727ACA70');
        $this->addSql('ALTER TABLE web_form_string_type DROP FOREIGN KEY FK_3ABAEE21727ACA70');
        $this->addSql('DROP TABLE album');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE calender_item');
        $this->addSql('DROP TABLE users_calenderItems');
        $this->addSql('DROP TABLE nieuws_item');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE web_form');
        $this->addSql('DROP TABLE web_form_email_type');
        $this->addSql('DROP TABLE web_form_int_type');
        $this->addSql('DROP TABLE web_form_radio_type');
        $this->addSql('DROP TABLE web_form_string_type');
    }
}
