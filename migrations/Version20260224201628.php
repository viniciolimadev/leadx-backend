<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260224201628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajusta coluna role.name de VARCHAR(255) para VARCHAR(50) após remoção do RoleEnum.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE role ALTER name TYPE VARCHAR(50)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE role ALTER name TYPE VARCHAR(255)');
    }
}
