<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230808122453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE informacion (id INT AUTO_INCREMENT NOT NULL, local_id INT NOT NULL, telefono INT NOT NULL, descripcion LONGTEXT NOT NULL, calle VARCHAR(255) NOT NULL, localidad VARCHAR(255) NOT NULL, ciudad VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E4B777A55D5A2101 (local_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE informacion_photo (id INT AUTO_INCREMENT NOT NULL, informacion_id INT NOT NULL, photo_path VARCHAR(1000) NOT NULL, INDEX IDX_B2E2DA598C899341 (informacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE local (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, nombre_local VARCHAR(255) NOT NULL, descripcion_local VARCHAR(255) NOT NULL, foto_local VARCHAR(1000) NOT NULL, estilo VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, INDEX IDX_8BD688E8DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, informacion_id INT NOT NULL, nombre_menu VARCHAR(255) NOT NULL, informacion_menu LONGTEXT NOT NULL, precio_menu VARCHAR(255) NOT NULL, estilo VARCHAR(255) NOT NULL, INDEX IDX_7D053A938C899341 (informacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE producto (id INT AUTO_INCREMENT NOT NULL, menus_id INT NOT NULL, informacion_id INT NOT NULL, nombre_producto VARCHAR(255) NOT NULL, informacion_producto LONGTEXT NOT NULL, precio_producto VARCHAR(255) NOT NULL, estilo VARCHAR(255) NOT NULL, INDEX IDX_A7BB061514041B84 (menus_id), INDEX IDX_A7BB06158C899341 (informacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE producto_photo (id INT AUTO_INCREMENT NOT NULL, producto_id INT NOT NULL, photo_path VARCHAR(1000) NOT NULL, INDEX IDX_54908C727645698E (producto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2265B05DE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE informacion ADD CONSTRAINT FK_E4B777A55D5A2101 FOREIGN KEY (local_id) REFERENCES local (id)');
        $this->addSql('ALTER TABLE informacion_photo ADD CONSTRAINT FK_B2E2DA598C899341 FOREIGN KEY (informacion_id) REFERENCES informacion (id)');
        $this->addSql('ALTER TABLE local ADD CONSTRAINT FK_8BD688E8DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A938C899341 FOREIGN KEY (informacion_id) REFERENCES informacion (id)');
        $this->addSql('ALTER TABLE producto ADD CONSTRAINT FK_A7BB061514041B84 FOREIGN KEY (menus_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE producto ADD CONSTRAINT FK_A7BB06158C899341 FOREIGN KEY (informacion_id) REFERENCES informacion (id)');
        $this->addSql('ALTER TABLE producto_photo ADD CONSTRAINT FK_54908C727645698E FOREIGN KEY (producto_id) REFERENCES producto (id)');
        $this->addSql('ALTER TABLE usuario_local ADD CONSTRAINT FK_9608E2F65D5A2101 FOREIGN KEY (local_id) REFERENCES local (id)');
        $this->addSql('ALTER TABLE usuario_local ADD CONSTRAINT FK_9608E2F6DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE menu_producto ADD CONSTRAINT FK_8BEA4383CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_producto ADD CONSTRAINT FK_8BEA43837645698E FOREIGN KEY (producto_id) REFERENCES producto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_photo ADD CONSTRAINT FK_851BA77FCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario_local DROP FOREIGN KEY FK_9608E2F65D5A2101');
        $this->addSql('ALTER TABLE menu_producto DROP FOREIGN KEY FK_8BEA4383CCD7E912');
        $this->addSql('ALTER TABLE menu_photo DROP FOREIGN KEY FK_851BA77FCCD7E912');
        $this->addSql('ALTER TABLE menu_producto DROP FOREIGN KEY FK_8BEA43837645698E');
        $this->addSql('ALTER TABLE usuario_local DROP FOREIGN KEY FK_9608E2F6DB38439E');
        $this->addSql('ALTER TABLE informacion DROP FOREIGN KEY FK_E4B777A55D5A2101');
        $this->addSql('ALTER TABLE informacion_photo DROP FOREIGN KEY FK_B2E2DA598C899341');
        $this->addSql('ALTER TABLE local DROP FOREIGN KEY FK_8BD688E8DB38439E');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A938C899341');
        $this->addSql('ALTER TABLE producto DROP FOREIGN KEY FK_A7BB061514041B84');
        $this->addSql('ALTER TABLE producto DROP FOREIGN KEY FK_A7BB06158C899341');
        $this->addSql('ALTER TABLE producto_photo DROP FOREIGN KEY FK_54908C727645698E');
        $this->addSql('DROP TABLE informacion');
        $this->addSql('DROP TABLE informacion_photo');
        $this->addSql('DROP TABLE local');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE producto');
        $this->addSql('DROP TABLE producto_photo');
        $this->addSql('DROP TABLE usuario');
    }
}
