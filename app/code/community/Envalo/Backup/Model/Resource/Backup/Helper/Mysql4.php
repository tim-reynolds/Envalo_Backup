<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 3/20/14
 * Time: 5:17 PM
 */ 
class Envalo_Backup_Model_Resource_Backup_Helper_Mysql4 extends Mage_Backup_Model_Resource_Helper_Mysql4 {

    public function getTableForeignKeysSql($tableName = null)
    {
        $foreignKeys = parent::getTableForeignKeysSql($tableName);
        $triggerSql = $this->getTableTriggerCreateSql($tableName);
        return $foreignKeys . "\n" . $triggerSql;
    }

    public function getTableTriggerCreateSql($tableName = null)
    {
        $results = array();
        $adapter = $this->_getReadAdapter();
        $createIndex = 'SQL Original Statement';
        $showTriggers = 'SHOW TRIGGERS';
        if($tableName !== null)
        {
            $quotedTableName = $adapter->quote($tableName);
            $showTriggers = 'SHOW TRIGGERS WHERE `Table` = ' . $quotedTableName;
        }
        $query = $adapter->query($showTriggers);
        $showCreateQueries = array();
        while($row = $query->fetch())
        {
            if(!isset($row['Trigger']))
            {
                continue;
            }
            $quotedTriggerName = $adapter->quoteIdentifier($row['Trigger']);
            $showCreateQueries[] = 'SHOW CREATE TRIGGER ' . $quotedTriggerName;
        }
        foreach($showCreateQueries as $showCreateQuery)
        {
            $row = $adapter->fetchRow($showCreateQuery);
            if(isset($row[$createIndex]))
            {
                $quotedTriggerName = $adapter->quoteIdentifier($row['Trigger']);
                $filteredCreateSyntax = preg_replace(
                    '/CREATE DEFINER=.+ TRIGGER /',
                    'CREATE TRIGGER ',
                    $row[$createIndex]);
                $results[] = sprintf('DROP TRIGGER IF EXISTS %s;', $quotedTriggerName);
                $results[] = "DELIMITER ;;";
                $results[] = $filteredCreateSyntax . ';;';
                $results[] = "DELIMITER ;";
            }
        }


        return implode("\n", $results);
    }
}
