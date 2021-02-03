<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201107154541 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE web_form_element');
        $this->addSql('ALTER TABLE web_form_string_type ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE web_form_string_type ADD CONSTRAINT FK_3ABAEE21727ACA70 FOREIGN KEY (parent_id) REFERENCES web_form (id)');
        $this->addSql('CREATE INDEX IDX_3ABAEE21727ACA70 ON web_form_string_type (parent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE web_form_element (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, front_label VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, end_label VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, required TINYINT(1) NOT NULL, Parent_id INT DEFAULT NULL, INDEX IDX_F72DAAFC727ACA70 (Parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE web_form_element ADD CONSTRAINT FK_F72DAAFCF08B48D3 FOREIGN KEY (Parent_id) REFERENCES web_form (id)');
        $this->addSql('ALTER TABLE web_form_string_type DROP FOREIGN KEY FK_3ABAEE21727ACA70');
        $this->addSql('DROP INDEX IDX_3ABAEE21727ACA70 ON web_form_string_type');
        $this->addSql('ALTER TABLE web_form_string_type DROP parent_id');
    }
}
