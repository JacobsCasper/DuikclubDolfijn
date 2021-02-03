<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201108134447 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE web_form_email_type ADD postition INT NOT NULL');
        $this->addSql('ALTER TABLE web_form_int_type ADD postition INT NOT NULL');
        $this->addSql('ALTER TABLE web_form_radio_type ADD postition INT NOT NULL');
        $this->addSql('ALTER TABLE web_form_string_type ADD postition INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE web_form_email_type DROP postition');
        $this->addSql('ALTER TABLE web_form_int_type DROP postition');
        $this->addSql('ALTER TABLE web_form_radio_type DROP postition');
        $this->addSql('ALTER TABLE web_form_string_type DROP postition');
    }
}
