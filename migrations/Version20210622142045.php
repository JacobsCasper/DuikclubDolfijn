<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210622142045 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE answers answers JSON NOT NULL');
        $this->addSql('ALTER TABLE calender_item CHANGE subscription_end_date subscription_end_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE page CHANGE body body VARCHAR(6000) DEFAULT NULL, CHANGE navigation_text navigation_text VARCHAR(255) DEFAULT NULL, CHANGE form_id form_id INT DEFAULT NULL, CHANGE file_paths file_paths JSON NOT NULL, CHANGE file_names file_names JSON NOT NULL');
        $this->addSql('ALTER TABLE user ADD phone_number VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE web_form_email_type CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE price price INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE web_form_int_type CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE price price INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE web_form_radio_type CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE price price INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) DEFAULT NULL, CHANGE choices choices JSON NOT NULL');
        $this->addSql('ALTER TABLE web_form_string_type CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE price price INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE answers answers LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE calender_item CHANGE subscription_end_date subscription_end_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE page CHANGE body body VARCHAR(6000) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE navigation_text navigation_text VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE form_id form_id INT DEFAULT NULL, CHANGE file_paths file_paths LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE file_names file_names LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE user DROP phone_number, DROP description, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE web_form_email_type CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE price price INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE web_form_int_type CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE price price INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE web_form_radio_type CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE price price INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE choices choices LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE web_form_string_type CHANGE parent_id parent_id INT DEFAULT NULL, CHANGE price price INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
