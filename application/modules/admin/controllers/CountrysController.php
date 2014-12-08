<?php
class admin_CountrysController extends My_Controller_Action
{
	protected $_clase = 'mPais';
	
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
    		$cPaises  = new My_Model_Paises();
    		$cCientes = new My_Model_Clientes();
    		$cDistribuidores = new My_Model_Distribuidores();
    		$cBanners = new My_Model_Banners();
    		$cPromos  = new My_Model_Promociones();
			$codeCountry = '';
			
    		if(isset($this->_dataIn['catId'])){
    			$codeCountry = $this->_dataIn['catId'];	
    		}else{
    			$codeCountry = $this->_dataUser['cod_pais'];
    		}
    		
    		$this->view->aClientes 		 = $cCientes->getDataTable($codeCountry);
    		$this->view->aDistribuidores = $cDistribuidores->getDataTable($codeCountry);
    		$this->view->aBanners		 = $cBanners->getDataTable($codeCountry);
    		$this->view->aPromos		 = $cPromos->getDataTable($codeCountry);
    		$this->view->data			 = $this->_dataIn;
    		$this->view->dataInfo		 = $cPaises->getData($codeCountry);
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  	   		
   }    
    
}