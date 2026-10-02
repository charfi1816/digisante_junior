<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261002220416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE child (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, birth_date DATE NOT NULL, avatar VARCHAR(255) NOT NULL, daily_screen_limit INT NOT NULL, parent_id INT NOT NULL, account_id INT NOT NULL, UNIQUE INDEX UNIQ_22B354299B6B5FBA (account_id), INDEX IDX_22B35429727ACA70 (parent_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE journal_entry (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, tv_screen INT NOT NULL, pc_screen INT NOT NULL, phone_screen INT NOT NULL, tablet_screen INT NOT NULL, console_screen INT NOT NULL, other_screen INT NOT NULL, child_id INT NOT NULL, UNIQUE INDEX UNIQ_JOURNAL_ENTRY_CHILD_DATE (child_id, date), INDEX IDX_C8FAAE5ADD62C21B (child_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE pain_zone (id INT AUTO_INCREMENT NOT NULL, zone VARCHAR(255) NOT NULL, intensity INT NOT NULL, journal_entry_id INT NOT NULL, INDEX IDX_1DD757436A86E4FB (journal_entry_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE wellness_content (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, title VARCHAR(255) NOT NULL, trigger_rule VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, url VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id))');
        $this->addSql('ALTER TABLE child ADD CONSTRAINT FK_22B35429727ACA70 FOREIGN KEY (parent_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE child ADD CONSTRAINT FK_22B354299B6B5FBA FOREIGN KEY (account_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE journal_entry ADD CONSTRAINT FK_C8FAAE5ADD62C21B FOREIGN KEY (child_id) REFERENCES child (id)');
        $this->addSql('ALTER TABLE pain_zone ADD CONSTRAINT FK_1DD757436A86E4FB FOREIGN KEY (journal_entry_id) REFERENCES journal_entry (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE child DROP FOREIGN KEY FK_22B35429727ACA70');
        $this->addSql('ALTER TABLE child DROP FOREIGN KEY FK_22B354299B6B5FBA');
        $this->addSql('ALTER TABLE journal_entry DROP FOREIGN KEY FK_C8FAAE5ADD62C21B');
        $this->addSql('ALTER TABLE pain_zone DROP FOREIGN KEY FK_1DD757436A86E4FB');
        $this->addSql('DROP TABLE child');
        $this->addSql('DROP TABLE journal_entry');
        $this->addSql('DROP TABLE pain_zone');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wellness_content');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
