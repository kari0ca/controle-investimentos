<?php

namespace App\Controllers;

class InvestmentController
{
    public function index()
    {
        //build query
        $query = "select i.nome, e.entidade, t.tipo, s.subtipo from investdb.invest i, investdb.entidade e, investdb.tipo_invest t, investdb.sub_tipo_invest s where i.identidade = e.identidade and i.idtipo = t.idtipoinvest and t.idsubtipo = s.idsubtipo;";
        //echo "<br>Query=".$query;

        //Execute query
        if (!$qry_result = mysqli_query($db, $query)) {
            echo ("<br><br>Error description: " . mysqli_error($db)) . "<br><br>";
        }

        //$row = mysqli_fetch_array($qry_result,MYSQLI_ASSOC);
        //$active = $row['active'];
        $count = mysqli_num_rows($qry_result);

        //Build Result String
        $display_string = "";
        $i = 1;

        while ($row = mysqli_fetch_array($qry_result, MYSQLI_ASSOC)) {
            $display_string .= '    <div class="row">';
            if ($i % 2 == 0) {
                $display_string .= '       <div class="col-xs-3" style="background-color:lightgray">' . $row[nome] . '</div>';
                $display_string .= '       <div class="col-xs-3" style="background-color:lightgray">' . $row[entidade] . '</div>';
                $display_string .= '       <div class="col-xs-3" style="background-color:lightgray">' . $row[tipo] . '</div>';
                $display_string .= '       <div class="col-xs-3" style="background-color:lightgray">' . $row[subtipo] . '</div>';
            } else {
                $display_string .= '       <div class="col-xs-3">' . $row[nome] . '</div>';
                $display_string .= '       <div class="col-xs-3">' . $row[entidade] . '</div>';
                $display_string .= '       <div class="col-xs-3">' . $row[tipo] . '</div>';
                $display_string .= '       <div class="col-xs-3">' . $row[subtipo] . '</div>';
            }
            $display_string .= '    </div>';
            $i = $i + 1;
        }
        //$display_string .= ' </div>';
        echo $display_string;
    }

    public function add()
    {
        echo '<!DOCTYPE html>';

        $error = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nomeinv = mysqli_real_escape_string($db, $_POST['nome']);
            $entidade = mysqli_real_escape_string($db, $_POST['entidade']);
            $tipoinv = mysqli_real_escape_string($db, $_POST['tipoinv']);
            $detalhe = mysqli_real_escape_string($db, $_POST['detalhe']);
            $dataini = mysqli_real_escape_string($db, $_POST['dataini']);
            $datafinal = mysqli_real_escape_string($db, $_POST['datafim']);
            $ativo = mysqli_real_escape_string($db, $_POST['ativo']);
            $ativo2 = mysqli_real_escape_string($db, $_POST['ativo2']);

            // Procura por investimento com o mesmo nome
            $sql = "SELECT nome FROM investdb.invest WHERE nome = '$nomeinv'";
            $result = mysqli_query($db, $sql);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            //$active = $row['active'];

            //Verifica se existe algum investimento com o mesmo nome
            $count = mysqli_num_rows($result);
            if ($count >= 1) {
                $error = "Já existe um Investimento com este nome";
            } else { //Se não existe um investimento com o mesmo nome, pode inserir no banco
                // Obtem o maior idsubtipo
                $sql = "SELECT max(idinvest) as idsubtipo FROM investdb.invest";
                $result = mysqli_query($db, $sql) or die(mysql_error());
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $idinvest = $row[idsubtipo];
                $idinvest = $idinvest + 1;

                //$sql_insert = "INSERT INTO investdb.invest values(".$idinvest.",'".$nomeinv."',".$entidade.",".$tipoinv.",'".$detalhe."','".$dataini."','".$datafinal."','".$ativo."')";
                $sql_insert = "INSERT INTO investdb.invest values(" . $idinvest . ",'" . $nomeinv . "'," . $entidade . "," . $tipoinv . ",'" . $detalhe . "')";

                if (!mysqli_query($db, $sql_insert)) {
                    echo "Error: " . $sql_insert . "<br>" . mysqli_error($db);
                }
            }
        }


        echo '<html lang="en">
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
                    </ul>
                </div>
            </div>
        </nav>
        
        
        <!-- Conteúdo -->
        <p></p>
        <span></span>
        <span></span>
        
        
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xs-12">
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-xs-4 form-group">
                                <input class="form-control" id="nome" name="nome" placeholder="Nome do investimento" type="text"
                                       required>
                            </div>
                            <div class="col-xs-4 form-group">
                                <select class="form-control" name="entidade">';

        $query = "SELECT identidade, entidade FROM investdb.entidade;";

        //Execute query
        $qry_result = mysqli_query($db, $query) or die(mysql_error());

        //Build Result String
        $display_string = "<optgroup label='Entidade Gestora'>";

        // Insert a new row in the table for each person returned
        while ($row = mysqli_fetch_array($qry_result, MYSQLI_ASSOC)) {
            $display_string .= '<option value="' . $row[identidade] . '">' . $row[entidade] . '</option>';
        }
        echo $display_string;

        echo '</select>
                                <!-- <input class="form-control" id="entidade" name="entidade" placeholder="Entidade gestora" type="text" required>
                                -->
                            </div>
                            <div class="col-xs-4 form-group">
                                <select class="form-control" name="tipoinv">';

        $query = "select t.idtipoinvest, t.tipo, s.subtipo from investdb.tipo_invest t, investdb.sub_tipo_invest s where t.idsubtipo = s.idsubtipo;";

        //Execute query
        $qry_result = mysqli_query($db, $query) or die(mysql_error());

        //Build Result String
        $display_string = "<optgroup label='Tipo de Investimento'>";

        // Insert a new row in the table for each person returned
        while ($row = mysqli_fetch_array($qry_result, MYSQLI_ASSOC)) {
            $display_string .= '<option value="' . $row[idtipoinvest] . '">' . $row[tipo] . '-' . $row[subtipo] . '</option>';
        }
        echo $display_string;

        echo '</select>
                                <!-- <input class="form-control" id="tipoinv" name="tipoinv" placeholder="Tipo de investimento" type="text" required>
                                -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <input class="form-control" id="detalhe" name="detalhe" placeholder="Detalhes do investimento"
                                       type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <div class="btn-group pull-right">
                                    <a href="cadastr-tipo.php" class="btn btn-info">
                                        <span class="glyphicon glyphicon-plus-sign"></span> Novo Tipo Investimento
                                    </a>
                                    <a href="cadastr-entidade.php" class="btn btn-info">
                                        <span class="glyphicon glyphicon-plus-sign"></span> Nova Entidade Gestora
                                    </a>
                                    <button class="btn btn-danger" type="reset">Cancelar</button>
                                    <button class="btn btn-default" type="submit">Cadastrar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                     ';
        if ($error != "") {
            echo '<div class="col-xs-12 form-group alert alert-danger">';
            echo $error;
            echo '</div>';
        }

        echo '</div>
                    <!-- Listagem de investimentos existentes -->
                    <div class="row">
                        <div class="col-xs-3" style="background-color:gray">Nome</div>
                        <div class="col-xs-3" style="background-color:gray">Entidade</div>
                        <div class="col-xs-3" style="background-color:gray">Tipo</div>
                        <div class="col-xs-3" style="background-color:gray">SubTipo</div>
                    </div>
                    <?php
                    include "InvestmentController.php";
                    ?>
                </div>
            </div>
        </div>
        </body>
        
        
        <!-- Footer -->
        <footer class="container-fluid">
            <div class="container">
                <div class="media-container-row content text-white">
                    <div class="col-12 col-xs-3">
                        <div class="media-wrap">
                            <a href="https://xxxxxxxxxx.com">
                                <img src="assets/images/logo24.png" alt="Mobirise">
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-xs-3 mbr-fonts-style display-7">
                        <h5 class="pb-3">
                            Address
                        </h5>
                        <p class="mbr-text">
                            1234 Street Name
                            <br>City, AA 99999
                        </p>
                    </div>
                    <div class="col-12 col-xs-3 mbr-fonts-style display-7">
                        <h5 class="pb-3">
                            Contacts
                        </h5>
                        <p class="mbr-text">
                            Email: support@mobirise.com
                            <br>Phone: +1 (0) 000 0000 001
                            <br>Fax: +1 (0) 000 0000 002
                        </p>
                    </div>
                    <div class="col-12 col-xs-3 mbr-fonts-style display-7">
                        <h5 class="pb-3">
                            Links
                        </h5>
                        <p class="mbr-text">
                            <a class="text-primary" href="https://xxxxxxxxxx.com">Website builder</a>
                            <br><a class="text-primary" href="https://xxxxxxxxxx.commobirise-free-win.zip">Download for
                                Windows</a>
                            <br><a class="text-primary" href="https://xxxxxxxxxx.commobirise-free-mac.zip">Download for Mac</a>
                        </p>
                    </div>
                </div>
        
                <p>© Copyright 2018 Blablabla - All Rights Reserved</p>
        </footer>
        
        </html>';
    }
}