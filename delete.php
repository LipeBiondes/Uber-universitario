<?php
require 'mysql.php';

$id = 0;

if(!$_SESSION['logado'])
{
    header("Location: index.php");
    exit;
}

if (!empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if (!empty($_POST)) {
    $id = $_POST['id'];

    //Delete do mysql:
    $mysql = MySQL::conectar();
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "
    START TRANSACTION;
    
    DELETE FROM usuario where idPessoa = ?;
    DELETE FROM carona where idPessoa = ?;
    DELETE FROM pessoa where id = ?;

    COMMIT;
    ";
    
    $q = $mysql->prepare($sql);
    $q->execute(array($id,$id,$id));

    MySQL::desconectar();
    header("Location: logout.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <title>Deletar Contato</title>

    <style>
        .gif{
            max-width: 100px;
            margin: 0;
        }
        .gif img{
            width: 100%;
        }
        .card-header h2{
            margin: auto;
        }
        .wellx{
            text-align: center;
            margin: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12 card" style="padding: 0;">
                <div class="card-header" style="text-align: center; display:flex;">
                    <figure class="gif"><img src="https://acegif.com/wp-content/gifs/car-driving-7.gif" alt="gif"></figure>
                    <h2>Uber Universitário</h2>
                </div>
            </div>
        </div>
        </br>
            <div class="col-12 card">
                <div class="span10 offset1">
                    <div class="row">
                        <h3 class="wellx">Excluir Contato</h3>
                    </div>
                    <br>
                    <form class="form-horizontal" action="delete.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $id;?>" />
                        <div class="alert alert-danger"> Deseja excluir minhas informações?
                        </div>
                        <div class="form actions">
                            <button type="submit" class="btn btn-danger">Sim</button>
                            <a href="painel.php" type="btn" class="btn btn-success">Não</a>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>
