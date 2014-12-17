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
	
	public function getTableDist($idDistribuidor){
		try{
			$result= Array();
			$this->query("SET NAMES utf8",false); 		
	    	$sql ="SELECT C.clt_id AS ID, 	CONCAT(C.clt_nombre,' ',C.clt_apellidos) AS NOMBRE,			      
					      CONCAT(C.clt_pais,',',C.clt_estado ) AS PAIS,
					      C.clt_email,C.clt_accesos,C.clt_sap_cardcode,
					      COUNT(M.masc_id) AS TOTAL_PET, C.clt_fecha_acceso
					FROM usuario_cliente C
					LEFT JOIN mascota M ON C.clt_id = M.clt_id
					WHERE C.id_distribuidor = $idDistribuidor
					GROUP BY C.clt_id
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

    public function getData($datauser){
      	$this->query("SET NAMES utf8",false); 
        
		$result= Array();
    	$sql ="SELECT * 
                FROM usuario_cliente  
                WHERE clt_id = $datauser";			         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query[0];			
		}	
        
		return $result;	        
    }  	
    
	public function userExist($suerName){
		$result= Array();
    	$sql ="SELECT COUNT(*) AS EXISTE
                FROM usuario_cliente 
                WHERE clt_email = '".$suerName."' LIMIT 1";			         	
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query[0]['EXISTE'];			
		}	
        
		return $result;			
	}    
	
    public function insertRow($data){
        $result     = Array();
        $result['status']  = false;
        
		$sql="INSERT INTO usuario_cliente 
			   SET id_distribuidor 	=  ".$data['inputDistribuidor']." , 
			   	   clt_pais			= '".$data['inputPais']."',
			   	   clt_nombre 	 	= '".$data['inputName']."',
				   clt_apellidos	= '".$data['inputApps']."',
				   clt_telefono		= '".$data['inputTel']."' ,
				   clt_estado 		=  ".$data['inputEstado']." ,			   
				   clt_email 		= '".$data['inputUser']."',
				   clt_clave 		= '".$data['inputPass']."'";
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
}