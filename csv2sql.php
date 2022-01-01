<?php

/**
* Autor: Josué Barros da Silva
* Website: joshuawebdev.wordpress.com
* Email: josue.barros1986@gmail.com
* Versão 1.3
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

if ( $argc < 4 ) {
    print( "Você deve informar qual o separador utilizado no arquivo csv para separar as colunas (vírgula, ponto e vírgula, etc)!\n" );
    exit();
}

try {

    $csv2sql->setFile($argv[1]);
    $csv2sql->setTable($argv[2]);
    $csv2sql->setSeparator($argv[3]);

    echo $csv2sql->getCreateTableQuery();

} catch ( Exception $e ) {
    echo "Ops! Algo de errado não esta certo. {$e->getMessage()}\n";
}