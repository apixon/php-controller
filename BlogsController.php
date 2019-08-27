<?php  
App::uses('AppController', 'Controller');
 
class BlogsController extends AppController {
 
	public $uses = array();
	
	public function beforeFilter() {
            parent::beforeFilter();
        }
	
	public function index() { 
		$link = HTTP_HOST."admin/users/reset_password/";
		$email ='info@apixon.com';
		$this->send_email(array("{PASSLINK}"),array($link),"requestaquote",EMAIL_NOTIFICATION,$email,NOREPLY_EMAIL);
		
		//$this->redirect(array('controller'=>'home','action'=>'index'));exit();
	}
}
