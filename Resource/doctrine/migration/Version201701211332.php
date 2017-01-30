<?php
/*
 * This file is part of the Hinagata
 *
 * Copyright(c) 2017 izayoi256 All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version201701211332 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable('plg_hinagata_info');
        $table->addColumn('id', 'integer', array('autoincrement' => true));
        $table->addColumn('name', 'text', array('notnull' => false));
        $table->setPrimaryKey(array('id'));
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('plg_hinagata_info');
    }

    /**
     * @param Schema $schema
     * @param string $tableName
     * @param array $columns
     * @param string $indexName
     * @param array $length
     * @param bool $unique
     * @return bool
     */
    protected function createIndex(Schema $schema, $tableName, array $columns, $indexName, array $length = array(), $unique = false)
    {
        if (!$schema->hasTable($tableName)) {
            return false;
        }

        $table = $schema->getTable($tableName);
        if (!$table->hasIndex($indexName)) {
            if ($this->connection->getDatabasePlatform()->getName() == 'mysql' && !empty($length)) {
                $cols = array();
                foreach ($length as $column => $len) {
                    $cols[] = sprintf('%s(%d)', $column, $len);
                }
                $index = $unique ?
                    'UNIQUE INDEX' :
                    'INDEX';
                $this->addSql(sprintf('CREATE %s %s ON %s(%s);', $index, $indexName, $tableName, implode(', ', $cols)));
            } else {
                $unique ?
                    $table->addUniqueIndex($columns, $indexName) :
                    $table->addIndex($columns, $indexName);
            }
            return true;
        }
        return false;
    }

    /**
     * @param Schema $schema
     * @param string $tableName
     * @param string $indexName
     * @return bool
     */
    protected function dropIndex(Schema $schema, $tableName, $indexName)
    {
        if (!$schema->hasTable($tableName)) {
            return false;
        }
        $table = $schema->getTable($tableName);
        if ($table->hasIndex($indexName)) {
            $table->dropIndex($indexName);
            return true;
        }
        return false;
    }
}
