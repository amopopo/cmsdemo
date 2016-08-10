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
Class rolesController Extends baseController {
	function beforeFilter(){
		$this->loginRequired();
		$this->buildMenu();
		$this->var['errors'] = '';
	}

	function index() {
		$sys_role = new sys_role();
		$this->var['roles'] = $sys_role->find_all('Admin');
	}
	
	function add(){
		$permission = new sys_permission();
		$this->var['permissions'] = $permission->find_all('admin');
		$this->var['menugrp_arr'] = array();
		$arr_permission = array();
		
		$pre_org = '';$cur_org = '';
		if(!empty($this->var['permissions'])){ 
		
		foreach($this->var['permissions'] as $ko=>$vo){
			if($vo->set_default ==0){ 
				if($vo->menu_grp==''){$vo->menu_grp=$vo->controller;}
				$cur_org = $vo->menu_grp; 
				if($pre_org != $cur_org){ 
					array_push($this->var['menugrp_arr'],$vo->menu_grp);
				 } 
				$pre_org = $vo->menu_grp;
			}else{
				array_push($arr_permission,$vo->id);
			}
		}}//end for.if

		if(common::setPost('save')=="Save"){
			$role = new sys_role();
			$new_role = $role->Create('sys_role',array('name'=>common::setPost('name'),
								'role_type'=>'Admin','no_delete'=>0,'deleted'=>0,
								'max_photo'=>-1,'max_video'=>-1,
								'date_created'=>date("Y-m-d H:m:s"),"created_by"=>$_SESSION['auth']->id,
								'date_modified'=>date("Y-m-d H:m:s"),"modified_by"=>$_SESSION['auth']->id
							));
			if($new_role->save() == false){
				$this->var['errors'] = $new_role->get_errors();
			}else{
				$name = common::setPost('name');
				$result = $role->find_by_name($name,'Admin');
				$result = array_pop($result);
				if(!empty($result)){	
					$id = $result->id;
					foreach($this->var['menugrp_arr'] as $k=>$vo){
						if(isset($_POST['PER_'.$vo]) && !empty($_POST['PER_'.$vo])){
							$arr_permission = array_merge($arr_permission,$_POST['PER_'.$vo]);
						}
					}

					foreach($arr_permission as $key=>$value){
						$role->update_permission($id,$value);	
					}
				}
				common::Redirect($this->group.'/'.$this->controller,'index');
			}
		}
	}
	
	function edit(){
		if(isset($this->param[0]) && !empty($this->param[0])){
			$sys_role = new sys_role();
			$this->var['role'] = $sys_role->find_by_id($this->param[0],'Admin');
			$this->var['role'] = array_pop($this->var['role']);
			if(!empty($this->var['role']) && $this->var['role']->no_delete==0 && $this->var['role']->deleted==0){
				$name = $this->var['role']->name;
				$permission = new sys_permission();
				$this->var['permissions'] = $permission->find_all('admin');
				$this->var['menugrp_arr'] = array();
				$arr_permission = array();
	
				$pre_org = '';$cur_org = '';
				if(!empty($this->var['permissions'])){ 
				foreach($this->var['permissions'] as $ko=>$vo){
					if($vo->set_default ==0){ 
						if($vo->menu_grp==''){$vo->menu_grp=$vo->controller;}
						$cur_org = $vo->menu_grp; 
						if($pre_org != $cur_org){ 
							array_push($this->var['menugrp_arr'],$vo->menu_grp);
						 } 
						$pre_org = $vo->menu_grp;
					}else{
						array_push($arr_permission,$vo->id);
					}
				}}//end for.if

				$cur_permission = $sys_role->find_permission_nonDefault($this->var['role']->id,'Admin');
				foreach($cur_permission as $k=>$v){ 
					if(!isset($this->var['PER_'.$v->menu_grp])){
						$this->var['PER_'.$v->menu_grp] = array();
					}
					array_push($this->var['PER_'.$v->menu_grp],$v->sys_permission_id);
				}

				if(common::setPost('save')=="Save"){
					$role = new sys_role();

					if($this->var['role']->save_edit($name) == false){
						$this->var['errors'] = $this->var['role']->get_errors();
					}else{
						/*DELETE PERMISSION*/
						$role->delete_permission($this->var['role']->id);

						foreach($this->var['menugrp_arr'] as $k=>$vo){
							if(isset($_POST['PER_'.$vo]) && !empty($_POST['PER_'.$vo])){
								$arr_permission = array_merge($arr_permission,$_POST['PER_'.$vo]);
							}
						}
	
						foreach($arr_permission as $key=>$value){
							$role->update_permission($this->var['role']->id,$value);	
						}
		
						$this->var['role']->name = common::setPost('name');
						$this->var['role']->date_modified = date("Y-m-d H:m:s");
						$this->var['role']->modified_by = $_SESSION['auth']->id;
						$this->var['role']->update_me();
						
						common::Redirect($this->group.'/'.$this->controller,'index');
					}
				}
			}else{
				common::Redirect('auth','pagenotfound');
			}
		}else{
			common::Redirect('auth','pagenotfound');
		}
	}
	
	function view(){
		if(isset($this->param[0]) && !empty($this->param[0])){
			$sys_role = new sys_role();
			$this->var['role'] = $sys_role->find_by_id($this->param[0],'Admin');
			$this->var['role'] = array_pop($this->var['role']);
			if(!empty($this->var['role']) && $this->var['role']->no_delete==1&& $this->var['role']->deleted==0){
				$name = $this->var['role']->name;
				$permission = new sys_permission();
				$this->var['permissions'] = $permission->find_all('admin');
				$this->var['menugrp_arr'] = array();

				$pre_org = '';$cur_org = '';
				if(!empty($this->var['permissions'])){ 
				foreach($this->var['permissions'] as $ko=>$vo){
					if($vo->set_default ==0){ 
						if($vo->menu_grp==''){$vo->menu_grp=$vo->controller;}
						$cur_org = $vo->menu_grp; 
						if($pre_org != $cur_org){ 
							array_push($this->var['menugrp_arr'],$vo->menu_grp);
						 } 
						$pre_org = $vo->menu_grp;
					}
				}}//end for.if

				$cur_permission = $sys_role->find_permission_nonDefault($this->var['role']->id,'Admin');
				foreach($cur_permission as $k=>$v){ 
					if(!isset($this->var['PER_'.$v->menu_grp])){
						$this->var['PER_'.$v->menu_grp] = array();
					}
					array_push($this->var['PER_'.$v->menu_grp],$v->sys_permission_id);
				}
			}else{
				common::Redirect('auth','pagenotfound');
			}
		}else{
			common::Redirect('auth','pagenotfound');
		}
	}
	
	function delete(){
		if(isset($this->param[0]) && !empty($this->param[0])){
			$sys_role = new sys_role();
			$this->var['role'] = $sys_role->find_by_id($this->param[0],'Admin');
			$this->var['role'] = array_pop($this->var['role']);
			if(!empty($this->var['role']) && $this->var['role']->no_delete==0 && $this->var['role']->deleted==0){
				$name = $this->var['role']->name;
				$permission = new sys_permission();
				$this->var['permissions'] = $permission->find_all('admin');
				$this->var['menugrp_arr'] = array();

				$pre_org = '';$cur_org = '';
				if(!empty($this->var['permissions'])){ 
				foreach($this->var['permissions'] as $ko=>$vo){
					if($vo->set_default ==0){ 
						if($vo->menu_grp==''){$vo->menu_grp=$vo->controller;}
						$cur_org = $vo->menu_grp; 
						if($pre_org != $cur_org){ 
							array_push($this->var['menugrp_arr'],$vo->menu_grp);
						 } 
						$pre_org = $vo->menu_grp;
					}
				}}//end for.if

				$cur_permission = $sys_role->find_permission_nonDefault($this->var['role']->id,'Admin');
				foreach($cur_permission as $k=>$v){ 
					if(!isset($this->var['PER_'.$v->menu_grp])){
						$this->var['PER_'.$v->menu_grp] = array();
					}
					array_push($this->var['PER_'.$v->menu_grp],$v->sys_permission_id);
				}
				
				$total_user = new sys_user();
				$total_user = $total_user->count_by_role($this->var['role']->id);
				if($total_user > 0){
					$this->var['role']->add_error("error","You cannot delete this role. There are users using this role. ");
					$this->var['errors'] = $this->var['role']->get_errors();
				}else{
					$this->var['role']->deleted = 1;
					$this->var['role']->date_modified = date("Y-m-d H:m:s");
					$this->var['role']->modified_by = $_SESSION['auth']->id;
					$this->var['role']->update_me();

					common::Redirect($this->group.'/'.$this->controller,'index');
				}
			}else{
				common::Redirect('auth','pagenotfound');
			}
		}else{
			common::Redirect('auth','pagenotfound');
		}
	}
}
?>