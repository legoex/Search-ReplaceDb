<?php
/**
 * Created by PhpStorm.
 * User: prg4vit
 * Date: 18.10.2019
 * Time: 11:02
 */

/*
 * @param string $host Db host
 * @param string $user Db user
 * @param string $password Db password
 * @param string $database Db name
 * @param int $port
 * @param string $from Search String
 * @param string $to Replace String
 * @return null
 */
function SearchAndReplace($host, $user, $password, $database, $port = 3306, $from, $to)
{
    $db = new mysqli($host, $user, $password, $database, $port);
    $db->set_charset("    utf8");
    if ($db->connect_errno) {
        die("No connect to db");
    }
    echo '<pre>';
    $sqlTables = "SHOW TABLES";

    $tables = $db->query($sqlTables);
    while ($table = $tables->fetch_row()) {
        $tablesArray[] = $table;
    }

    foreach ($tablesArray as $table) {
        echo '<h1>' . 'Table ' . $table[0] . ' start!' . '</h1>' . PHP_EOL;
        $fieldsSql = "SHOW COLUMNS FROM " . $table[0];

        $field = $db->query($fieldsSql);
        while ($fieldData = $field->fetch_assoc()) {
            $fields[] = $fieldData;
        }
        foreach ($fields as $field) {
            if (substr($field['Type'], 0, 7) == 'varchar' || substr($field['Type'], 0, 4) == 'text') {
                $query = /** @lang SQL */
                    "UPDATE " . $table[0] . " SET " . $field['Field'] . " = REPLACE(" . $field['Field'] . ", $from, $to)";
                echo $query;
                $db->query($query);
                echo 'Field ' . $field['Field'] . '- Done!!!' . PHP_EOL;
            }
        }

    }
    $db->close();
    echo 'Executed';
}