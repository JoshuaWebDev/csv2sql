<?php

function upperCase($table_name, $csv_head) {

    $sql = "";

    foreach ( $csv_head as $field ) {
        $sql .= "\nUPDATE {$table_name} SET {$field}=upper({$field});";
    }

    return $sql."\n";

}

function removeWhiteSpace($table_name, $csv_head) {

    $sql = "";

    foreach ( $csv_head as $field ) {
        // remove dois espaços em branco
        $sql .= "\nUPDATE {$table_name} SET {$field}=replace({$field}, '  ', ' ') WHERE {$field} LIKE '%  %';";
        // remove um espaço em branco
        $sql .= "\nUPDATE {$table_name} SET {$field}=replace({$field}, ' ', '');";
        // removendo espaços à direita e à esquerda
        $sql .= "\nUPDATE {$table_name} SET {$field}=trim({$field});";
    }

    return $sql."\n";

}

function removeDotAndTraces($table_name, $csv_head) {

    $sql = "";

    foreach ( $csv_head as $field ) {
        // remove pontos desnecessários
        $sql .= "\nUPDATE {$table_name} SET {$field}=replace({$field}, '.', '');";
        // remove traços desnecessários
        $sql .= "\nUPDATE {$table_name} SET {$field}=replace({$field}, '-', '');";
    }

    return $sql."\n";

}

function setEmail($table_name, $email) {

    $sql = "\n-- ** Converte letras do email para minúsculas";
    
    $sql .=  "\nUPDATE {$table_name} SET {$email}=lower({$email});";

    return $sql."\n";

}

function removeAccents($table_name, $nome) {

    $sql = "\n-- REMOVE ACENTO DE {$nome}";

    $sql .= "\nALTER TABLE {$table_name} ADD {$nome}_sem_acento varchar(255);";
    $sql .= "\nUPDATE {$table_name} SET {$nome}_sem_acento={$nome};";
    $sql .= "\nUPDATE {$table_name} SET {$nome}_sem_acento=upper({$nome});";
    $sql .= "\nUPDATE {$table_name} SET {$nome}_sem_acento=trim({$nome});";

    $sql .= "\nUPDATE {$table_name} SET {$nome}_sem_acento=translate({$nome}, 'ÁÀÃÂÄÉÈẼÊËÍÌĨÎÏÓÒÕÔÖÚÙŨÛÜÇ', 'AAAAAEEEEEIIIIIOOOOOUUUUUC');";

    return $sql."\n";

}

function setPhoneCell($table_name, $number) {

    $sql = "\n-- REMOVE TRAÇOS DE {$number}";

    $sql .= "\nUPDATE {$table_name} SET $number=regexp_replace($number , '[^0-9]*', '', 'g');";

    return $sql."\n";

}

function setPhoneCellOutPattern($table_name, $cell) {

    return "\nUPDATE {$table_name} SET {$cell}=NULL WHERE length({$cell})!=8";

}

function setPhoneNumberOutPattern($table_name, $number) {

    return "\nUPDATE {$table_name} SET {$cell}=NULL WHERE length({$cell})!=8";

}

function setNULLDateOutPattern($table_name, $date) {

    return "\nUPDATE {$table_name} SET {$date} = NULL WHERE length({$date})!=10;";

}

function setCPF($table_name, $cpf) {

    $sql = "\n-- CPF FORA DO PADRÃO ";

    $sql .= "\nUPDATE {$table_name} SET {$cpf}=NULL WHERE length({$cpf})!=11";

    return $sql."\n";

}

function setNewDate($table_name, $date, $new_date) {

    $sql = "\n-- FORMATA DATA {$date}";

    $sql .= "\nALTER TABLE {$table_name} add column {$date}_nova DATE;";
    $sql .= "\nUPDATE {$table_name} SET {$date}_nova=(split_part({$date},'-',3)||'-'||split_part({$date},'-',2)||'-'||split_part({$date},'-',1))::date;";
    $sql .= "\nALTER TABLE {$table_name} DROP {$date};";
    $sql .= "\nALTER TABLE {$table_name} ADD COLUMN {$new_date} DATE;";
    $sql .= "\nUPDATE {$table_name} SET {$new_date}={$date}_nova;";
    $sql .= "\nALTER TABLE {$table_name} DROP {$date}_nova;";

    return $sql."\n";

}

function fillFieldVoidDates($table_name, $date) {

    return "\nUPDATE {$table_name} SET {$date}='2001-01-01' WHERE {$date} IS NULL;";

}

function fillFieldVoid($table_name, $field) {

    return "\nUPDATE {$table_name} SET {$field}='".strtoupper($field)."' WHERE {$field} IS NULL;";

}

function setStatesToUF($table_name, $csv_head) {

}

