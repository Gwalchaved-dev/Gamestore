<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240926105244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jeux_images (id INT AUTO_INCREMENT NOT NULL, jeu_id INT DEFAULT NULL, image_path VARCHAR(255) NOT NULL, alt_text VARCHAR(255) NOT NULL, INDEX IDX_AFF33A4C8C9E392E (jeu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jeux_images ADD CONSTRAINT FK_AFF33A4C8C9E392E FOREIGN KEY (jeu_id) REFERENCES jeux_videos (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jeux_images DROP FOREIGN KEY FK_AFF33A4C8C9E392E');
        $this->addSql('DROP TABLE jeux_images');
    }
}
