<?php
// src/Migrations/Version20200424122452.php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200424122452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates `category` table and its relationships';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE category (
                id CHAR(36) NOT NULL PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                user_id CHAR(36) DEFAULT NULL,
                group_id CHAR(36) DEFAULT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL,
                INDEX idx_category_user_id (user_id),
                INDEX idx_category_group_id (group_id),
                CONSTRAINT fk_category_user_id FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE,
                CONSTRAINT fk_category_group_id FOREIGN KEY (group_id) REFERENCES user_group (id) ON UPDATE CASCADE ON DELETE CASCADE
            ) 
            DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE category');
    }
}
