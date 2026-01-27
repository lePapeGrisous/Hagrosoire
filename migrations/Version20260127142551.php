<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260127142551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meteo ADD COLUMN sun_hours INTEGER DEFAULT NULL');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sensor AS SELECT id, name, date, humidity, longitude, latitude, batterie, temperature FROM sensor');
        $this->addSql('DROP TABLE sensor');
        $this->addSql('CREATE TABLE sensor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date DATETIME NOT NULL, humidity INTEGER NOT NULL, longitude DOUBLE PRECISION NOT NULL, latitude DOUBLE PRECISION NOT NULL, batterie DOUBLE PRECISION NOT NULL, temperature INTEGER NOT NULL)');
        $this->addSql('INSERT INTO sensor (id, name, date, humidity, longitude, latitude, batterie, temperature) SELECT id, name, date, humidity, longitude, latitude, batterie, temperature FROM __temp__sensor');
        $this->addSql('DROP TABLE __temp__sensor');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__meteo AS SELECT id, date, wind, rain, rain_prob, hr_pct, t_c FROM meteo');
        $this->addSql('DROP TABLE meteo');
        $this->addSql('CREATE TABLE meteo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATETIME NOT NULL, wind INTEGER NOT NULL, rain INTEGER NOT NULL, rain_prob INTEGER NOT NULL, hr_pct INTEGER NOT NULL, t_c INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO meteo (id, date, wind, rain, rain_prob, hr_pct, t_c) SELECT id, date, wind, rain, rain_prob, hr_pct, t_c FROM __temp__meteo');
        $this->addSql('DROP TABLE __temp__meteo');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sensor AS SELECT id, name, date, humidity, longitude, latitude, batterie, temperature FROM sensor');
        $this->addSql('DROP TABLE sensor');
        $this->addSql('CREATE TABLE sensor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date DATETIME NOT NULL, humidity INTEGER NOT NULL, longitude INTEGER NOT NULL, latitude INTEGER NOT NULL, batterie INTEGER NOT NULL, temperature INTEGER NOT NULL)');
        $this->addSql('INSERT INTO sensor (id, name, date, humidity, longitude, latitude, batterie, temperature) SELECT id, name, date, humidity, longitude, latitude, batterie, temperature FROM __temp__sensor');
        $this->addSql('DROP TABLE __temp__sensor');
    }
}
