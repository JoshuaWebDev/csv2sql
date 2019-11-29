<?php

/**
* Autor: Josué Barros da Silva
* Website: joshuawebdev.wordpress.com
* Email: josue.barros1986@gmail.com
* Versão 1.2
*
* Lê um arquivo no formato csv ao qual consiste em uma tabela
* importada de um banco de dados qualquer
* linha do arquivo .csv contém os atributos da tabela
* A segunda linha em diante contém os dados de cada registro da tabela
* A primeira linha é dividida e transformada em um array
* onde seus elementos são os atributos da tabela
* As demais linhas do arquivo são convertidas em arrays bidimensionais
* onde cada elemento do array é um dado da tabela
*
* É possível salvar o conteúdo em outro arquivo por meio do
* comando php csv2sql.php [nome_arquivo].csv [nome_tabela] > [nome_arquivo].sql
* onde [nome_arquivo].csv é o arquivo que será submetido
* [nome_tabela] é o nome da tabela que será gerada com os dados de [arquivo].csv
* e [nome_arquivo].sql é o arquivo contendo as queries geradas pelo csv2sq
*/


// Verifica se os argumentos foram informados corretamente
if ( $argc < 2 ) {
    print( "Após invocar o nome do programa digite o nome do arquivo que será convertido!\n" );
    exit();
}

if ( $argc < 3 ) {
    print( "Após o nome do arquivo que será convertido informe o nome da tabela que será criada!\n" );
    exit();
}

// nome do arquivo csv
$file_name = $argv[1];

// nome da tabela que será criada
$table_name = $argv[2];

// Verifica se o arquivo existe
function handleFile( $file_name ) {

    if ( !file_exists( $file_name  ) ) {
        throw new Exception( "O arquivo {$file_name} não existe ou encontra-se em outra pasta!" );
    }

    // retorna um número inteiro, o indicador do arquivo
    return file( $file_name );

}

function createTable( $table_name, $csv_head ) {

    // elimina quebra de linhas
    $csv_head = preg_replace( "/(\r\n|\n|\r)+/", "", $csv_head );

    // inicia a string contendo as instruções em sql
    $sql = "CREATE TABLE {$table_name} (";

    for ( $i = 0; $i < count( $csv_head ); $i++ ) {

        $sql .= "\n    {$csv_head[$i]} VARCHAR(255),";

    }

    // elimina a última vírgula após o último parênteses
    $sql = preg_replace( "/,$/", "", $sql );

    return $sql .= "\n);";
}

try {

    $csv_file_array = handleFile( $file_name );

    $csv_head = explode( ";", $csv_file_array[0] );

    echo createTable( $table_name, $csv_head );

} catch ( Exception $e ) {
    echo "Aviso: ", $e->getMessage(), "\n";
}

?>
