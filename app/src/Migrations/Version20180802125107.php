<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use function Sodium\add;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180802125107 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');

        $this->addSql('
            CREATE OR REPLACE FUNCTION pseudo_encrypt(VALUE bigint) returns int AS $$
DECLARE
l1 int;
l2 int;
r1 int;
r2 int;
i int:=0;
BEGIN
 l1:= (VALUE >> 16) & 65535;
 r1:= VALUE & 65535;
 WHILE i < 3 LOOP
   l2 := r1;
   r2 := l1 # ((((1366 * r1 + 150889) % 714025) / 714025.0) * 32767)::int;
   l1 := l2;
   r1 := r2;
   i := i + 1;
 END LOOP;
 RETURN ((r1 << 16) + l1);
END;
$$ LANGUAGE plpgsql strict immutable
        ');

        $this->addSql('CREATE SEQUENCE IF NOT EXISTS id_serial_seq START 1;');

        $this->addSql('
            CREATE TABLE Card (
              id INT PRIMARY KEY DEFAULT pseudo_encrypt(nextval(\'id_serial_seq\')),
              uuid UUID NOT NULL DEFAULT uuid_generate_v4(),
              card BIGINT NOT NULL,
              mm SMALLINT NOT NULL CHECK(mm > 0 AND mm < 13),
              yy INT NOT NULL CHECK(yy < 2100),
              cvv INT NOT NULL CHECK(cvv <= 999) ,
              name VARCHAR(255)              
            )
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
       $this->addSql('DROP TABLE Card;');
       $this->addSql('DROP SEQUENCE id_serial_seq;');
       $this->addSql('DROP FUNCTION IF EXISTS bounded_pseudo_encrypt');
       $this->addSql('DROP FUNCTION IF EXISTS pseudo_encrypt_24');
    }
}
