<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261003192223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE content_recommendation (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, journal_entry_id INT NOT NULL, wellness_content_id INT NOT NULL, INDEX IDX_29D9DE066A86E4FB (journal_entry_id), INDEX IDX_29D9DE06ECE23650 (wellness_content_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE content_recommendation ADD CONSTRAINT FK_29D9DE066A86E4FB FOREIGN KEY (journal_entry_id) REFERENCES journal_entry (id)');
        $this->addSql('ALTER TABLE content_recommendation ADD CONSTRAINT FK_29D9DE06ECE23650 FOREIGN KEY (wellness_content_id) REFERENCES wellness_content (id)');
        $this->addSql('ALTER TABLE wellness_content DROP trigger_rule');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content_recommendation DROP FOREIGN KEY FK_29D9DE066A86E4FB');
        $this->addSql('ALTER TABLE content_recommendation DROP FOREIGN KEY FK_29D9DE06ECE23650');
        $this->addSql('DROP TABLE content_recommendation');
        $this->addSql('ALTER TABLE wellness_content ADD trigger_rule VARCHAR(255) NOT NULL');
    }
}
