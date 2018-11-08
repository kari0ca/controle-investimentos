<?php

namespace App\Controllers;

use App\Database\Transaction;

/**
 * Class UsersController
 * @package App\Controllers
 */
class UsersController extends Controller
{
    /**
     * Login method
     * @throws \Exception
     */
    public function login()
    {
        if (isset($_SESSION['user'])) {
            return header('Location: /?controller=App\Controllers\Wallet');
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                Transaction::open();
                $connection = Transaction::get();

                $statement = $connection->prepare('SELECT `iduser`, `pass` FROM `user` WHERE `login` = :login');
                $statement->bindParam(':login', $_POST['username'], \PDO::PARAM_STR);
                $statement->execute();

                $result = $statement->fetchObject();

                if ($result && password_verify($_POST['password'], $result->pass)) {
                    $_SESSION['user'] = [
                        'login' => $_POST['username'],
                        'iduser' => $result->iduser,
                    ];

                    header('Location: /?controller=App\Controllers\Wallet');
                } else {
                    $error = "Login ou Senha invalidos";
                }
                Transaction::close();
            } catch (\Exception $exception) {
                Transaction::rollback();
                throw $exception;
            }
        }

        require_once 'header.php';
        echo '<body>';
        echo '<!-- Barra de navegação -->';
        require_once 'menu.php';
        echo '<!-- Conteúdo -->';
        echo '    <p></p>';
        echo '    <span></span>';
        echo '    <span></span>';

        echo '    <div class="container">';
        echo '        <div class="row justify-content-center">';
        echo '            <div class="col-xs-12">';
        echo '                <form action="?controller=App\Controllers\Users&method=login" method="post">';
        echo '                    <div class="row">';
        echo '                        <div class="col-xs-4 form-group">';
        echo '                            <input class="form-control" id="login" name="username" placeholder="Login" type="text" required value="' . ($_POST['username'] ?? null) . '">';
        echo '                        </div>';
        echo '                        <div class="col-xs-4 form-group">';
        echo '                            <input class="form-control" id="pass" name="password" placeholder="Senha" type="password" required value="' . ($_POST['password'] ?? null) . '">';
        echo '                        </div>';
        echo '                    </div>';
        echo '                    <div class="row">';
        echo '                        <div class="col-xs-4 form-group text-align:center">';
        echo '                            <a href="?controller=App\Controllers\Users&method=add">Cadastrar usuário</a></div>';
        echo '                            <div class="col-xs-4 form-group">';
        echo '                                <button class="btn btn-default pull-right align:right" type="submit">Entrar</button>';
        echo '                            </div>';
        echo '                        </div>';
        echo '                    </div>';
        echo '            </form>';
        echo '            <div class="row">';
        if (!empty($error)) {
            echo '<div class="col-xs-8 form-group alert alert-danger">';
            echo $error;
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        require 'footer.php';
        echo '</body>';
        echo '</html>';
    }

    /**
     * Logout method
     */
    public function logout()
    {
        if (session_destroy()) {
            header('Location: /?controller=App\Controllers\Users&method=login');
        }
    }

    /**
     * @throws \Exception
     */
    public function add()
    {
        $error = "";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                Transaction::open();
                $connection = Transaction::get();

                $statement = $connection->prepare('SELECT count(*) FROM `user` WHERE `login` = :login');
                $statement->bindParam(':login', $_POST['username'], \PDO::PARAM_STR);
                $statement->execute();
                $count = $statement->fetchColumn();

                if ((int)$count > 0) {
                    $error = 'Já existe um usuário com este nome';
                }

                if ($_POST['password1'] != $_POST['password2']) {
                    $error .= 'A confirmação da senha não é igual';
                }

                $statement = $connection->prepare('SELECT max(`iduser`)  FROM `user`');
                $statement->execute();
                $lastId = $statement->fetchColumn();

                $passwordHash = password_hash($_POST['password1'], PASSWORD_DEFAULT);
                $idUser = $lastId + 1;

                if(empty($error)) {
                    $statement = $connection->prepare('INSERT INTO user (`iduser`, `nome`, `login`, `pass`, `aux_senha`, `email`) VALUES (:iduser, :nome, :login, :pass, :aux_senha, :email)');
                    $statement->bindParam(':iduser', $idUser, \PDO::PARAM_INT);
                    $statement->bindParam(':nome', $_POST['nome'], \PDO::PARAM_STR);
                    $statement->bindParam(':login', $_POST['username'], \PDO::PARAM_STR);
                    $statement->bindParam(':pass', $passwordHash, \PDO::PARAM_STR);
                    $statement->bindParam(':aux_senha', $_POST['lembrete'], \PDO::PARAM_STR);
                    $statement->bindParam(':email', $_POST['email'], \PDO::PARAM_STR);
                    $statement->execute();
                }

                Transaction::close();

                header('Location: /?controller=App\Controllers\Users&method=login');

            } catch (\Exception $exception) {
                Transaction::rollback();
                throw $exception;
            }
        }

        require_once 'header.php';
        echo '<body>';
        echo '<!--Barra de navegação-->';

        require_once 'menu.php';

        echo '<!--Conteúdo -->
        <p ></p >
        <span ></span >
        <span ></span >
        
        <div class="container" >
            <div class="row justify-content-center" >
                <div class="col-xs-12" >
                    <form action="/?controller=App\Controllers\Users&method=add" method="post">
                        <div class="row" >
                            <div class="col-xs-4 form-group" >
                                <input class="form-control" id="nome" name="nome" placeholder="Nome" type="text" required value="' . ($_POST['nome'] ?? null) . '">
                            </div >
                            <div class="col-xs-4 form-group" >
                                <input class="form-control" id="login" name="username" placeholder="Login" type="text" required value="' . ($_POST['username'] ?? null) . '">
                            </div >
                        </div >
                        <div class="row" >
                            <div class="col-xs-8 form-group" >
                                <input class="form-control" id="email" name="email" placeholder = "E-mail" type="email" required value="' . ($_POST['email'] ?? null) . '">
                            </div >
                        </div >
                        <div class="row" >
                            <div class="col-xs-4 form-group" >
                                <input class="form-control" id="pass1" name="password1" placeholder="Senha" type="password" required value="' . ($_POST['password1'] ?? null) . '">
                            </div >
                            <div class="col-xs-4 form-group" >
                                <input class="form-control" id="pass2" name="password2" placeholder="Repita a Senha" type="password" required value="' . ($_POST['password2'] ?? null) . '">
                            </div >
                        </div >
                        <div class="row" >
                            <div class="col-xs-4 form-group" >
                                <input class="form-control" id="lembrete" name="lembrete" placeholder="Lembrete da senha" type="text" required value="' . ($_POST['lembrete'] ?? null) . '">
                            </div >
                        </div >
                        <div class="row" >
                            <div class="col-xs-4 form-group" >
                                <button class="btn btn-danger pull-right align:right" type="reset" >Cancelar</button >
                            </div >
                            <div class="col-xs-4 form-group" >
                                <button class="btn btn-default pull-right align:left " type="submit" >Cadastrar</button >
                            </div >
                        </div >
                    </form >
                    <div class="row" > ';
        if ($error != "") {
            echo '<div class="col-xs-8 form-group alert alert-danger" > ';
            echo $error;
            echo '</div > ';
        }

        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        require 'footer.php';
        echo '</body>';
        echo '</html>';
    }
}