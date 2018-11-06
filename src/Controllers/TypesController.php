<?php

namespace App\Controllers;

class TypesController extends Controller
{
    public function index()
    {
        //build query
        $query = "select t.tipo, s.subtipo from investdb.tipo_invest t, investdb.sub_tipo_invest s where t.idsubtipo=s.idsubtipo";
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
                $display_string .= '       <div class="col-xs-6" style="background-color:lightgray">' . $row[tipo] . '</div>';
                $display_string .= '       <div class="col-xs-6" style="background-color:lightgray">' . $row[subtipo] . '</div>';
            } else {
                $display_string .= '       <div class="col-xs-6">' . $row[tipo] . '</div>';
                $display_string .= '       <div class="col-xs-6">' . $row[subtipo] . '</div>';
            }
            $display_string .= '    </div>';
            $i = $i + 1;
        }
        //$display_string .= ' </div>';
        echo $display_string;
    }

    public function add()
    {
        $idsubtipo = $_POST["subtipo"];
        $error = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $mytipo = mysqli_real_escape_string($db, $_POST['tipo']);
            // Procura por outros tipos de investimento com o mesmo nome
            $sql = "SELECT tipo FROM investdb.tipo_invest WHERE tipo = '$mytipo'";
            $result = mysqli_query($db, $sql);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $active = $row['active'];

            $count = mysqli_num_rows($result);
            if ($count >= 1) {
                $error = "Já existe um Tipo de Investimento com este nome";
            } else {

                // Obtem o maior id_tipo
                $sql = "SELECT max(idtipoinvest) as idtipo FROM investdb.tipo_invest";
                $result = mysqli_query($db, $sql) or die(mysqli_error($db));
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $idtipo = $row[idtipo];
                $idtipo = $idtipo + 1;


                //echo "<br>Final das validações";
                //echo "<br>dados de validação: Idtipo:".$idtipo." Tipo:".$mytipo." SubTipo:".$idsubtipo;
                //INSERT INTO `investdb`.`user` (`iduser`, `nome`, `login`, `pass`, `aux_senha`, `email`) VALUES ('', 'afdasfd ', 'asdas sa', '123', '123', 'wg rwg wrg');
                $sql_insert = "INSERT INTO investdb.tipo_invest values(" . $idtipo . ",'" . $mytipo . "'," . $idsubtipo . ")";
                //echo "<br>SQL=".$sql_insert;

                if (!mysqli_query($db, $sql_insert)) {
                    echo "<br><br>Error: " . $sql_insert . "<br>" . mysqli_error($db);
                }
            }
        }


        echo '<html lang="en">
        <head>
            <title>Controle de investimentos</title>
            <meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
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
            <div class="container">
                <form action = "" method = "post">
                    <div class="row">
                        <div class="col-xs-6 form-group">
                            <input class="form-control" id="tipo" name="tipo" placeholder=" Tipo de Investimento" type="text" required>
                        </div>
                        <div class="col-xs-2 form-group text-align:center"> <p> Sub-Tipo de Investimento</p>
                        </div>
                        <div class="col-xs-4 form-group">
                            <select class="form-control" name="subtipo">
                    ';
        $query = "SELECT idsubtipo, subtipo FROM investdb.sub_tipo_invest";

        //Execute query
        $qry_result = mysqli_query($db, $query) or die(mysql_error());

        //Build Result String
        $display_string = "";

        // Insert a new row in the table for each person returned
        while ($row = mysqli_fetch_array($qry_result, MYSQLI_ASSOC)) {
            $display_string .= '<option value="' . $row[idsubtipo] . '">' . $row[subtipo] . '</option>';
        }
        echo $display_string;

        echo '        </select>
                        </div>
            
            
            
                        <!--
                  <div class="dropdown col-xs-2 form-group">
                    <button class="btn btn-primary dropdown-toggle dropdown-menu-right" type="button" id="subtipo" name="subtipo" data-toggle="dropdown">Subtipo de Investimento
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu">';
        $query = "SELECT subtipo FROM investdb.sub_tipo_invest";

        //Execute query
        $qry_result = mysqli_query($db, $query) or die(mysql_error());

        //Build Result String
        $display_string = "";

        // Insert a new row in the table for each person returned
        while ($row = mysqli_fetch_array($qry_result, MYSQLI_ASSOC)) {
            $display_string .= '<li><a href="#">' . $row[subtipo] . '</a></li>';
        }
        echo $display_string;

        echo '</ul>
                  </div>
                  -->

            <div class="row">
                <div class="col-xs-12 form-group">
                    <div class="btn-group pull-right">
                        <a href="cadastr-subtipo.php" class="btn btn-info">
                            <span class="glyphicon glyphicon-plus-sign"></span> Novo SubTipo
                        </a>
                        <button class="btn btn-danger" type="reset">Cancelar</button>
                        <button class="btn btn-default" type="submit">Cadastrar</button>
                    </div>
                </div>
            </div>
    </form>




    <!-- Mensagem de erro -->
    <div class="row">';

        if ($error != "") {
            echo '<div class="col-xs-10 form-group alert alert-danger">';
            echo $error;
            echo '</div>';
        }

        echo '</div>

    <!-- Listagem de tipos existentes -->
    <div class="row">
        <div class="col-xs-6" style="background-color:gray">Tipo</div>
        <div class="col-xs-6" style="background-color:gray">SubTipo</div>
    </div>
      ';
//        include "Types.php";
        echo '</div>
<p></p>
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
                    <br><a class="text-primary" href="https://xxxxxxxxxx.commobirise-free-win.zip">Download for Windows</a>
                    <br><a class="text-primary" href="https://xxxxxxxxxx.commobirise-free-mac.zip">Download for Mac</a>
                </p>
            </div>
        </div>

        <p>© Copyright 2018 Blablabla - All Rights Reserved</p>
</footer>

</html>';
    }
}