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
                <li>
                    <a href="/?controller=App\Controllers\Users&method=login">
                        <span class="glyphicon glyphicon-log-in"></span> Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>