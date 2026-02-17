<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260131120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial schema for portfolio content';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, title_en VARCHAR(200) NOT NULL, title_fr VARCHAR(200) NOT NULL, short_description_en LONGTEXT NOT NULL, short_description_fr LONGTEXT NOT NULL, image_path VARCHAR(255) DEFAULT NULL, project_url VARCHAR(255) DEFAULT NULL, sort_order INT NOT NULL DEFAULT 0, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, name_en VARCHAR(150) NOT NULL, name_fr VARCHAR(150) NOT NULL, category_en VARCHAR(150) DEFAULT NULL, category_fr VARCHAR(150) DEFAULT NULL, sort_order INT NOT NULL DEFAULT 0, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_experience (id INT AUTO_INCREMENT NOT NULL, company_en VARCHAR(200) NOT NULL, company_fr VARCHAR(200) NOT NULL, role_en VARCHAR(200) NOT NULL, role_fr VARCHAR(200) NOT NULL, location_en VARCHAR(200) DEFAULT NULL, location_fr VARCHAR(200) DEFAULT NULL, start_date DATE NOT NULL, end_date DATE DEFAULT NULL, is_current TINYINT(1) NOT NULL DEFAULT 0, description_en LONGTEXT NOT NULL, description_fr LONGTEXT NOT NULL, sort_order INT NOT NULL DEFAULT 0, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE education (id INT AUTO_INCREMENT NOT NULL, institution_en VARCHAR(200) NOT NULL, institution_fr VARCHAR(200) NOT NULL, degree_en VARCHAR(200) NOT NULL, degree_fr VARCHAR(200) NOT NULL, location_en VARCHAR(200) DEFAULT NULL, location_fr VARCHAR(200) DEFAULT NULL, start_date DATE NOT NULL, end_date DATE DEFAULT NULL, is_current TINYINT(1) NOT NULL DEFAULT 0, description_en LONGTEXT NOT NULL, description_fr LONGTEXT NOT NULL, sort_order INT NOT NULL DEFAULT 0, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resume (id INT AUTO_INCREMENT NOT NULL, title_en VARCHAR(200) DEFAULT NULL, title_fr VARCHAR(200) DEFAULT NULL, file_path_en VARCHAR(255) DEFAULT NULL, file_path_fr VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hobby (id INT AUTO_INCREMENT NOT NULL, name_en VARCHAR(150) NOT NULL, name_fr VARCHAR(150) NOT NULL, description_en LONGTEXT DEFAULT NULL, description_fr LONGTEXT DEFAULT NULL, icon VARCHAR(100) DEFAULT NULL, sort_order INT NOT NULL DEFAULT 0, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_info (id INT AUTO_INCREMENT NOT NULL, display_name VARCHAR(200) NOT NULL, headline_en VARCHAR(200) NOT NULL, headline_fr VARCHAR(200) NOT NULL, summary_en LONGTEXT NOT NULL, summary_fr LONGTEXT NOT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(50) DEFAULT NULL, location_en VARCHAR(200) DEFAULT NULL, location_fr VARCHAR(200) DEFAULT NULL, linkedin_url VARCHAR(255) DEFAULT NULL, github_url VARCHAR(255) DEFAULT NULL, website_url VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_message (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, email VARCHAR(180) NOT NULL, subject VARCHAR(200) NOT NULL, message LONGTEXT NOT NULL, locale VARCHAR(2) NOT NULL DEFAULT \'en\', is_read TINYINT(1) NOT NULL DEFAULT 0, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE testimonial (id INT AUTO_INCREMENT NOT NULL, author_name VARCHAR(150) NOT NULL, author_role_en VARCHAR(150) DEFAULT NULL, author_role_fr VARCHAR(150) DEFAULT NULL, company_en VARCHAR(150) DEFAULT NULL, company_fr VARCHAR(150) DEFAULT NULL, content_en LONGTEXT DEFAULT NULL, content_fr LONGTEXT DEFAULT NULL, status VARCHAR(20) NOT NULL DEFAULT \'pending\', submitted_locale VARCHAR(2) NOT NULL DEFAULT \'en\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE testimonial');
        $this->addSql('DROP TABLE contact_message');
        $this->addSql('DROP TABLE contact_info');
        $this->addSql('DROP TABLE hobby');
        $this->addSql('DROP TABLE resume');
        $this->addSql('DROP TABLE education');
        $this->addSql('DROP TABLE work_experience');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE users');
    }
}
