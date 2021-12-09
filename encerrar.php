<?php
include 'mysql.php';
if (!$_SESSION['logado']) {
    header("Location: logout.php");
    exit();
}


if (isset($_GET['id'])) {
    $mysql = MySQL::conectar();
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "START TRANSACTION;
        UPDATE carona  set tipo = 'NENHUM' WHERE idPessoa = ?;
        COMMIT;";
    $q = $mysql->prepare($sql);
    $q->execute(array($_GET['id']));

    MySQL::desconectar();

    header("Location:painel.php");
}
