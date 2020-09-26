<?php namespace DBDiff\SQLGen;


class MigrationGenerator {

    public static function generate($diffs, $method) {
        $sql = "";
        // group by table and diff type
        $tables = array();
        foreach ($diffs as $diff) {
            if (empty($tables[$diff->table])) {
                $tables[$diff->table] = array();
            }
            $reflection = new \ReflectionClass($diff);
            $diff_type = $reflection->getShortName();
            if(empty($tables[$diff->table][$diff_type])) {
                $tables[$diff->table][$diff_type] = array();
            }
            $tables[$diff->table][$diff_type][] = $diff;
        }
        
        foreach($tables as $table_diffs) {
            foreach($table_diffs as $type => $diffs_by_type) {
                $sqlGenClass = __NAMESPACE__."\\DiffToSQL\\".$type."SQL";

                if (substr($type, 0, strlen("Alter")) === "Alter") {
                    $table = $diffs_by_type[0]->table;
                    $sql .= "ALTER TABLE `$table` ";

                    $is_first = true;
                    foreach($diffs_by_type as $diff) {
                        $gen = new $sqlGenClass($diff);
                        if(!$is_first) {
                            $sql .= ", ";
                        }
                        $sql .= $gen->$method();
                        $is_first = false;
                    }
                    $sql .= ";\n";
                } else {
                    foreach($diffs_by_type as $diff) {
                        $gen = new $sqlGenClass($diff);
                        $sql .= $gen->$method()."\n";   
                    }
                }
            }
        }
        return $sql;
    }

}
