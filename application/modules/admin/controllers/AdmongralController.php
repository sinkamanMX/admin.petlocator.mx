<?php
class admin_AdmongralController extends My_Controller_Action
{
	protected $_clase = 'mAdmonGral';
	
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
    		$cPaises = new My_Model_Paises();
    		$aPaises = $cPaises->getResumen();

    		$this->view->aPaises = $aPaises;
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  	   		
   }
   
   public function moreinfoAction(){
    	try{
    		$classObject 	= new My_Model_Paises();
    		$cFunctions = new  My_Controller_Functions();
    		$dataInfo	= Array();
    		$sEstatus	= '';
    		$sPais		= '';
    		$catId		= "";
		
			if($this->_idUpdate!="-1"){
				$dataInfo = $classObject->getData($this->_idUpdate);
				$catId	  = $this->_dataIn['catId'];
				$sPais	  = $dataInfo['code'];
				$sEstatus = $dataInfo['status'];
			}
			
			if($this->_dataOp=='update'){
				if($this->_idUpdate!=""){
					$updated = $classObject->updateRow($this->_dataIn);
					if($updated['status']){
						$dataInfo = $classObject->getData($this->_idUpdate);
						$catId	  = $this->_idUpdate;
						$sPais	  = $dataInfo['code'];
						$sEstatus = $dataInfo['status'];											 		
						$this->_resultOp= 'okRegister';
					}else{
						$this->_aErrors['status'] = 'no-info';
					}
				}else{
					$this->_aErrors['status'] = 'no-info';
				}
			}
			
			$aPaises	= $classObject->getCboStat(0,$catId);
			$this->view->aPaises = $cFunctions->selectDb($aPaises,$sPais);
			$this->view->aStatus = $cFunctions->cboStatus($sEstatus);
			$this->view->data 	 = $dataInfo;
			$this->view->dataIn  = $this->_dataIn;
			$this->view->idToUpdate = $this->_idUpdate;
			$this->view->resultOp= $this->_resultOp;
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  	   		   	
   }
   
   public function getdetailAction(){
   	
   }
      
}