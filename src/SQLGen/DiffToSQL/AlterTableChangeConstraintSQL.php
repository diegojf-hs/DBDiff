<?php namespace DBDiff\SQLGen\DiffToSQL;

use DBDiff\SQLGen\SQLGenInterface;


class AlterTableChangeConstraintSQL implements SQLGenInterface {

    function __construct($obj) {
        $this->obj = $obj;
    }
    
    public function getUp() {
        $table = $this->obj->table;
        $name = $this->obj->name;
        $schema = $this->obj->diff->getNewValue();
        return "DROP CONSTRAINT `$key`, ADD $schema";
    }

    public function getDown() {
        $table = $this->obj->table;
        $name = $this->obj->name;
        $schema = $this->obj->diff->getOldValue();
        return "DROP CONSTRAINT `$key`, ADD $schema";
    }

}
