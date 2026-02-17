<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260210103000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add author photo path to contact info';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contact_info ADD photo_path VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contact_info DROP photo_path');
    }
}
