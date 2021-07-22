<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210722131248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team ADD movie_id INT NOT NULL, ADD person_id INT NOT NULL, ADD job_id INT NOT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FBE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61F8F93B6FC ON team (movie_id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61F217BBB47 ON team (person_id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61FBE04EA9 ON team (job_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F8F93B6FC');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F217BBB47');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FBE04EA9');
        $this->addSql('DROP INDEX IDX_C4E0A61F8F93B6FC ON team');
        $this->addSql('DROP INDEX IDX_C4E0A61F217BBB47 ON team');
        $this->addSql('DROP INDEX IDX_C4E0A61FBE04EA9 ON team');
        $this->addSql('ALTER TABLE team DROP movie_id, DROP person_id, DROP job_id');
    }
}
