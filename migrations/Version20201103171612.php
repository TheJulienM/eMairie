<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201103171612 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, id_projet INT NOT NULL, texte LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mairie CHANGE codepostal code_postal INT NOT NULL');
        $this->addSql('ALTER TABLE projets ADD id_user INT DEFAULT NULL, ADD id_mairie INT DEFAULT NULL, DROP idUser, DROP idMairie, CHANGE nblike nb_like INT NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE idrole id_role INT NOT NULL, CHANGE iddiscord id_discord INT DEFAULT NULL, CHANGE villemairie ville_mairie VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('ALTER TABLE mairie CHANGE code_postal codePostal INT NOT NULL');
        $this->addSql('ALTER TABLE projets ADD idUser INT DEFAULT NULL, ADD idMairie INT DEFAULT NULL, DROP id_user, DROP id_mairie, CHANGE nb_like nbLike INT NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE id_role idRole INT NOT NULL, CHANGE id_discord idDiscord INT DEFAULT NULL, CHANGE ville_mairie villeMairie VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
