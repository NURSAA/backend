<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230124134156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dish_orders (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, dish_id INT NOT NULL, details VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted_status VARCHAR(255) NOT NULL, INDEX IDX_ED28FAE18D9F6D38 (order_id), INDEX IDX_ED28FAE1148EB0CB (dish_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `dishes` (id INT AUTO_INCREMENT NOT NULL, file_id INT DEFAULT NULL, menu_section_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price INT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted_status VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_584DD35D93CB796C (file_id), INDEX IDX_584DD35DF98E57A8 (menu_section_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dish_ingredient (dish_id INT NOT NULL, ingredient_id INT NOT NULL, INDEX IDX_77196056148EB0CB (dish_id), INDEX IDX_77196056933FE08C (ingredient_id), PRIMARY KEY(dish_id, ingredient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `files` (id INT AUTO_INCREMENT NOT NULL, original_name VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted_status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `floors` (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, level INT NOT NULL, name VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted_status VARCHAR(255) NOT NULL, INDEX IDX_C7668712B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `ingredient_groups` (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, name VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted_status VARCHAR(255) NOT NULL, INDEX IDX_1C17F931B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `ingredients` (id INT AUTO_INCREMENT NOT NULL, ingredient_group_id INT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted_status VARCHAR(255) NOT NULL, INDEX IDX_4B60114F8C5289C9 (ingredient_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `menu_sections` (id INT AUTO_INCREMENT NOT NULL, menu_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, section_order INT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted_status VARCHAR(255) NOT NULL, INDEX IDX_7C7060ECCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `menus` (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted_status VARCHAR(255) NOT NULL, INDEX IDX_727508CFB1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `orders` (id INT AUTO_INCREMENT NOT NULL, reservation_id INT NOT NULL, payment_id INT NOT NULL, status VARCHAR(255) NOT NULL, amount INT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted_status VARCHAR(255) NOT NULL, INDEX IDX_E52FFDEEB83297E7 (reservation_id), UNIQUE INDEX UNIQ_E52FFDEE4C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payments (id INT AUTO_INCREMENT NOT NULL, amount DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted_status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservations (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, restaurant_id INT NOT NULL, start DATE NOT NULL, end DATE NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted_status VARCHAR(255) NOT NULL, INDEX IDX_4DA239A76ED395 (user_id), INDEX IDX_4DA239B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_table (reservation_id INT NOT NULL, table_id INT NOT NULL, INDEX IDX_B5565FE1B83297E7 (reservation_id), INDEX IDX_B5565FE1ECFF285C (table_id), PRIMARY KEY(reservation_id, table_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `restaurants` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted_status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `tables` (id INT AUTO_INCREMENT NOT NULL, floor_id INT NOT NULL, name VARCHAR(255) NOT NULL, seats INT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted_status VARCHAR(255) NOT NULL, INDEX IDX_84470221854679E2 (floor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `users` (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, role VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted_status VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), INDEX IDX_1483A5E9B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dish_orders ADD CONSTRAINT FK_ED28FAE18D9F6D38 FOREIGN KEY (order_id) REFERENCES `orders` (id)');
        $this->addSql('ALTER TABLE dish_orders ADD CONSTRAINT FK_ED28FAE1148EB0CB FOREIGN KEY (dish_id) REFERENCES `dishes` (id)');
        $this->addSql('ALTER TABLE `dishes` ADD CONSTRAINT FK_584DD35D93CB796C FOREIGN KEY (file_id) REFERENCES `files` (id)');
        $this->addSql('ALTER TABLE `dishes` ADD CONSTRAINT FK_584DD35DF98E57A8 FOREIGN KEY (menu_section_id) REFERENCES `menu_sections` (id)');
        $this->addSql('ALTER TABLE dish_ingredient ADD CONSTRAINT FK_77196056148EB0CB FOREIGN KEY (dish_id) REFERENCES `dishes` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dish_ingredient ADD CONSTRAINT FK_77196056933FE08C FOREIGN KEY (ingredient_id) REFERENCES `ingredients` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `floors` ADD CONSTRAINT FK_C7668712B1E7706E FOREIGN KEY (restaurant_id) REFERENCES `restaurants` (id)');
        $this->addSql('ALTER TABLE `ingredient_groups` ADD CONSTRAINT FK_1C17F931B1E7706E FOREIGN KEY (restaurant_id) REFERENCES `restaurants` (id)');
        $this->addSql('ALTER TABLE `ingredients` ADD CONSTRAINT FK_4B60114F8C5289C9 FOREIGN KEY (ingredient_group_id) REFERENCES `ingredient_groups` (id)');
        $this->addSql('ALTER TABLE `menu_sections` ADD CONSTRAINT FK_7C7060ECCD7E912 FOREIGN KEY (menu_id) REFERENCES `menus` (id)');
        $this->addSql('ALTER TABLE `menus` ADD CONSTRAINT FK_727508CFB1E7706E FOREIGN KEY (restaurant_id) REFERENCES `restaurants` (id)');
        $this->addSql('ALTER TABLE `orders` ADD CONSTRAINT FK_E52FFDEEB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservations (id)');
        $this->addSql('ALTER TABLE `orders` ADD CONSTRAINT FK_E52FFDEE4C3A3BB FOREIGN KEY (payment_id) REFERENCES payments (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239A76ED395 FOREIGN KEY (user_id) REFERENCES `users` (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239B1E7706E FOREIGN KEY (restaurant_id) REFERENCES `restaurants` (id)');
        $this->addSql('ALTER TABLE reservation_table ADD CONSTRAINT FK_B5565FE1B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservations (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_table ADD CONSTRAINT FK_B5565FE1ECFF285C FOREIGN KEY (table_id) REFERENCES `tables` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `tables` ADD CONSTRAINT FK_84470221854679E2 FOREIGN KEY (floor_id) REFERENCES `floors` (id)');
        $this->addSql('ALTER TABLE `users` ADD CONSTRAINT FK_1483A5E9B1E7706E FOREIGN KEY (restaurant_id) REFERENCES `restaurants` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dish_orders DROP FOREIGN KEY FK_ED28FAE1148EB0CB');
        $this->addSql('ALTER TABLE dish_ingredient DROP FOREIGN KEY FK_77196056148EB0CB');
        $this->addSql('ALTER TABLE `dishes` DROP FOREIGN KEY FK_584DD35D93CB796C');
        $this->addSql('ALTER TABLE `tables` DROP FOREIGN KEY FK_84470221854679E2');
        $this->addSql('ALTER TABLE `ingredients` DROP FOREIGN KEY FK_4B60114F8C5289C9');
        $this->addSql('ALTER TABLE dish_ingredient DROP FOREIGN KEY FK_77196056933FE08C');
        $this->addSql('ALTER TABLE `dishes` DROP FOREIGN KEY FK_584DD35DF98E57A8');
        $this->addSql('ALTER TABLE `menu_sections` DROP FOREIGN KEY FK_7C7060ECCD7E912');
        $this->addSql('ALTER TABLE dish_orders DROP FOREIGN KEY FK_ED28FAE18D9F6D38');
        $this->addSql('ALTER TABLE `orders` DROP FOREIGN KEY FK_E52FFDEE4C3A3BB');
        $this->addSql('ALTER TABLE `orders` DROP FOREIGN KEY FK_E52FFDEEB83297E7');
        $this->addSql('ALTER TABLE reservation_table DROP FOREIGN KEY FK_B5565FE1B83297E7');
        $this->addSql('ALTER TABLE `floors` DROP FOREIGN KEY FK_C7668712B1E7706E');
        $this->addSql('ALTER TABLE `ingredient_groups` DROP FOREIGN KEY FK_1C17F931B1E7706E');
        $this->addSql('ALTER TABLE `menus` DROP FOREIGN KEY FK_727508CFB1E7706E');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239B1E7706E');
        $this->addSql('ALTER TABLE `users` DROP FOREIGN KEY FK_1483A5E9B1E7706E');
        $this->addSql('ALTER TABLE reservation_table DROP FOREIGN KEY FK_B5565FE1ECFF285C');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239A76ED395');
        $this->addSql('DROP TABLE dish_orders');
        $this->addSql('DROP TABLE `dishes`');
        $this->addSql('DROP TABLE dish_ingredient');
        $this->addSql('DROP TABLE `files`');
        $this->addSql('DROP TABLE `floors`');
        $this->addSql('DROP TABLE `ingredient_groups`');
        $this->addSql('DROP TABLE `ingredients`');
        $this->addSql('DROP TABLE `menu_sections`');
        $this->addSql('DROP TABLE `menus`');
        $this->addSql('DROP TABLE `orders`');
        $this->addSql('DROP TABLE payments');
        $this->addSql('DROP TABLE reservations');
        $this->addSql('DROP TABLE reservation_table');
        $this->addSql('DROP TABLE `restaurants`');
        $this->addSql('DROP TABLE `tables`');
        $this->addSql('DROP TABLE `users`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
