<?php

class distribuitor_SalesController extends My_Controller_Action
{	
	protected $_clase = 'mSales';
	
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
			$aCarShop   = Array();
			$aDatainfo  = Array();
			$sEstado    = '';
			$cEstados   = new My_Model_Estados(); 
			$cFunctions = new My_Controller_Functions();
			$cClientes	= new My_Model_Clientes();
			
			$codCountry = (isset($this->_dataIn['strCountry']) && $this->_dataIn['strCountry']!="") ? $this->_dataIn['strCountry']: $this->_dataUser['cod_pais'];
			$aEstados    = $cEstados->getCbo($codCountry);

			$aNamespace = new Zend_Session_Namespace("sService");

			if(isset($aNamespace->dataUser)){
				$aDatainfo	= $aNamespace->dataUser;
				$sEstado	= $aDatainfo['inputEstado'];
			}
			
			if($this->_dataOp=='step1'){
				$validaUser = $cClientes->userExist($this->_dataIn['inputUser']);
				if($validaUser==0){
					if(isset($aNamespace->dataUser)){
						unset($aNamespace->dataUser);
					}
					
					$aNamespace->dataUser = $this->_dataIn;
		            $this->_redirect('/distribuitor/sales/products');
				}else{
					$this->_aErrors['userExist'] = 1;
				}
			}			
			
			if($this->_dataOp!="" && count($this->_aErrors)>0){
				$aDatainfo['inputName'] = $this->_dataIn['inputName'];
				$aDatainfo['inputApps'] = $this->_dataIn['inputApps'];
				$aDatainfo['inputUser'] = $this->_dataIn['inputUser'];
				$aDatainfo['inputPass'] = $this->_dataIn['inputPass'];
				$aDatainfo['inputTel']  = $this->_dataIn['inputTel'];
				$sEstado	   		    = $this->_dataIn['inputEstado'];
			}
			$this->view->data	  = $aDatainfo;
			$this->view->aErrors  = $this->_aErrors;
			$this->view->aEstados = $cFunctions->selectDb($aEstados,$sEstado);
			$this->view->aCarShop = $aCarShop; 
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }  

    public function productsAction()
    {
    	try{
			$cProducts  = new My_Model_Productos();   
			$cFunctions = new My_Controller_Functions(); 	
			$cDistribuidores =  new My_Model_Distribuidores();	
			$codCountry    = (isset($this->_dataIn['strCountry']) && $this->_dataIn['strCountry']!="") ? $this->_dataIn['strCountry']: $this->_dataUser['cod_pais'];
			$sDistribuidor = $this->_dataUser['id_distribuidor'];
						     		
			$aNamespace = new Zend_Session_Namespace("sService");
						
			if(!isset($aNamespace->dataUser)){
				$this->_redirect('/distribuitor/sales/index');				
			}

			if($this->_dataOp=='step2'){
				$iCounter = 0;
				$aProducts 	= Array();
				$aProdRecibe= $this->_dataIn['products'];				
				for($i=0;$i<count($aProdRecibe);$i++){					
					$aProducts[$iCounter]['id'] 	= $aProdRecibe[$i];
					$aProducts[$iCounter]['count']	= $this->_dataIn['selectCant'.$aProdRecibe[$i]];
					$aProducts[$iCounter]['data']	= $cProducts->getData($aProdRecibe[$i]);
					$iCounter++;
				}
				
				if(count($aProducts)>0){
					if(isset($aNamespace->aDataPet)){
						unset($aNamespace->aDataPet);
					}
					
					$aNamespace->aDataPet = $aProducts;
		            $this->_redirect('/distribuitor/sales/pets');					
				}else{
					$this->_aErrors['noProducts'] = 1;
				}
			}
			
    		$this->view->aCounter  = $cFunctions->cbo_number(50,0); 
			$this->view->aProducts = $cProducts->getDataTable($codCountry);
			$this->view->aDataDist = $cDistribuidores->getData($sDistribuidor);
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }   	
    }
    
    public function petsAction(){
    	try{
    		$cProducts  = new My_Model_Productos();   
    		$cClients   = new My_Model_Clientes();
			$cFunctions = new My_Controller_Functions(); 	
			$cDistribuidores=  new My_Model_Distribuidores();
			$cTrackers	= new My_Model_Trackers();
			$cMascotas	= new My_Model_Mascotas();
			$cPedidos	= new My_Model_Pedidos();
			
			$codCountry    = (isset($this->_dataIn['strCountry']) && $this->_dataIn['strCountry']!="") ? $this->_dataIn['strCountry']: $this->_dataUser['cod_pais'];
			$sDistribuidor = $this->_dataUser['id_distribuidor'];	
			
			$aNamespace = new Zend_Session_Namespace("sService");
						
			if(isset($aNamespace->aDataPet)){
				$aDataProducts	= $aNamespace->aDataPet;					
			}else{
				$this->_redirect('/distribuitor/sales/products');					
			}
			
			$aAllProducts = Array();
			foreach ($aDataProducts as $key => $items){							
				for($i=0;$i<$items['count'];$i++){
					$aData = Array();
					$aData['id'] 	= $items['id'];
					$aData['data'] 	= $items['data'];  
					$aAllProducts[] = $aData; 
				}
			}
			
    		if($this->_dataOp=='step3'){
    			$dataClient = $aNamespace->dataUser;
				$dataClient['inputDistribuidor'] = $sDistribuidor;
				$dataClient['inputPais']     	 = $codCountry;
				
    			$insertClient = $cClients->insertRow($dataClient);
    			$aErrors=0;
    			if($insertClient['status']){
    			    foreach($aAllProducts as $key => $items){
    			    	$idTracker = $this->_dataIn['selectCant'.$items['id']];    			    	
    			    	$aInsertPet = Array();
    			    	$aInsertPet['inputCliente'] 	 = $insertClient['id'];
    			    	$aInsertPet['inputDistribuidor'] = $sDistribuidor;
    			    	$aInsertPet['inputNombre']		 = 'Mi Mascota';
    			    	$aInsertPet['inputTracker']		 = $idTracker;
    			    	$aInsertPet['inputTracker']		 = $idTracker;   			    	
    					$insertPet = $cMascotas->insertRow($aInsertPet);
    					if(!$insertPet['status']){
    						$aErrors++;						
    					}    				
    				}   
    			}
    			
    			
    			/**
    			 * Insertar el pedido 
    			 */
    				$strPedido = '';
    				$iPeso     = '';
    				$iTotal    = '';
    				$iTotalP   = 0;
    				$aDataDist = $cDistribuidores->getData($sDistribuidor);
	    			foreach ($aDataProducts as $key => $items){
	    				$iTotalP   += $items['count'];
	    				$iPrecio    = $aDataDist['precio_distribuidor'];
	    				$pesoProd   = $items['data']['prod_peso']*$items['count'];
	    				$iSubTotal  = $items['count']*$iPrecio;
	    				  
	    				$strPedido .= 'Clave: '.$items['data']['prod_clave'].'\n';
	    				$strPedido .= 'Producto:'.$items['data']['prod_titulo'].'\n';
	    				$strPedido .= 'Cantidad:'.$items['count'].'\n';
	    				$strPedido .= utf8_encode('Precio Publico: $').number_format($iPrecio,2).'\n';
	    				$strPedido .= 'Subtotal: $'.number_format($iSubTotal,2).'\n\n';
	    				$iTotal    += $iSubTotal;
	    				$iPeso     += $pesoProd;
					}
					
					$strPedido .= 'Peso: '.number_format($iPeso,0,'.',',').' grms\n';
					$strPedido .= utf8_encode('Cargo por envio: $ 0.00\n');
					$strPedido .= 'IVA: $ 0.00\n';
					$strPedido .= 'Total:$ '.number_format($iTotal,2).'\n';
    									
					$aDataPedido = Array();
					$aDataPedido['inputCliente'] = $insertClient['id'];
					$aDataPedido['inputPedido']  = $strPedido;
					$aDataPedido['inputTotal']   = $iTotal;
					$aDataPedido['inputPeso']    = $iPeso;
					$aDataPedido['inputPay'] 	 = 1;
					$aDataPedido['inputCantidad']= $iTotalP;
					$aDataPedido['codeCountry']  = $codCountry;
										
					$insertPedido = $cPedidos->insertRow($aDataPedido);
					if(!$insertClient['status']){
						$aErrors++;	
					}
 			
				
    			if($aErrors==0){
					$aNamespace = new Zend_Session_Namespace("sService");
		
		    		if(isset($aNamespace->dataUser)){
						unset($aNamespace->dataUser);
					}			
		    	    if(isset($aNamespace->dataProducts)){
						unset($aNamespace->dataProducts);
					}		
    				if(isset($aNamespace->aDataPet)){
						unset($aNamespace->aDataPet);
					}						
					$this->_redirect('/distribuitor/sales/finish');		
    			}
			}			
			
			
			$aAllTrackers = $cTrackers->getFreeTrackersCbo($sDistribuidor);
			$this->view->aProducts = $aAllProducts;
			$this->view->iCounts   = count($aAllProducts);
			$this->view->aTrackers = $cFunctions->selectDb($aAllTrackers);			
    	} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        } 	
    }
    
    
    public function finishAction(){
    	
    }  
    
    public function cancelAction(){
    	try{
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();        		
			$aNamespace = new Zend_Session_Namespace("sService");

    		if(isset($aNamespace->dataUser)){
				unset($aNamespace->dataUser);
			}			
    	    if(isset($aNamespace->dataProducts)){
				unset($aNamespace->dataProducts);
			}			
			$this->_redirect('/distribuitor/sales/index');				
    	} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        } 	     	
    }  

    public function moreinfoAction()
    {
    	try{
	        $this->view->layout()->setLayout('layout_blank');
	        	        
	        $cPedidos  = new My_Model_Pedidos();
	        $this->view->Order   = $cPedidos->getInfoOrder($this->_idUpdate);	
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }   	
    }    
}