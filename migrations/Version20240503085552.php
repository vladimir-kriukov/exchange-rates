<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240503085552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rates (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, base VARCHAR(3) NOT NULL, rate NUMERIC(16, 8) NOT NULL, currency VARCHAR(3) NOT NULL, INDEX idx_base_currency (base, currency), UNIQUE INDEX UNIQ_44D4AB3CAA9E377AC0B4FE616956883F (date, base, currency), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE rates');
    }
}
