<?php  
 App::uses('AppController', 'Controller');
 
class CompanyController extends AppController {
 
	public $uses = array();
        public $components = array('Securimage', 'Session','Common');
	
        public function beforeFilter() {
            parent::beforeFilter();
        }


 
	public function aboutus() {
	}
        
        public function portfolio() {
	    App::Import('Model','ProjectCategory');
	    $ProjectCategory = new ProjectCategory();
	    $conditions = array("ProjectCategory.is_deleted"=>0);
	    $ProjectCategory=$ProjectCategory->find('all',array('conditions' => $conditions,'order'=> array('ProjectCategory.category_order' => 'asc')));
	    $this->set(compact('ProjectCategory'));
	    
	    App::Import('Model','ProjectDetail');
	    $this->ProjectDetail = new ProjectDetail();
	    $ProjectEcommerce=$this->ProjectDetail->find('all',array("recursive"=>-1,'conditions' => array("ProjectDetail.is_deleted"=>0,"ProjectDetail.is_ecommerce"=>1)));
	    $ProjectEcommerce = Set::classicExtract($ProjectEcommerce,'{n}.ProjectDetail');
	    $this->set(compact('ProjectEcommerce'));  
	
	    $ProjectResponsive=$this->ProjectDetail->find('all',array("recursive"=>-1,'conditions' => array("ProjectDetail.is_deleted"=>0,"ProjectDetail.is_responsive"=>1)));
	    $ProjectResponsive = Set::classicExtract($ProjectResponsive,'{n}.ProjectDetail');
	    $this->set(compact('ProjectResponsive'));
	}
        
        public function project($slug = null) {
		if (!$slug){ 
			$this->redirect('/portfolio');exit();
		}else{
			App::Import('Model','Project detail');
			$this->ProjectDetail = new ProjectDetail();
			$conditions = array("ProjectDetail.is_deleted"=>0,"ProjectDetail.slug" => $slug);
			$is_valid =$this->ProjectDetail->find('count',array('conditions' => $conditions , 'recursive' => -1 ) );
			if($is_valid > 0){
				$this->set('title_for_layout', 'Project');
				$p_info=$this->ProjectDetail->find('first' ,array('conditions' => $conditions,'limit'=>1));
				$this->set('p_info',$p_info);
				$this->ProjectDetail->id =$p_info['ProjectDetail']['id'];
				  
				$n_info=$this->ProjectDetail->find('neighbors' ,array('conditions' => array("ProjectDetail.is_deleted"=>0) ));
				if(isset($n_info['prev']['ProjectDetail']['slug'])){
					$this->set('pr_slug',$n_info['prev']['ProjectDetail']['slug']);
				}else{ $this->set('pr_slug','#');  }
				
				if(isset($n_info['next']['ProjectDetail']['slug'])){
					$this->set('nx_slug',$n_info['next']['ProjectDetail']['slug']);
				}else{ $this->set('nx_slug','#'); }
				
			}else{
				$this->redirect('/portfolio');exit();
			}
		} 
	} 
        
        public function career() {	
	   $this->set('random_keywords',$this->Common->random_keyword());
	}
	
        public function team() {
	}
        
        public function training() {
	    $this->set('random_keywords',$this->Common->random_keyword());	
	}
        
        public function contactus() {
		  App::Import('Model','Contact');
		  $Contact = new Contact();
		     
		 if ($this->request->is('post')) { 
			$errors =  $Contact->validate_data($this->data['Contact']);  
			if(count($errors) == 0){
				if($this->Securimage->check($this->data['Contact']['captcha_code']) != false) {
				    $result = $Contact->save_data($this->data);
				    if($result){
					$date =  date('F d Y');
					$date_time =  date('F d Y h:i:s');
					$email = $this->data['Contact']['email'];
					$name = ' '.ucfirst($this->data['Contact']['firstname']);
					$subject = $this->data['Contact']['subject'];
					$message = $this->data['Contact']['message'];
					
					$this->send_email(array("{NAME}","{PHONE}","{EMAIL}","{DATE}"),array($name,PHONE,EMAIL,$date),"autoresponder",EMAIL_NOTIFICATION,$email,NOREPLY_EMAIL);
					$this->send_email(array("{NAME}","{M_EMAIL}","{SUBJECT}","{MESSAGE}","{EMAIL}","{PHONE}","{DATE}"),array($name,$email,$subject,$message,EMAIL,PHONE,$date_time),"contactus",EMAIL_NOTIFICATION,EMAIL_SITE_ALERT,NOREPLY_EMAIL);
					$this->Session->setFlash('<i class="fa fa-check fa-fw"></i> <strong>Your message has been sent successfully.</strong>', 'message/green'); 
					$this->redirect('/contactus');
					
				    } else {  
					$this->set("errors",$errors);
					$this->Session->setFlash('<i class="fa fa-times fa-fw"></i> <strong>Please enter all the mandatory fields correctly.</strong>', 'message/red'); 
				    }
				}else{
					$this->Session->setFlash('<i class="fa fa-times fa-fw"></i> <strong>Please enter valid security code correctly.</strong>', 'message/red'); 
				}     
			} else {  
				    $this->set("errors",$errors);
				$this->Session->setFlash('<i class="fa fa-times fa-fw"></i> <strong>Please enter all the mandatory fields correctly.</strong>', 'message/red'); 
			}
		     
		 }
		//pr($Contactus);
	}
	
	public function requestaquote(){
		App::Import('Model','Quote');
		$this->Quote = new Quote();
	      
		if ($this->request->is('post')) { 
			$errors =  $this->Quote->validate_data($this->data['Quote']);  
			if(count($errors) == 0){
				if($this->Securimage->check($this->data['Quote']['captcha_code']) != false) {
					if(isset($this->data['Quote']['services'])){
					$services = $this->data['Quote']['services'];
					if(is_array($services)){
						$s = '';
						foreach($services as $val){
							$s .= $val.',';								
						} 
						$this->request->data['Quote']['services'] =  trim($s,','); 
						}
					}
					//pr($this->data);die;
					$result = $this->Quote->save_data($this->data);
					if($result){
						$email = $this->data['Quote']['email'];
						$date =  date('F d Y');
						$date_time =  date('F d Y h:i:s');
						$name = ' '.ucfirst($this->data['Quote']['first_name']); 
						$srvices = $this->request->data['Quote']['services']; 
						$this->send_email(array("{NAME}","{PHONE}","{EMAIL}","{DATE}"),array($name,PHONE,EMAIL,$date),"autoresponder",EMAIL_NOTIFICATION,$email,NOREPLY_EMAIL);
						$this->send_email(array("{FIRST_NAME}","{LAST_NAME}","{M_EMAIL}","{M_PHONE}","{SKYPE}","{BUDGET}","{SUBJECT}","{PRIORITY}","{INTERESTED}","{MESSAGE}","{PHONE}","{EMAIL}","{DATE}"),array($this->data['Quote']['first_name'],$this->data['Quote']['last_name'],$this->data['Quote']['email'],$this->data['Quote']['phone_no'],$this->data['Quote']['skype_im'],$this->data['Quote']['budget'],$this->data['Quote']['subject'],$this->data['Quote']['priority'],$this->request->data['Quote']['services'],$this->data['Quote']['message'],PHONE,EMAIL,$date_time),"quote",EMAIL_NOTIFICATION,EMAIL_SITE_ALERT,NOREPLY_EMAIL);
						$this->Session->setFlash('<i class="fa fa-check fa-fw"></i> <strong>Your Message has been sent and is valuable to us. You will soon hear from one of our experts.</strong>', 'message/green'); 
						$this->redirect('/requestaquote');
					} else {  
						$this->set("errors",$errors);
						$this->Session->setFlash('<i class="fa fa-times fa-fw"></i> <strong>Please enter all the mandatory fields correctly.</strong>', 'message/red'); 
					} 	
				}else{
					$this->Session->setFlash('<i class="fa fa-times fa-fw"></i> <strong>Please enter valid security code correctly.</strong>', 'message/red'); 
				} 
				
			} else {  
				$this->set("errors",$errors);
				$this->Session->setFlash('<i class="fa fa-times fa-fw"></i> <strong>Please enter all the mandatory fields correctly.</strong>', 'message/red'); 
			}
		 
		}
		$this->set('random_keywords',$this->Common->random_keyword());
	}
        
        
}
