<?php
    require 'mysql.php';

    if (!$_SESSION['logado']) {
        header("Location: index.php");
        exit;
    }

    $id = null;
    if (!empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }

    if (null == $id) {
        header("Location: painel.php");
    }

    if (!empty($_POST) && !empty($_FILES["imagem"]["name"])) {
        $nomeErro = null;
        $telefoneErro = null;
        $emailErro = null;
        $senhaErro = null;
        $data_nascimentoErro = null;
        $sexoErro = null;

        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $data_nascimento = $_POST['data_nascimento'];
        $sexo = $_POST['sexo'];
        $carona = $_POST['carona'];
        //Validação
        $validacao = true;
        if (empty($nome)) {
            $nomeErro = 'Por favor digite o nome!';
            $validacao = false;
        }

        if (empty($telefone)) {
            $telefoneErro = 'Por favor digite o telefone!';
            $validacao = false;
        }

        if (empty($email)) {
            $emailErro = 'Por favor digite o email!';
            $validacao = false;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErro = 'Por favor digite um email válido!';
            $validacao = false;
        }

        if (!empty($_FILES["imagem"]["name"])) {
        } else {
            $erroImg .= 'Selecione uma imagem\n';
            $validacao = false;
        }

        if (empty($senha)) {
            $senhaErro = 'Por favor digite a senha!';
            $validacao = false;
        }

        if (empty($data_nascimento)) {
            $data_nascimentoErro = 'Por favor digite a data de nascimento!';
            $validacao = false;
        }

        if (empty($sexo)) {
            $sexoErro = 'Por favor preenche o campo!';
            $validacao = false;
        }

        // update data
        if ($validacao) {
            $mysql = MySQL::conectar();
            $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = "START TRANSACTION;
            UPDATE pessoa  set nome = ?, data_nascimento = ?, sexo = ? , imagem = ?, telefone = ? WHERE id = ?;
            UPDATE usuario  set email = ?, senha = ? WHERE idPessoa = ?;
            UPDATE carona  set tipo = ? WHERE idPessoa = ?;
            COMMIT;";
            $q = $mysql->prepare($sql);

            $imagem = file_get_contents($_FILES["imagem"]["tmp_name"]);

            $q->execute(array($nome, $data_nascimento, $sexo, $imagem, $telefone, $id, $email,md5($senha), $id, $carona, $id));

            MySQL::desconectar();
            header("Location: painel.php");
        }
    } 
    else {
        $mysql = MySQL::conectar();
        $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT
        `pessoa`.`id`,
        `pessoa`.`nome`,
        `pessoa`.`sexo`,
        `pessoa`.`data_nascimento`,
        `pessoa`.`imagem`,
        `pessoa`.`telefone`,
        `usuario`.`email`,
        `usuario`.`senha`,
        `carona`.`idPessoa`,
        `carona`.`tipo`
    FROM
        `pessoa`,
        `usuario`,
        `carona` 
    WHERE
        `pessoa`.`id` = `usuario`.`idPessoa` AND `pessoa`.`id` = `carona`.`idPessoa` AND `pessoa`.`id` = ?";
        $q = $mysql->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $nome = $data['nome'];
        $email = $data['email'];
        $senha = $data['senha'];
        $data_nascimento = $data['data_nascimento'];
        $sexo = $data['sexo'];
        $carona = $data['tipo'];
        $imagem = $data['imagem'];
        $telefone = $data['telefone'];
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
    <!-- using new bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Atualizar Contato</title>
    
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
        <div class="span10 offset1">
            <div class="row">
                <div class="col-12 card" style="padding: 0;">
                    <div class="card-header" style="text-align: center; display:flex;">
                        <figure class="gif"><img src="https://acegif.com/wp-content/gifs/car-driving-7.gif" alt="gif"></figure>
                        <h2>Uber Universitário</h2>
                    </div>
                </div>
            </div>
            </br>
            <div class="card">
                <div class="card-header">
                    <h3 class="well"> Atualizar Contato </h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="update.php?id=<?php echo $id ?>"  enctype="multipart/form-data" method="post">

                        <div class="control-group <?php echo !empty($nomeErro) ? 'error' : ''; ?>">
                            <label class="control-label">Nome</label>
                            <div class="controls">
                                <input name="nome" class="form-control" size="50" type="text" placeholder="Nome"
                                    value="<?php echo !empty($nome) ? $nome : ''; ?>">
                                <?php if (!empty($nomeErro)): ?>
                                    <span class="text-danger"><?php echo $nomeErro; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="control-group <?php echo !empty($emailErro) ? 'error' : ''; ?>">
                            <label class="control-label">Email</label>
                            <div class="controls">
                                <input name="email" class="form-control" size="40" type="text" placeholder="Email"
                                    value="<?php echo !empty($email) ? $email : ''; ?>">
                                <?php if (!empty($emailErro)): ?>
                                    <span class="text-danger"><?php echo $emailErro; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="control-group <?php echo !empty($telefoneErro) ? 'error' : ''; ?>">
                            <label class="control-label">Telefone</label>
                            <div class="controls">
                                <input name="telefone" class="form-control" size="30" lenth="11" type="number" placeholder="(xx) xxxx-xxxx"
                                value="<?php echo !empty($telefone) ? $telefone : ''; ?>">
                                <?php if (!empty($telefoneErro)): ?>
                                    <span class="text-danger"><?php echo $telefoneErro; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="control-group <?php echo !empty($senhaErro) ? 'error' : ''; ?>">
                            <label class="control-label">Senha</label>
                            <div class="controls">
                                <input name="senha" class="form-control" size="20" type="password" placeholder="Senha"
                                    value="<?php echo !empty($senha) ? '': ''; ?>">
                                <?php if (!empty($senhaErro)): ?>
                                    <span class="text-danger"><?php echo $senhaErro; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="control-group <?php echo !empty($data_nascimentoErro) ? 'error' : ''; ?>">
                            <label class="control-label">Data de Nascimento</label>
                            <div class="controls">
                                <input name="data_nascimento" class="form-control" size="10" type="date" placeholder="Data de Nascimento"
                                    value="<?php echo !empty($data_nascimento) ? $data_nascimento : ''; ?>">
                                <?php if (!empty($data_nascaimentoErro)): ?>
                                    <span class="text-danger"><?php echo $data_nascimentoErro; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="control-group <?php echo !empty($sexoErro) ? 'error' : ''; ?>">
                            <label class="control-label">Sexo</label>
                            <div class="controls">
                                <div class="form-check">
                                    <p class="form-check-label">
                                        <input class="form-check-input" type="radio" name="sexo" id="sexo"
                                            value="M" <?php echo ($sexo == "M") ? "checked" : null; ?>/> Masculino
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sexo" id="sexo"
                                        value="F" <?php echo ($sexo == "F") ? "checked" : null; ?>/> Feminino
                                </div>
                                </p>
                                <?php if (!empty($sexoErro)): ?>
                                    <span class="text-danger"><?php echo $sexoErro; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="control-group">
                        <label class="control-label">Sua foto atual: </label>
                            <?php echo '<img width="100" heigth="100" alt="'.$nome.'" src="data:image/jpeg;base64,'.base64_encode($imagem).'"/>'; ?>
                        </div>  
                        <br>

                        <div class="control-group">
                            <label class="control-label" for="imagem">Adiconar foto:</label>
                                <div class="controls">
                                    <input type="file" id="image" name="imagem" accept="image/png, image/jpeg">
                                </div>
                        </div>

                        <div class="control-group">
                            <div class="controls">
                             
                                <br>
                                <label class="control-label">Você oferece algum tipo de carona?</label>
                                <br>
                                <div class="controls">
                                    <select class="control-label" id="carona" name="carona">
                                        <?php
                                            $mysql = MySQL::conectar();
                                            $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                            $sql = "SELECT
                                            `carona`.`idPessoa`,
                                            `carona`.`tipo`,
                                            `pessoa`.`id`
                                            FROM
                                            `carona`,
                                            `pessoa`
                                            WHERE
                                            `pessoa`.`id` = `carona`.`idPessoa` AND `pessoa`.`id` = ?";
                                            $q = $mysql->prepare($sql);
                                            $q->execute(array($id));
                                            $info = $q->fetch(PDO::FETCH_OBJ);
                                            $caronas = array('NENHUM','CARRO','MOTO','(PÉ) COMPANHIA','ÔNIBUS','VAN','BICICLETA');
                                          
                                        for ($i = 0; $i < 7; $i++) {
                                            echo '<option value="'.$caronas[$i].'" '.(!strcmp($caronas[$i], $info->tipo) ? "selected" : "").'>'.$caronas[$i].'</option>';
                                        }
                                        MySQL::desconectar();
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br/>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">Atualizar</button>
                            <a href="painel.php" class="btn btn-warning">Voltar<a>
                        </div>
                    </form>
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
