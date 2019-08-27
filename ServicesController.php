<?php  
App::uses('AppController', 'Controller');
 
class ServicesController extends AppController {
 
    public $uses = array();
    var $components = array('Common');
    
    public function beforeFilter() {
            parent::beforeFilter();
	    $this->set('random_keywords',$this->Common->random_keyword());
    }
    
    public function index() { 
    }
    public function webdesign() {
    }
    public function cms() {
    } 
    public function ecommerce() {
    }  
    public function seo() {
    } 
    public function blog() {
    } 
    public function social() {
    }
    
    
}

?>