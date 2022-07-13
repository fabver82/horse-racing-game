<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220713115931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bet (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, horse_id INT NOT NULL, race_id INT NOT NULL, odd DOUBLE PRECISION NOT NULL, result VARCHAR(10) DEFAULT NULL, amount INT NOT NULL, INDEX IDX_FBF0EC9BA76ED395 (user_id), INDEX IDX_FBF0EC9B76B275AD (horse_id), INDEX IDX_FBF0EC9B6E59D40D (race_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE horse (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, image VARCHAR(255) NOT NULL, speed DOUBLE PRECISION NOT NULL, endurance DOUBLE PRECISION NOT NULL, form DOUBLE PRECISION NOT NULL, fitness DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE race (id INT AUTO_INCREMENT NOT NULL, winner_id INT DEFAULT NULL, start_at DATETIME NOT NULL, length INT NOT NULL, INDEX IDX_DA6FBBAF5DFCD4B8 (winner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE race_horse (race_id INT NOT NULL, horse_id INT NOT NULL, INDEX IDX_84A40E096E59D40D (race_id), INDEX IDX_84A40E0976B275AD (horse_id), PRIMARY KEY(race_id, horse_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, coins INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9B76B275AD FOREIGN KEY (horse_id) REFERENCES horse (id)');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9B6E59D40D FOREIGN KEY (race_id) REFERENCES race (id)');
        $this->addSql('ALTER TABLE race ADD CONSTRAINT FK_DA6FBBAF5DFCD4B8 FOREIGN KEY (winner_id) REFERENCES horse (id)');
        $this->addSql('ALTER TABLE race_horse ADD CONSTRAINT FK_84A40E096E59D40D FOREIGN KEY (race_id) REFERENCES race (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE race_horse ADD CONSTRAINT FK_84A40E0976B275AD FOREIGN KEY (horse_id) REFERENCES horse (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bet DROP FOREIGN KEY FK_FBF0EC9B76B275AD');
        $this->addSql('ALTER TABLE race DROP FOREIGN KEY FK_DA6FBBAF5DFCD4B8');
        $this->addSql('ALTER TABLE race_horse DROP FOREIGN KEY FK_84A40E0976B275AD');
        $this->addSql('ALTER TABLE bet DROP FOREIGN KEY FK_FBF0EC9B6E59D40D');
        $this->addSql('ALTER TABLE race_horse DROP FOREIGN KEY FK_84A40E096E59D40D');
        $this->addSql('ALTER TABLE bet DROP FOREIGN KEY FK_FBF0EC9BA76ED395');
        $this->addSql('DROP TABLE bet');
        $this->addSql('DROP TABLE horse');
        $this->addSql('DROP TABLE race');
        $this->addSql('DROP TABLE race_horse');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
