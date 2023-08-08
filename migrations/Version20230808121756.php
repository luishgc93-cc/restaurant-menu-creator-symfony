<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230808121756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE informacion_photo (id INT AUTO_INCREMENT NOT NULL, informacion_id INT NOT NULL, photo_path VARCHAR(1000) NOT NULL, INDEX IDX_B2E2DA598C899341 (informacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE informacion_photo ADD CONSTRAINT FK_B2E2DA598C899341 FOREIGN KEY (informacion_id) REFERENCES informacion (id)');
        $this->addSql('ALTER TABLE informacion DROP INDEX local_id, ADD UNIQUE INDEX UNIQ_E4B777A55D5A2101 (local_id)');
        $this->addSql('ALTER TABLE informacion DROP fotos_informativas, CHANGE telefono telefono INT NOT NULL, CHANGE descripcion descripcion LONGTEXT NOT NULL, CHANGE calle calle VARCHAR(255) NOT NULL, CHANGE localidad localidad VARCHAR(255) NOT NULL, CHANGE ciudad ciudad VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE local_id local_id INT NOT NULL');
        $this->addSql('ALTER TABLE local CHANGE nombre_local nombre_local VARCHAR(255) NOT NULL, CHANGE descripcion_local descripcion_local VARCHAR(255) NOT NULL, CHANGE foto_local foto_local VARCHAR(1000) NOT NULL, CHANGE usuario_id usuario_id INT NOT NULL, CHANGE estilo estilo VARCHAR(255) DEFAULT NULL, CHANGE url url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE local RENAME INDEX usuario_id TO IDX_8BD688E8DB38439E');
        $this->addSql('ALTER TABLE usuario_local ADD CONSTRAINT FK_9608E2F65D5A2101 FOREIGN KEY (local_id) REFERENCES local (id)');
        $this->addSql('ALTER TABLE usuario_local ADD CONSTRAINT FK_9608E2F6DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE menu DROP foto_menu, CHANGE nombre_menu nombre_menu VARCHAR(255) NOT NULL, CHANGE informacion_menu informacion_menu LONGTEXT NOT NULL, CHANGE precio_menu precio_menu VARCHAR(255) NOT NULL, CHANGE informacion_id informacion_id INT NOT NULL, CHANGE estilo estilo VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE menu RENAME INDEX informacion_id TO IDX_7D053A938C899341');
        $this->addSql('ALTER TABLE menu_producto ADD CONSTRAINT FK_8BEA4383CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_producto ADD CONSTRAINT FK_8BEA43837645698E FOREIGN KEY (producto_id) REFERENCES producto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_photo ADD CONSTRAINT FK_851BA77FCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE producto DROP foto_producto, CHANGE nombre_producto nombre_producto VARCHAR(255) NOT NULL, CHANGE informacion_producto informacion_producto LONGTEXT NOT NULL, CHANGE precio_producto precio_producto VARCHAR(255) NOT NULL, CHANGE menus_id menus_id INT NOT NULL, CHANGE estilo estilo VARCHAR(255) NOT NULL, CHANGE informacion_id informacion_id INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_A7BB06158C899341 ON producto (informacion_id)');
        $this->addSql('ALTER TABLE producto RENAME INDEX menu_id TO IDX_A7BB061514041B84');
        $this->addSql('ALTER TABLE producto_photo ADD CONSTRAINT FK_54908C727645698E FOREIGN KEY (producto_id) REFERENCES producto (id)');
        $this->addSql('ALTER TABLE usuario DROP is_verified');
        $this->addSql('ALTER TABLE usuario RENAME INDEX email TO UNIQ_2265B05DE7927C74');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE informacion_photo DROP FOREIGN KEY FK_B2E2DA598C899341');
        $this->addSql('DROP TABLE informacion_photo');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A938C899341');
        $this->addSql('ALTER TABLE menu ADD foto_menu VARCHAR(1000) DEFAULT NULL, CHANGE informacion_id informacion_id INT DEFAULT NULL, CHANGE nombre_menu nombre_menu VARCHAR(255) DEFAULT NULL, CHANGE informacion_menu informacion_menu TEXT DEFAULT NULL, CHANGE precio_menu precio_menu VARCHAR(255) DEFAULT NULL, CHANGE estilo estilo INT DEFAULT 0');
        $this->addSql('ALTER TABLE menu RENAME INDEX idx_7d053a938c899341 TO informacion_id');
        $this->addSql('ALTER TABLE usuario_local DROP FOREIGN KEY FK_9608E2F65D5A2101');
        $this->addSql('ALTER TABLE usuario_local DROP FOREIGN KEY FK_9608E2F6DB38439E');
        $this->addSql('ALTER TABLE usuario ADD is_verified TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario RENAME INDEX uniq_2265b05de7927c74 TO email');
        $this->addSql('ALTER TABLE local DROP FOREIGN KEY FK_8BD688E8DB38439E');
        $this->addSql('ALTER TABLE local CHANGE usuario_id usuario_id INT DEFAULT NULL, CHANGE nombre_local nombre_local VARCHAR(255) DEFAULT NULL, CHANGE descripcion_local descripcion_local VARCHAR(255) DEFAULT NULL, CHANGE foto_local foto_local VARCHAR(1000) DEFAULT NULL, CHANGE estilo estilo INT DEFAULT 0, CHANGE url url VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE local RENAME INDEX idx_8bd688e8db38439e TO usuario_id');
        $this->addSql('ALTER TABLE producto_photo DROP FOREIGN KEY FK_54908C727645698E');
        $this->addSql('ALTER TABLE informacion DROP INDEX UNIQ_E4B777A55D5A2101, ADD INDEX local_id (local_id)');
        $this->addSql('ALTER TABLE informacion DROP FOREIGN KEY FK_E4B777A55D5A2101');
        $this->addSql('ALTER TABLE informacion ADD fotos_informativas VARCHAR(1000) DEFAULT NULL, CHANGE local_id local_id INT DEFAULT NULL, CHANGE telefono telefono INT DEFAULT NULL, CHANGE descripcion descripcion TEXT DEFAULT NULL, CHANGE calle calle VARCHAR(255) DEFAULT NULL, CHANGE localidad localidad VARCHAR(255) DEFAULT NULL, CHANGE ciudad ciudad VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE menu_photo DROP FOREIGN KEY FK_851BA77FCCD7E912');
        $this->addSql('ALTER TABLE producto DROP FOREIGN KEY FK_A7BB061514041B84');
        $this->addSql('ALTER TABLE producto DROP FOREIGN KEY FK_A7BB06158C899341');
        $this->addSql('DROP INDEX IDX_A7BB06158C899341 ON producto');
        $this->addSql('ALTER TABLE producto ADD foto_producto VARCHAR(1000) DEFAULT NULL, CHANGE menus_id menus_id INT DEFAULT NULL, CHANGE informacion_id informacion_id INT DEFAULT NULL, CHANGE nombre_producto nombre_producto VARCHAR(255) DEFAULT NULL, CHANGE informacion_producto informacion_producto TEXT DEFAULT NULL, CHANGE precio_producto precio_producto VARCHAR(255) DEFAULT NULL, CHANGE estilo estilo INT DEFAULT 0');
        $this->addSql('ALTER TABLE producto RENAME INDEX idx_a7bb061514041b84 TO menu_id');
        $this->addSql('ALTER TABLE menu_producto DROP FOREIGN KEY FK_8BEA4383CCD7E912');
        $this->addSql('ALTER TABLE menu_producto DROP FOREIGN KEY FK_8BEA43837645698E');
    }
}
