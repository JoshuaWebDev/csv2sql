**CSV2SQL**
===============================================================
> Script em PHP que lê um arquivo no formato csv e converte-o para instruções sql

+ Autor: [Joshua](joshuawebdev.wordpress.com)
+ Linguagem: [PHP](https://www.php.net)
+ Versão 1.2

Descrição:
---------------------------------------------------------------
Lê um arquivo no formato csv ao qual consiste em uma tabela importada de um banco de dados qualquer.  

A primeira linha do arquivo .csv contém os atributos da tabela. A segunda linha em diante contém os dados de cada registro da tabela. A primeira linha é dividida e transformada em um array onde seus elementos são os atributos da tabela. As demais linhas do arquivo também são convertidas em arrays onde cada elemento do array é um dado da tabela.

Dependências
--------------------------------------------------------------
É necessário ter instalado em seu computador a o PHP a partir da versão 5.6 (versão com suporte a execução pelo terminal).

O arquivo principal é csv2sql.php, que depende de queries.php, que, por sua vez, depende de getdatafromcsv.php. É necessário que todos estejam em um mesmo diretório, caso contrário, estranhamente não funcionará.

Execução
--------------------------------------------------------------
O programa roda por meio de um terminal (prompt de comando, no caso do Windows). Ele recebe dois parâmetros:

1. arquivo .csv que será convertido para .sql
2. nome da tabela que será gerada após a conversão

Após efetuar o download dos arquivos

* csv2sql.php
* queries.php
*	getdatafromcsv.php

e salvá-los em um mesmo diretório, execute o comando:

    php csv2sql.php [param1] [param2]

**[param1]** e **[param2]** são os parâmetros citados acima.

### Exemplo de Uso:

Vamos converter o arquivo data_example.csv para um arquivo sql com as instruções para gerar uma tabela chamada alunos.

    php csv2sql.php data_example.csv alunos

Porém isso exibirá as queries geradas no terminal e isso não é muito interessante. Para gerar um arquivo com as queries de criação da tabela adicione ` > [nome_arquivo].sql` após o segundo parâmetro, onde **[nome_arquivo].sql** será o nome do arquivo que pretende-se criar. Vejamos um exemplo mais completo onde geramos a tabela alunos e a salvamos no arquivo alunos.sql.

    php csv2sql.php data_example.csv alunos > alunos.sql

Melhorias Futuras
----------------------------------------------------------------
- A saída é gerada no terminal. Para ser gerada em um arquivo é necessário adicionar ` > [nome_arquivo].sql` após o segundo parâmetro, onde nome_arquivo.sql é o nome do arquivo que receberá a saída.
- O programa carece de mais funções de tratamento dos dados obtidos por meio da importação do arquivo .csv
