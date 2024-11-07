<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241106135607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agence (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE cart_jeux_videos (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, jeux_video_id INT DEFAULT NULL, shopping_cart_id INT DEFAULT NULL, command_id INT DEFAULT NULL, INDEX IDX_35951954CA8BBF (jeux_video_id), INDEX IDX_3595195445F80CD (shopping_cart_id), INDEX IDX_3595195433E1689A (command_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE command (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, total NUMERIC(10, 2) NOT NULL, status VARCHAR(50) NOT NULL, user_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, INDEX IDX_8ECAEAD4A76ED395 (user_id), INDEX IDX_8ECAEAD4D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(180) NOT NULL, prenom VARCHAR(180) NOT NULL, adresse VARCHAR(255) NOT NULL, codepostal VARCHAR(10) NOT NULL, ville VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, created_at DATETIME NOT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, agence_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_5D9F75A16C6E55B5 (nom), UNIQUE INDEX UNIQ_5D9F75A1E7927C74 (email), INDEX IDX_5D9F75A1D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE jeux_images (id INT AUTO_INCREMENT NOT NULL, image_path VARCHAR(255) NOT NULL, alt_text VARCHAR(255) DEFAULT NULL, jeu_id INT DEFAULT NULL, INDEX IDX_AFF33A4C8C9E392E (jeu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE jeux_videos (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, prix NUMERIC(10, 2) NOT NULL, genre VARCHAR(50) NOT NULL, image VARCHAR(255) NOT NULL, second_image VARCHAR(255) DEFAULT NULL, third_image VARCHAR(255) DEFAULT NULL, stock INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, command_id INT DEFAULT NULL, cart_jeux_video_id INT DEFAULT NULL, INDEX IDX_52EA1F0933E1689A (command_id), INDEX IDX_52EA1F0934D2DDD1 (cart_jeux_video_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE shopping_cart (id INT AUTO_INCREMENT NOT NULL, date_creation DATETIME NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_72AAD4F6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse_postale VARCHAR(255) NOT NULL, code_postal VARCHAR(10) NOT NULL, ville VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE vente (id INT AUTO_INCREMENT NOT NULL, jeux_videos_id INT NOT NULL, date_vente DATE NOT NULL, prix_vente DOUBLE PRECISION NOT NULL, quantitévendue INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cart_jeux_videos ADD CONSTRAINT FK_35951954CA8BBF FOREIGN KEY (jeux_video_id) REFERENCES jeux_videos (id)');
        $this->addSql('ALTER TABLE cart_jeux_videos ADD CONSTRAINT FK_3595195445F80CD FOREIGN KEY (shopping_cart_id) REFERENCES shopping_cart (id)');
        $this->addSql('ALTER TABLE cart_jeux_videos ADD CONSTRAINT FK_3595195433E1689A FOREIGN KEY (command_id) REFERENCES command (id)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD4D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE jeux_images ADD CONSTRAINT FK_AFF33A4C8C9E392E FOREIGN KEY (jeu_id) REFERENCES jeux_videos (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F0933E1689A FOREIGN KEY (command_id) REFERENCES command (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F0934D2DDD1 FOREIGN KEY (cart_jeux_video_id) REFERENCES cart_jeux_videos (id)');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_jeux_videos DROP FOREIGN KEY FK_35951954CA8BBF');
        $this->addSql('ALTER TABLE cart_jeux_videos DROP FOREIGN KEY FK_3595195445F80CD');
        $this->addSql('ALTER TABLE cart_jeux_videos DROP FOREIGN KEY FK_3595195433E1689A');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD4A76ED395');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD4D725330D');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A1D725330D');
        $this->addSql('ALTER TABLE jeux_images DROP FOREIGN KEY FK_AFF33A4C8C9E392E');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F0933E1689A');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F0934D2DDD1');
        $this->addSql('ALTER TABLE shopping_cart DROP FOREIGN KEY FK_72AAD4F6A76ED395');
        $this->addSql('DROP TABLE agence');
        $this->addSql('DROP TABLE cart_jeux_videos');
        $this->addSql('DROP TABLE command');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE jeux_images');
        $this->addSql('DROP TABLE jeux_videos');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE shopping_cart');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vente');
    }
}
