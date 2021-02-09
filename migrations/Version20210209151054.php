<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210209151054 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_calenderItems (user_id INT NOT NULL, calender_item_id INT NOT NULL, INDEX IDX_35ADBC45A76ED395 (user_id), INDEX IDX_35ADBC4579F389E3 (calender_item_id), PRIMARY KEY(user_id, calender_item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_calenderItems ADD CONSTRAINT FK_35ADBC45A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_calenderItems ADD CONSTRAINT FK_35ADBC4579F389E3 FOREIGN KEY (calender_item_id) REFERENCES calender_item (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user_calender_item');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_calender_item (user_id INT NOT NULL, calender_item_id INT NOT NULL, INDEX IDX_574C2F0979F389E3 (calender_item_id), INDEX IDX_574C2F09A76ED395 (user_id), PRIMARY KEY(user_id, calender_item_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_calender_item ADD CONSTRAINT FK_574C2F0979F389E3 FOREIGN KEY (calender_item_id) REFERENCES calender_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_calender_item ADD CONSTRAINT FK_574C2F09A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user_calenderItems');
    }
}
