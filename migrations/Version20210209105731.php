<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210209105731 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_calenderItems (calender_item_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D2C6191879F389E3 (calender_item_id), INDEX IDX_D2C61918A76ED395 (user_id), PRIMARY KEY(calender_item_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_calenderItems ADD CONSTRAINT FK_D2C6191879F389E3 FOREIGN KEY (calender_item_id) REFERENCES calender_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_calenderItems ADD CONSTRAINT FK_D2C61918A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user_subscription');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_968AF0BA2B36786B ON web_form (title)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_subscription (id INT AUTO_INCREMENT NOT NULL, subscription_date DATE NOT NULL, CalenderItem_id INT DEFAULT NULL, INDEX IDX_230A18D15BAEFB8F (CalenderItem_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_subscription ADD CONSTRAINT FK_230A18D15BAEFB8F FOREIGN KEY (CalenderItem_id) REFERENCES calender_item (id)');
        $this->addSql('DROP TABLE users_calenderItems');
        $this->addSql('DROP INDEX UNIQ_968AF0BA2B36786B ON web_form');
    }
}
