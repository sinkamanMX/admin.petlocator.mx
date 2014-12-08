<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Banners extends My_Db_Table
{
    protected $_schema 	= 'petlocator';
	protected $_name 	= 'banners';
	protected $_primary = 'id_banner';
		
	public function getDataTable($codeCountry){
		try{
			$result= Array();
			$this->query("SET NAMES utf8",false); 		
	    	$sql ="SELECT id_banner AS ID,$this->_name.*,IF(estatus=1,'Activo','inactivo') AS ESTATUS
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
	
    public function getData($idObject){
		$result= Array();
		$this->query("SET NAMES utf8",false); 
    	$sql ="SELECT  *
				FROM $this->_name 
				WHERE $this->_primary = ".$idObject." LIMIT 1";	    	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}	
        
		return $result;	    	
    }	
	
    public function insertRow($data){
        $result     = Array();
        $result['status']  = false;
        $nameImage  = ($data['nameImagen']!="") ? ",imagen			='".$data['nameImagen']."'": "";             
        
        $sql="INSERT $this->_name
        		SET cod_pais		= '".$data['codeCountry']."',
					nombre			= '".$data['inputName']."',
					descripcion		= '".$data['inputDesc']."',
					url				= '".$data['inputURL']."',	
					fecha_inicio	= '".$data['inputFechaIn']."',
					fecha_fin		= '".$data['inputFechaFin']."',
					estatus			= ".$data['inputEstatus']." ".$nameImage;
        try{            
    		$query   = $this->query($sql,false);
    		$sql_id ="SELECT LAST_INSERT_ID() AS ID_LAST;";
			$query_id   = $this->query($sql_id);
			if(count($query_id)>0){
				$result['id']	   = $query_id[0]['ID_LAST'];
				$result['status']  = true;					
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;	
    }   

    public function updateRow($data){
       	$result     = Array();
        $result['status']  = false;

        $nameImage  = ($data['nameImagen']!="") ? ",IMAGEN			='".$data['nameImagen']."'": "";

        $sql="UPDATE $this->_name	
        		SET nombre			= '".$data['inputName']."',
					descripcion		= '".$data['inputDesc']."',
					url				= '".$data['inputURL']."',	
					fecha_inicio	= '".$data['inputFechaIn']."',
					fecha_fin		= '".$data['inputFechaFin']."',
					estatus			= ".$data['inputEstatus']." ".
        			$nameImage."
			  WHERE $this->_primary = ".$data['catId']." LIMIT 1";
        try{            
    		$query   = $this->query($sql,false);
			if($query){
				$result['status']  = true;								
			}	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;
    }         
	
}