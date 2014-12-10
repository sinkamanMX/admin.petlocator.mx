<?php
/**
 * Archivo de definición de usuarios
 * 
 * @author EPENA
 * @package library.My.Models
 */

/**
 * Modelo de tabla: usuarios
 *
 * @package library.My.Models
 * @author EPENA
 */
class My_Model_Mascotas extends My_Db_Table
{
    protected $_schema 	= 'petlocator';
	protected $_name 	= 'mascota';
	protected $_primary = 'masc_id';
	
	public function getPets($idUser){
		$result= Array();
        /*$this->query("SET lc_time_names = 'es_ES'",false);   */      
		$this->query("SET NAMES utf8",false);         
        $sql ="SELECT DATE_FORMAT(masc_aniversario, '%c %M %Y') AS dateAniv,mascota.*,GPSTracker.*,GPSTrackerStatus.*
                ,IF(mascota.masc_estatus = 0,'Inactivo','Activo') AS M_ESTATUS,
                DATE_FORMAT(masc_fecha_alta, '%c %M %Y %r') AS masc_fecha_alta,
                                LatestPosition.*
                FROM mascota
                INNER JOIN GPSTracker ON mascota.masc_gpsTrackerID = GPSTracker.gpsTrackerID
                INNER JOIN GPSTrackerStatus ON GPSTracker.gpsTrackerStatus = GPSTrackerStatus.statusID
        		 LEFT JOIN LatestPosition   ON mascota.masc_id = LatestPosition.petID                 
                WHERE  clt_id = $idUser 
                GROUP BY mascota.masc_id
                ORDER BY mascota.masc_nombre ASC, LatestPosition.dateTime DESC";
        try{            
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
    
    public function getPetInfo($codPet,$iduser=-1){
		$result     = false;
		$filtro 	= ($iduser>0) ? " AND mascota.clt_id = ".$iduser: ""; 
        /*$this->query("SET lc_time_names = 'es_ES'",false);  */  
		$this->query("SET NAMES utf8",false);     	
        $sql= "SELECT DATE_FORMAT(masc_fecha_alta, '%c %M %Y %r') AS masc_fecha_alta,mascota.*,GPSTracker.*,GPSTrackerStatus.*,IF(mascota.masc_estatus = 0,'Inactivo','Activo') AS masc_estatus,
                masc_aniversario AS dateAniversario,GeoZone.geozoneID,mascota.clt_id, mascota.masc_id,
            	FROM mascota 
                INNER JOIN GPSTracker ON mascota.masc_gpsTrackerID = GPSTracker.gpsTrackerID
                INNER JOIN GPSTrackerStatus ON GPSTracker.gpsTrackerStatus = GPSTrackerStatus.statusID
                 LEFT JOIN mascota_geozona ON mascota_geozona.masc_id = mascota.masc_id
        		 LEFT JOIN GeoZone  ON GeoZone.geozoneID = mascota_geozona.geozoneID 
            	WHERE masc_idAleatorio = '$codPet' ".$filtro." ";
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query[0];			
		}	
        
		return $result;	   
    }      
    
    public function updatePetInfo($data){
        $result     = false;
        $idPet      = $data['idPetKey'];
        $namepet    = $data['inputNombre'];
        $especiePet = $data['inputEspecie'];
        $razaPet    = $data['inputRaza'];
        $anivPet    = $data['inputAniv']." 00:00:00"; 
        $picPet		= $data['nameImg'];
		$this->query("SET NAMES utf8",false);                 
        $sql="UPDATE mascota SET
                masc_nombre     = '$namepet',
                masc_especie    = '$especiePet',
                masc_raza       = '$razaPet',
                masc_aniversario= '$anivPet',
                masc_foto  		= '$picPet',
                masc_fecha_actualizacion= CURRENT_TIMESTAMP
                WHERE masc_idAleatorio = '$idPet' limit 1";
        try{            
    		$query   = $this->query($sql,false);
    		$result  = true;	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;	
    } 

    public function insertPet($data){
        $result     = false;
        $idClient   = $data['clt_id'];
        $namepet    = $data['inputNombre'];
        $especiePet = $data['inputEspecie'];
        $razaPet    = $data['inputRaza'];
        $anivPet    = $data['inputAniv']." 00:00:00"; 
        $picPet		= $data['nameImg'];
        $imei		= $data['inputIMEI'];
        $idtracker  = $data['idTracker'];
        $masc_random= $this->RandomString();
		$this->query("SET NAMES utf8",false);         
        $sql="INSERT INTO mascota SET
        		clt_id			= $idClient,
                masc_nombre     = '$namepet',
                masc_especie    = '$especiePet',
                masc_raza       = '$razaPet',
                masc_aniversario= '$anivPet',
                masc_foto  		= '$picPet',
                masc_idAleatorio = '$masc_random',
                masc_gpsTrackerID= $idtracker,
                masc_fecha_alta  = CURRENT_TIMESTAMP, 
                masc_estatus	 = 1
                masc_fecha_actualizacion= CURRENT_TIMESTAMP";
        try{            
    		$query   = $this->query($sql,false);
    		$result  = true;
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result; 	
    }
    
	function RandomString($length=10,$uc=TRUE,$n=TRUE,$sc=FALSE)
	{
	    $source = 'abcdefghijklmnopqrstuvwxyz';
	    if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    if($n==1) $source .= '1234567890';
	    if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
	    if($length>0){
	        $rstr = "";
	        $source = str_split($source,1);
	        for($i=1; $i<=$length; $i++){
	            mt_srand((double)microtime() * 1000000);
	            $num = mt_rand(1,count($source));
	            $rstr .= $source[$num-1];
	        }
	 
	    }
	    return $rstr;
	}
	
	public function getPetsPendientes($idUser){
		$result= Array();
		$this->query("SET NAMES utf8",false);       
        
        $sql ="SELECT DATE_FORMAT(masc_aniversario, '%c %M %Y') AS dateAniv,mascota.*,GPSTracker.*,GPSTrackerStatus.*
                ,IF(mascota.masc_estatus = 0,'Inactivo','Activo') AS masc_estatus,
                DATE_FORMAT(masc_fecha_alta, '%c %M %Y %r') AS masc_fecha_alta
                FROM mascota
                INNER JOIN GPSTracker ON mascota.masc_gpsTrackerID = GPSTracker.gpsTrackerID
                INNER JOIN GPSTrackerStatus ON GPSTracker.gpsTrackerStatus = GPSTrackerStatus.statusID
                LEFT JOIN pet_mascota_promocion mp ON mascota.`masc_id` = mp.`masc_id`
                WHERE  clt_id = $idUser 
                AND    mp.id_promocion IS NULL
                ORDER BY mascota.masc_nombre ASC";
        try{            
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

	public function getDataInfo($idPet){ 
		$this->query("SET NAMES utf8",false); 		
        $sql= "SELECT *
            	FROM mascota 
            	WHERE masc_id = ".$idPet;
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query[0];			
		}	
        
		return $result;			
	}
	
	public function getAllPets(){
		$result= Array();
        $this->query("SET lc_time_names = 'es_ES'",false);  
		$this->query("SET NAMES utf8",false);        
        
        $sql ="SELECT DATE_FORMAT(masc_aniversario, '%c %M %Y') AS dateAniv,mascota.*,GPSTracker.*,GPSTrackerStatus.*
                ,IF(mascota.masc_estatus = 0,'Inactivo','Activo') AS masc_estatus,
                DATE_FORMAT(masc_fecha_alta, '%c %M %Y %r') AS masc_fecha_alta, usuario_cliente.*
                FROM mascota
                LEFT JOIN GPSTracker ON mascota.masc_gpsTrackerID 		  = GPSTracker.gpsTrackerID
                LEFT JOIN GPSTrackerStatus ON GPSTracker.gpsTrackerStatus = GPSTrackerStatus.statusID
                LEFT JOIN usuario_cliente  ON mascota.clt_id 			  = usuario_cliente.clt_id 
                ORDER BY mascota.masc_nombre ASC";
        try{            
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
	
    public function changeStatus($Status,$idItem){
        $result     = false;
		$this->query("SET NAMES utf8",false); 
        $sql="UPDATE mascota SET
                masc_estatus  = $Status
                WHERE masc_id = $idItem limit 1";
        try{            
    		$query   = $this->query($sql,false);
    		$result  = true;	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;	    	
    }    

    public function getInfo($idItem){
      	$this->query("SET NAMES utf8",false);     	
        /*$this->query("SET lc_time_names = 'es_ES'",false);  */  
        $sql= "SELECT DATE_FORMAT(masc_fecha_alta, '%c %M %Y %r') AS masc_fecha_alta,mascota.*,GPSTracker.*,GPSTrackerStatus.*,IF(mascota.masc_estatus = 0,'Inactivo','Activo') AS masc_estatus,
                masc_aniversario AS dateAniversario, usuario_cliente.*,masc_aniversario AS dateAniversario,LatestPosition.*,mascota.clt_id, mascota.masc_id
            	FROM mascota 
                INNER JOIN GPSTracker ON mascota.masc_gpsTrackerID = GPSTracker.gpsTrackerID
                INNER JOIN GPSTrackerStatus ON GPSTracker.gpsTrackerStatus = GPSTrackerStatus.statusID
				INNER JOIN usuario_cliente  ON mascota.clt_id = usuario_cliente.clt_id 
				LEFT JOIN LatestPosition    ON mascota.masc_id = LatestPosition.petID 
            	WHERE masc_id = ".$idItem;
		$query   = $this->query($sql);
		if(count($query)>0){
			$result	 = $query[0];			
		}	
        
		return $result;	   
    }       
}