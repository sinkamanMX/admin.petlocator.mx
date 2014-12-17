<?php
class My_Model_Pedidos extends My_Db_Table
{
    protected $_schema 	= 'petlocator';
	protected $_name 	= 'pedido';
	protected $_primary = 'pdd_id';
	
	
	public function insertRow($data){
        $result     = Array();
		$this->query("SET NAMES utf8",false);  
        $result['status']  = false;
        
		$sql="INSERT INTO $this->_name 
			SET clt_id			=  ".$data['inputCliente'].",
				pdd_pedido		= '".$data['inputPedido']."',
				pdd_total		=  ".$data['inputTotal'].",
				pdd_peso		=  ".$data['inputPeso'].",
				pdd_cantidad	=  ".$data['inputCantidad'].",
				pdd_fecha_alta	=  CURRENT_TIMESTAMP,
				pdd_pagado		=  ".$data['inputPay'].",
				pdd_pago_metodo =  0,
				pdd_payment_date=  CURRENT_TIMESTAMP,
				cod_pais		=  '".$data['codeCountry']."',
				pdd_estatus		=  0";
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
	

	public function getOrdersByDist($idDistribuitor){
		$result= Array();
        $this->query("SET NAMES utf8",false);  
    	$sql ="SELECT CONCAT(C.clt_nombre,' ',C.clt_apellidos) AS CLIENTE, IF(P.pdd_pagado=0,'SI','NO') AS E_PAGO , P.*
				FROM pedido P
				INNER JOIN usuario_cliente C ON P.clt_id = C.clt_id
				WHERE P.clt_id IN (
					SELECT clt_id
					FROM usuario_cliente
					WHERE id_distribuidor= $idDistribuitor
				)
				ORDER BY P.pdd_fecha_alta DESC";			         	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;			
	}                                    
	
	public function getInfoOrder($idOrder){
		$result= Array();
        $this->query("SET lc_time_names = 'es_ES'",false);  
    	$sql ="SELECT  *
                FROM pedido
                WHERE pdd_id = $idOrder LIMIT 1;";			         	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}	
        
		return $result;			
	}
	/*
	
	public function setOrder($data){
        $this->query("SET NAMES utf8",false);   	
    	$result=false;
		$sql="INSERT INTO pedido SET 
				clt_id 			= ".$data['idCliente'].",
				pdd_pedido		= '".$data['pedido']."',
				pdd_peso		= ".$data['peso'].",
				pdd_total		= ".$data['total'].",
				pdd_fecha_alta	= CURRENT_TIMESTAMP,
				pdd_fecha_act	= CURRENT_TIMESTAMP,
				pdd_estatus		= 0 ";
        try{
    		$query   = $this->query($sql,false);
    		$result  = true;	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;
	}
	
	public function getLastId($idClient){
		$result= Array();
    	$sql ="SELECT  *
                FROM pedido WHERE clt_id = ".$idClient." order by pdd_id desc LIMIT 1 ;";			         	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}	
        
		return $result;			
	}
	
	public function updateOrder($data){
        $this->query("SET NAMES utf8",false);     	
    	$result=false;
		$sql="UPDATE pedido SET pdd_xml 	= '".$data['pedido_xml']."'
				where pdd_id = ".$data['idOrder'];
        try{            
    		$query   = $this->query($sql,false);
    		$result  = true;	
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;			   		
	}
	
	public function valiateOrder($id_cliente){        
    	$result=true;
		$sql="SELECT TIME_TO_SEC( TIMEDIFF(CURRENT_TIMESTAMP,pdd_fecha_alta)) AS VALIDATE
 				FROM pedido where clt_id = ".$id_cliente
				." order by pdd_id desc LIMIT 1";
        try{            
    		$query   = $this->query($sql,true);
    		if(count($query[0])>0){
    			$result  = ($query[0]['VALIDATE']<300) ? false : true;	
    		}    				
        }catch(Exception $e) {
            echo $e->getMessage();
            echo $e->getErrorMessage();
        }
		return $result;	
	}
	
	public function getAllOrders(){
    	$result=true;
		$this->query("SET NAMES utf8",false); 
    	$sql ="SELECT pedido.*,DATE_FORMAT(pdd_fecha_alta, '%c %M %Y %r') AS pdd_fecha_alta, usuario_cliente.*
				FROM pedido
    			INNER  JOIN usuario_cliente  ON pedido.clt_id 			  = usuario_cliente.clt_id";			         	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query;			
		}	
        
		return $result;				
	}

	public function getInfo($idOrder){
		$result= Array();
		$this->query("SET NAMES utf8",false); 
    	$sql ="SELECT  *
                FROM pedido
                WHERE pdd_id = $idOrder LIMIT 1;";			         	
		$query   = $this->query($sql);
		if(count($query)>0){		  
			$result = $query[0];			
		}	
        
		return $result;			
	}	
	
    public function updateRow($data){
        $result     = Array();
        $result['status']  = false;

		$sql="UPDATE pedido SET 
				pdd_estatus		= ".$data['inputEstatus'].",
				pdd_pagado		= ".$data['inputPago'].",
				pdd_pago_metodo = ".$data['inputMetodo'].",
				pdd_observaciones='".$data['inputObservaciones']."',
				pdd_pedido		= '".$data['inputPedido']."',
				pdd_peso		= ".$data['inputPeso'].",
				pdd_total		= ".$data['inputTotal'].",
				pdd_observaciones_envio='".$data['inputObservacionesE']."',
				pdd_fecha_act	= CURRENT_TIMESTAMP
			 WHERE pdd_id 		= ".$data['catId'];
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
    }  	*/
}