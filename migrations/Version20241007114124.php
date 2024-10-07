<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241007114124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jeux_images CHANGE alt_text alt_text VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE jeux_videos ADD second_image VARCHAR(255) DEFAULT NULL, ADD third_image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jeux_videos DROP second_image, DROP third_image');
        $this->addSql('ALTER TABLE jeux_images CHANGE alt_text alt_text VARCHAR(255) NOT NULL');
    }
}
