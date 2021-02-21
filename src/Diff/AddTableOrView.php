<?php namespace DBDiff\Diff;


class AddTableOrView {

    function __construct($table, $connection, $type = 'table') {
        $this->table = $table;
        $this->connection = $connection;
        $this->type = $type;
    }
}
