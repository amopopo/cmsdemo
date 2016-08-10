<?php

Class baseController Extends loginController {
	public $param = array();
	public $group;
	public $controller;
	public $action;
	
 function __construct($group,$controller,$action) {
	$this->group = $group;
	$this->controller = $controller;
	$this->action = $action;
	$this->var['layout_pagetitle'] = '';
	$this->beforeFilter();
	if(!isset($_SESSION['auth'])){
		$mysessionvar = new StdClass();
		$mysessionvar->date_format = "d/m/Y";
		$_SESSION['auth'] = $mysessionvar;
	}
}

 public function __set($index, $value){
	$this->vars[$index] = $value;
 }
 
 public function setParam($value){
	$this->param = $value;
 }
 
 function beforeFilter(){}
 
 function locked(){
 	$settings = new sys_settings();	
	$settings = $settings->find_all();
	$settings = array_pop($settings);
	if(!empty($settings) && $settings->lock_website==1){
		$this->frontloginRequired();
	}
 }
 
 function buildMenu(){
 	$this->var['cur_controller'] = $this->controller;
 	$this->var['mainmenu'] = array();
	$this->var['displaymenu'] = array();
	$this->var['displaygrp'] = array();
	$pregrp = ''; $curgrp = '';
	$permissions = array();
	$permissions = new sys_user;
	if(isset($_SESSION['auth']) && isset($_SESSION['auth']->id) && !empty($_SESSION['auth']->id)){
		if(OTGROUP=='' && OTCONTROLLER=='auth'){
			$permissions = $permissions->FindAllPermission($_SESSION['auth']->id,'home');
		}else{
			$permissions = $permissions->FindAllPermission($_SESSION['auth']->id,OTGROUP);
		}
	}

	if(!empty($permissions)){
		$this->var['mainmenu'] = $permissions;
		
		foreach($permissions as $key=>$value){ 
			if($value->controller == $this->controller){$this->var['cur_controller'] = $value->name;}
			if($value->menu_grp != ''){$this->var['displaygrp'][$value->controller]=$value->menu_grp;}
			$curgrp = $value->menu_grp;
			if($curgrp=='' || $pregrp != $curgrp){
				array_push($this->var['displaymenu'],$value);
			}
			$pregrp = $curgrp;
		}
	}
 }
 
 function seo(){
	$this->var['mkmetakey'] = '';
	$this->var['mkmetadesc'] = '';

	$this->var['mksyssettings'] = new sys_settings();
	$this->var['mksyssettings'] = $this->var['mksyssettings']->find_all();
	$this->var['mksyssettings'] = array_pop($this->var['mksyssettings']);
	if(!empty($this->var['mksyssettings'])){ 
		if(!empty($this->var['mksyssettings']->metadesc)){$this->var['mkmetadesc'] = $this->var['mksyssettings']->metadesc;}
		if(!empty($this->var['mksyssettings']->metakey)){$this->var['mkmetakey'] = $this->var['mksyssettings']->metakey;}
	}
 }
 
 
/**
 * @all controllers must contain an index method
 */
//abstract function index();
}

?>
