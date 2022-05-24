<?php

require __DIR__ . '/vendor/autoload.php';

use JoshuaWebDev\Csv2Sql\Csv2Sql;

$csv2sql = new Csv2Sql;

// Verifica se os argumentos foram informados corretamente
if ( $argc < 2 ) {
    print( "Após invocar o nome do programa digite o nome do arquivo que será convertido!\n" );
    exit();
}

if ( $argc < 3 ) {
    print( "Após o nome do arquivo que será convertido informe o nome da tabela que será criada!\n" );
    exit();
}

try {

    $csv2sql->setFile($argv[1]);
    $csv2sql->setTable($argv[2]);

    $content = $csv2sql->getCreateTableQuery();
    $content .= "\n\n";

    foreach($csv2sql->getInsertDataQuery() as $data) {
        $content .= $data;
    }

    file_put_contents($argv[2] . '.sql', $content);

} catch ( Exception $e ) {
    echo "Ops! Algo de errado não esta certo. {$e->getMessage()}\n";
}