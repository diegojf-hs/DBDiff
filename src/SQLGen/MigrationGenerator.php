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
        
        foreach($tables as $table_name => $table_diffs) {
            $alters = "";
            $non_alters = "";
            $combined_alters = array();

            foreach($table_diffs as $type => $diffs_for_type) {
                $sqlGenClass = __NAMESPACE__."\\DiffToSQL\\".$type."SQL";

                if (substr($type, 0, strlen("Alter")) === "Alter") {
                    $combined_alters = array_merge($combined_alters, $diffs_for_type);
                } else {
                    foreach($diffs_for_type as $diff) {
                        $gen = new $sqlGenClass($diff);
                        $non_alters .= $gen->$method()."\n";   
                    }
                }
            }
            
            if (count($combined_alters)){
                $alters .= "ALTER TABLE `$table_name` ";
                
                $is_first = true;
                foreach($combined_alters as $diff) {
                    $gen = new $sqlGenClass($diff);
                    if(!$is_first) {
                        $alters .= ", ";
                    }
                    $alters .= $gen->$method();
                    $is_first = false;
                }
                $alters .= ";\n";
            }
            $sql .= $alters.$non_alters;
        }
        return $sql;
    }

}

