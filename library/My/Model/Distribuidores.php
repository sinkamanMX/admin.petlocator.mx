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
        
        $sql="INSERT $this->_name
        		SET nombre  		= '".$data['inputName'] ."',   
				    calle			= '".$data['inputCalle']."',
				    no_int			= '".$data['InputNoint']."',
				    no_ext			= '".$data['InputNoExt']."',
				    colonia			= '".$data['inputColonia']."',
				    cod_pais		= '".$data['inputCodePais']."',
				    id_estado		= '".$data['inputEstado']."', 
				    municipio		= '".$data['inputMuni']."', 
				    cp    			= '".$data['inputCp']."', 
				    web_url			= '".$data['inputWeb']."', 
				    contacto		= '".$data['inputNamContacto']."', 	
				    contacto_email	= '".$data['inputEmContacto']."', 
				    contacto_tel	= '".$data['inputTelContacto']."', 
				    contacto_tel2	= '".$data['inputTelContacto2']."', 
				    horario			= '".$data['inputHorario']."', 
				    dias			= '".$data['inputDias']."', 
				    status			=  ".$data['inputEstatus'].", 
				    latitud			=  ".$data['inputLatitud'].", 
				    longitud 		=  ".$data['inputLongitud'];
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
        $sql="UPDATE $this->_name	
        		SET nombre  		= '".$data['inputName'] ."',   
				    calle			= '".$data['inputCalle']."',
				    no_int			= '".$data['InputNoint']."',
				    no_ext			= '".$data['InputNoExt']."',
				    colonia			= '".$data['inputColonia']."',
				    cod_pais		= '".$data['inputCodePais']."',
				    id_estado		= '".$data['inputEstado']."', 
				    municipio		= '".$data['inputMuni']."', 
				    cp    			= '".$data['inputCp']."', 
				    web_url			= '".$data['inputWeb']."', 
				    contacto		= '".$data['inputNamContacto']."', 	
				    contacto_email	= '".$data['inputEmContacto']."', 
				    contacto_tel	= '".$data['inputTelContacto']."', 
				    contacto_tel2	= '".$data['inputTelContacto2']."', 
				    horario			= '".$data['inputHorario']."', 
				    dias			= '".$data['inputDias']."', 
				    status			=  ".$data['inputEstatus'].", 
				    latitud			=  ".$data['inputLatitud'].", 
				    longitud 		=  ".$data['inputLongitud']."
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