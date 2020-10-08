<?php namespace DBDiff;

use DBDiff\Params\ParamsFactory;
use DBDiff\DB\DBManager;

class MiscScript {

    function __construct() {
        $this->manager = new DBManager;
    }

    function run() {
        $params = ParamsFactory::get();

        $this->manager->connect($params);
        $this->manager->testResources($params);
        
        $sourceTables = $this->manager->getTables('source');
        $schema = $this->{$connection}->select("SHOW CREATE TABLE test.heartbeat")[0]['Create Table'];

        var_export($schema);
    }
}

