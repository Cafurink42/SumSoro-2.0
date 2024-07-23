<?php
define ('DB_SERVER', 'localhost');
define ('DB_USERNAME', 'root');
define ('DB_PASSWORD', '');
define ('DB_NAME', 'userssumsoro');

try{
    $pdo = new PDO("mysql:host=". DB_SERVER . ";dbname=". DB_NAME, DB_USERNAME, DB_PASSWORD);

    $pdo ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(PDOException $i){
    die("Erro: Não foi possível conectar ao banco" . $i->getMessage());

}

?>
