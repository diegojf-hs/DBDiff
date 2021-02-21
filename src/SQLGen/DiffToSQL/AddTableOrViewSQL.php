<?php namespace DBDiff\SQLGen\DiffToSQL;

use DBDiff\SQLGen\SQLGenInterface;


class AddTableOrViewSQL implements SQLGenInterface {

    function __construct($obj) {
        $this->obj = $obj;
    }
    
    public function getUp() {
        $table = $this->obj->table;
        $connection = $this->obj->connection;
        $res = $connection->select("SHOW CREATE TABLE `$table`");
        $table_or_view_declaration = "";
        if (!empty($res[0]['Create Table'])) {
            $table_or_view_declaration = $res[0]['Create Table'].';';
        } else {
            $table_or_view_declaration = $res[0]['Create View'].';';
        }
        return $table_or_view_declaration;
    }

    public function getDown() {
        $table = $this->obj->table;
        $type = $this->obj->type;
        return "DROP ". ($type === 'table' ? 'TABLE' : 'VIEW' )." `$table`;";
    }
}
