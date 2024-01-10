<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240110142003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE term_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE ignore_term_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE ignore_term (id INT NOT NULL, value VARCHAR(512) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F389D1191D775834 ON ignore_term (value)');
        $this->addSql('ALTER TABLE term_en ALTER learned SET DEFAULT false');
        $this->addSql('ALTER TABLE term_en_term_ru DROP CONSTRAINT FK_AB31A525E03BECBA');
        $this->addSql('ALTER TABLE term_en_term_ru DROP CONSTRAINT FK_AB31A525B5E377B6');
        $this->addSql('ALTER TABLE term_en_term_ru ADD CONSTRAINT FK_AB31A525E03BECBA FOREIGN KEY (term_en_id) REFERENCES term_en (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE term_en_term_ru ADD CONSTRAINT FK_AB31A525B5E377B6 FOREIGN KEY (term_ru_id) REFERENCES term_ru (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE term_ru ALTER learned SET DEFAULT false');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE ignore_term_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE term_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE ignore_term');
        $this->addSql('ALTER TABLE term_en ALTER learned DROP DEFAULT');
        $this->addSql('ALTER TABLE term_en_term_ru DROP CONSTRAINT fk_ab31a525e03becba');
        $this->addSql('ALTER TABLE term_en_term_ru DROP CONSTRAINT fk_ab31a525b5e377b6');
        $this->addSql('ALTER TABLE term_en_term_ru ADD CONSTRAINT fk_ab31a525e03becba FOREIGN KEY (term_en_id) REFERENCES term_en (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE term_en_term_ru ADD CONSTRAINT fk_ab31a525b5e377b6 FOREIGN KEY (term_ru_id) REFERENCES term_ru (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE term_ru ALTER learned DROP DEFAULT');
    }
}
