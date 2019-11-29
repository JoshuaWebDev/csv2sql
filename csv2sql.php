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

require_once('queries.php');
require_once('data_handler.php');

/*/ Verifica se os argumentos foram informados corretamente
if ( $argc < 2 ) {
    print( "Após invocar o nome do programa digite o nome do arquivo que será convertido!\n" );
    exit();
}

if ( $argc < 3 ) {
    print( "Após o nome do arquivo que será convertido informe o nome da tabela que será criada!\n" );
    exit();
}*/

$sql_file = "";

// nome do arquivo csv
$file_name = "/home/joshua/www/migracoes/constelação/pessoas/constelacao_alunos_responsaveis.csv";
//$argv[1];

// nome da tabela que será criada
$table_name = "alunos_constelacao";
//$argv[2];

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
    $csv_data = extractDataFromCSV( $csv_file_array );


    // adiciona à $sql_file uma string contendo comandos sql para criação da tabela
    $sql_file .= "-- ** Criando a tabela {$table_name}\n";
    $sql_file .= createTable( $table_name, $csv_head );

    // copia os dados do arquivo csv para a tabela recém-criada
    $sql_file .= "\n-- ** Migrando os dados do arquivo csv para a tabela {$table_name}";
    $sql_file .= copyTable( $table_name, $csv_head, $file_name );

    // adiciona uma chave primária à tabela
    $sql_file .= "\n-- ** Adicionando chave primária";
    $sql_file .= addSerialPrimaryKey( $table_name );

/* ----------------------------- TRATANDO DO DADOS --------------------------------- */

    // altera o nome dos atributos para caixa alta
    $sql_file .= "\n-- ** Alterando os campos para maiúsculo";
    $sql_file .= upperCase($table_name, $csv_head);

    // retira espaços em branco
    $sql_file .= "\n-- ** Retirando espaços em branco";
    $sql_file .= removeWhiteSpace($table_name, $csv_head);

    // retirando pontos e espaços desnecessários
    $sql_file .= "\n-- ** Retirando traços e pontos desnecessários";
    $sql_file .= removeDotAndTraces($table_name, $csv_head);

    // converte letras do email para minúsculas
    $sql_file .= setEmail($table_name, "aluno_email");

    // remove acentos
    $sql_file .= removeAccents($table_name, "nome_aluno");
    $sql_file .= removeAccents($table_name, "nome_pai");
    $sql_file .= removeAccents($table_name, "nome_mae");
    $sql_file .= removeAccents($table_name, "nome_responsavel");

    // formata números de telefone
    //$sql_file .= setPhoneCell($table_name, $number)

    // TRANSFORMA DATAS FORA DO PADRÃO EM NULL
    $sql_file .= "\n-- FORMATA DATAS FORA DO PADRÃO";
    $sql_file .= setNULLDateOutPattern($table_name, "aluno_nascimento");
    $sql_file .= setNULLDateOutPattern($table_name, "nasc_responsavel");
    $sql_file .= "\n";

    // FORMATA DATAS FORA DO PADRÃO
    $sql_file .= setNewDate($table_name, "aluno_nascimento", "dt_nasc_aluno");
    $sql_file .= setNewDate($table_name, "nasc_responsavel", "dt_nasc_responsavel");

    // PESQUISANDO ATRIBUTOS COM CAMPOS VAZIOS

    $sql_file .= searchEmptyValues( $table_name, $csv_head );

    // PREENCHER VALORES VAZIOS
    $sql_file .= "\n-- PREENCHER VALORES VAZIOS";
    $sql_file .= fillFieldVoidDates($table_name, "dt_nasc_aluno");
    $sql_file .= fillFieldVoidDates($table_name, "dt_nasc_responsavel");

    $sql_file .= fillFieldVoid($table_name, "nome_pai");
    $sql_file .= fillFieldVoid($table_name, "nome_mae");
    $sql_file .= fillFieldVoid($table_name, "nome_mae");

    // FORMATA CPF FORA DO PADRÃO
    $sql_file .= setCPF($table_name, "cpf_aluno");

    echo $sql_file."\n";

} catch ( Exception $e ) {
    echo "Aviso: ", $e->getMessage(), "\n";
}

?>
