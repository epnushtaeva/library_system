<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260223173351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(1500) NOT NULL, birth_date DATE NOT NULL, address VARCHAR(1500) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(2500) NOT NULL, all_count INT NOT NULL, free_count INT NOT NULL, broned_count INT NOT NULL, on_hand_count INT NOT NULL, description VARCHAR(255) NOT NULL, author_id INT NOT NULL, janre_id INT NOT NULL, INDEX IDX_CBE5A331F675F31B (author_id), INDEX IDX_CBE5A3312DDCE2DD (janre_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE book_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE book_to_cell (id INT AUTO_INCREMENT NOT NULL, broned_to DATETIME DEFAULT NULL, book_id INT NOT NULL, cell_id INT NOT NULL, status_id INT NOT NULL, INDEX IDX_BF49BEBB16A2B381 (book_id), INDEX IDX_BF49BEBBCB39D93A (cell_id), INDEX IDX_BF49BEBB6BF700BD (status_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE cell (id INT AUTO_INCREMENT NOT NULL, cell_number VARCHAR(255) NOT NULL, next_cell_id INT DEFAULT NULL, prev_cell_id INT DEFAULT NULL, INDEX IDX_CB8787E29B7F398 (next_cell_id), INDEX IDX_CB8787E297F53EEC (prev_cell_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE janre (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331F675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A3312DDCE2DD FOREIGN KEY (janre_id) REFERENCES janre (id)');
        $this->addSql('ALTER TABLE book_to_cell ADD CONSTRAINT FK_BF49BEBB16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE book_to_cell ADD CONSTRAINT FK_BF49BEBBCB39D93A FOREIGN KEY (cell_id) REFERENCES cell (id)');
        $this->addSql('ALTER TABLE book_to_cell ADD CONSTRAINT FK_BF49BEBB6BF700BD FOREIGN KEY (status_id) REFERENCES book_status (id)');
        $this->addSql('ALTER TABLE cell ADD CONSTRAINT FK_CB8787E29B7F398 FOREIGN KEY (next_cell_id) REFERENCES cell (id)');
        $this->addSql('ALTER TABLE cell ADD CONSTRAINT FK_CB8787E297F53EEC FOREIGN KEY (prev_cell_id) REFERENCES cell (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331F675F31B');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A3312DDCE2DD');
        $this->addSql('ALTER TABLE book_to_cell DROP FOREIGN KEY FK_BF49BEBB16A2B381');
        $this->addSql('ALTER TABLE book_to_cell DROP FOREIGN KEY FK_BF49BEBBCB39D93A');
        $this->addSql('ALTER TABLE book_to_cell DROP FOREIGN KEY FK_BF49BEBB6BF700BD');
        $this->addSql('ALTER TABLE cell DROP FOREIGN KEY FK_CB8787E29B7F398');
        $this->addSql('ALTER TABLE cell DROP FOREIGN KEY FK_CB8787E297F53EEC');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE book_status');
        $this->addSql('DROP TABLE book_to_cell');
        $this->addSql('DROP TABLE cell');
        $this->addSql('DROP TABLE janre');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
