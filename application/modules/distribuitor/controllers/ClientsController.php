<?php

class distribuitor_ClientsController extends My_Controller_Action
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
			/*$cTrackers  = new My_Model_Trackers();
			$cPaises  	= new My_Model_Paises(); 
			$codCountry = (isset($this->_dataIn['strCountry']) && $this->_dataIn['strCountry']!="") ? $this->_dataIn['strCountry']: $this->_dataUser['cod_pais'];
			$aDataTable = $cTrackers->getDataTableAdmon($codCountry,$this->_dataUser['adm_tipo'],$this->_dataUser['id_distribuidor']);
			
			$this->view->dataCountry = $cPaises->getData($codeCountry); 			
			$this->view->aResume = $aDataTable;*/
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
    
    public function moreinfoAction(){
		try{
    		$dataInfo   = Array();
    		$cFunctions	= new My_Controller_Functions();
    		$classObject= new My_Model_Clientes();
			$cPaises	= new My_Model_Paises();
			$cMascotas	= new My_Model_Mascotas();
			
			$cDistribuidores = new My_Model_Distribuidores();
    		$sPais			= $this->_dataUser['cod_pais']; 
    		$aPaises    	= $cPaises->getCbo();
			$aDistribuidres = Array();    
			$aMascotas		= Array();		
    		$sEstatus		= '';
    		$sDistribuidor 	= (isset($this->_dataIn['codeDist']) && $this->_dataIn['codeDist']!="") ? $this->_dataIn['codeDist']: $this->_dataUser['id_distribuidor'];
    		
		    if($this->_idUpdate >-1){
    	    	$dataInfo		= $classObject->getData($this->_idUpdate);
    	    	$aMascotas		= $cMascotas->getPets($this->_idUpdate);
			}			
			
		    if($this->_dataOp=='changeStatus'){
		    	$cValidateInt = new Zend_Validate_Digits();
		    	if($cValidateInt->isValid($this->_dataIn['petID']) && $cValidateInt->isValid($this->_dataIn['petStatus'])){
		    		$change = $cMascotas->changeStatus($this->_dataIn['petStatus'],$this->_dataIn['petID']);
		    		$aMascotas	= $cMascotas->getPets($this->_idUpdate);
		    	}
		    }			
			
    		$this->view->aPets		= $aMascotas;    		
			$this->view->data 		= $dataInfo;
			$this->view->errors 	= $this->_aErrors;	
			$this->view->resultOp   = $this->_resultOp;
			$this->view->catId		= $this->_idUpdate;
			$this->view->idToUpdate = $this->_idUpdate;
			$this->view->dataCountry = $cPaises->getData($sPais); 
			$this->view->dataDist	= $cDistribuidores->getData($sDistribuidor);						    		
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        } 
    }
}