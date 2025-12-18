<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251218104454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__zone AS SELECT id, name, space_type, ru, surface, uniformity, sensor_id FROM zone');
        $this->addSql('DROP TABLE zone');
        $this->addSql('CREATE TABLE zone (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, space_type VARCHAR(255) NOT NULL, ru INTEGER NOT NULL, surface INTEGER NOT NULL, uniformity VARCHAR(255) NOT NULL, sensor_id INTEGER DEFAULT NULL, meteo_id INTEGER DEFAULT NULL, CONSTRAINT FK_A0EBC007A247991F FOREIGN KEY (sensor_id) REFERENCES sensor (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A0EBC007CC6645DC FOREIGN KEY (meteo_id) REFERENCES meteo (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO zone (id, name, space_type, ru, surface, uniformity, sensor_id) SELECT id, name, space_type, ru, surface, uniformity, sensor_id FROM __temp__zone');
        $this->addSql('DROP TABLE __temp__zone');
        $this->addSql('CREATE INDEX IDX_A0EBC007A247991F ON zone (sensor_id)');
        $this->addSql('CREATE INDEX IDX_A0EBC007CC6645DC ON zone (meteo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__zone AS SELECT id, name, space_type, ru, surface, uniformity, sensor_id FROM zone');
        $this->addSql('DROP TABLE zone');
        $this->addSql('CREATE TABLE zone (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, space_type VARCHAR(255) NOT NULL, ru INTEGER NOT NULL, surface INTEGER NOT NULL, uniformity VARCHAR(255) NOT NULL, sensor_id INTEGER DEFAULT NULL, CONSTRAINT FK_A0EBC007A247991F FOREIGN KEY (sensor_id) REFERENCES sensor (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO zone (id, name, space_type, ru, surface, uniformity, sensor_id) SELECT id, name, space_type, ru, surface, uniformity, sensor_id FROM __temp__zone');
        $this->addSql('DROP TABLE __temp__zone');
        $this->addSql('CREATE INDEX IDX_A0EBC007A247991F ON zone (sensor_id)');
    }
}
