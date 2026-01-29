<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260129091454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__weekly_decision AS SELECT id, monday, tuesday, wensday, thursday, friday, saturday, sunday, starting_time, spray_duration FROM weekly_decision');
        $this->addSql('DROP TABLE weekly_decision');
        $this->addSql('CREATE TABLE weekly_decision (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, monday BOOLEAN DEFAULT NULL, tuesday BOOLEAN DEFAULT NULL, wensday BOOLEAN DEFAULT NULL, thursday BOOLEAN DEFAULT NULL, friday BOOLEAN DEFAULT NULL, saturday BOOLEAN DEFAULT NULL, sunday BOOLEAN DEFAULT NULL, starting_time TIME DEFAULT NULL, spray_duration INTEGER DEFAULT NULL, zone_id INTEGER DEFAULT NULL, CONSTRAINT FK_141BD3759F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO weekly_decision (id, monday, tuesday, wensday, thursday, friday, saturday, sunday, starting_time, spray_duration) SELECT id, monday, tuesday, wensday, thursday, friday, saturday, sunday, starting_time, spray_duration FROM __temp__weekly_decision');
        $this->addSql('DROP TABLE __temp__weekly_decision');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_141BD3759F2C3FAB ON weekly_decision (zone_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__weekly_decision AS SELECT id, monday, tuesday, wensday, thursday, friday, saturday, sunday, starting_time, spray_duration FROM weekly_decision');
        $this->addSql('DROP TABLE weekly_decision');
        $this->addSql('CREATE TABLE weekly_decision (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, monday BOOLEAN DEFAULT NULL, tuesday BOOLEAN DEFAULT NULL, wensday BOOLEAN DEFAULT NULL, thursday BOOLEAN DEFAULT NULL, friday BOOLEAN DEFAULT NULL, saturday BOOLEAN DEFAULT NULL, sunday BOOLEAN DEFAULT NULL, starting_time TIME DEFAULT NULL, spray_duration INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO weekly_decision (id, monday, tuesday, wensday, thursday, friday, saturday, sunday, starting_time, spray_duration) SELECT id, monday, tuesday, wensday, thursday, friday, saturday, sunday, starting_time, spray_duration FROM __temp__weekly_decision');
        $this->addSql('DROP TABLE __temp__weekly_decision');
    }
}
