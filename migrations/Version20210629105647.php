<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210629105647 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE submission (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, shoe_size INT NOT NULL, experience VARCHAR(255) NOT NULL, submit_date DATETIME NOT NULL, UNIQUE INDEX UNIQ_DB055AF3F85E0677 (username), UNIQUE INDEX UNIQ_DB055AF3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE submition');
        $this->addSql('ALTER TABLE answer CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE answers answers JSON NOT NULL');
        $this->addSql('ALTER TABLE calender_item CHANGE subscription_end_date subscription_end_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE page CHANGE body body VARCHAR(6000) DEFAULT NULL, CHANGE navigation_text navigation_text VARCHAR(255) DEFAULT NULL, CHANGE form_id form_id INT DEFAULT NULL, CHANGE file_paths file_paths JSON NOT NULL, CHANGE file_names file_names JSON NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE web_form_email_type CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE price price INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE web_form_int_type CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE price price INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE web_form_radio_type CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE price price INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) DEFAULT NULL, CHANGE choices choices JSON NOT NULL');
        $this->addSql('ALTER TABLE web_form_string_type CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE price price INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE submition (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, phone_number VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, shoe_size INT NOT NULL, experience VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_1CC2DC6DF85E0677 (username), UNIQUE INDEX UNIQ_1CC2DC6DE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE submission');
        $this->addSql('ALTER TABLE answer CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE answers answers LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE calender_item CHANGE subscription_end_date subscription_end_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE page CHANGE body body VARCHAR(6000) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE navigation_text navigation_text VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE form_id form_id INT DEFAULT NULL, CHANGE file_paths file_paths LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE file_names file_names LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE web_form_email_type CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE price price INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE web_form_int_type CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE price price INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE web_form_radio_type CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE price price INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE choices choices LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE web_form_string_type CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE price price INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
