<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260227033532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commerce (id BINARY(16) NOT NULL, name VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, lat VARCHAR(255) DEFAULT NULL, lng VARCHAR(255) DEFAULT NULL, active TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, stamp VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, owner_id BINARY(16) NOT NULL, category_id BINARY(16) NOT NULL, INDEX IDX_BBF5FDF97E3C61F9 (owner_id), INDEX IDX_BBF5FDF912469DE2 (category_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE commerce_category (id BINARY(16) NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE stamp (id BINARY(16) NOT NULL, active TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, user_id BINARY(16) NOT NULL, commerce_id BINARY(16) NOT NULL, INDEX IDX_554E9F08A76ED395 (user_id), INDEX IDX_554E9F08B09114B7 (commerce_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE swap (id BINARY(16) NOT NULL, user_id BINARY(16) NOT NULL, ticket_id BINARY(16) NOT NULL, INDEX IDX_25938561A76ED395 (user_id), INDEX IDX_25938561700047D2 (ticket_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE ticket (id BINARY(16) NOT NULL, qty INT NOT NULL, image VARCHAR(255) NOT NULL, active TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, discount INT NOT NULL, total INT NOT NULL, commerce_id BINARY(16) NOT NULL, INDEX IDX_97A0ADA3B09114B7 (commerce_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id BINARY(16) NOT NULL, name VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, active TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE commerce ADD CONSTRAINT FK_BBF5FDF97E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commerce ADD CONSTRAINT FK_BBF5FDF912469DE2 FOREIGN KEY (category_id) REFERENCES commerce_category (id)');
        $this->addSql('ALTER TABLE stamp ADD CONSTRAINT FK_554E9F08A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE stamp ADD CONSTRAINT FK_554E9F08B09114B7 FOREIGN KEY (commerce_id) REFERENCES commerce (id)');
        $this->addSql('ALTER TABLE swap ADD CONSTRAINT FK_25938561A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE swap ADD CONSTRAINT FK_25938561700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3B09114B7 FOREIGN KEY (commerce_id) REFERENCES commerce (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commerce DROP FOREIGN KEY FK_BBF5FDF97E3C61F9');
        $this->addSql('ALTER TABLE commerce DROP FOREIGN KEY FK_BBF5FDF912469DE2');
        $this->addSql('ALTER TABLE stamp DROP FOREIGN KEY FK_554E9F08A76ED395');
        $this->addSql('ALTER TABLE stamp DROP FOREIGN KEY FK_554E9F08B09114B7');
        $this->addSql('ALTER TABLE swap DROP FOREIGN KEY FK_25938561A76ED395');
        $this->addSql('ALTER TABLE swap DROP FOREIGN KEY FK_25938561700047D2');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3B09114B7');
        $this->addSql('DROP TABLE commerce');
        $this->addSql('DROP TABLE commerce_category');
        $this->addSql('DROP TABLE stamp');
        $this->addSql('DROP TABLE swap');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE user');
    }
}
