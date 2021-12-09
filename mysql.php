<?php
session_start();
 
ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);




class MySQL
{
    private static $dbNome = 'pessoa';
    private static $dbHost = 'localhost';
    private static $dbUsuario = 'root';
    private static $dbSenha = '';
    
    private static $cont = null;
    
    public function __construct()
    {
        die('A função Init nao é permitido!');
    }
    
    public static function conectar()
    {
        if (null == self::$cont) {
            try {
                self::$cont =  new PDO("mysql:host=".self::$dbHost.";"."dbname=".self::$dbNome, self::$dbUsuario, self::$dbSenha);
            } catch (PDOException $exception) {
                die($exception->getMessage());
            }
        }
        return self::$cont;
    }
    
    public static function debugArray()
    {
        echo '<pre>';
        print_r($_POST);
        echo '</pre>';
        echo '<pre>';
        print_r($_FILES);
        echo '</pre>';
    }

    public static function desconectar()
    {
        self::$cont = null;
    }

    public static function getMatricula($id = 0)
    {
        return date('Y').'555'.str_pad($id, 4, "0", STR_PAD_LEFT);
    }
}


//echo MySQL::debugArray();