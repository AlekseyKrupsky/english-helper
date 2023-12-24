<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231224164941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE term_en_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE term_ru_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE term_en (id INT NOT NULL, term VARCHAR(255) NOT NULL, learned BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX term_en_index ON term_en (term)');
        $this->addSql('CREATE TABLE term_en_term_ru (term_en_id INT NOT NULL, term_ru_id INT NOT NULL, PRIMARY KEY(term_en_id, term_ru_id))');
        $this->addSql('CREATE INDEX IDX_AB31A525E03BECBA ON term_en_term_ru (term_en_id)');
        $this->addSql('CREATE INDEX IDX_AB31A525B5E377B6 ON term_en_term_ru (term_ru_id)');
        $this->addSql('CREATE TABLE term_ru (id INT NOT NULL, term VARCHAR(255) NOT NULL, learned BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX term_ru_index ON term_ru (term)');
        $this->addSql('ALTER TABLE term_en_term_ru ADD CONSTRAINT FK_AB31A525E03BECBA FOREIGN KEY (term_en_id) REFERENCES term_en (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE term_en_term_ru ADD CONSTRAINT FK_AB31A525B5E377B6 FOREIGN KEY (term_ru_id) REFERENCES term_ru (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE term_en_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE term_ru_id_seq CASCADE');
        $this->addSql('ALTER TABLE term_en_term_ru DROP CONSTRAINT FK_AB31A525E03BECBA');
        $this->addSql('ALTER TABLE term_en_term_ru DROP CONSTRAINT FK_AB31A525B5E377B6');
        $this->addSql('DROP TABLE term_en');
        $this->addSql('DROP TABLE term_en_term_ru');
        $this->addSql('DROP TABLE term_ru');
    }
}
