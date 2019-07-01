<?php
class ClienteModel
{
	private $pdo;

	public function __CONSTRUCT()
	{
		try
		{
            $this->pdo = Database::Conectar();
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function Buscar($criterio)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT * FROM cliente WHERE Nombre LIKE '%$criterio%' ORDER BY Nombre LIMIT 8");
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
}