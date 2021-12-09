<!DOCTYPE html>
<html lang="pt-br">
 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="shortcut icon" href="https://www.uepa.br/sites/default/files/brasaouepa1.png" type="image/x-icon">
    <title>Painel Principal</title>

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

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>

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
        <div class="row">
            <div class="col-12 card">
                    <table class="table table-striped" id="minhaTabela" name="minhaTabela">
                        <thead>
                            <tr>
                                <th scope="col">Matrícula</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Email</th>
                                <th scope="col">Sexo</th>
                                <th scope="col">Data de Nascimento</th>
                                <th scope="col">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'mysql.php';
                            $dados = null;
                            if (!$_SESSION['logado']) {
                                header("Location: logout.php");
                                exit();
                            }
                            $mysql = MySQL::conectar();
                            $sql = 'SELECT
                            `pessoa`.`id`,
                            `pessoa`.`nome`,
                            `pessoa`.`sexo`,
                            `pessoa`.`data_nascimento`,
                            `usuario`.`email`,
                            `carona`.`tipo`,
                            `carona`.`idPessoa`
                        FROM
                            `pessoa`,
                            `usuario`,
                            `carona`
                        WHERE
                            `pessoa`.`id` = `usuario`.`idPessoa` AND `pessoa`.`id` = `carona`.`idPessoa` AND `pessoa`.`id` = '.$_SESSION['id'].'
                        ORDER BY
                            `pessoa`.`id`
                        DESC';
                            foreach ($mysql->query($sql)as $row) {
                                echo '<tr>';
                                echo '<th scope="row">'. Mysql::getMatricula($row['id']) . '</th>';
                                echo '<td>'. $row['nome'] . '</td>';
                                echo '<td>'. $row['email'] . '</td>';
                                echo '<td>'. $row['sexo'] . '</td>';
                                $date = date_create($row['data_nascimento']);
                                echo '<td>'. date_format($date, "d/m/Y") . '</td>';
                                echo '<td width=250>';
                                echo '<a class="btn btn-primary" href="read.php?id='.$row['id'].'">Info</a>';
                                echo ' ';
                                echo '<a class="btn btn-warning" href="update.php?id='.$row['id'].'">Atualizar</a>';
                                echo ' ';
                                echo '<a class="btn btn-danger" href="delete.php?id='.$row['id'].'">Excluir</a>';
                                echo '</td>';
                                echo '</tr>';

                                $dados = $row;
                            }
                            MySQL::desconectar();
                            ?>
                        </tbody>
                        <br>
                    </table>
                <div>
                    <a href="logout.php" class="btn btn-danger">Sair</a>

                    <?php

                    if ($dados['tipo'] != "NENHUM") {
                        echo ' <a href="encerrar.php?id='.$_SESSION['id'].'" class="btn btn-danger">Deseja encerrar o serviço de carona?</a>';
                    }
                    
                    ?>
                     
                    <a href="pesquisa.php" class="btn btn-success">Pesquisar</a>
                </div>
                <br>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    
    <!-- Latest compiled and minified JavaScript -->
    <script src="assets/js/bootstrap.min.js"></script>
    <script>
    $(document).ready( function () {
    $('#minhaTabela').DataTable();
    } );
    </script>
</body>

</html>
