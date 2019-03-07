<?php

require_once('getdatafromcsv.php');

function createTable( $table_name, $csv_head ) {

    // inicia a string contendo as instruções em sql
    $sql = "CREATE TABLE {$table_name} (";

    for ( $i = 0; $i < count( $csv_head ); $i++ ) {

        $sql .= "\n    {$csv_head[$i]} VARCHAR(255),";

    } 

    // elimina a última vírgula após o último parênteses
    $sql = preg_replace( "/,$/", "", $sql );

    return $sql .= "\n);\n";
}

function copyTable( $table_name, $csv_head, $file_name ) {

    $sql = "COPY {$table_name} (";

    for($i = 0; $i < count( $csv_head ); $i++ ) {

        $sql .= " {$csv_head[$i]},";

    }

    // elimina a última vírgula após o último parênteses
    $sql = preg_replace( "/,$/", "", $sql );

    return $sql .= " ) FROM '{$file_name}' DELIMITER ';' CSV HEADER;\n";

}

function addSerialPrimaryKey( $table_name ) {

    return "\nALTER TABLE {$table_name} ADD id serial primary key;\n";

}