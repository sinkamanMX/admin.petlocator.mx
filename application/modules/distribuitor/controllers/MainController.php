<?php
class distribuitor_MainController extends My_Controller_Action
{
	protected $_clase = 'mDealers';
	
    public function init()
    {
    	try{    		
    		$sessions = new My_Controller_Auth();
			$perfiles = new My_Model_Perfiles();
	        if($sessions->validateSession()){
		        $this->_dataUser   = $sessions->getContentSession(); 	
			}else{
				$this->_redirect("/");
			}    		
			$this->view->dataUser   = $this->_dataUser;
			$this->view->modules    = $perfiles->getModules($this->_dataUser['adm_tipo']);
			$this->view->moduleInfo = $perfiles->getDataModule($this->_clase);
						
			$this->_dataIn 					= $this->_request->getParams();
			$this->_dataIn['userCreate']	= $this->_dataUser['ID_USUARIO'];
	    	if(isset($this->_dataIn['optReg'])){
				$this->_dataOp = $this->_dataIn['optReg'];				
			}
			
			if(isset($this->_dataIn['catId'])){
				$this->_idUpdate = $this->_dataIn['catId'];				
			}					
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  		
    }
    
   public function indexAction(){
    	try{
    		$cPaises  		 = new My_Model_Paises();   
    		$cClientes 		 = new My_Model_Clientes();
    		$classObject	 = new My_Model_Distribuidores();
    		$cTrackers  	 = new My_Model_Trackers(); 
    		$cPedidos 		 = new My_Model_Pedidos();
    				    		
    		$sDistribuidor   = (isset($this->_dataIn['catId']) && $this->_dataIn['catId']!="") ? $this->_dataIn['catId']: $this->_dataUser['id_distribuidor'];
    		$codeCountry 	 = (isset($this->_dataIn['codeCountry']) && $this->_dataIn['codeCountry']!="") ? $this->_dataIn['codeCountry']: $this->_dataUser['cod_pais'];
    		
    		
    		$dataInfo 	= $classObject->getData($sDistribuidor);
			$aClients	= $cClientes->getTableDist($sDistribuidor);    		    	
			$aDataTable = $cTrackers->getDataTableAdmon($codeCountry,3,$sDistribuidor);
			$aPedidos	= $cPedidos->getOrdersByDist($sDistribuidor);
			
			$this->view->aTrackers   = $aDataTable;    		    		
    		$this->view->data 		 = $dataInfo;
			$this->view->dataCountry = $cPaises->getData($codeCountry);    		
    		$this->view->dataIn		 = $this->_dataIn;
    		$this->view->aClients	 = $aClients;
    		$this->view->aPedidos	 = $aPedidos;
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  	   		
   }    
    
   
    
   public function getinfoAction(){
    	try{
    		$classObject = new My_Model_Distribuidores();
			$cPaises     = new My_Model_Paises();
    		$cFunctions  = new  My_Controller_Functions();
			$dataInfo	 = Array();
    		$sEstatus	 = '';
    		$sCountry 	 = (isset($this->_dataIn['codeCountry']) && $this->_dataIn['codeCountry']!="") ? $this->_dataIn['codeCountry']: $this->_dataUser['cod_pais'];  		    				    				
    		$aPaises	 = $cPaises->getCbo(); 
    		$cEstados	 = new My_Model_Estados();
    		$aEstados	 = Array();
    		$sEstado	 = '';
    		
    	    if($this->_idUpdate!="-1"){
				$dataInfo = $classObject->getData($this->_idUpdate);
				$sEstatus = $dataInfo['status'];
				$sCountry = $dataInfo['cod_pais'];
				$sEstado  = $dataInfo['id_estado'];
				
				$aEstados = $cEstados->getCbo($sCountry);
			}
			
			$aEstados = $cEstados->getCbo($sCountry);
			
    		if($this->_dataOp=='new'){
				$this->_dataIn['inputCodePais'] = ($this->_dataUser['adm_tipo']==1) ? $this->_dataIn['inputCodePais'] : $this->_dataUser['cod_pais'];    							
				$insert = $classObject->insertRow($this->_dataIn);
				if($insert['status']){
					$this->_redirect("/distribuitor/main/index?strInput=".$insert['id']."&codeCountry=".$sCountry);
																	
					$this->_resultOp= 'okRegister';
				}else{
					$this->_aErrors['status'] = 'no-info';
				}            		            	            									
			}elseif($this->_dataOp=='update'){
				$this->_dataIn['inputCodePais'] = ($this->_dataUser['adm_tipo']==1) ? $this->_dataIn['inputCodePais'] : $this->_dataUser['cod_pais'];
				$insert = $classObject->updateRow($this->_dataIn);
				if($insert['status']){
					$this->_redirect("/distribuitor/main/index?strInput=".$insert['id']."&codeCountry=".$sCountry);				
					$this->_resultOp= 'okRegister';
				}else{
					$this->_aErrors['status'] = 'no-info';
				}            	            				
			}
			
			if(count($this->_aErrors)>0 && $this->_dataOp!=""){
				$dataInfo['nombre'] 		= $this->_dataIn['inputName'];
				$dataInfo['calle'] 			= $this->_dataIn['inputCalle'];	
				$dataInfo['no_int'] 		= $this->_dataIn['InputNoint'];				
				$dataInfo['no_ext'] 		= $this->_dataIn['InputNoExt'];				
				$dataInfo['colonia'] 		= $this->_dataIn['inputColonia'];		
				$dataInfo['cod_pais'] 		= $this->_dataIn['inputCodePais'];		
				$dataInfo['id_estado'] 		= $this->_dataIn['inputEstado'];		
				$dataInfo['municipio'] 		= $this->_dataIn['inputMuni'];		
				$dataInfo['cp'] 			= $this->_dataIn['inputCp'];		
				$dataInfo['web_url'] 		= $this->_dataIn['inputWeb'];																										
				$dataInfo['contacto']		= $this->_dataIn['inputNamContacto'];			
				$dataInfo['contacto_email'] = $this->_dataIn['inputEmContacto'];			
				$dataInfo['contacto_tel'] 	= $this->_dataIn['inputTelContacto'];			
				$dataInfo['contacto_tel2'] 	= $this->_dataIn['inputTelContacto2'];			
				$dataInfo['horario'] 		= $this->_dataIn['inputHorario'];																							
				$dataInfo['dias'] 			= $this->_dataIn['inputDias'];
				$dataInfo['status'] 		= $this->_dataIn['inputEstatus'];
				$dataInfo['latitud'] 		= $this->_dataIn['inputLatitud'];												
				$dataInfo['longitud'] 		= $this->_dataIn['inputLongitud'];					    
				$sEstatus 					= $this->_dataIn['inputEstatus'];
				$sCountry			 		= $this->_dataIn['inputCodePais'];
				$sEstado  					= $this->_dataIn['inputEstado'];					                 
            }				
			
			$this->view->aStatus 	= $cFunctions->cboStatus($sEstatus);
			$this->view->data 		= $dataInfo;
			$this->view->dataIn  	= $this->_dataIn;
			$this->view->idToUpdate = $this->_idUpdate;
			$this->view->catId 		= $this->_dataIn['catId'];
			$this->view->resultOp	= $this->_resultOp;
			$this->view->dataCountry= $cPaises->getData($sCountry);			
    		$this->view->aPaises 	= $cFunctions->selectDb($aPaises,$sCountry);  				
			$this->view->aEstados 	= $cFunctions->selectDb($aEstados,$aEstados);  
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  	   		
   }     
}