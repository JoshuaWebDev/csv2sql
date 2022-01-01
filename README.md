**CSV2SQL**
===============================================================
> Script em PHP que lê um arquivo no formato csv e converte-o para instruções sql

+ Autor: [Joshua](joshuawebdev.wordpress.com)
+ Linguagem: [PHP](https://www.php.net)
+ Versão 1.3

Descrição:
---------------------------------------------------------------
Lê um arquivo no formato csv ao qual consiste em uma tabela importada de um banco de dados ou planilha.  

A primeira linha do arquivo .csv contém os atributos da tabela. A segunda linha em diante contém os dados de cada registro da tabela. A primeira linha é dividida e transformada em um array onde seus elementos são os atributos da tabela. As demais linhas do arquivo também são convertidas em arrays onde cada elemento do array é um dado da tabela.

Dependências
--------------------------------------------------------------
É necessário ter instalado em seu computador o [PHP](https://www.php.net) a partir da versão 5.6 (versão com suporte a execução pelo terminal) e o gerenciador de dependências [Composer](https://getcomposer.org/).

Instalação
--------------------------------------------------------------

```
composer require joshuawebdev/csv2sql
```

Execução
--------------------------------------------------------------
O programa roda por meio de um terminal (prompt de comando, no caso do Windows). Ele recebe dois parâmetros:

1. local do arquivo csv que será convertido para sql
2. nome da tabela que será gerada após a conversão

Exemplo de Execução:

```
php csv2sql.php [param1] [param2]
```

**[param1]** e **[param2]** são os parâmetros citados acima.

### Exemplo de Uso:

Vamos converter o arquivo data_example.csv para um arquivo sql com as instruções para gerar uma tabela chamada alunos.

```
php csv2sql.php csv/data_example.csv alunos
```

Porém isso exibirá as queries geradas no terminal e isso não é muito interessante. Para gerar um arquivo com as queries de criação da tabela adicione ` > [nome_arquivo].sql` após o segundo parâmetro, onde **[nome_arquivo].sql** será o nome do arquivo que pretende-se criar. Vejamos um exemplo mais completo onde geramos a tabela alunos e a salvamos no arquivo alunos.sql.

```
php csv2sql.php csv/data_example.csv alunos > alunos.sql
```

Recomendações
-----------------------------------------------------------------
- Esta versão do programa ainda exige algumas melhorias em relação a tratamento do arquivo csv antes da conversão, por exemplo eliminação de espaços em branco, linhas desnecessárias ou outras informações geradas automaticamente por uma planilha e que não sejam necessária para a criação das queries. Então uma dica seria eliminar essas inconformidades antes de executar o programa.
- Pode ser que ao gerar as queries haja algum resíduo de texto aos qual não deveria estar lá quando forem executados em um banco de dados, por exemplo vírgulas a mais ou campo numéricos entre aspas duplas. Então é importante removê-los antes de tentar executar estas queries em seu banco de dados.
- O padrão dos arquivos csv é usar vírgulas como separadores, porém, quando se trabalha com unidade monetária brasileira (R$ 10,00, por exemplo) isso pode se tornar um problema, pois também é usada a vírgula para separa as casas decimais. Neste caso há duas soluções: ou a unidade monetária é covertida para o padrão americano (onde é usado o ponto para separar as casas decimais) ou pode-se alterar o separador para outro caractere, como ponto-e-vírgula. Você pode fazer isso alterando a propriedade `$separator` em `src/Csv2Sql.php`.
- Você também pode implementar a biblioteca em seu projeto instanciando um objeto da Classe Csv2Sql. Exemplo:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use JoshuaWebDev\Csv2Sql\Csv2Sql;

$csv2sql = new Csv2Sql;

$csv2sql->setFile("csv/dados.csv");   // aqui você informa o local onde está o arquivo csv
$csv2sql->setTable("nome_da_tabela"); // aqui você dá o nome da tabela

$createDB = $csv2sql->getCreateTableQuery(); // query gerada para criar o BD (CREATE TABLE ...)
$insertData = $csv2sql->getInsertDataQuery() // array contendo as queries para inserir os dados (INSERT INTO ...)

// HÁ OUTROS MÉTODO QUE VOCÊ PODE USAR COMO POR EXEMPLO

$csv2sql->getTableName()     // exibe o nome da tabela
$csv2sql->getColumnNames()   // retorna um array com o nome das colunas (head)
$csv2sql->getDataFromTable() // return um array com os dados obtidos do arquivo csv (sem head, só os dados)
```

Melhorias Futuras
-----------------------------------------------------------------
- [ ] A saída é gerada no terminal. Para ser gerada em um arquivo é necessário adicionar ` > [nome_arquivo].sql` após o terceiro parâmetro, onde nome_arquivo.sql é o nome do arquivo que receberá a saída. Como melhoria futura seria interessante o programa poder gerar este arquivo automaticamente com o nome da tabela sem a necessidade de mais um parâmetro, como ` > [nome_arquivo].sql`.
- [ ] O programa carece de funções de tratamento dos dados obtidos por meio da importação do arquivo .csv, como por exemplo: funções que eliminem espaços em branco, redudâncias, etc.
- [ ] A querie gerada retorna todos os campos do tipo string, independente de qual era o tipo original (integer, double, datetime, etc).
