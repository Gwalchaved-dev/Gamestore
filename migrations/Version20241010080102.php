<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241010080102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Renaming Order to Command and updating related tables.';
    }

    public function up(Schema $schema): void
    {
        // Créez la table 'command' si elle n'existe pas déjà
        if (!$schema->hasTable('command')) {
            $this->addSql('CREATE TABLE command (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, total NUMERIC(10, 2) NOT NULL, status VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_8ECAEAD4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
            $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        }

        // Supprimez la table 'order' si elle existe
        if ($schema->hasTable('order')) {
            // D'abord, supprimez la contrainte de clé étrangère de 'order_item' vers 'order'
            $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F098D9F6D38');
            // Ensuite, supprimez la table 'order'
            $this->addSql('DROP TABLE `order`');
        }

        // Modification de la table 'order_item'
        if ($schema->hasTable('order_item')) {
            $this->addSql('DROP INDEX IDX_52EA1F098D9F6D38 ON order_item');
            $this->addSql('ALTER TABLE order_item CHANGE quantity quantite INT NOT NULL, CHANGE order_id command_id INT DEFAULT NULL');
            $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F0933E1689A FOREIGN KEY (command_id) REFERENCES command (id)');
            $this->addSql('CREATE INDEX IDX_52EA1F0933E1689A ON order_item (command_id)');
        }
    }

    public function down(Schema $schema): void
    {
        // Restauration de la table 'order' si nécessaire
        if (!$schema->hasTable('order')) {
            $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, total NUMERIC(10, 2) NOT NULL, status VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
            $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        }

        // Suppression de la table 'command' si elle existe
        if ($schema->hasTable('command')) {
            $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD4A76ED395');
            $this->addSql('DROP TABLE command');
        }

        // Restauration de la table 'order_item'
        if ($schema->hasTable('order_item')) {
            $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F0933E1689A');
            $this->addSql('DROP INDEX IDX_52EA1F0933E1689A ON order_item');
            $this->addSql('ALTER TABLE order_item CHANGE quantite quantity INT NOT NULL, CHANGE command_id order_id INT DEFAULT NULL');
            $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
            $this->addSql('CREATE INDEX IDX_52EA1F098D9F6D38 ON order_item (order_id)');
        }
    }
}
