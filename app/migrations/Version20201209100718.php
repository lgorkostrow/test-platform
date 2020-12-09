<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Domain\Currency\Enum\DefaultCurrencyEnum;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201209100718 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE currency (ccy VARCHAR(3) NOT NULL, buy NUMERIC(10, 2) DEFAULT NULL, sale NUMERIC(10, 2) DEFAULT NULL, enabled TINYINT(1) DEFAULT \'1\' NOT NULL, `default` TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(ccy)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE advertisement CHANGE currency currency VARCHAR(3) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE currency');
        $this->addSql('ALTER TABLE advertisement CHANGE currency currency VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }

    public function postUp(Schema $schema): void
    {
        $this->connection->transactional(static function (Connection $connection) {
            foreach (DefaultCurrencyEnum::LIST as $ccy) {
                $connection->createQueryBuilder()
                    ->insert('currency')
                    ->setValue('ccy', ':ccy')
                    ->setValue('`default`', ':default')
                    ->setValue('created_at', 'NOW()')
                    ->setParameter('ccy', $ccy)
                    ->setParameter('default', true, ParameterType::BOOLEAN)
                    ->execute()
                ;
            }

            $connection->createQueryBuilder()
                ->insert('currency')
                ->setValue('ccy', ':ccy')
                ->setValue('`default`', ':default')
                ->setValue('created_at', 'NOW()')
                ->setValue('sale', 1)
                ->setValue('buy', 1)
                ->setParameter('ccy', DefaultCurrencyEnum::BASE_CURRENCY)
                ->setParameter('default', true, ParameterType::BOOLEAN)
                ->execute()
            ;
        });
    }
}
