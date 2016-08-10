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
Class Template {
/*
 * @the registry
 * @access private
 */
private $registry;

/*
 * @Variables array
 * @access private
 */
	public $var = array();

/**
 *
 * @constructor
 *
 * @access public
 *
 * @return void
 *
 */
function __construct() {
	//$this->registry = $registry;
}


 /**
 *
 * @set undefined vars
 *
 * @param string $index
 *
 * @param mixed $value
 *
 * @return void
 *
 */
 public function __set($index, $value){
        $this->var[$index] = $value;
 }

function show($group,$controller,$view,$param=null) {
	$base_path = VIEW_PATH;
	$main_path = '';
	//if(empty($group)){
	//	$main_path = $base_path.$controller. '/' . $view . '.html';
	//}else{
		$base_path .= $group."/";
		$main_path = $base_path."layout.html";
	//}
	//echo $main_path;
	if (file_exists($main_path) == false){
		throw new Exception('Template not found in '. $main_path);
		return false;
	}else{
		$use = new stdClass(); 
		$use->group = $group;
		$use->controller = $controller;
		$use->action = $view;

		// Load variables
		foreach ($this->var as $key => $value){
			$use->var[$key] = $value;
		}

		$view_path = $base_path.$controller. '/' . $view . '.html';
		if (file_exists($view_path)) {
			include ($main_path);
		}else{
			common::Redirect('auth','pagenotfound');
		}
	}
}
}

?>
