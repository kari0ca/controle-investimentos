<?php

namespace App\Controllers;

use App\Database\Connection;

class WalletController extends Controller
{
    public function index()
    {
        echo '<!DOCTYPE html>';

        $idsubtipo = $_POST['subtipo'] ?? null;
        $error = '';

        echo '<!-- AJAX -->
        <script language="javascript" type="text/javascript">
            function ajaxFunction() { //Browser Support Code
                var ajaxRequest;  // The variable that makes Ajax possible!
        
                try {
                    // Opera 8.0+, Firefox, Safari
                    ajaxRequest = new XMLHttpRequest();
                } catch (e) {
                    // Internet Explorer Browsers
                    try {
                        ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
                    } catch (e) {
                        try {
                            ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (e) {
                            // Something went wrong
                            alert("Your browser broke!");
                            return false;
                        }
                    }
                }
        
                // Create a function that will receive data
                // sent from the server and will update
                // div section in the same page.
        
                ajaxRequest.onreadystatechange = function () {
                    if (ajaxRequest.readyState === 4) {
                        var ajaxDisplay = document.getElementById(\'ajaxDiv\');
                        ajaxDisplay.innerHTML = ajaxRequest.responseText;
                    }
                }
        
                // Now get the value from user and pass it to
                // server script.
        
                var nome = document.getElementById(\'nome\').value;
                var tipo = document.getElementById(\'tipo\').value;
                var subtipo = document.getElementById(\'subtipo\').value;
                var queryString = "?nome=" + nome;
        
                queryString += "&tipo=" + tipo + "&subtipo=" + subtipo;
                ajaxRequest.open("GET", "get-carteira.php" + queryString, true);
                ajaxRequest.send(null);
            }
        </script>
        
        <html lang="en">
        <head>
            <title>Controle de investimentos</title>
            <meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1"/>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
            <link rel="stylesheet" href="/css/style.css">
        </head>
        <body>
        
        <!-- Barra de navegação -->
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Logo</a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="./index.php">Início</a></li>
                        <li><a href="./sobre.php">Sobre</a></li>
                        <li><a href="./contato.php">Contato</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="./login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                        <li><a href="./logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        
        
        <!-- Conteúdo -->
        <p></p>
        <span></span>
        <span></span>
        
        
        <div class="container">
        
            <!-- Listagem de investimentos -->
            <div class="row justify-content-center">
                <div class="col-xs-12">
                    <div class="row">
                        <p>
                        <h2>Carteira de Investimentos</h2></p>
                    </div>
                    <form name="myForm" method="get" action="/?controller=App\Controllers\Wallet">
                    <input type="hidden" name="controller" value="App\Controllers\Wallet">
                        <div class="row">
                            <div class="col-xs-1"><h4>Filtros</h4>
                            </div>
                            <div class="col-xs-3">Nome:
                                <select class="form-control" name="nome">
                                    <option value=""></option>';

        $connection = Connection::open();

        $statement = $connection->query('SELECT distinct(`nome`) as `nome` FROM `invest`');
        $statement->execute();

        $display_string = '';
        while ($result = $statement->fetchObject()) {
            $display_string .= '<option value="' . $result->nome . '">' . $result->nome . '</option>';
        }
        echo $display_string;

        echo '</select>';

        echo '</div>
                    <div class="col-xs-3">Tipo:
                        <select class="form-control" name="tipo">
                            <option value=""></option>';

        $statement = $connection->query('SELECT distinct(`tipo`) as `tipo` FROM `tipo_invest`');
        $statement->execute();

        //Build Result String
        $display_string = '';
        while ($result = $statement->fetchObject()) {
            $display_string .= '<option value="' . $result->tipo . '">' . $result->tipo . '</option>';
        }
        echo $display_string;

        echo '</select>
                    </div>
                    <div class="col-xs-3">SubTipo:
                        <select class="form-control" name="subtipo">
                            <option value=""></option>';


        $statement = $connection->query('SELECT distinct(`subtipo`) as `subtipo` FROM `sub_tipo_invest`');
        $statement->execute();

        //Execute query
        //Build Result String
        $display_string = "";
        while ($result = $statement->fetchObject()) {
            $display_string .= '<option value="' . $result->subtipo . '">' . $result->subtipo . '</option>';
        }
        echo $display_string;

        echo '</select>
                    </div>
                    <div class="col-xs-1">
                        <button class="btn btn-default" type="submit">Filtrar</button>
                    </div>
                </div>
            </form>
            <br><br>
            <div id="ajaxDiv">';
                
                $this->getWallet();
                
            echo '</div>
        </div>
    </div>
</div>
<p></p>
</body>';
    }


    public function getWallet()
    {
        //echo "Entrou no get-carteira.php   ";
        // Retrieve data from Query String
        $nome = $_GET['nome'] ?? null;
        $tipo = $_GET['tipo'] ?? null;
        $subtipo = $_GET['subtipo'] ?? null;

        $connection = Connection::open();

        $query = "select c.idcarteira, c.idinvest, i.nome, e.entidade, c.data_ini, c.rent_val, c.rent_perc, c.ativo from investdb.carteira c, investdb.invest i, investdb.entidade e where c.idinvest = i.idinvest and i.identidade = e.identidade";
        $query .= ' and iduser = :iduser';
        if (!empty($nome)) {
            $query .= ' and i.nome = :nome';
        }

        $statement = $connection->prepare($query);
        $statement->bindParam(':iduser', $_SESSION['iduser'], \PDO::PARAM_INT);
        if (!empty($nome)) {
            $statement->bindParam(':nome', $nome, \PDO::PARAM_STR);
        }

        //Build Result String
        $display_string = "<table class='table table-hover'>";
        $display_string .= "<thead>";
        $display_string .= "	<tr>";
        $display_string .= "		<th>Id</th>";
        $display_string .= "		<th>Nome</th>";
        $display_string .= "		<th>Tipo</th>";
        $display_string .= "		<th>SubTipo</th>";
        $display_string .= "		<th>Rent Total $</th>";
        $display_string .= "		<th>Rent Mês %</th>";
        $display_string .= "		<th>Rent Mês %</th>";
        $display_string .= "		<th>Ativo</th>";
        $display_string .= "	</tr>";
        $display_string .= "</thead>";


        // Insert a new row in the table for each person returned
        while ($result = $statement->fetchObject()) {

            $display_string .= '<thead>';
            $display_string .= '	<tr>';
            $display_string .= '		<td>' . $result->idinvest . '</td>';
            $display_string .= '		<td>' . $result->nome . '</td>';
            $display_string .= '		<td>' . $result->tipo . '</td>';
            $display_string .= '		<td>' . $result->subtipo . '</td>';
            $display_string .= '		<td></td>';
            $display_string .= '		<td></td>';
            $display_string .= '		<td></td>';
            $display_string .= '		<td></td>';
            $display_string .= '	</tr>';
            $display_string .= '</thead>';

            //echo "<br> Id = " . $row[idinvest] . ",";
        }

        $display_string .= "</table>";
        echo $display_string;
    }


}