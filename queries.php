<?php

require_once('getdatafromcsv.php');

function createTable( $table_name, $csv_head ) {

    // elimina quebra de linhas
    $csv_head = preg_replace( "/(\r\n|\n|\r)+/", "", $csv_head );

    // inicia a string contendo as instruções em sql
    $sql = "CREATE TABLE {$table_name} (";

    for ( $i = 0; $i < count( $csv_head ); $i++ ) {

        // elimina as aspas duplas
        $csv_head[$i] = preg_replace( "/\"/", "", $csv_head[$i] );

        $sql .= "\n    {$csv_head[$i]} VARCHAR(255),";

    } 

    // elimina a última vírgula após o último parênteses
    $sql = preg_replace( "/,$/", "", $sql );

    return $sql .= "\n);\n";
}

function copyTable( $table_name ) {

}

function addSerialPrimaryKey( $table_name ) {

    return "\nALTER TABLE {$table_name} ADD id serial primary key;\n";

}