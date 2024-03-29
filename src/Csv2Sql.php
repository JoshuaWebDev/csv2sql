<?php

namespace JoshuaWebDev\Csv2Sql;

/**
* @author Josué Barros da Silva
* Website: joshuawebdev.wordpress.com
* GitHub: https://github.com/JoshuaWebDev
* Email: josue.barros1986@gmail.com
* @version 1.3
*
* Lê um arquivo no formato csv ao qual consiste em uma tabela importada de um banco de dados qualquer
* A primeira linha do arquivo csv contém os atributos da tabela
* A segunda linha em diante contém os dados de cada registro da tabela
* A primeira linha é dividida e transformada em um array onde seus elementos são os atributos da tabela
* As demais linhas do arquivo são convertidas em arrays bidimensionais onde cada elemento do array é um dado da tabela
*
* É possível salvar o conteúdo em outro arquivo por meio do comando
* php csv2sql.php [nome_arquivo].csv [nome_tabela] > [nome_arquivo].sql
* onde [nome_arquivo].csv é o arquivo que será submetido
* [nome_tabela] é o nome da tabela que será gerada com os dados de [arquivo].csv
* e [nome_arquivo].sql é o arquivo contendo as queries geradas pelo csv2sq
*/

class Csv2Sql
{
    private $fileName = null;         // nome do arquivo csv com os dados que serão inseridos na tabela
    private $separator = ";";         // utilizado para separar as colunas no arquivo csv (padrão = "vírgula")
    private $csvFileArray = null;     // array contendo cada uma das linhas do arquivo csv
    private $tableName = null;        // nome da tabela que será criada
    private $columnNames = null;      // nome das colunas geradas a partir da primeira linha do arquivo csv
    private $dataFromTable = [];      // array que contém as linhas do arquivo csv com os dados que serão usados para alimentar a tabela
    private $createTableQuery = null; // instrução sql que cria a tabela
    private $insertDataQuery = null;  // instrução sql que irá inserir os dados na tabela recêm-criada

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return array
     */
    public function getColumnNames()
    {
        return $this->columnNames;
    }

    /**
     * @return array
     */
    public function getDataFromTable()
    {
        return $this->dataFromTable;
    }

    /**
     * @return string
     */
    public function getCreateTableQuery()
    {
        return $this->createTableQuery;
    }

    /**
     * @return string
     */
    public function getInsertDataQuery()
    {
        return $this->insertDataQuery;
    }

    /**
     * @return void
     */
    public function setFile( $filename)
    {
        $filearray = $this->handleFile($filename);
        // cria um array com o nome das colunas da tabela a partir da primeira linha do arquivo csv
        $this->columnNames = $this->makeColumnNames( $filearray );
        $this->dataFromTable = $this->setDataFromTable( $filearray );
        $this->fileName = $filename;
    }

    /**
     * @return void
     */
    public function setTable( $tablename )
    {
        $this->tableName = $tablename;
        $this->createTable();
        $this->insertDataToTable();
    }

    /**
     * @return int
     */
    private function handleFile( $filename )
    {
        if (  is_null( $filename ) ) {
            throw new Exception( "O nome do arquivo está nulo (NULL)" );
        }

        if ( !file_exists( $filename  ) ) {
            throw new Exception( "O arquivo {$filename} não existe ou encontra-se em outra pasta!" );
        }
    
        // retorna um número inteiro, o indicador do arquivo
        return file( $filename );
    }

    /**
     * cria um array com o nome das tabelas a partir da primeira linha do arquivo csv
     * @return array
     */
    private function makeColumnNames( $csvfilearray )
    {
        $head = explode( $this->separator, $csvfilearray[0] );
    
        // elimina quebra de linhas
        $head = preg_replace( "/(\r\n|\n|\r)+/", "", $head );

        // se houver um separador no final da linha ele é eliminado
        $head = preg_replace( "/". $this->separator ."$/", "", $head );
    
        return $head;
    
    }

    /**
     * @return array
     */
    private function setDataFromTable( $csvfilearray )
    {
        $counter = 1;
        $linesFromFile = [];

        for ($counter; $counter < count($csvfilearray); $counter++) {
            $temp = explode($this->separator, $csvfilearray[$counter]);

            array_push($linesFromFile, $temp);
        }

        return $linesFromFile;
    }

    /**
     * cria a query utilizada para criar a tabela
     * @return void
     */
    private function createTable()
    {
        if (is_null($this->columnNames)) {
            return "Nome das colunas está nulo (NULL)";
        }

        $colunas = $this->columnNames;

        $sql = "CREATE TABLE IF NOT EXISTS {$this->tableName} (\n\tid INT NOT NULL PRIMARY KEY";

        foreach ($colunas as $col) {
            if (!empty($col)) {
                $sql .= ",\n\t$col VARCHAR";
            }
        }

        $sql .= "\n);";

        $this->createTableQuery = $sql;
    }

    /**
     * @return void
     */
    private function insertDataToTable()
    {
        $items = [];

        // if (is_null($this->tableName)) {
        //     return "Você precisa informar o nome da tabela onde serão inseridos os dados\n";
        // }

        // if (is_null($this->columnNames)) {
        //     return "Nome das colunas está nulo (NULL)\n";
        // }

        foreach ($this->dataFromTable as $row) {
            $sql = "INSERT INTO {$this->tableName} (";

            foreach ($this->columnNames as $key => $column) {
                if (!empty($column)) {
                    $sql .= "\"{$column}\"";

                    if ($key < count($this->columnNames) - 2) {
                        $sql .= ",";
                    }
                }
            }

            $sql .= ")\nVALUES (\n\t";

            foreach($row as $k => $r) {
                if (!empty($r)) {
                    $sql .= "\"{$r}";

                    if ($k < count($row) - 2) {
                        $sql .= "\", ";
                    }
                }
            }

            $sql .= ");\n\n";

            array_push($items, $sql);
        }

        $this->insertDataQuery = $items;
    }
}