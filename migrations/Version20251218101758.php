<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251218101758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hydrolique_sum (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATETIME NOT NULL, etc INTEGER NOT NULL, rain INTEGER NOT NULL, stock INTEGER NOT NULL, volume INTEGER NOT NULL, decision VARCHAR(255) NOT NULL, zone_id INTEGER DEFAULT NULL, sensor_id INTEGER DEFAULT NULL, CONSTRAINT FK_6FEB52579F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6FEB5257A247991F FOREIGN KEY (sensor_id) REFERENCES sensor (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6FEB52579F2C3FAB ON hydrolique_sum (zone_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6FEB5257A247991F ON hydrolique_sum (sensor_id)');
        $this->addSql('CREATE TABLE sensor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date DATETIME NOT NULL, humidity INTEGER NOT NULL, longitude INTEGER NOT NULL, latitude INTEGER NOT NULL, batterie INTEGER NOT NULL, temperature INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE zone (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, space_type VARCHAR(255) NOT NULL, ru INTEGER NOT NULL, surface INTEGER NOT NULL, uniformity VARCHAR(255) NOT NULL, sensor_id INTEGER DEFAULT NULL, CONSTRAINT FK_A0EBC007A247991F FOREIGN KEY (sensor_id) REFERENCES sensor (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A0EBC007A247991F ON zone (sensor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE hydrolique_sum');
        $this->addSql('DROP TABLE sensor');
        $this->addSql('DROP TABLE zone');
    }
}
