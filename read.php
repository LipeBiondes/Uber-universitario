<?php
require 'mysql.php';


if(!$_SESSION['logado'])
{
    header("Location: index.php");
    exit;
}

$id = null;
if (!empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if (null == $id) {
    header("Location: painel.php");
} else {
    $mysql = MySQL::conectar();
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT
                        `pessoa`.`id`,
                        `pessoa`.`nome`,
                        `pessoa`.`sexo`,
                        `pessoa`.`data_nascimento`,
                        `usuario`.`email`,
                        `usuario`.`senha`,
                        `pessoa`.`imagem`
                    FROM
                        `pessoa`,
                        `usuario`
                    WHERE
                        `pessoa`.`id` = `usuario`.`idPessoa` AND `pessoa`.`id` = ?";

    $q = $mysql->prepare($sql);
    $q->execute(array($id));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    MySQL::desconectar();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="https://www.uepa.br/sites/default/files/brasaouepa1.png" type="image/x-icon">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <title>Consultando</title>

    
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
        .well{
            text-align: center;
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
        <div class="span10 offset1">
            <div class="card">
                <div class="card-header">
                    <h3 class="well">Informações de cadastro</h3>
                </div>
                <div class="container">
                    <div class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label">Nome:</label>
                            <div class="controls form-control">
                                <label class="carousel-inner">
                                    <?php echo $data['nome']; ?>
                                </label>
                            </div>
                        </div>
                        
                        <div class="control-group">
                            <label class="control-label">Email:</label>
                            <div class="controls form-control disabled">
                                <label class="carousel-inner">
                                    <?php echo $data['email']; ?>
                                </label>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Data de Nascimento:</label>
                            <div class="controls form-control disabled">
                                <label class="carousel-inner">
                                <?php 
                                
                                $date = date_create($data['data_nascimento']);
                                echo date_format($date,"d/m/Y"); 
                                
                                ?>
                                </label>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Sexo:</label>
                            <div class="controls form-check disabled">
                                <label class="carousel-inner">
                                    <?php echo $data['sexo']; ?>
                                </label>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label"> Minha foto:</label>
                            <div div class="card-header" style="display:flex;"> 
                                <div class="controls form-check disabled" style="padding: 0;">
                                    <label class="carousel-inner">
                                    
                                        <?php echo '<img heigth="auto" width="400" class="img-thumbnail" alt="'.$data['nome'].'" src="data:image/jpeg;base64,'.base64_encode($data['imagem']).'"/>'; ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <br/>

                        <div class="form-actions">
                            <a href="painel.php" class="btn btn-success">Voltar<a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
            integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
            crossorigin="anonymous"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
