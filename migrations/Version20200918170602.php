<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200918170602 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE web_form_element DROP FOREIGN KEY FK_F72DAAFCF08B48D3');
        $this->addSql('DROP INDEX IDX_F72DAAFCF08B48D3 ON web_form_element');
        $this->addSql('ALTER TABLE web_form_element DROP Parent_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE web_form_element ADD Parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE web_form_element ADD CONSTRAINT FK_F72DAAFCF08B48D3 FOREIGN KEY (Parent_id) REFERENCES web_form (id)');
        $this->addSql('CREATE INDEX IDX_F72DAAFCF08B48D3 ON web_form_element (Parent_id)');
    }
}
