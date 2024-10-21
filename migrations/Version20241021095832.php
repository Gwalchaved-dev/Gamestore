<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241021095832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_jeux_videos ADD command_id INT DEFAULT NULL, CHANGE shopping_cart_id shopping_cart_id INT DEFAULT NULL, CHANGE jeux_video_id jeux_video_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart_jeux_videos ADD CONSTRAINT FK_3595195433E1689A FOREIGN KEY (command_id) REFERENCES command (id)');
        $this->addSql('CREATE INDEX IDX_3595195433E1689A ON cart_jeux_videos (command_id)');
        $this->addSql('ALTER TABLE command ADD agence_id INT DEFAULT NULL, CHANGE status status VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD4D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('CREATE INDEX IDX_8ECAEAD4D725330D ON command (agence_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD4D725330D');
        $this->addSql('DROP INDEX IDX_8ECAEAD4D725330D ON command');
        $this->addSql('ALTER TABLE command DROP agence_id, CHANGE status status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE cart_jeux_videos DROP FOREIGN KEY FK_3595195433E1689A');
        $this->addSql('DROP INDEX IDX_3595195433E1689A ON cart_jeux_videos');
        $this->addSql('ALTER TABLE cart_jeux_videos DROP command_id, CHANGE jeux_video_id jeux_video_id INT NOT NULL, CHANGE shopping_cart_id shopping_cart_id INT NOT NULL');
    }
}
