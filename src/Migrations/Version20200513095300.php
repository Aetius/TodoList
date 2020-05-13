<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200513095300 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function preUp(Schema $schema): void
    {
        $db = ($this->connection);

        $users = $db->fetchAll('SELECT * FROM user ');
        if (!is_null($users)){
            foreach ($users as $user){
                if($user['roles'] === "null"){
                    $db->executeQuery('UPDATE user SET roles=JSON_ARRAY("ROLE_USER")');
                }
            }
        }
        $db->executeQuery ('INSERT INTO user (username, password, email, roles) VALUES ("anonymous", "anonymous", "anonymous@anonymous.fr", JSON_ARRAY("ROLE_ANONYMOUS") )');
        $anonymous = $db->fetchAll('SELECT * FROM user WHERE username="anonymous"');
        $anonymousId = $anonymous[0]['id'];
        $db->executeQuery('ALTER TABLE task ADD user_id INT DEFAULT NULL');
        $db->executeQuery("UPDATE task SET user_id = $anonymousId");
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE task CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_527EDB25A76ED395 ON task (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25A76ED395');
        $this->addSql('DROP INDEX IDX_527EDB25A76ED395 ON task');
        $this->addSql('ALTER TABLE task DROP user_id');
    }
}
