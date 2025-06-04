<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506191256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Aeroport CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE pays pays VARCHAR(100) NOT NULL, CHANGE ville ville VARCHAR(100) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Avion ADD nb_places INT NOT NULL, DROP nbPlaces, CHANGE modele modele VARCHAR(100) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Avion RENAME INDEX idhangar TO IDX_234D9D38C6AACD90
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON Gerer
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Gerer ADD PRIMARY KEY (idUtilisateur, idVol)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Gerer RENAME INDEX idutilisateur TO IDX_103C68BD5D419CCB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Gerer RENAME INDEX idx_d1fd47b928148a6c TO IDX_103C68BD28148A6C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Hangar CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE capacite capacite INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Hangar RENAME INDEX idaeroport TO IDX_A5BB650ACC13F8D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Pilote CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE prenom prenom VARCHAR(100) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Realiser DROP FOREIGN KEY realiser_ibfk_2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX matriculePilote ON Realiser
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON Realiser
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Realiser CHANGE matriculePilote matricule INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Realiser ADD CONSTRAINT FK_7BAB8D0712B2DC9C FOREIGN KEY (matricule) REFERENCES pilote (matricule)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7BAB8D0712B2DC9C ON Realiser (matricule)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Realiser ADD PRIMARY KEY (idVol, matricule)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Realiser RENAME INDEX idx_82ddef5128148a6c TO IDX_7BAB8D0728148A6C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Utilisateur CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE prenom prenom VARCHAR(100) NOT NULL, CHANGE email email VARCHAR(100) NOT NULL, CHANGE mot_de_passe mot_de_passe VARCHAR(255) NOT NULL, CHANGE adresse adresse LONGTEXT NOT NULL, CHANGE numero_de_telephone numero_de_telephone VARCHAR(20) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Utilisateur RENAME INDEX email TO UNIQ_1D1C63B3E7927C74
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Vol DROP FOREIGN KEY vol_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Vol DROP FOREIGN KEY vol_ibfk_2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX aeroportDepart ON Vol
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX aeroportArrivee ON Vol
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Vol ADD date_heure_depart DATETIME NOT NULL, ADD date_heure_arrivee DATETIME NOT NULL, ADD date_heure_prevue_depart DATETIME NOT NULL, ADD date_heure_prevue_arrivee DATETIME NOT NULL, ADD aeroportDepart_id INT NOT NULL, ADD aeroportArrivee_id INT NOT NULL, DROP dateHeureDepart, DROP dateHeureArrivee, DROP dateHeurePrevueDepart, DROP dateHeurePrevueArrivee, DROP aeroportDepart, DROP aeroportArrivee
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Vol ADD CONSTRAINT FK_95C97EB790344E8 FOREIGN KEY (aeroportDepart_id) REFERENCES aeroport (idAeroport)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Vol ADD CONSTRAINT FK_95C97EBA5F88DAA FOREIGN KEY (aeroportArrivee_id) REFERENCES aeroport (idAeroport)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_95C97EB790344E8 ON Vol (aeroportDepart_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_95C97EBA5F88DAA ON Vol (aeroportArrivee_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Vol RENAME INDEX idavion TO IDX_95C97EB3489DE25
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE aeroport CHANGE nom nom VARCHAR(100) DEFAULT NULL, CHANGE pays pays VARCHAR(100) DEFAULT NULL, CHANGE ville ville VARCHAR(100) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avion ADD nbPlaces INT DEFAULT NULL, DROP nb_places, CHANGE modele modele VARCHAR(100) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avion RENAME INDEX idx_234d9d38c6aacd90 TO idHangar
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON gerer
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gerer ADD PRIMARY KEY (idVol, idUtilisateur)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gerer RENAME INDEX idx_103c68bd5d419ccb TO idUtilisateur
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gerer RENAME INDEX idx_103c68bd28148a6c TO IDX_D1FD47B928148A6C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hangar CHANGE nom nom VARCHAR(100) DEFAULT NULL, CHANGE capacite capacite INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hangar RENAME INDEX idx_a5bb650acc13f8d3 TO idAeroport
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pilote CHANGE nom nom VARCHAR(100) DEFAULT NULL, CHANGE prenom prenom VARCHAR(100) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE realiser DROP FOREIGN KEY FK_7BAB8D0712B2DC9C
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_7BAB8D0712B2DC9C ON realiser
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON realiser
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE realiser CHANGE matricule matriculePilote INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE realiser ADD CONSTRAINT realiser_ibfk_2 FOREIGN KEY (matriculePilote) REFERENCES Pilote (matricule) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX matriculePilote ON realiser (matriculePilote)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE realiser ADD PRIMARY KEY (idVol, matriculePilote)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE realiser RENAME INDEX idx_7bab8d0728148a6c TO IDX_82DDEF5128148A6C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur CHANGE nom nom VARCHAR(100) DEFAULT NULL, CHANGE prenom prenom VARCHAR(100) DEFAULT NULL, CHANGE email email VARCHAR(100) DEFAULT NULL, CHANGE mot_de_passe mot_de_passe VARCHAR(255) DEFAULT NULL, CHANGE adresse adresse TEXT DEFAULT NULL, CHANGE numero_de_telephone numero_de_telephone VARCHAR(20) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur RENAME INDEX uniq_1d1c63b3e7927c74 TO email
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol DROP FOREIGN KEY FK_95C97EB790344E8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol DROP FOREIGN KEY FK_95C97EBA5F88DAA
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_95C97EB790344E8 ON vol
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_95C97EBA5F88DAA ON vol
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol ADD dateHeureDepart DATETIME DEFAULT NULL, ADD dateHeureArrivee DATETIME DEFAULT NULL, ADD dateHeurePrevueDepart DATETIME DEFAULT NULL, ADD dateHeurePrevueArrivee DATETIME DEFAULT NULL, ADD aeroportDepart INT DEFAULT NULL, ADD aeroportArrivee INT DEFAULT NULL, DROP date_heure_depart, DROP date_heure_arrivee, DROP date_heure_prevue_depart, DROP date_heure_prevue_arrivee, DROP aeroportDepart_id, DROP aeroportArrivee_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol ADD CONSTRAINT vol_ibfk_1 FOREIGN KEY (aeroportDepart) REFERENCES Aeroport (idAeroport) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol ADD CONSTRAINT vol_ibfk_2 FOREIGN KEY (aeroportArrivee) REFERENCES Aeroport (idAeroport) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX aeroportDepart ON vol (aeroportDepart)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX aeroportArrivee ON vol (aeroportArrivee)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol RENAME INDEX idx_95c97eb3489de25 TO idAvion
        SQL);
    }
}
