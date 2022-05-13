<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220513095256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lap_time (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, car_id INT NOT NULL, track_id INT NOT NULL, date DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_30163F4EE48FD905 (game_id), UNIQUE INDEX UNIQ_30163F4EC3C6F69F (car_id), UNIQUE INDEX UNIQ_30163F4E5ED23C43 (track_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lap_time ADD CONSTRAINT FK_30163F4EE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE lap_time ADD CONSTRAINT FK_30163F4EC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE lap_time ADD CONSTRAINT FK_30163F4E5ED23C43 FOREIGN KEY (track_id) REFERENCES track (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lap_time');
    }
}
