<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Clientes extends My_Db_Table
{
    protected $_schema 	= 'petlocator';
	protected $_name 	= 'usuario_cliente';
	protected $_primary = 'clt_id';
		
	public function getDataTable($codeCountry){
		try{
			$result= Array();
			$this->query("SET NAMES utf8",false); 		
	    	$sql ="SELECT clt_id AS ID, CONCAT(clt_nombre,' ',clt_apellidos) AS NOMBRE,
				      CONCAT(clt_pais,',',clt_estado ) AS PAIS,
				      clt_email,clt_fecha_acceso,clt_sap_cardcode,
				      pet_distribuidores.nombre AS NDISTRIBUIDOR 
				FROM $this->_name
				LEFT JOIN pet_distribuidores ON usuario_cliente.id_distribuidor = pet_distribuidores.id_distribuidor				
				WHERE clt_pais = '$codeCountry'
				ORDER BY NOMBRE ASC";
			$query   = $this->query($sql);
			if(count($query)>0){		  
				$result = $query;			
			}
		}catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
        
		return $result;			
	}
	
	
}