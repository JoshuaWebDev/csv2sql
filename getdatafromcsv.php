<?php

function getHeadFromCSV( $csv_file_array ) {

    $head = explode( ";", $csv_file_array[0] );

    // elimina quebra de linhas
    $head = preg_replace( "/(\r\n|\n|\r)+/", "", $head );

    // retorna o array $head sem as aspas
    return preg_replace( "/\"/", "", $head );

}

function extractDataFromCSV( $rows ) {

    $data = array();

    // passa por todas as linhas depois da primeira
    for( $i = 0; $i < count( $rows ); $i++ ) {
        
        @$row = explode( ";", $rows[$i+1] );

        for( $j = 0; $j < count( $row ); $j++ ) {

            // preg_replace() elimina as aspas duplas
            $data[$i][$j] = preg_replace( "/\"/", "", $row[$j] );
        
        }
    
    }

    array_pop( $data );

    return $data;
}