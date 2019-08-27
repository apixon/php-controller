<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    var $components = array('Email'); // Not necessary if declared in your app controller
     
    public function beforeFilter() {
        $this->layout = 'default';
         //   parent::beforeFilter();
    }
    
     /*
    *Function for sending email 4_28_2014
    */
    
    function send_email($replace_fields=array(),$replace_with=array(),$email_template=null,$from=null,$to=null,$reply_to=null){
	
        $this->Email->delivery = MAIL_DELIVERY;//possible values smtp or mail
        $this->Email->smtpOptions = array('host' => SMTP_HOST,'username' => SMTP_USERNAME,'password' =>SMTP_PWD,'port'=>SMTP_PORT);
        App::import('Model','EmailTemplate');
	$this->EmailTemplate = new EmailTemplate();
        $template = $this->EmailTemplate->find("first",array("conditions"=>array('EmailTemplate.slug'=>$email_template)));
        $template_data = $template['EmailTemplate']['description']; 
        $template_info = str_replace($replace_fields,$replace_with,$template_data);
        $this->set('data',$template_info);
	$this->Email->to = $to;
        $this->Email->subject = $template['EmailTemplate']['subject'];
        if(!is_null($from) && trim($from)!=""){		
            $this->Email->from = $from;
        }
        else{
            $this->Email->from = false;
            $this->Email->fromName = false;
        }
        $this->Email->template = 'email_template';
        $this->Email->replyTo  = $reply_to;
        $this->Email->sendAs = 'both';
	
        if($this->Email->send()){
            return true;
        }else{
            return false;
        }
    }
}

