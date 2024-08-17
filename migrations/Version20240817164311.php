<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240817164311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking CHANGE restaurant_id restaurant_id INT NOT NULL');
        $this->addSql('ALTER TABLE menu CHANGE restaurant_id restaurant_id INT NOT NULL');
        $this->addSql('ALTER TABLE picture CHANGE restaurant_id restaurant_id INT NOT NULL, CHANGE update_at update_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE restaurant ADD owner_id INT DEFAULT NULL, CHANGE am_opening_time am_opening_time LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE pm_opening_time pm_opening_time LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EB95123F7E3C61F9 ON restaurant (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking CHANGE restaurant_id restaurant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu CHANGE restaurant_id restaurant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE picture CHANGE restaurant_id restaurant_id INT DEFAULT NULL, CHANGE update_at update_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123F7E3C61F9');
        $this->addSql('DROP INDEX UNIQ_EB95123F7E3C61F9 ON restaurant');
        $this->addSql('ALTER TABLE restaurant DROP owner_id, CHANGE am_opening_time am_opening_time LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE pm_opening_time pm_opening_time LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }
}
