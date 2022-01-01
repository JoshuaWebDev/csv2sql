**CSV2SQL**
===============================================================
> Script em PHP que lê um arquivo no formato csv e converte-o para instruções sql

+ Autor: [Joshua](joshuawebdev.wordpress.com)
+ Linguagem: [PHP](https://www.php.net)
+ Versão 1.3

Descrição:
---------------------------------------------------------------
Lê um arquivo no formato csv ao qual consiste em uma tabela importada de um banco de dados qualquer ou planilha.  

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
O programa roda por meio de um terminal (prompt de comando, no caso do Windows). Ele recebe três parâmetros:

1. local do arquivo csv que será convertido para sql
2. nome da tabela que será gerada após a conversão
3. separador utilizado pelo arquivo csv para separar as colunas (por padrão é uma vírgula, mas em alguns arquivos pode ser outro, como um ponto e vírgula, por exemplo)

Exemplo de Execução:

```
php csv2sql.php [param1] [param2] [param3]
```

**[param1]**, **[param2]** e **[param3]** são os parâmetros citados acima.

### Exemplo de Uso:

Vamos converter o arquivo data_example.csv para um arquivo sql com as instruções para gerar uma tabela chamada alunos.

```
php csv2sql.php csv/data_example.csv ; alunos
```

Porém isso exibirá as queries geradas no terminal e isso não é muito interessante. Para gerar um arquivo com as queries de criação da tabela adicione ` > [nome_arquivo].sql` após o segundo parâmetro, onde **[nome_arquivo].sql** será o nome do arquivo que pretende-se criar. Vejamos um exemplo mais completo onde geramos a tabela alunos e a salvamos no arquivo alunos.sql.

```
php csv2sql.php csv/data_example.csv ; alunos > alunos.sql
```

Recomendações
-----------------------------------------------------------------
- Esta versão do programa ainda exige algumas melhorias em relação a tratamento do arquivo csv antes da conversão, por exemplo eliminação de espaços em branco, linhas desnecessárias ou outras informações geradas automaticamente por uma planilha e que não sejam necessária para a criação das queries. Então uma dica seria eliminar essas inconformidades antes de executar o programa.
- Pode ser que ao gerar as queries haja algum resíduo de texto aos qual não deveria estar lá quando forem executados em um banco de dados, por exemplo vírgulas a mais ou campo numéricos entre aspas duplas. Então é importante removê-los antes de tentar executar estas queries em seu banco de dados.

Melhorias Futuras
-----------------------------------------------------------------
- [ ] A saída é gerada no terminal. Para ser gerada em um arquivo é necessário adicionar ` > [nome_arquivo].sql` após o terceiro parâmetro, onde nome_arquivo.sql é o nome do arquivo que receberá a saída. Como melhoria futura seria interessante o programa poder gerar este arquivo automaticamente com o nome da tabela sem a necessidade de mais um parâmetro, como ` > [nome_arquivo].sql`.
- [ ] O programa carece de funções de tratamento dos dados obtidos por meio da importação do arquivo .csv, como por exemplo: funções que eliminem espaços em branco, redudâncias, etc.
- [ ] A querie gerada retorna todos os campos do tipo string, independente de qual era o tipo original (integer, double, date, etc).
