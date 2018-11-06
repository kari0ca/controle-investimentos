<?php

namespace App\Models\Tables;

use App\Models\Entities\Type;

class Types
{
    public function add(Type $type)
    {
        if ($this->count() >= 1) {
            $error = "Já existe um Tipo de Investimento com este nome";
        } else {
            //echo "<br>Final das validações";
            //echo "<br>dados de validação: Idtipo:".$idtipo." Tipo:".$mytipo." SubTipo:".$idsubtipo;
            //INSERT INTO `investdb`.`user` (`iduser`, `nome`, `login`, `pass`, `aux_senha`, `email`) VALUES ('', 'afdasfd ', 'asdas sa', '123', '123', 'wg rwg wrg');
            $sql_insert = "INSERT INTO investdb.tipo_invest values(" . $this->getnextId() . ",'" . $mytipo . "'," . $idsubtipo . ")";
            //echo "<br>SQL=".$sql_insert;

            if (!mysqli_query($db, $sql_insert)) {
                echo "<br><br>Error: " . $sql_insert . "<br>" . mysqli_error($db);
            }
        }
    }

    public function count()
    {
        $mytipo = mysqli_real_escape_string($db, $_POST['tipo']);
        // Procura por outros tipos de investimento com o mesmo nome
        $sql = "SELECT tipo FROM investdb.tipo_invest WHERE tipo = '$mytipo'";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $active = $row['active'];

        return mysqli_num_rows($result);
    }

    public function getnextId()
    {
        // Obtem o maior id_tipo
        $sql = "SELECT max(idtipoinvest) as idtipo FROM investdb.tipo_invest";
        $result = mysqli_query($db, $sql) or die(mysql_error());
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $idtipo = $row[idtipo];

        return $idtipo + 1;
    }
}