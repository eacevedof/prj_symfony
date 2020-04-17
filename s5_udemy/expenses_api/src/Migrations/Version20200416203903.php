<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200416203903 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_user_group_user_id');
        $this->addSql('ALTER TABLE user_group CHANGE id id VARCHAR(255) NOT NULL, CHANGE owner_id owner_id VARCHAR(255) DEFAULT NULL, CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_group RENAME INDEX idx_user_group_user_id TO IDX_8F02BF9D7E3C61F9');
        $this->addSql('ALTER TABLE user CHANGE id id VARCHAR(255) NOT NULL, CHANGE name name VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\'');
        $this->addSql('ALTER TABLE user_group_user DROP FOREIGN KEY FK_user_group_user_group_id');
        $this->addSql('ALTER TABLE user_group_user DROP FOREIGN KEY FK_user_group_user_user_id');
        $this->addSql('ALTER TABLE user_group_user CHANGE user_id user_id VARCHAR(255) NOT NULL, CHANGE group_id group_id VARCHAR(255) NOT NULL, ADD PRIMARY KEY (user_id, group_id)');
        $this->addSql('ALTER TABLE user_group_user ADD CONSTRAINT FK_3AE4BD5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group_user ADD CONSTRAINT FK_3AE4BD5FE54D947 FOREIGN KEY (group_id) REFERENCES user_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group_user RENAME INDEX idx_user_group_user_user_id TO IDX_3AE4BD5A76ED395');
        $this->addSql('ALTER TABLE user_group_user RENAME INDEX idx_user_group_user_group_id TO IDX_3AE4BD5FE54D947');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE id id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE name name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE email email VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE password password BINARY(200) NOT NULL, CHANGE roles roles VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9D7E3C61F9');
        $this->addSql('ALTER TABLE user_group CHANGE id id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE owner_id owner_id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE name name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_user_group_user_id FOREIGN KEY (owner_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group RENAME INDEX idx_8f02bf9d7e3c61f9 TO IDX_user_group_user_id');
        $this->addSql('ALTER TABLE user_group_user DROP FOREIGN KEY FK_3AE4BD5A76ED395');
        $this->addSql('ALTER TABLE user_group_user DROP FOREIGN KEY FK_3AE4BD5FE54D947');
        $this->addSql('ALTER TABLE user_group_user DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE user_group_user CHANGE user_id user_id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE group_id group_id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE user_group_user ADD CONSTRAINT FK_user_group_user_group_id FOREIGN KEY (group_id) REFERENCES user_group (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group_user ADD CONSTRAINT FK_user_group_user_user_id FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group_user RENAME INDEX idx_3ae4bd5a76ed395 TO IDX_user_group_user_user_id');
        $this->addSql('ALTER TABLE user_group_user RENAME INDEX idx_3ae4bd5fe54d947 TO IDX_user_group_user_group_id');
    }
}
