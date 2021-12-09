<?php
require 'mysql.php';
//Acompanha os erros de validação



// Processar so quando tenha uma chamada post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeErro = null;
    $telefoneErro = null;
    $senhaErro = null;
    $senha2Erro = null;
    $data_nascimentoErro = null;
    $emailErro = null;
    $sexoErro = null;
    $mysql = MySQL::conectar();
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $carona = $_POST['carona'];
    $erroImg = '';

    if (!empty($_POST) && !empty($_FILES["imagem"]["name"])) {
        
        $validacao = true;
        $novoUsuario = false;
        if (!empty($_POST['nome'])) {
            $nome = $_POST['nome'];
        } else {
            $nomeErro = 'Por favor digite o seu nome!';
            $validacao = false;
        }

        if (!empty($_POST['telefone'])) {
            $telefone = $_POST['telefone'];
        } else {
            $telefoneErro = 'Por favor digite o número do telefone!';
            $validacao = False;
        }

        if (!empty($_FILES["imagem"]["name"])) {
             
        } else {
            $erroImg .= 'Selecione uma imagem\n';
            $validacao = false;
        }

        if (!empty($_POST['confirmar_senha'])) {
            $senha = $_POST['confirmar_senha'];
        } else {
            $senha2Erro = 'Por favor digite uma senha(2) válida!';
            $validacao = false;
        }

        if (!empty($_POST['senha'])) {
            $senha = $_POST['senha'];
        } else {
            $senhaErro = 'Por favor digite uma senha válida!';
            $validacao = false;
        }

        if (!empty($_POST['data_nascimento'])) {
            $data_nascimento = $_POST['data_nascimento'];
        } else {
            $nascimentoErro = 'Por favor digite o número do nascimento!';
            $validacao = false;
        }

        if (!empty($_POST['email'])) {
            $email = $_POST['email'];
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $emailErro = 'Por favor digite um endereço de email válido!';
                $validacao = false;
            }
            $sql = "SELECT * FROM `usuario` WHERE `email` = ?";
    
            $q = $mysql->prepare($sql);
            $q->execute(array($email));
            $data = $q->fetch(PDO::FETCH_ASSOC);
            if (!empty($data)) {
                $emailErro = 'Email já existe!';
                $validacao = false;
            }
        } else {
            $emailErro = 'Por favor digite um endereço de email!';
            $validacao = false;
        }

        if (!empty($_POST['sexo'])) {
            $sexo = $_POST['sexo'];
        } else {
            $sexoErro = 'Por favor selecione um campo!';
            $validacao = false;
        }
    }

    //Inserindo no MySQL:
    if ($validacao) {
        try {
            //inserir pessoa
            $sql = "
            START TRANSACTION;
            INSERT INTO pessoa (nome, sexo, data_nascimento,imagem,telefone) VALUES(?,?,?,?,?);
            SELECT LAST_INSERT_ID() INTO @idAs;
            INSERT INTO usuario (email, senha,idPessoa) VALUES(?,?,@idAs);
            INSERT INTO carona (tipo, idPessoa) VALUES(?,@idAs);
            COMMIT;
            ";
            $q = $mysql->prepare($sql);

            $avatar = file_get_contents($_FILES["imagem"]["tmp_name"]);
            $q->execute(array($nome, $sexo, $data_nascimento,$avatar,$telefone,$email,md5($senha),$carona));
        } catch (PDOException $Exception) {
            echo '<script type="text/JavaScript">
            alert("'.$Exception->getMessage().'");
            </script>';
            return;
        }
        //
        MySQL::desconectar();
        echo '<script type="text/JavaScript">
        redirectTime = "1000";
        redirectURL = "index.php";
        function timedRedirect() {
            setTimeout("location.href = redirectURL;",redirectTime);
            alert("Cadastro realizado com sucesso, será redirecionado para tela de login");
        }
        timedRedirect();
        </script>';
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrando</title>
    <link rel="shortcut icon" href="https://www.uepa.br/sites/default/files/brasaouepa1.png" type="image/x-icon">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    
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
        textarea{
            text-align: justify;
            resize: none;
            overflow:auto;
            font-family: "Times New Roman", Times, serif;
        }
    </style>

    <!--Verificando o meu formulário-->
    <script language="javascript">
      function valida_dados (nomeform) {
        if (nomeform.senha.value.length<2 || nomeform.senha.value.length>20)//verifica o comprimento da string
          {
              alert ("A senha deve conter entre 2 a 20 caracteres.");
              return false;
          }
        if (nomeform.senha.value != nomeform.confirmar_senha.value) //verifica se as senhas são iguais
          {
            alert ("Senhas não coincidem!");
            return false;
          }
    return true
        }
    </script>

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
            <br>

            <div clas="span10 offset1" style="margin: auto;">
                <div class="card">
                    <div class="card-header">
                        <h3 class="well"> Registro </h3>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" action="create.php" method="post" onSubmit="return valida_dados(this)" enctype="multipart/form-data">

                        <div class="control-group  <?php echo !empty($nomeErro) ? 'error ' : ''; ?>">
                            <label class="control-label">Nome:</label>
                            <div class="controls">
                                <input size="50" class="form-control" name="nome" type="text" placeholder="Nome"
                                    value="<?php echo !empty($nome) ? $nome : ''; ?>">
                                <?php if (!empty($nomeErro)): ?>
                                    <span class="text-danger"><?php echo $nomeErro; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="control-group <?php !empty($emailErro) ? '$emailErro ' : ''; ?>">
                            <label class="control-label">Email:</label>
                            <div class="controls">
                                <input size="40" class="form-control" name="email" type="text" placeholder="Email"
                                    value="<?php echo !empty($email) ? $email : ''; ?>">
                                <?php if (!empty($emailErro)): ?>
                                    <span class="text-danger"><?php echo $emailErro; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="control-group <?php echo !empty($telefoneErro) ? 'error ' : ''; ?>">
                        <label class="control-label">Telefone</label>
                        <div class="controls">
                            <input size="14" class="form-control" name="telefone" type="number" placeholder="(xx) xxxx-xxxx"
                                   value="<?php echo !empty($telefone) ? $telefone : ''; ?>">
                            <?php if (!empty($telefoneErro)): ?>
                                <span class="text-danger"><?php echo $telefoneErro; ?></span>
                            <?php endif; ?>
                        </div>
                        </div>

                        <div class="control-group <?php echo !empty($senhaErro) ? 'error ' : ''; ?>">
                            <label class="control-label">Senha:</label>
                            <div class="controls">
                                <input size="20" class="form-control" name="senha" type="password" placeholder="Senha"
                                    value="<?php echo !empty($senha) ? $senha : ''; ?>">
                                <?php if (!empty($senhaErro)): ?>
                                    <span class="text-danger"><?php echo $senhaErro; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="control-group <?php echo !empty($senha2Erro) ? 'error ' : ''; ?>">
                            <label class="control-label">Confirme sua senha:</label>
                            <div class="controls">
                                <input size="20" class="form-control" name="confirmar_senha" type="password" placeholder="Senha"
                                    value="<?php echo !empty($senha2) ? $senha2 : ''; ?>">
                                <?php if (!empty($senha2Erro)): ?>
                                    <span class="text-danger"><?php echo $senha2Erro; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="control-group <?php echo !empty($data_nascimentoErro) ? 'error ' : ''; ?>">
                            <label class="control-label">Data de Nascimento:</label>
                            <div class="controls">
                                <input size="10" class="form-control" name="data_nascimento" type="date" placeholder="data_nascimento" 
                                    value="<?php echo !empty($data_nascimento) ? $data_nascimento : ''; ?>">
                                <?php if (!empty($data_nascimentoErro)): ?>
                                    <span class="text-danger"><?php echo $data_nascimentoErro; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="control-group <?php !empty($sexoErro) ? 'echo($sexoErro)' : ''; ?>">
                            <div class="controls">
                                <label class="control-label">Sexo:</label>
                                <div class="form-check">
                                    <p class="form-check-label">
                                        <input class="form-check-input" type="radio" name="sexo" id="sexo"
                                            value="M" <?php isset($_POST["sexo"]) && $_POST["sexo"] == "M" ? "checked" : null; ?>/>
                                        Masculino</p>
                                </div>
                                <div class="form-check">
                                    <p class="form-check-label">
                                        <input class="form-check-input" id="sexo" name="sexo" type="radio"
                                            value="F" <?php isset($_POST["sexo"]) && $_POST["sexo"] == "F" ? "checked" : null; ?>/>
                                        Feminino</p>
                                </div>
                                <?php if (!empty($sexoErro)): ?>
                                    <span class="help-inline text-danger"><?php echo $sexoErro; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <br>

                        <div class="control-group">
                            <label class="control-label" for="imagem">Adiconar foto:</label>
                            <div class="controls">
                                <input type="file" id="image" name="imagem" accept="image/png, image/jpeg" required>
                            </div>
                        </div>
                        <br>

                        <div class="control-group">
                            <div class="controls">
                                <label class="control-label">Você oferece algum tipo de carona?</label>
                                <br>
                                <div class="controls">
                                    <select class="control-label" id="carona" name="carona" required>
                                        <option value="NENHUM">Nenhum</option>
                                        <option value="CARRO">Carro</option>
                                        <option value="MOTO">Moto</option>
                                        <option value="(PÉ) COMPANHIA">(Pé) Companhia</option>
                                        <option value="ÔNIBUS">Ônibus</option>
                                        <option value="VAN">Van</option>
                                        <option value="BICICLETA">Bicileta</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="control-group">
                            <div class="controls">
                                <label class="control-label">Termos e Condições:</label>
                                <br>
                                <div class="controls">
    <textarea id="w3review" name="w3review" rows="10" cols="60" readonly>
Política de Privacidade:

    OS TERMOS E CONDIÇÕES SÃO DE EXTREMA IMPORTÂNCIA, QUE SEJAM LIDOS COM ATENÇÃO, POIS NELES LISTAMOS PORQUE ESTAMOS RECOLHENDO SUAS INFORMAÇÕES E PONTUAREMOS COMO VAMOS COLHER E TRATAR SEUS DADOS. 

    Entenda que o intuito de recolhermos suas informações é aprimorar o nosso site CARONA e melhorar a sua experiência como torcedor de acordo com aquilo que lhe interessa. Para se adaptar aos usuários podemos mudar nossa Política de Privacidade, porém você será informado de tais mudanças no e-mail cadastrado.

    OS DADOS QUE SERÃO RECOLHIDOS:

    Dados Cadastrais: Quando se inscreve em nosso site, para realizar tal situação pedimos nome, sobrenome, cpf, email, sexo, data de nascimento.
Dados públicos: dados pessoais que o acesso é público, ou que foram tornados públicos pelo seu titular, incluindo dados sensíveis (definidos pela LGPD (Lei Geral de Proteção de Dados), como aqueles que revelem orientação religiosa, política ou sexual, convicção filosófica, participação em movimentos políticos ou sociais, informações de saúde ou genéticas).

    COMO FAREMOS O RECOLHIMENTO DE INFORMAÇÕES:

    Através da criação de uma conta no CARAONA: Quando se inscreve em nosso site pedimos nome completo, matricula, email, sexo, data de nascimento, foto.

    QUAIS OS OBJETIVOS EM UTILIZAR ESSES DADOS:

    Uma função importante que seus dados tem é de promover segurança a você e para a gente do CARONA, coletamos alguns dados específicos para termos certeza que esse usuário não comprometa nossa segurança e caso aconteça algo poderemos rastrear o indivíduo que lesou a sua ou a nossa segurança.
Inovações em nosso site: Com os seus dados podemos observar o que os usuários que visitam nosso site têm em comum e podemos desenvolver aplicações e serviços especialmente para você, além de melhorias constantes no site.

    OS DADOS SERÃO DELETADOS:

    Quando o usuário recusar alguma atualização dos termos os seus dados serão deletados da plataforma definitivamente.
Caso o usuário exclua sua conta no CARONA automaticamente todos os seus dados e registros serão apagados da plataforma perpetuamente.

    Caso ocorra o fim do site CARONA os dados e registros de todos os usuários sejam cadastrados ou não, serão excluídos permanentemente.
 </textarea>
                                </div>
                                <input type = "checkbox" id = "codificação" name = "aceito" value = "codificação" checked required>
                                <label for = "coding"> Eu aceito os termos.</label>
                            </div>
                        </div>

                        <div class="form-actions">
                            <br/>
                            <button type="submit" class="btn btn-success">Registrar-se</button>
                            <a href="index.php" class="btn btn-success">Voltar<a>
                        </div>
                        </form>
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

