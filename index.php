<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <title>Migrador 2.0</title>
</head>

<body>
<form enctype="multipart/form-data" action="migrador.php" method="post">
  <fieldset>
    <legend>Informe os dados para migração:</legend>
    
    Nome da Escola: <input type="text" name="school_name" value=""><br><br>
    Arquivo CSV: <input type="file" name="csv_file"><br><br>

    <input type="submit" value="Executar">
  </fieldset>
</form>
</body>

</html>