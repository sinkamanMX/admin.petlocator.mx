<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Paises extends My_Db_Table
{
    protected $_schema 	= 'petlocator';
	protected $_name 	= 'loc_paises';
	protected $_primary = 'code';
		
	public function getCbo(){
		try{
			$result= Array();
			$this->query("SET NAMES utf8",false); 		
	    	$sql ="SELECT $this->_primary AS ID, name AS NAME 
	    			FROM $this->_name 
	    			WHERE status = 1 
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
	
    public function getData($idObject){
		$result= Array();
		$this->query("SET NAMES utf8",false); 
    	$sql ="SELECT  *
				FROM $this->_name 
				WHERE $this->_primary = '$idObject' LIMIT 1";	    	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}	
        
		return $result;	    	
    }

    public function getCboStat($idStatus,$includeId=""){
 		try{
 			$filter= ($includeId!="") ? ' OR '.$this->_primary.'  = "'.$includeId.'"' : '' ;
			$result= Array();
			$this->query("SET NAMES utf8",false); 		
	    	$sql ="SELECT $this->_primary AS ID, name AS NAME 
	    			FROM $this->_name 
	    			WHERE status = $idStatus 
	    			$filter
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
	
	public function getPaises($idStatus){
		try{
			$result= Array();
			$this->query("SET NAMES utf8",false); 		
	    	$sql ="SELECT *
	    			FROM $this->_name 
	    			WHERE status = $idStatus 
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
	
	public function getDistribuidores($codCountry){
		try{
			$result= Array();
			$this->query("SET NAMES utf8",false); 		
	    	$sql ="SELECT COUNT(id_distribuidor) AS TOTAL_DISTRIBUIDOR
					FROM pet_distribuidores 
					WHERE cod_pais = '$codCountry'";
			$query   = $this->query($sql);
			if(count($query)>0){		  
				$result = $query[0]['TOTAL_DISTRIBUIDOR'];			
			}
			return $result;
			
		}catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }  		
	}

	public function getClientes($codCountry){
		try{
			$result= Array();
			$this->query("SET NAMES utf8",false); 		
	    	$sql ="SELECT COUNT(clt_id)  AS TOTAL_CLIENTES
					FROM usuario_cliente
					WHERE clt_pais = '$codCountry'";
			$query   = $this->query($sql);
			if(count($query)>0){		  
				$result = $query[0]['TOTAL_CLIENTES'];			
			}
			return $result;
			
		}catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }  		
	}

	public function getTrackers($codCountry){
		try{
			$result= Array();
			$this->query("SET NAMES utf8",false); 		
	    	$sql ="SELECT COUNT(gpsTrackerID) AS TOTAL_TRACKERS
					FROM GPSTracker
					WHERE cod_pais = '$codCountry'";
			$query   = $this->query($sql);
			if(count($query)>0){		  
				$result = $query[0]['TOTAL_TRACKERS'];			
			}
			return $result;
			
		}catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }  		
	}	
	
	public function getSales($codCountry){
		try{
			$result= Array();
			$this->query("SET NAMES utf8",false); 		
	    	$sql ="SELECT COUNT(pdd_id) AS TOTAL_PEDIDOS, ROUND(SUM(pdd_total),2) AS TOTAL_VENDIDO
					FROM pedido
					WHERE cod_pais = '$codCountry'";
			$query   = $this->query($sql);
			if(count($query)>0){		  
				$result = $query[0];			
			}
			return $result;
			
		}catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }  		
	}		
	
	public function getResumen(){
		try{
			$result= Array();
			$aPaises = $this->getPaises(1); 
			foreach($aPaises as $key => $items){
				$aSales 			= $this->getSales($items['code']);				
				$items['name']    	= trim($items['name']);
				$items['NoDist']    = $this->getDistribuidores($items['code']);
				$items['NoPedido']  = $aSales['TOTAL_PEDIDOS'];
				$items['NoSales']   = $aSales['TOTAL_VENDIDO'];
				$items['NoClients'] = $this->getClientes($items['code']);
				$items['NoTrackers']= $this->getTrackers($items['code']);
								
				$result[] 		= $items;
			}

			return $result;			
		}catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }  				
	}
	
    public function updateRow($data){
       $result     = Array();
        $result['status']  = false;

        $sql="UPDATE $this->_name			 
				SET latitude	= ".(($data['inputLatitud']!="")  ? $data['inputLatitud']: 0.000000).",
					longitude	= ".(($data['inputLongitud']!="") ? $data['inputLongitud']: 0.000000).",
					status		= ".$data['inputEstatus'].",
					divisa		='".$data['inputDivisa']."',
					texto_divisa='".$data['inputTextDivisa']."',
					diferencia_horario= ".$data['inputDiferencia']."
				WHERE $this->_primary = '".$data['catId']."' LIMIT 1";

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