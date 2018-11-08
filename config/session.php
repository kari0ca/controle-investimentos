<?php
if (empty($_SESSION['user']['login'])) {
    if(!isset($_GET['method']) && $_GET['method'] !== 'login') {
        header("Location: ?controller=App\Controllers\Users&method=login");
        die();
    }
}


$connection = \App\Database\Connection::open();

$statement = $connection->prepare('SELECT `iduser`, `nome`, `login` FROM `user` WHERE login = :login');
$statement->bindParam(':login', $_SESSION['user']['login'], \PDO::PARAM_STR);
$statement->execute();

$result = $statement->fetchObject();

//$loginname_session = $row['nome'];//username
//$login_user = $row['login'];
//$iduser = $row['iduser'];


