<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200827113527 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_subscription (id INT AUTO_INCREMENT NOT NULL, subscription_date DATE NOT NULL, CalenderItem_id INT DEFAULT NULL, INDEX IDX_230A18D15BAEFB8F (CalenderItem_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_subscription ADD CONSTRAINT FK_230A18D15BAEFB8F FOREIGN KEY (CalenderItem_id) REFERENCES calender_item (id)');
        $this->addSql('ALTER TABLE calender_item ADD max_subscriptions INT NOT NULL, ADD subscription_end_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE nieuws_item ADD picture_path LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_subscription');
        $this->addSql('ALTER TABLE calender_item DROP max_subscriptions, DROP subscription_end_date');
        $this->addSql('ALTER TABLE nieuws_item DROP picture_path');
        $this->addSql('ALTER TABLE user DROP roles');
    }
}
