<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221007132103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_offer (tag_id INT NOT NULL, offer_id INT NOT NULL, INDEX IDX_B3A9E38BAD26311 (tag_id), INDEX IDX_B3A9E3853C674EE (offer_id), PRIMARY KEY(tag_id, offer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tag_offer ADD CONSTRAINT FK_B3A9E38BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_offer ADD CONSTRAINT FK_B3A9E3853C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag_offer DROP FOREIGN KEY FK_B3A9E38BAD26311');
        $this->addSql('ALTER TABLE tag_offer DROP FOREIGN KEY FK_B3A9E3853C674EE');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_offer');
    }
}
