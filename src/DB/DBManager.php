<?php namespace DBDiff\DB;

use Illuminate\Database\Capsule\Manager as Capsule;
use DBDiff\Exceptions\DBException;


class DBManager {
    const STAGING_DB_IPS = ['192.168.151.61', '45.33.17.150'];

    function __construct() {
        $this->capsule = new Capsule;
    }

    public function connect($params) {
        foreach ($params->input as $key => $input) {
            if ($key === 'kind') continue;
            $server = $params->{$input['server']};
            $db = $input['db'];
            $this->capsule->addConnection([
                'driver'    => 'mysql',
                'host'      => $server['host'],
                'port'      => $server['port'],
                'database'  => $db,
                'username'  => $server['user'],
                'password'  => $server['password'],
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ], $key);
        }
    }

    public function testResources($params) {
        $input = $params->input['source'];
        $this->testResource($params->input['source'], 'source', $params->{$input['server']});
        $input = $params->input['target'];
        $this->testResource($params->input['target'], 'target', $params->{$input['server']});
    }

    public function testResource($input, $res, $server) {
        try {
            $this->capsule->getConnection($res);
        } catch(\Exception $e) {
            if (in_array($server['host'], self::STAGING_DB_IPS)) {
                // The database probably does not exist, attempt to create it
                $this->createDB($input['db'], $server);
            } else {
                throw new DBException("Can't connect to target database");
            }
        }
        if (!empty($input['table'])) {
            try {
                $this->capsule->getConnection($res)->table($input['table'])->first();
            } catch(\Exception $e) {
                throw new DBException("Can't access target table");
            }
        }
    }

    private function createDB($db_name, $server) {
        $this->capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => $server['host'],
            'port'      => 3306,
            'username'  => $server['user'],
            'password'  => $server['password'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci'
        ], 'connection_without_db');
        try {
            $this->getDB('connection_without_db')->select("CREATE DATABASE IF NOT EXISTS ".$db_name.";");
            sleep(5); // due to minimal replication lag in Staging
        } catch(\Exception $e) {
            throw new DBException("Unable to create database ".$db_name);
        }
    }

    public function getDB($res) {
        return $this->capsule->getConnection($res);
    }

    public function getTables($connection) {
        $result = $this->getDB($connection)->select("show tables");
        return array_flatten($result);
    }

    public function getColumns($connection, $table) {
        $result = $this->getDB($connection)->select("show columns from `$table`");
        return array_pluck($result, 'Field');
    }

    public function getKey($connection, $table) {
        $keys = $this->getDB($connection)->select("show indexes from `$table`");
        $ukey = [];
        foreach ($keys as $key) {
            if ($key['Key_name'] === 'PRIMARY') {
                $ukey[] = $key['Column_name'];
            }
        }
        return $ukey;
    }

}
