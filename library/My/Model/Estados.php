<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Estados extends My_Db_Table
{
    protected $_schema 	= 'petlocator';
	protected $_name 	= 'loc_estados';
	protected $_primary = 'ID';
		
	public function getCbo($codCountry){
		try{
			$result= Array();
			$this->query("SET NAMES utf8",false); 		
	    	$sql ="SELECT code AS ID, name AS NAME 
	    			FROM $this->_name 
	    			WHERE country = '$codCountry' 
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