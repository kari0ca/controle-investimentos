<?php

namespace App\Controllers;

class WalletController extends Controller
{
    public function getController()
    {
        //echo "Entrou no get-carteira.php   ";
        // Retrieve data from Query String
        $nome = $_GET['nome'];
        $tipo = $_GET['tipo'];
        $subtipo = $_GET['subtipo'];

        // Escape User Input to help prevent SQL Injection
        $nome = mysqli_real_escape_string($db,$nome);
        $tipo = mysqli_real_escape_string($db,$tipo);
        $subtipo = mysqli_real_escape_string($db,$subtipo);

        echo "userid=".$_SESSION['iduser'];

        /*
        select c.idcarteira, c.idinvest, i.nome, e.entidade, c.data_ini, c.rent_val, c.rent_perc, c.ativo
    from investdb.carteira c, investdb.invest i, investdb.entidade e
    where c.idinvest = i.idinvest and i.identidade = e.identidade;
        */


        //build query
        //$query = "select i.idinvest, i.nome, t.tipo, s.subtipo from investdb.invest i, investdb.tipo_invest t, investdb.sub_tipo_invest s where i.idtipo=t.idtipoinvest and t.idsubtipo=s.idsubtipo";
        $query = "select c.idcarteira, c.idinvest, i.nome, e.entidade, c.data_ini, c.rent_val, c.rent_perc, c.ativo from investdb.carteira c, investdb.invest i, investdb.entidade e where c.idinvest = i.idinvest and i.identidade = e.identidade";
        $query .= " and iduser=".$_SESSION['iduser'];
        if ($nome  != ''){
            $query .= " and i.nome='".$nome."'";
        }

        /*  comentario previo de tipo e subtipo
       if ($tipo  != ''){
          $query .= " and t.tipo='".$tipo."'";
       }
       if ($subtipo  != ''){
          $query .= " and s.subtipo='".$subtipo."'";
       }
        */


        //Execute query
        $qry_result = mysqli_query($db,$query) or die(mysql_error());

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
        while($row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC)) {

            $display_string .= '<thead>';
            $display_string .= '	<tr>';
            $display_string .= '		<td>' . $row[idinvest] . '</td>';
            $display_string .= '		<td>' . $row[nome] . '</td>';
            $display_string .= '		<td>' . $row[tipo] . '</td>';
            $display_string .= '		<td>' . $row[subtipo] . '</td>';
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

    public function index()
    {
        echo '<!DOCTYPE html>';

        $idsubtipo = $_POST["subtipo"];
        $error = "";

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
                    if (ajaxRequest.readyState == 4) {
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
                    <form name="myForm">
                        <div class="row">
                            <div class="col-xs-1"><h4>Filtros</h4>
                            </div>
                            <div class="col-xs-3">Nome:
                                <select class="form-control" name="nome">
                                    <option value=""></option>';
        $query = "SELECT distinct(nome) as nome FROM investdb.invest";

        //Execute query
        $qry_result = mysqli_query($db, $query) or die(mysql_error());
        $display_string = "";
        while ($row = mysqli_fetch_array($qry_result, MYSQLI_ASSOC)) {
            $display_string .= '<option value="' . $row[nome] . '">' . $row[nome] . '</option>';
        }
        echo $display_string;

        echo '</select>
                    </div>
                    <div class="col-xs-3">Tipo:
                        <select class="form-control" name="tipo">
                            <option value=""></option>';

        $query = "SELECT distinct(tipo) as tipo FROM investdb.tipo_invest";

        //Execute query
        $qry_result = mysqli_query($db, $query) or die(mysql_error());

        //Build Result String
        $display_string = "";
        while ($row = mysqli_fetch_array($qry_result, MYSQLI_ASSOC)) {
            $display_string .= '<option value="' . $row[tipo] . '">' . $row[tipo] . '</option>';
        }
        echo $display_string;

        echo '</select>
                    </div>
                    <div class="col-xs-3">SubTipo:
                        <select class="form-control" name="subtipo">
                            <option value=""></option>';

        $query = "SELECT distinct(subtipo) as subtipo FROM investdb.sub_tipo_invest";

        //Execute query
        $qry_result = mysqli_query($db, $query) or die(mysql_error());
        //Build Result String
        $display_string = "";
        while ($row = mysqli_fetch_array($qry_result, MYSQLI_ASSOC)) {
            $display_string .= '<option value="' . $row[subtipo] . '">' . $row[subtipo] . '</option>';
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
            <div id="ajaxDiv">
                <?php
                include "get-carteira.php";
                ?>
            </div>
        </div>
    </div>
</div>
<p></p>
</body>';
    }
}