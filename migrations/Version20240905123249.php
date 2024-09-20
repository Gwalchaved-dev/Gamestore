<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240905123249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // La colonne 'quantité' n'existe pas, nous supprimons la modification inutile.
        // Aucun changement requis dans cette migration pour 'cart_jeux_videos'.
    }

    public function down(Schema $schema): void
    {
        // Cette migration ne fait rien, donc le rollback n'a pas besoin de rétablir quoi que ce soit.
    }
}