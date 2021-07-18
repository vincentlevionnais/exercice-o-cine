<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210718103658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE casting ADD person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE casting ADD CONSTRAINT FK_D11BBA50217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_D11BBA50217BBB47 ON casting (person_id)');
        $this->addSql('ALTER TABLE movie ADD casting_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26F9EB2648F FOREIGN KEY (casting_id) REFERENCES casting (id)');
        $this->addSql('CREATE INDEX IDX_1D5EF26F9EB2648F ON movie (casting_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE casting DROP FOREIGN KEY FK_D11BBA50217BBB47');
        $this->addSql('DROP INDEX IDX_D11BBA50217BBB47 ON casting');
        $this->addSql('ALTER TABLE casting DROP person_id');
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26F9EB2648F');
        $this->addSql('DROP INDEX IDX_1D5EF26F9EB2648F ON movie');
        $this->addSql('ALTER TABLE movie DROP casting_id');
    }
}
