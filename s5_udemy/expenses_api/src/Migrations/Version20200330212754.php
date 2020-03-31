<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200330212754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates `user` table';
    }

    public function up(Schema $schema): void
    {
        //el tipo BINARY la comparación es más rapido y más eficiente tambien
        //permite comparar la contraseña por el cod bin y no su caracter
        $this->addSql('
            CREATE TABLE user (
                id CHAR(36) NOT NULL PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL,
                password BINARY(200) NOT NULL,
                roles VARCHAR(100) NOT NULL,
                create_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL
            )
            DEFAULT CHARACTER SET utf8mb4 
            COLLATE utf8mb4_general_ci
            ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user');
    }
}
