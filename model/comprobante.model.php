<?php
require 'lib/anexgrid.php';

class ComprobanteModel
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

	public function Listar()
	{
		try
		{
            /* Instanciamos AnexGRID */
            $anexGrid = new AnexGrid();
            
            /* Contamos los registros*/
            $total = $this->pdo->query("
                SELECT COUNT(*) Total
                FROM comprobante
            ")->fetchObject()->Total;

            /* Nuestra consulta dinámica */
            $registros = $this->pdo->query("
                SELECT * FROM comprobante
                ORDER BY $anexGrid->columna $anexGrid->columna_orden
                LIMIT $anexGrid->pagina,$anexGrid->limite")->fetchAll(PDO::FETCH_ASSOC
             );

            foreach($registros as $k => $r)
            {
                /* Traemos los clientes que tiene asignado cada comprobante */
                $cliente = $this->pdo->query("SELECT * FROM cliente c WHERE c.id = " . $r['Cliente_id'])
                                ->fetch(PDO::FETCH_ASSOC);

                $registros[$k]['Cliente'] = $cliente;
                
                /* Traemos el detalle */
                $registros[$k]['Detalle'][] = $this->pdo->query("SELECT * FROM comprobante_detalle cd WHERE cd.Comprobante_id = " . $r['id'])
                                                   ->fetch(PDO::FETCH_ASSOC);
                
                foreach($registros[$k]['Detalle'] as $k1 => $d)
                {
                    $registros[$k]['Detalle'][$k1]['Producto'] = $this->pdo->query("SELECT * FROM producto p WHERE p.id = " . $d['Producto_id'])
                                                                      ->fetch(PDO::FETCH_ASSOC);
                }
            }
            
            return $anexGrid->responde($registros, $total);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function Obtener($id)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT * FROM comprobante WHERE id = ?");
			$stm->execute(array($id));
            
			$c = $stm->fetch(PDO::FETCH_OBJ);
            
            /* El cliente asignado */
            $c->{'Cliente'} = $this->pdo->query("SELECT * FROM cliente c WHERE c.id = " . $c->Cliente_id)
                                        ->fetch(PDO::FETCH_OBJ);

            /* Traemos el detalle */
            $c->{'Detalle'} = $this->pdo->query("SELECT * FROM comprobante_detalle cd WHERE cd.Comprobante_id = " . $c->id)
                                        ->fetchAll(PDO::FETCH_OBJ);

            foreach($c->Detalle as $k => $d)
            {
                $c->Detalle[$k]->{'Producto'} = $this->pdo->query("SELECT * FROM producto p WHERE p.id = " . $d->Producto_id)
                                                          ->fetch(PDO::FETCH_OBJ);
            }
            
            return $c;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function Eliminar($id)
	{
		try 
		{
			$stm = $this->pdo->prepare("DELETE FROM comprobante WHERE id = ?");
			$stm->execute(array($id));
		}
        catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function Registrar($comprobante)
	{
		try 
		{
            /* Registramos el comprobante */
            $sql = "INSERT INTO comprobante(Cliente_id, IGV, SubTotal, Total) VALUES (?, ?, ?, ?);";
            $this->pdo->prepare($sql)
                      ->execute(
                        array(
                            $comprobante['cliente_id'],
                            $comprobante['igv'],
                            $comprobante['subtotal'],
                            $comprobante['total']
                        ));

            /* El ultimo ID que se ha generado */
            $comprobante_id = $this->pdo->lastInsertId();
            
            /* Recorremos el detalle para insertar */
            foreach($comprobante['items'] as $d)
            {
                $sql = "INSERT INTO comprobante_detalle (Comprobante_id,Producto_id,Cantidad,PrecioUnitario,Total) 
                        VALUES (?, ?, ?, ?, ?)";
                
                $this->pdo->prepare($sql)
                          ->execute(
                            array(
                                $comprobante_id,
                                $d['producto_id'],
                                $d['cantidad'],
                                $d['precio'],
                                $d['total']
                            ));
            }
            
            return true;
		}
        catch (Exception $e) 
		{
			return false;
		}
	}
}