<?php

namespace Sylius\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171027081419 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sylius_customer_set (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_6D2173EB77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('INSERT INTO sylius_customer_set (id, code, name) VALUES(1, "default", "Default")');
        $this->addSql('ALTER TABLE sylius_customer ADD customer_set_id INT DEFAULT NULL');
        $this->addSql('UPDATE sylius_customer SET customer_set_id = 1');
        $this->addSql('ALTER TABLE sylius_customer ADD CONSTRAINT FK_7E82D5E6A13ED149 FOREIGN KEY (customer_set_id) REFERENCES sylius_customer_set (id)');
        $this->addSql('CREATE INDEX IDX_7E82D5E6A13ED149 ON sylius_customer (customer_set_id)');
        $this->addSql('ALTER TABLE sylius_channel ADD customer_set_id INT NOT NULL');
        $this->addSql('UPDATE sylius_channel SET customer_set_id = 1');
        $this->addSql('ALTER TABLE sylius_channel ADD CONSTRAINT FK_16C8119EA13ED149 FOREIGN KEY (customer_set_id) REFERENCES sylius_customer_set (id)');
        $this->addSql('CREATE INDEX IDX_16C8119EA13ED149 ON sylius_channel (customer_set_id)');
        $this->addSql('DROP INDEX UNIQ_7E82D5E6E7927C74 ON sylius_customer');
        $this->addSql('DROP INDEX UNIQ_7E82D5E6A0D96FBF ON sylius_customer');
        $this->addSql('CREATE UNIQUE INDEX email_customer_set ON sylius_customer (email, customer_set_id)');
        $this->addSql('CREATE UNIQUE INDEX email_canonical_customer_set ON sylius_customer (email_canonical, customer_set_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_customer DROP FOREIGN KEY FK_7E82D5E6A13ED149');
        $this->addSql('ALTER TABLE sylius_channel DROP FOREIGN KEY FK_16C8119EA13ED149');
        $this->addSql('DROP TABLE sylius_customer_set');
        $this->addSql('DROP INDEX IDX_16C8119EA13ED149 ON sylius_channel');
        $this->addSql('ALTER TABLE sylius_channel DROP customer_set_id');
        $this->addSql('DROP INDEX IDX_7E82D5E6A13ED149 ON sylius_customer');
        $this->addSql('ALTER TABLE sylius_customer DROP customer_set_id');
        $this->addSql('DROP INDEX email_customer_set ON sylius_customer');
        $this->addSql('DROP INDEX email_canonical_customer_set ON sylius_customer');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7E82D5E6E7927C74 ON sylius_customer (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7E82D5E6A0D96FBF ON sylius_customer (email_canonical)');
    }
}
