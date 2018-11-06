<?php
$user_check = $_SESSION['login_user'] ?? null;

$ses_sql = mysqli_query($db, "select iduser, nome, login from investdb.user where login = '$user_check' ");

$row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);

$loginname_session = $row['nome'];//username
$login_user = $row['login'];
$iduser = $row['iduser'];


if (!isset($_SESSION['login_user'])) {
    header("location:login.php");
    die('Não ignore meu cabeçalho...');
}
?>