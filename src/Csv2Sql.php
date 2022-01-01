<?php

namespace JoshuaWebDev\Csv2Sql;

class Csv2Sql
{
    private $fileName = null;         // nome do arquivo csv com os dados que serão inseridos na tabela
    private $separator = ",";         // utilizado para separar as colunas no arquivo csv (padrão = "vírgula")
    private $csvFileArray = null;     // array contendo cada uma das linhas do arquivo csv
    private $tableName = null;        // nome da tabela que será criada
    private $columnNames = null;      // nome das colunas geradas a partir da primeira linha do arquivo csv
    private $dataFromTable = [];      // array que contém as linhas do arquivo csv com os dados que serão usados para alimentar a tabela
    private $createTableQuery = null; // instrução sql que cria a tabela
    private $insertDataQuery = null;  // instrução sql que irá inserir os dados na tabela recêm-criada

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function getColumnNames()
    {
        return $this->columnNames;
    }

    public function getDataFromTable()
    {
        return $this->dataFromTable;
    }

    public function getCreateTableQuery()
    {
        return $this->createTableQuery;
    }

    public function getInsertDataQuery()
    {
        return $this->insertDataQuery;
    }

    public function setFile( $filename)
    {
        $filearray = $this->handleFile($filename);
        // cria um array com o nome das colunas da tabela a partir da primeira linha do arquivo csv
        $this->columnNames = $this->makeColumnNames( $filearray );
        $this->dataFromTable = $this->setDataFromTable( $filearray );
        $this->fileName = $filename;
    }

    public function setTable( $tablename )
    {
        $this->tableName = $tablename;
        $this->createTable();
        $this->insertDataToTable();
    }

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

    // cria um array com o nome das tabelas a partir da primeira linha do arquivo csv
    private function makeColumnNames( $csvfilearray )
    {
        $head = explode( $this->separator, $csvfilearray[0] );
    
        // elimina quebra de linhas
        $head = preg_replace( "/(\r\n|\n|\r)+/", "", $head );

        // se houver um separador no final da linha ele é eliminado
        $head = preg_replace( "/". $this->separator ."$/", "", $head );
    
        return $head;
    
    }

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

    // cria a query utilizada para criar a tabela
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