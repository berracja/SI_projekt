<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240816123208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE todolists ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE todolists ADD CONSTRAINT FK_AD289A112469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_AD289A112469DE2 ON todolists (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE todolists DROP FOREIGN KEY FK_AD289A112469DE2');
        $this->addSql('DROP INDEX IDX_AD289A112469DE2 ON todolists');
        $this->addSql('ALTER TABLE todolists DROP category_id');
    }
}
