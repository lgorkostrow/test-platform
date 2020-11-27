<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201127091401 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE advertisement_attachment (attachment_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', file_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_6190E760464E68B (attachment_id), INDEX IDX_6190E76093CB796C (file_id), PRIMARY KEY(attachment_id, file_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', path VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, size NUMERIC(10, 1) DEFAULT \'0\' NOT NULL, mime_type VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE advertisement_attachment ADD CONSTRAINT FK_6190E760464E68B FOREIGN KEY (attachment_id) REFERENCES advertisement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advertisement_attachment ADD CONSTRAINT FK_6190E76093CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advertisement_attachment DROP FOREIGN KEY FK_6190E76093CB796C');
        $this->addSql('DROP TABLE advertisement_attachment');
        $this->addSql('DROP TABLE file');
    }
}
