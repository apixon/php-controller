<?php  
//App::uses('AppController', 'Controller');
 
class HomeController extends AppController {
 
	public $uses = array();
	
	public function beforeFilter() {
            parent::beforeFilter();
        }
	
	public function index() {
		$this->set('title_for_layout', 'Apixon - Your Dream is Our Design - Website Designing, Software Development India,Punjab, Jalandhar, hoshiarpur, chandigarh, ludhiana, Kapurthala, New Delhi,amritsar ,mumbai website development, website redesigning ,Search Engine Ranking, SEO , website designer jalandhar,website designers jalandhar, logo, wedding albums, wedding website, printing solutions, corporate identity, event management jalandhar, ecommerce ,e commerce');
		App::Import('Model','ProjectDetail');
		$ProjectCategory = new ProjectDetail();
	    
		$ProjectCategory=$ProjectCategory->find('all',array('conditions' => array('ProjectDetail.featured'=> "1","ProjectDetail.is_deleted"=>0),'order'=>array('ProjectDetail.project_order' => 'asc' ) )); 
		 $this->set(compact('ProjectCategory'));
	}
}
