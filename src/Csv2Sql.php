<?php

namespace JoshuaWebDev\Csv2Sql;

class Csv2Sql
{
    private $fileName = null;         // nome do arquivo csv com os dados que serão inseridos na tabela
    private $separator = ",";         // utilizado para separar as colunas no arquivo csv (padrão = "vírgula")
    private $csvFileArray = null;     // array contendo cada uma das linhas do arquivo csv
    private $tableName = null;        // nome da tabela que será criada
    private $columnNames = null;      // nome das colunas geradas a partir da primeira linha do arquivo csv
    private $createTableQuery = null; // instrução sql que cria a tabela

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

    public function getCreateTableQuery()
    {
        return $this->createTableQuery;
    }

    public function setFile( $filename)
    {
        $filearray = $this->handleFile($filename);
        $this->columnNames = $this->makeColumnNames( $filearray );
        $this->fileName = $filename;
    }

    public function setTable( $tablename )
    {
        $this->tableName = $tablename;
        $this->createTable();
    }

    public function setSeparator( $separator )
    {
        $this->separator = $separator;
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

    private function makeColumnNames( $csvfilearray ) {

        $head = explode( $this->separator, $csvfilearray[0] );
    
        // elimina quebra de linhas
        $head = preg_replace( "/(\r\n|\n|\r)+/", "", $head );

        // se houver um separador no final da linha ele é eliminado
        $head = preg_replace( "/". $this->separator ."$/", "", $head );
    
        return $head;
    
    }

    private function createTable()
    {
        if (is_null($this->columnNames)) {
            return "Nome das colunas está nulo (NULL)";
        }

        $colunas = $this->columnNames;

        $sql = "CREATE TABLE {$this->tableName} (ID INT NOT NULL PRIMARY KEY";

        foreach ($colunas as $col) {
            $sql .= ", $col";
        }

        $sql .= ");\n";

        $this->createTableQuery = $sql;
    }
}