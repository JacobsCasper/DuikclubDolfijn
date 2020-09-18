<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200918131517 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE web_form_element ADD end_label VARCHAR(255) NOT NULL, CHANGE linked_entity front_label VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE web_form_string_type ADD end_label VARCHAR(255) NOT NULL, CHANGE linked_entity front_label VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE web_form_element ADD linked_entity VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP front_label, DROP end_label');
        $this->addSql('ALTER TABLE web_form_string_type ADD linked_entity VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP front_label, DROP end_label');
    }
}
