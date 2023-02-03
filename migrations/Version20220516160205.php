<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220516160205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation CHANGE id id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reponsereclamation CHANGE numero numero INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservationevenement CHANGE idEvent idEvent INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservationm CHANGE id_maison id_maison INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservationv CHANGE Id Id INT DEFAULT NULL');
        $this->addSql('DROP INDEX idS_2 ON sponsor');
        $this->addSql('ALTER TABLE users CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE pwd pwd VARCHAR(255) NOT NULL, CHANGE poste poste VARCHAR(30) DEFAULT \'NULL\', CHANGE salaire salaire VARCHAR(30) DEFAULT \'NULL\', CHANGE role role LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE reponsereclamation CHANGE numero numero INT NOT NULL');
        $this->addSql('ALTER TABLE reservationevenement CHANGE idEvent idEvent INT NOT NULL');
        $this->addSql('ALTER TABLE reservationm CHANGE id_maison id_maison INT NOT NULL');
        $this->addSql('ALTER TABLE reservationv CHANGE Id Id INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX idS_2 ON sponsor (idS)');
        $this->addSql('DROP INDEX UNIQ_1483A5E9E7927C74 ON users');
        $this->addSql('ALTER TABLE users CHANGE nom nom VARCHAR(30) NOT NULL, CHANGE prenom prenom VARCHAR(30) NOT NULL, CHANGE email email VARCHAR(30) NOT NULL, CHANGE pwd pwd VARCHAR(500) NOT NULL, CHANGE poste poste VARCHAR(30) DEFAULT NULL, CHANGE salaire salaire VARCHAR(30) DEFAULT NULL, CHANGE role role VARCHAR(30) NOT NULL');
    }
}
