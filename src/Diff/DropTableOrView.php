<?php namespace DBDiff\Diff;


class DropTableOrView {

    function __construct($table, $connection, $type = 'table') {
        $this->table = $table;
        $this->connection = $connection;
        $this->type = $type;
    }
}
