<?php
/**
 * App_Controller Class
 *
 * License : Cannot be use commercially or privately without author (CHEW LEE TING) consent/acknowledgement/permission. Author retained all rights.
 * 
 * Copyright (c) 2011, mikyadesign.com
 * All rights reserved.
 *
 *	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS 
 *	IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, 
 *	THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR 
 *	PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR 
 *	CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, 
 *	EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, 
 *	PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR 
 *	PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF 
 *	LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING 
 *	NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS 
 *	SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author	CHEW LEE TING <miya@mikyadesign.com>
 * @copyright	2011 mikyadesign.com
 * @version     mkcms v1.1
 * Latest modification date : 15 June 2011
 *
 */
class router {
 /*
 * @the registry
 */
 //private $registry;
 private $template;
 /*
 * @the controller path
 */
 private $path;

 private $args = array();

 public $file;
 public $group;
 public $controller;
 public $action; 
 public $mainHighlight;
 public $params = array();

 function __construct($template) {
      //  $this->registry = $registry;
	  $this->template = $template;
 }

 /**
 *
 * @set controller directory path
 *
 * @param string $path
 *
 * @return void
 *
 */
 function setPath($path) { //echo $path;
	/*** check if path i sa directory ***/
	if (is_dir($path) == false)
	{
		throw new Exception ('Invalid controller path: `' . $path . '`');
	}
	/*** set the path ***/
 	$this->path = $path;
}



 /**
 *
 * @load the controller
 *
 * @access public
 *
 * @return void
 *
 */
 public function loader()
 {
	/*** check the route ***/
	$this->getController();
	
	/*** if the file is not there diaf ***/
	if (is_readable($this->file) == false){
        $this->controller = 'auth';
		$this->action = "pagenotfound";
		$this->file = $this->path .'/'. $this->controller . 'Controller.php';
	}

	/*** include the controller ***/
	include $this->file;

	/*** a new controller class instance ***/
	if(!defined('OTGROUP')){ define('OTGROUP', $this->group); }
	if(!defined('OTCONTROLLER')){define('OTCONTROLLER', $this->controller);}
	if(!defined('OTACTION')){define('OTACTION', $this->action);}
	$paramArr = '';
	if(!empty($this->params) && is_array($this->params)){
		$paramArr =  implode('/',$this->params);
	}
	if(!defined('OTPARAMS')){define('OTPARAMS',$paramArr );}
	
	$class = $this->controller . 'Controller';
	//$controller = new $class($this->registry,$this->group,$this->controller,$this->action);
	$controller = new $class($this->group,$this->controller,$this->action);
	$controller->setParam($this->params);
	$grouphelper_file = HELPER_PATH.$this->group.'_helpers.php';
	$helper_file = HELPER_PATH.$this->group.'/'.$this->controller.'_helpers.php';
	if (file_exists($grouphelper_file) && is_readable($grouphelper_file)){ include($grouphelper_file);}
	if (file_exists($helper_file) && is_readable($helper_file)){ include($helper_file);}

	/*** check if the action is callable ***/
	if (is_callable(array($controller, $this->action)) == false){
		common::redirect('auth','pagenotfound');
	}else{
		$action = $this->action;
	}

	/*** run the action ***/	
	$controller->$action();

	/*** show in template ***/
	$this->template->var = $controller->var;
	$this->template->mainHighlight = $this->mainHighlight;
	$this->template->show($this->group,$this->controller,$this->action);
 }
 
 /**
 *
 * @get the controller
 *
 * @access private
 *
 * @return void
 *
 */
private function getController() {
	/*** get the route from the url ***/
	//$route = (empty($_GET['rt'])) ? '' : $_GET['rt'];
	if(empty($_GET['rt'])){
		$route = DEFAULT_CONTROLLER;
	}else{
		$route = $_GET['rt'];
	}
	$this->group = "admin";
	$this->controller = "dashboard";
	$this->action = "index";
	$this->mainHighlight = "home";

	if (!empty($route)){
		$route = rtrim($route,'/');
		$param = explode('/', $route);	
		if(isset( $param[0]) && !empty($param[0])){
			switch($param[0]){
				case 'login':
					$login = new loginController();
					if(!defined('OTGROUP')){ define('OTGROUP', 'login');}
					if(!defined('OTCONTROLLER')){ define('OTCONTROLLER', '');}
					if(!defined('OTACTION')){ define('OTACTION', '');}
					if(!defined('OTPARAMS')){ define('OTPARAMS', '');}
					$login->loginRequired();
					break;
				case 'auth':
					$this->group = "";
					$this->controller = "auth";
					array_shift($param);
					if(isset( $param[0]) && !empty($param[0])){
						$this->action = $param[0];
						array_shift($param);
					}else{
						$this->action = "pagenotfound";
					}
					break;
				default : 
					if($param[0]=="home"||$param[0]=="admin"){
						$this->group = $param[0];
						array_shift($param);
						if(isset( $param[0]) && !empty($param[0])){
							$this->controller = $param[0];
							array_shift($param);
						}else{
							//if($this->group=='admin'){
							//	$this->controller = 'dashboard';
							//}
							$this->controller = "";
							$this->action = "";
						}
						if(isset( $param[0]) && !empty($param[0])){
							$this->action = $param[0];
							array_shift($param);
						}
					}else{
						$this->group = 'home';
						if($param[0]=="categories"){
							$this->controller = $param[0];
							$this->mainHighlight  = $this->controller;
							array_shift($param);
							if(isset( $param[0]) && !empty($param[0])){
								$this->action = "show";
								$this->mainHighlight  = $param[0];
							}
						}else{
							$this->controller = "pages";
							if(isset( $param[0]) && !empty($param[0])){
								$this->action = "show";
								//$this->action = $param[0];
								$this->mainHighlight  = $param[0];
								//array_shift($param);
							}
						}
					}
					break;
			}//end switch
		}
	}

	if(!empty($param)){
		$this->params = $param;
	}

	/*** set the file path ***/
	$this->file = $this->path .'/';
	if(!empty($this->group)){$this->file .= $this->group .'/';}
	$this->file .= $this->controller . 'Controller.php';
}

}
?>