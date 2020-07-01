<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200629181508 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page ADD nieuwsItem_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB62089DA3E80 FOREIGN KEY (nieuwsItem_id) REFERENCES nieuws_item (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_140AB62089DA3E80 ON page (nieuwsItem_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB62089DA3E80');
        $this->addSql('DROP INDEX UNIQ_140AB62089DA3E80 ON page');
        $this->addSql('ALTER TABLE page DROP nieuwsItem_id');
    }
}
