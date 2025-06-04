<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250505151615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE aeroport (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, pays VARCHAR(100) NOT NULL, ville VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE avion (id INT AUTO_INCREMENT NOT NULL, hangar_id INT DEFAULT NULL, modele VARCHAR(100) NOT NULL, nb_places INT NOT NULL, INDEX IDX_234D9D382FEFB5A5 (hangar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE gerer (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, vol_id INT NOT NULL, INDEX IDX_103C68BDFB88E14F (utilisateur_id), INDEX IDX_103C68BD9F2BFB7A (vol_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE hangar (id INT AUTO_INCREMENT NOT NULL, aeroport_id INT NOT NULL, nom VARCHAR(100) NOT NULL, capacite INT NOT NULL, INDEX IDX_A5BB650AF1089B86 (aeroport_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE pilote (id INT AUTO_INCREMENT NOT NULL, matricule INT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE realiser (id INT AUTO_INCREMENT NOT NULL, vol_id INT NOT NULL, pilote_id INT NOT NULL, INDEX IDX_7BAB8D079F2BFB7A (vol_id), INDEX IDX_7BAB8D07F510AAE9 (pilote_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, adresse VARCHAR(100) NOT NULL, numero_de_telephone VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE vol (id INT AUTO_INCREMENT NOT NULL, aeroport_depart_id INT NOT NULL, aeroport_arrivee_id INT NOT NULL, avion_id INT NOT NULL, date_heure_depart DATETIME NOT NULL, date_heure_arrivee DATETIME NOT NULL, date_heure_prevue_depart DATETIME NOT NULL, date_heure_prevue_arrivee DATETIME NOT NULL, INDEX IDX_95C97EBE3CBAF6E (aeroport_depart_id), INDEX IDX_95C97EBA1B96354 (aeroport_arrivee_id), INDEX IDX_95C97EB80BBB841 (avion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avion ADD CONSTRAINT FK_234D9D382FEFB5A5 FOREIGN KEY (hangar_id) REFERENCES hangar (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gerer ADD CONSTRAINT FK_103C68BDFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gerer ADD CONSTRAINT FK_103C68BD9F2BFB7A FOREIGN KEY (vol_id) REFERENCES vol (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hangar ADD CONSTRAINT FK_A5BB650AF1089B86 FOREIGN KEY (aeroport_id) REFERENCES aeroport (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE realiser ADD CONSTRAINT FK_7BAB8D079F2BFB7A FOREIGN KEY (vol_id) REFERENCES vol (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE realiser ADD CONSTRAINT FK_7BAB8D07F510AAE9 FOREIGN KEY (pilote_id) REFERENCES pilote (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol ADD CONSTRAINT FK_95C97EBE3CBAF6E FOREIGN KEY (aeroport_depart_id) REFERENCES aeroport (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol ADD CONSTRAINT FK_95C97EBA1B96354 FOREIGN KEY (aeroport_arrivee_id) REFERENCES aeroport (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol ADD CONSTRAINT FK_95C97EB80BBB841 FOREIGN KEY (avion_id) REFERENCES avion (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE avion DROP FOREIGN KEY FK_234D9D382FEFB5A5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gerer DROP FOREIGN KEY FK_103C68BDFB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gerer DROP FOREIGN KEY FK_103C68BD9F2BFB7A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hangar DROP FOREIGN KEY FK_A5BB650AF1089B86
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE realiser DROP FOREIGN KEY FK_7BAB8D079F2BFB7A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE realiser DROP FOREIGN KEY FK_7BAB8D07F510AAE9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol DROP FOREIGN KEY FK_95C97EBE3CBAF6E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol DROP FOREIGN KEY FK_95C97EBA1B96354
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol DROP FOREIGN KEY FK_95C97EB80BBB841
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE aeroport
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE avion
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE gerer
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE hangar
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE pilote
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE realiser
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE utilisateur
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE vol
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
