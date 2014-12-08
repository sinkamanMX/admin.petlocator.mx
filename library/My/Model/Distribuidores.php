<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Distribuidores extends My_Db_Table
{
    protected $_schema 	= 'petlocator';
	protected $_name 	= 'pet_distribuidores';
	protected $_primary = 'id_distribuidor';
		
	public function getCbo($codeCountry){
		try{
			$result= Array();
			$this->query("SET NAMES utf8",false); 		
	    	$sql ="SELECT $this->_primary AS ID, nombre AS NAME 
	    			FROM $this->_name 
	    			WHERE cod_pais = '$codeCountry' 
	    			ORDER BY name ASC";
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
	
	public function getDataTable($codeCountry){
		try{
			$result= Array();
			$this->query("SET NAMES utf8",false); 		
	    	$sql ="SELECT D.id_distribuidor AS ID, D.nombre, CONCAT(D.cod_pais,', ',E.name) AS LUGAR,D.contacto, 
					IF(D.status=1,'Activo','inactivo') AS ESTATUS
					FROM pet_distribuidores D
					INNER JOIN loc_estados E ON  D.cod_pais = E.country AND D.id_estado = E.code
					AND D.cod_pais = '$codeCountry'";
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