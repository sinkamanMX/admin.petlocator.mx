<?php
class admin_BannersController extends My_Controller_Action
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
    
   public function getinfoAction(){
    	try{
    		$cPaises     = new My_Model_Paises();
    		$classObject = new My_Model_Banners();
    		$cFunctions  = new  My_Controller_Functions();
    		$dataInfo	 = Array();
    		$sEstatus	 = '';    		    		
			$codeCountry = '';
			
    		if(isset($this->_dataIn['codeCountry'])){
    			$codeCountry = $this->_dataIn['codeCountry'];	
    		}else{
    			$codeCountry = $this->_dataUser['cod_pais'];
    		}    	

    		if($this->_idUpdate!="-1"){
				$dataInfo = $classObject->getData($this->_idUpdate);
				$sEstatus = $dataInfo['estatus'];
			}
			
			if($this->_dataOp=='new'){				
				$sNameImage = '';				
				if($_FILES['imageProfile']['name']!=""){
		            $allowedExts = array("jpeg", "jpg", "png");
					$temp = explode(".", $_FILES["imageProfile"]["name"]);
					$extension = end($temp);

					$newNameimage = Date("Ymdhis").".".$extension;
					if ((($_FILES["imageProfile"]["type"] == "image/jpeg")
						|| ($_FILES["imageProfile"]["type"] == "image/jpg")
						|| ($_FILES["imageProfile"]["type"] == "image/pjpeg")
						|| ($_FILES["imageProfile"]["type"] == "image/x-png")
						|| ($_FILES["imageProfile"]["type"] == "image/png"))
						&& ($_FILES["imageProfile"]["size"] < 10485760)
						&& in_array($extension, $allowedExts)) {
								
						  if ($_FILES["imageProfile"]["error"] > 0) {
						  		$this->_aErrors['errorImage'] = 1;
						  }else{
							  $sNameImage  = $newNameimage;

							  $upload = move_uploaded_file($_FILES["imageProfile"]["tmp_name"],  "/var/www/vhosts/test/htdocs/assets/images/banners/" . $newNameimage);
							  if(!$upload){
								$this->_aErrors['errorImage'] = 1;  	
							  }else{
							  	$nameDelete = "/var/www/vhosts/test/htdocs/assets/images/banners/".$dataInfo['IMAGEN'];							  	
							  	if(file_exists($nameDelete)){
							  		unlink($nameDelete);	
							  	}							  	
							  	$this->_dataIn['nameImagen'] = $sNameImage;
							  }			
						  }
					}else {
					  $this->_aErrors['errorImage'] = 1;
					}										
	            }	
							
				if(count($this->_aErrors)==0){
	            	$this->_dataIn['nameImagen'] = ($sNameImage!="") ? $this->_dataIn['nameImagen'] : '';
					$insert = $classObject->insertRow($this->_dataIn);
					if($insert['status']){
						$this->_idUpdate = $insert['id'];
						$dataInfo = $classObject->getData($this->_idUpdate);
						$sEstatus = $dataInfo['estatus'];
						$this->_resultOp= 'okRegister';
					}else{
						$this->_aErrors['status'] = 'no-info';
					}            	
	            }	            										
			}elseif($this->_dataOp=='update'){
				$sNameImage = '';				
				if($_FILES['imageProfile']['name']!=""){
		            $allowedExts = array("jpeg", "jpg", "png");
					$temp = explode(".", $_FILES["imageProfile"]["name"]);
					$extension = end($temp);

					$newNameimage = Date("Ymdhis").".".$extension;
					if ((($_FILES["imageProfile"]["type"] == "image/jpeg")
						|| ($_FILES["imageProfile"]["type"] == "image/jpg")
						|| ($_FILES["imageProfile"]["type"] == "image/pjpeg")
						|| ($_FILES["imageProfile"]["type"] == "image/x-png")
						|| ($_FILES["imageProfile"]["type"] == "image/png"))
						&& ($_FILES["imageProfile"]["size"] < 10485760)
						&& in_array($extension, $allowedExts)) {
								
						  if ($_FILES["imageProfile"]["error"] > 0) {
						  		$this->_aErrors['errorImage'] = 1;
						  }else{
							  $sNameImage  = $newNameimage;

							  $upload = move_uploaded_file($_FILES["imageProfile"]["tmp_name"],  "/var/www/vhosts/test/htdocs/assets/images/banners/" . $newNameimage);
							  if(!$upload){
								$this->_aErrors['errorImage'] = 1;  	
							  }else{
							  	$nameDelete = "/var/www/vhosts/test/htdocs/assets/images/banners/".$dataInfo['IMAGEN'];							  	
							  	if(file_exists($nameDelete)){
							  		unlink($nameDelete);	
							  	}							  	
							  	$this->_dataIn['nameImagen'] = $sNameImage;
							  }			
						  }
					}else {
					  $this->_aErrors['errorImage'] = 1;
					}										
	            }	
	            
				if(count($this->_aErrors)==0){
	            	$this->_dataIn['nameImagen'] = ($sNameImage!="") ? $this->_dataIn['nameImagen'] : '';
					$insert = $classObject->updateRow($this->_dataIn);
					if($insert['status']){
						$dataInfo = $classObject->getData($this->_idUpdate);
						$sEstatus = $dataInfo['estatus'];
						$this->_resultOp= 'okRegister';
					}else{
						$this->_aErrors['status'] = 'no-info';
					}            	
	            }	            				
			}			
								
			$this->view->aStatus = $cFunctions->cboStatus($sEstatus);
			$this->view->data 	 = $dataInfo;
			$this->view->dataIn  = $this->_dataIn;
			$this->view->idToUpdate = $this->_idUpdate;
			$this->view->resultOp= $this->_resultOp;
    		$this->view->dataCountry	 = $cPaises->getData($codeCountry);    		
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  	   		
   }    
    
}