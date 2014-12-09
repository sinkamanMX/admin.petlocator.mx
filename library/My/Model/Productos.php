<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Productos extends My_Db_Table
{
    protected $_schema 	= 'petlocator';
	protected $_name 	= 'producto';
	protected $_primary = 'prod_id';
		
	public function getCbo($codCountry){
		try{
			$result= Array();
			$this->query("SET NAMES utf8",false); 		
	    	$sql ="SELECT $this->_primary AS ID, prod_titulo AS NAME 
	    			FROM $this->_name 
	    			WHERE prod_estatus = 1 
	    			  AND cod_pais     = '$codCountry'
	    			ORDER BY name ASC";
			$query   = $this->query($sql);
			if(count($query)>0){		  
				$result = $query;			
			}
			return $result;
			
		}catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }       		
	}	
}	