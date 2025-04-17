<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250416010433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE investimento (id INT AUTO_INCREMENT NOT NULL, produto_investimento_id INT NOT NULL, id_user_id INT NOT NULL, saldo_inicial NUMERIC(10, 2) NOT NULL, data_investimento DATETIME NOT NULL, INDEX IDX_8D61B2F96FCFCE90 (produto_investimento_id), INDEX IDX_8D61B2F979F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE produto_investimento (id INT AUTO_INCREMENT NOT NULL, nome_investimento VARCHAR(255) NOT NULL, data_criacao DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE resgate (id INT AUTO_INCREMENT NOT NULL, produto_investimento_id INT NOT NULL, id_user_id INT NOT NULL, saldo_final_com_imposto NUMERIC(10, 2) NOT NULL, data_investimento DATETIME NOT NULL, INDEX IDX_99BF5D026FCFCE90 (produto_investimento_id), INDEX IDX_99BF5D0279F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE investimento ADD CONSTRAINT FK_8D61B2F96FCFCE90 FOREIGN KEY (produto_investimento_id) REFERENCES produto_investimento (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE investimento ADD CONSTRAINT FK_8D61B2F979F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resgate ADD CONSTRAINT FK_99BF5D026FCFCE90 FOREIGN KEY (produto_investimento_id) REFERENCES produto_investimento (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resgate ADD CONSTRAINT FK_99BF5D0279F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE investimento DROP FOREIGN KEY FK_8D61B2F96FCFCE90
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE investimento DROP FOREIGN KEY FK_8D61B2F979F37AE5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resgate DROP FOREIGN KEY FK_99BF5D026FCFCE90
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resgate DROP FOREIGN KEY FK_99BF5D0279F37AE5
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE investimento
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE produto_investimento
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE resgate
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `user`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
