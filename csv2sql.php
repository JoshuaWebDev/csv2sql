<?php

/**
* Autor: Josué Barros da Silva
* Website: joshuawebdev.wordpress.com
* Email: josue.barros1986@gmail.com
* Versão 1.1
*
* Lê um arquivo no formato csv ao qual consiste em uma tabela
* importada de um banco de dados qualquer
* A primeira linha contém os atributos da tabela
* A segunda linha em diante contém os dados de cada registro da tabela
* A primeira linha é dividida e transformada em um array
* onde seus elementos são os atributos da tabela
* As demais linhas do arquivo também são convertidas em arrays
* onde cada elemento do array é um dado da tabela
*
* É possível salvar o conteúdo em outro arquivo por meio do
* comando php csv2sql.php > [nome_arquivo.sql], substituindo o argumento
* após ">" pelo nome do arquivo sem os colchetes []
*/

require_once('queries.php');

// Verifica se os argumentos foram informados corretamente
if ( $argc < 2 ) {
    print( "Após invocar o nome do programa digite o nome do arquivo que será convertido!\n" );
    exit();
}

if ( $argc < 3 ) {
    print( "Após o nome do arquivo que será convertido informe o nome da tabela que será criada!\n" );
    exit();
}

$sql_file = "";

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

try {

    $csv_file_array = handleFile( $file_name );


    // getHeadFromCSV retorna uma array com o nome dos atributos da tabela
    $csv_head = getHeadFromCSV( $csv_file_array );

    // extractDataFromCSV retorna um array bidimensional contenddo os dados da tabela
    $csv_data = extractDataFromCSV( $csv_head, $csv_file_array );


    // adiciona à $sql_file uma string contendo comandos sql para criação da tabela
    $sql_file .= createTable( $table_name, $csv_head );

    // adiciona uma chave primária à tabela
    $sql_file .= addSerialPrimaryKey( $table_name );

    $sql_file .= copyTable( $table_name, $csv_head, $file_name );

    echo $sql_file."\n";

} catch ( Exception $e ) {
    echo "Aviso: ", $e->getMessage(), "\n";
}

?>
