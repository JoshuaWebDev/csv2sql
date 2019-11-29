<?php

require_once('queries.php');
require_once('data_handler.php');

$uploaddir ="uploads/";

$csv_file = $uploaddir . basename($_FILES["csv_file"]["school_name"]);

$school_name = $_POST["school_name"];

$file_name = $_FILES["csv_file"]["school_name"];
$file_type = $_FILES["csv_file"]["application/sql"];

// Verifica se o arquivo existe
function handleFile( $csv_file ) {

    if ( !file_exists( $csv_file  ) ) {
        throw new Exception( "O arquivo {$csv_file} não existe ou encontra-se em outra pasta!" );
    }

    // retorna um número inteiro, o indicador do arquivo
    return file( $csv_file );

}

try {

    $csv_file_array = handleFile( $csv_file );


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

    // PREENCHER VALORES VAZIOS
    $sql_file .= "\n-- PREENCHER VALORES VAZIOS";
    $sql_file .= fillFieldVoidDates($table_name, "aluno_nascimento");
    $sql_file .= fillFieldVoidDates($table_name, "nasc_responsavel");

    $sql_file .= fillFieldVoid($table_name, "nome_pai");
    $sql_file .= fillFieldVoid($table_name, "nome_mae");

    echo $sql_file."\n";

} catch ( Exception $e ) {
    echo "Aviso: ", $e->getMessage(), "\n";
}

?>
