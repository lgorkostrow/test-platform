<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130131226 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advertisement_attachment DROP FOREIGN KEY FK_6190E760464E68B');
        $this->addSql('DROP INDEX IDX_6190E760464E68B ON advertisement_attachment');
        $this->addSql('ALTER TABLE advertisement_attachment DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE advertisement_attachment CHANGE attachment_id advertisement_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE advertisement_attachment ADD CONSTRAINT FK_6190E760A1FBF71B FOREIGN KEY (advertisement_id) REFERENCES advertisement (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_6190E760A1FBF71B ON advertisement_attachment (advertisement_id)');
        $this->addSql('ALTER TABLE advertisement_attachment ADD PRIMARY KEY (advertisement_id, file_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advertisement_attachment DROP FOREIGN KEY FK_6190E760A1FBF71B');
        $this->addSql('DROP INDEX IDX_6190E760A1FBF71B ON advertisement_attachment');
        $this->addSql('ALTER TABLE advertisement_attachment DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE advertisement_attachment CHANGE advertisement_id attachment_id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE advertisement_attachment ADD CONSTRAINT FK_6190E760464E68B FOREIGN KEY (attachment_id) REFERENCES advertisement (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_6190E760464E68B ON advertisement_attachment (attachment_id)');
        $this->addSql('ALTER TABLE advertisement_attachment ADD PRIMARY KEY (attachment_id, file_id)');
    }
}
