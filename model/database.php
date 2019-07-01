<?php
class Database
{
    public static function Conectar()
    {
		$pdo = new \PDO('mysql:host=localhost;dbname=factura', 'root', 'mister');
        //$pdo = new PDO('mysql:host=localhost;dbname=factura;charset=utf8', 'root', 'mister');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
        return $pdo;
    }
}