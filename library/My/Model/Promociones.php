<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Promociones extends My_Db_Table
{
    protected $_schema 	= 'petlocator';
	protected $_name 	= 'promociones';
	protected $_primary = 'id_promocion';
		
	public function getDataTable($codeCountry){
		try{
			$result= Array();
			$this->query("SET NAMES utf8",false); 		
	    	$sql ="SELECT $this->_name.*,IF(estatus=1,'Activo','inactivo') AS ESTATUS
				FROM $this->_name				
				WHERE cod_pais = '$codeCountry'
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