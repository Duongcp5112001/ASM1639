<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210821015321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD user_phone_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398DBFEC7CB FOREIGN KEY (user_phone_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F5299398DBFEC7CB ON `order` (user_phone_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398DBFEC7CB');
        $this->addSql('DROP INDEX IDX_F5299398DBFEC7CB ON `order`');
        $this->addSql('ALTER TABLE `order` DROP user_phone_id');
    }
}
