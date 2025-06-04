<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250510000309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE gerer MODIFY id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON gerer
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gerer DROP id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gerer ADD PRIMARY KEY (utilisateur_id, vol_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hangar CHANGE aeroport_id aeroport_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX unique_matricule ON pilote
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE realiser MODIFY id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON realiser
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE realiser DROP id, CHANGE matriculePilote matriculePilote INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE realiser ADD PRIMARY KEY (vol_id, matriculePilote)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE realiser RENAME INDEX idx_7bab8d07f510aae9 TO IDX_7BAB8D07E3E6E484
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur CHANGE adresse adresse LONGTEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur (email)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol ADD pilote_id INT DEFAULT NULL, CHANGE avion_id avion_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol ADD CONSTRAINT FK_95C97EBF510AAE9 FOREIGN KEY (pilote_id) REFERENCES pilote (matricule)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_95C97EBF510AAE9 ON vol (pilote_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE gerer ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur CHANGE adresse adresse VARCHAR(100) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE realiser ADD id INT AUTO_INCREMENT NOT NULL, CHANGE matriculePilote matriculePilote INT DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE realiser RENAME INDEX idx_7bab8d07e3e6e484 TO IDX_7BAB8D07F510AAE9
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX unique_matricule ON pilote (matricule)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol DROP FOREIGN KEY FK_95C97EBF510AAE9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_95C97EBF510AAE9 ON vol
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol DROP pilote_id, CHANGE avion_id avion_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hangar CHANGE aeroport_id aeroport_id INT NOT NULL
        SQL);
    }
}
