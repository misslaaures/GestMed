<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200930205824 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE acte (id INT AUTO_INCREMENT NOT NULL, libact VARCHAR(100) NOT NULL, prixact INT NOT NULL, datemodif DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, datemodif DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, patientid_id INT NOT NULL, praticienid_id INT NOT NULL, dateconsult DATE NOT NULL, motif VARCHAR(255) DEFAULT NULL, examen LONGTEXT DEFAULT NULL, diagnostic LONGTEXT DEFAULT NULL, observation LONGTEXT DEFAULT NULL, datemodif DATETIME NOT NULL, INDEX IDX_964685A6ABF0A384 (patientid_id), INDEX IDX_964685A6C9DE4F32 (praticienid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entree (id INT AUTO_INCREMENT NOT NULL, produitid_id INT NOT NULL, qteentree INT NOT NULL, description VARCHAR(100) NOT NULL, datemodif DATETIME NOT NULL, INDEX IDX_598377A6BF993A3 (produitid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, nompat VARCHAR(100) NOT NULL, sexe VARCHAR(10) NOT NULL, datenaispat DATE NOT NULL, fonctionpat VARCHAR(100) NOT NULL, adressepat VARCHAR(255) NOT NULL, telephonepat VARCHAR(100) NOT NULL, datemodif DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE praticien (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, sexe VARCHAR(10) NOT NULL, datenais DATE NOT NULL, specialite VARCHAR(100) NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(100) NOT NULL, datemodif DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, categorieid_id INT NOT NULL, designation VARCHAR(100) NOT NULL, prix INT NOT NULL, qtestock INT NOT NULL, stockmini INT NOT NULL, INDEX IDX_29A5EC275548691B (categorieid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sortie (id INT AUTO_INCREMENT NOT NULL, produitid_id INT NOT NULL, qtesortie INT NOT NULL, description LONGTEXT NOT NULL, datemodif DATETIME NOT NULL, INDEX IDX_3C3FD3F2BF993A3 (produitid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A6ABF0A384 FOREIGN KEY (patientid_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A6C9DE4F32 FOREIGN KEY (praticienid_id) REFERENCES praticien (id)');
        $this->addSql('ALTER TABLE entree ADD CONSTRAINT FK_598377A6BF993A3 FOREIGN KEY (produitid_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC275548691B FOREIGN KEY (categorieid_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2BF993A3 FOREIGN KEY (produitid_id) REFERENCES produit (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC275548691B');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A6ABF0A384');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A6C9DE4F32');
        $this->addSql('ALTER TABLE entree DROP FOREIGN KEY FK_598377A6BF993A3');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2BF993A3');
        $this->addSql('DROP TABLE acte');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE entree');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE praticien');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE sortie');
    }
}
