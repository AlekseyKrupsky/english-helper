<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231224174140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX term_en_index');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_424C93EEA50FE78D ON term_en (term)');
        $this->addSql('DROP INDEX term_ru_index');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CDAADE94A50FE78D ON term_ru (term)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_424C93EEA50FE78D');
        $this->addSql('CREATE INDEX term_en_index ON term_en (term)');
        $this->addSql('DROP INDEX UNIQ_CDAADE94A50FE78D');
        $this->addSql('CREATE INDEX term_ru_index ON term_ru (term)');
    }
}
