<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201108104449 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE web_form_email_type ADD price INT DEFAULT NULL, ADD comment VARCHAR(255) DEFAULT NULL, DROP front_label, DROP end_label');
        $this->addSql('ALTER TABLE web_form_int_type ADD price INT DEFAULT NULL, ADD comment VARCHAR(255) DEFAULT NULL, DROP front_label, DROP end_label');
        $this->addSql('ALTER TABLE web_form_radio_type ADD price INT DEFAULT NULL, ADD comment VARCHAR(255) DEFAULT NULL, DROP front_label, DROP end_label');
        $this->addSql('ALTER TABLE web_form_string_type ADD price INT DEFAULT NULL, ADD comment VARCHAR(255) DEFAULT NULL, DROP front_label, DROP end_label');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE web_form_email_type ADD end_label VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP price, CHANGE comment front_label VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE web_form_int_type ADD end_label VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP price, CHANGE comment front_label VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE web_form_radio_type ADD end_label VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP price, CHANGE comment front_label VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE web_form_string_type ADD end_label VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP price, CHANGE comment front_label VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
