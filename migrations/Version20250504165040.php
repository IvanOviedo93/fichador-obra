<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250504165040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE empleado (id INT AUTO_INCREMENT NOT NULL, obra_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nombre VARCHAR(255) NOT NULL, INDEX IDX_D9D9BF523C2672C8 (obra_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE empleado_herramienta (empleado_id INT NOT NULL, herramienta_id INT NOT NULL, INDEX IDX_ED8E991C952BE730 (empleado_id), INDEX IDX_ED8E991CB2C900C2 (herramienta_id), PRIMARY KEY(empleado_id, herramienta_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE herramienta (id INT AUTO_INCREMENT NOT NULL, obra_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, descripcion LONGTEXT DEFAULT NULL, cantidad INT NOT NULL, disponible TINYINT(1) NOT NULL, INDEX IDX_B4A036D53C2672C8 (obra_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE obra (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, direccion VARCHAR(255) NOT NULL, inicio DATE NOT NULL, fin DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE empleado ADD CONSTRAINT FK_D9D9BF523C2672C8 FOREIGN KEY (obra_id) REFERENCES obra (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE empleado_herramienta ADD CONSTRAINT FK_ED8E991C952BE730 FOREIGN KEY (empleado_id) REFERENCES empleado (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE empleado_herramienta ADD CONSTRAINT FK_ED8E991CB2C900C2 FOREIGN KEY (herramienta_id) REFERENCES herramienta (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE herramienta ADD CONSTRAINT FK_B4A036D53C2672C8 FOREIGN KEY (obra_id) REFERENCES obra (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE empleado DROP FOREIGN KEY FK_D9D9BF523C2672C8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE empleado_herramienta DROP FOREIGN KEY FK_ED8E991C952BE730
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE empleado_herramienta DROP FOREIGN KEY FK_ED8E991CB2C900C2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE herramienta DROP FOREIGN KEY FK_B4A036D53C2672C8
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE empleado
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE empleado_herramienta
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE herramienta
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE obra
        SQL);
    }
}
