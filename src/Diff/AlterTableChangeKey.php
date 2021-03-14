<?php namespace DBDiff\Diff;


class AlterTableChangeKey {

    function __construct($table, $key, $diff, $isPKey = false) {
        $this->table = $table;
        $this->key = $key;
        $this->diff = $diff;
        $this->isPKey = $isPKey;
    }
}
