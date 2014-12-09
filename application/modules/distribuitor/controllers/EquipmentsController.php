<?php

class distribuitor_EquipmentsController extends My_Controller_Action
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
    

    public function indexAction()
    {
		try{
			$cTrackers  = new My_Model_Trackers();
			$cPaises  	= new My_Model_Paises(); 
			$codCountry = (isset($this->_dataIn['strCountry']) && $this->_dataIn['strCountry']!="") ? $this->_dataIn['strCountry']: $this->_dataUser['cod_pais'];
			$aDataTable = $cTrackers->getDataTableAdmon($codCountry,$this->_dataUser['adm_tipo'],$this->_dataUser['id_distribuidor']);
			
			$this->view->dataCountry = $cPaises->getData($codeCountry); 			
			$this->view->aResume = $aDataTable;
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
    
    public function moreinfoAction(){
		try{
    		$dataInfo   = Array();
    		$cFunctions	= new My_Controller_Functions();
    		$classObject= new My_Model_Trackers();
			$cPaises	= new My_Model_Paises();
			$cDistribuidores = new My_Model_Distribuidores();
    		$sPais			= $this->_dataUser['cod_pais']; 
    		$aPaises    	= $cPaises->getCbo();
			$aDistribuidres = Array();    		
    		$sEstatus		= '';
    		$sDistribuidor 	= (isset($this->_dataIn['codeDist']) && $this->_dataIn['codeDist']!="") ? $this->_dataIn['codeDist']: $this->_dataUser['id_distribuidor'];
    		
		    if($this->_idUpdate >-1){
    	    	$dataInfo		= $classObject->getData($this->_idUpdate);
    	    	$sEstatus		= $dataInfo['gpsTrackerStatus'];
    	    	$sPais			= $dataInfo['cod_pais'];
    	    	$sDistribuidor 	= $dataInfo['id_distribuidor'];
			}		
			
		    if($this->_dataOp=='update'){	  		
				if($this->_idUpdate>-1){
					 $validateIMEI = $classObject->validateData($this->_dataIn['inputImei'],$this->_idUpdate,'imei');
					 if($validateIMEI){
						 $updated = $classObject->updateRow($this->_dataIn);
						 if($updated['status']){						 	
						 	$this->_redirect("/distribuitor/main/index?catId=".$sDistribuidor."&codeCountry=".$sPais);
						 	/*		
					 		$dataInfo   	= $classObject->getData($this->_idUpdate);
					 		$sEstatus		= $dataInfo['gpsTrackerStatus'];
					 		$sPais			= $dataInfo['cod_pais'];	
    	    				$sDistribuidor 	= $dataInfo['id_distribuidor'];	
    	    				*/				 		
						 	$this->_resultOp= 'okRegister';
						 }		
					 }else{
					 	$this->_aErrors['eIMEI'] = '1';
					 }	
				}else{
					$this->_aErrors['status'] = 'no-info';
				}	
			}else if($this->_dataOp=='new'){	
				$validateIMEI = $classObject->validateData($this->_dataIn['inputImei'],-1,'imei');
				 if($validateIMEI){
					 	$insert = $classObject->insertRow($this->_dataIn);
				 		if($insert['status']){	
				 			$this->_redirect("/distribuitor/main/index?catId=".$sDistribuidor."&codeCountry=".$sPais);
				 			/*$this->_idUpdate = $insert['id'];		
				 			
							$dataInfo        = $classObject->getData($this->_idUpdate);
							$sEstatus	     = $dataInfo['gpsTrackerStatus'];
							$sPais			 = $dataInfo['cod_pais'];
							$sDistribuidor 	 = $dataInfo['id_distribuidor'];*/
							$this->_resultOp = 'okRegister';							 		
						}else{
							$this->_aErrors['status'] = 'no-insert';
						}
				 }else{
				 	$this->_aErrors['status'] = '1';
				 }		
			}		   

			if(count($this->_aErrors)>0 && $this->_dataOp!=""){				
				$dataInfo['gpsTrackerIMEI'] = $this->_dataIn['inputImei'];
				$dataInfo['gpsTrackerSerialNumber']= $this->_dataIn['inputNoSerie'];
				$dataInfo['telephone'] 		= $this->_dataIn['inputTel'];
				$dataInfo['IPaddress'] 		= $this->_dataIn['inputIp'];				
				$sEstatus	 				= $this->_dataIn['inputEstatus'];
			}
			
			$aDistribuidres			= $cDistribuidores->getCbo($sPais);
			$this->view->aDistrib   = $cFunctions->selectDb($aDistribuidres,$sDistribuidor);  
    		$this->view->aStatus  	= $cFunctions->cboStatus($sEstatus);
    		$this->view->aPaises 	= $cFunctions->selectDb($aPaises,$sPais);
    		

			$this->view->data 		= $dataInfo;
			$this->view->errors 	= $this->_aErrors;	
			$this->view->resultOp   = $this->_resultOp;
			$this->view->catId		= $this->_idUpdate;
			$this->view->idToUpdate = $this->_idUpdate;
			$this->view->dataCountry = $cPaises->getData($sPais); 				    		
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        } 
    }
}