<?php
/**
 * Created by PhpStorm.
 * User: prg4vit
 * Date: 18.10.2019
 * Time: 11:02
 */

$db = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, 3306);
$db->set_charset("utf8");
if ($db->connect_errno) {
    die("No connect to db");
}
echo '<pre>';
$sqlTables = "SHOW TABLES";
$sqlManufacturers = "select keyword from ukr_url_alias where query like 'manufacturer_id%'";


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
                "UPDATE " . $table[0] . " SET " . $field['Field'] . " = REPLACE(" . $field['Field'] . ", 'http://e-ukrservice.com', 'https://e-ukrservice.com')";
            echo $query;
            $db->query($query);
            echo 'Field ' . $field['Field'] . '- Done!!!' . PHP_EOL;
        }
    }

}

$db->close();