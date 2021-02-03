<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201107170848 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE web_form_email_type CHANGE front_label front_label VARCHAR(255) DEFAULT NULL, CHANGE end_label end_label VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE web_form_int_type CHANGE front_label front_label VARCHAR(255) DEFAULT NULL, CHANGE end_label end_label VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE web_form_radio_type CHANGE front_label front_label VARCHAR(255) DEFAULT NULL, CHANGE end_label end_label VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE web_form_string_type CHANGE front_label front_label VARCHAR(255) DEFAULT NULL, CHANGE end_label end_label VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE web_form_email_type CHANGE front_label front_label VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE end_label end_label VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE web_form_int_type CHANGE front_label front_label VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE end_label end_label VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE web_form_radio_type CHANGE front_label front_label VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE end_label end_label VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE web_form_string_type CHANGE front_label front_label VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE end_label end_label VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
