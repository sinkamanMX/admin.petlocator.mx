<?php
/**
 * Archivo de definici—n de perfiles
 * 
 * @author epena
 * @package library.My.Models
 */
class My_Model_Trackers extends My_Db_Table
{
    protected $_schema 	= 'petlocator';
	protected $_name 	= 'GPSTracker';
	protected $_primary = 'gpsTrackerID';

	public function getDataTableAdmon($codCountry,$profile=-1,$codDist=-1){
		$result= Array();
		$this->query("SET NAMES utf8",false);
		$filter  = "";
		
		if($profile==2){
			$filter = "WHERE T.cod_pais  = '$codCountry' ";
		}elseif($profile==3){
			$filter = "WHERE T.cod_pais  = '$codCountry' AND  T.id_distribuidor = $codDist";
		}
		 		
    	$sql ="SELECT T.gpsTrackerID, T.cod_pais, T.gpsTrackerIMEI, T.carrier, 
    			IF(T.gpsTrackerStatus IS NULL,'Inactivo','Activo') AS ESTATUS , P.bandera, 
    			IF(M.masc_id IS NULL,'Sin Asignar','Asignado') AS ASIGNADO,
    			IF(M.masc_id IS NULL,'Sin Mascota',M.masc_nombre) AS MASCOTA , 
    			IF(M.masc_estatus = 0,'Inactivo','Activo') AS ESTATUS_MASCOTA,
    			(L.dateTime) AS U_REPORTE,
    			D.nombre as N_DISTRIBUIDOR
				FROM GPSTracker T
				 LEFT JOIN pet_distribuidores D on T.id_distribuidor = D.id_distribuidor
				 LEFT JOIN loc_paises P ON T.cod_pais = P.code
				 LEFT JOIN mascota    M ON T.gpsTrackerID = M.masc_gpsTrackerID
				 LEFT JOIN LastPosition L ON L.petID = M.masc_id
				 $filter
				GROUP BY T.gpsTrackerID
				ORDER BY T.cod_pais, T.gpsTrackerIMEI";    	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}
        
		return $result;			
	}
	
    public function getData($idObject){
		$result= Array();
		$this->query("SET NAMES utf8",false); 
    	$sql ="SELECT T.gpsTrackerID, T.cod_pais, T.gpsTrackerIMEI, T.carrier, 
    			IF(T.gpsTrackerStatus IS NULL,'Inactivo','Activo') AS ESTATUS , P.bandera, 
    			IF(M.masc_id IS NULL,'Sin Asignar','Asignado') AS ASIGNADO,
    			IF(M.masc_id IS NULL,'Sin Mascota',M.masc_nombre) AS MASCOTA , 
    			IF(M.masc_estatus = 0,'Inactivo','Activo') AS ESTATUS_MASCOTA,
    			T.gpsTrackerStatus,
    			T.gpsTrackerSerialNumber,
    			T.telephone,
    			T.IPaddress,
    			T.id_distribuidor
				FROM GPSTracker T
				 LEFT JOIN loc_paises P ON T.cod_pais = P.code
				 LEFT JOIN mascota    M ON T.gpsTrackerID = M.masc_gpsTrackerID
				 WHERE T.gpsTrackerID = $idObject 
				GROUP BY T.gpsTrackerID
				ORDER BY T.cod_pais, T.gpsTrackerIMEI
                LIMIT 1";	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}	
        
		return $result;	    	
    }	
    
    public function validateData($dataSearch,$idObject,$optionSearch){
		$result=true;		
		$this->query("SET NAMES utf8",false);
		$filter = ($optionSearch=='imei') ? ' gpsTrackerIMEI = "'.$dataSearch.'"': ' IP = "'.$dataSearch.'"';
    	$sql ="SELECT $this->_primary
	    		FROM $this->_name
				WHERE $this->_primary <> $idObject
                 AND  $filter";
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = false;
		}
        
		return $result;		    	
    } 

    public function updateRow($data){
       $result     = Array();
        $result['status']  = false;

        $sql="UPDATE $this->_name			 
				SET gpsTrackerAlias			=  '".$data['inputImei']."',
					gpsTrackerIMEI			=  '".$data['inputImei']."',
					cod_pais				=  '".$data['inputCodePais']."',
					id_distribuidor			=   ".$data['inputDistribuidor'].",
					gpsTrackerSerialNumber	=  '".$data['inputNoSerie']."',
					gpsTrackerStatus		=  ".$data['inputEstatus'].",
					IPaddress				=  '".$data['inputIp']."',
					telephone				=  '".$data['inputTel']."'
				WHERE $this->_primary =".$data['catId']." LIMIT 1";
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

    public function insertRow($data){
        $result     = Array();
        $result['status']  = false;
        
        $sql="INSERT $this->_name			 
				SET gpsTrackerAlias			=  '".$data['inputImei']."',
				    cod_pais				=  '".$data['inputCodePais']."',
				    id_distribuidor			=   ".$data['inputDistribuidor'].",
					gpsTrackerIMEI			=  '".$data['inputImei']."',
					gpsTrackerSerialNumber	=  '".$data['inputNoSerie']."',
					gpsTrackerStatus		=   ".$data['inputEstatus'].",
					carrier					=  'RW',
					IPaddress				=  '".$data['inputIp']."',
					telephone				=  '".$data['inputTel']."'";
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