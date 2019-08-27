<?php
class CommonComponent extends Component {
    
     public function random_keyword(){
	 App::Import('Model','Technology');
	 $this->Technology = new Technology();
	 
	 return $this->Technology->find('all', array( 
		  'conditions' => array('Technology.is_deleted' => '0'), 
		  'order' => 'rand()',
		  'limit' => KEYWORD_LIMIT,
	  )) ;
	 
     }
}


?>