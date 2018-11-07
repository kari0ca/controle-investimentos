<?php
if (!isset($_SESSION['login_user'])) {
    if(!isset($_GET['method']) && $_GET['method'] !== 'login') {
        header("Location: ?controller=App\Controllers\Users&method=login");
        die();
    }
}

$user_check = $_SESSION['login_user'] ?? null;

$connection = \App\Database\Connection::open();

$statement = $connection->prepare('SELECT `iduser`, `nome`, `login` FROM `user` WHERE login = :login');
$statement->bindParam(':login', $user_check, \PDO::PARAM_STR);
$statement->execute();

$result = $statement->fetchObject();

//$loginname_session = $row['nome'];//username
//$login_user = $row['login'];
//$iduser = $row['iduser'];


