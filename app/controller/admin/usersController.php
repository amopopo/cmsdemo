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
Class usersController Extends baseController {
	function beforeFilter(){
		$this->loginRequired();
		$this->buildMenu();
		$this->var['errors'] = '';
	}

	function index() {
		$sys_user = new sys_user();
		$this->var['pagination'] = common::paginate($sys_user->find_count(), 30, $this->param, 'id');
		$this->var['users'] = $sys_user->find_all_paginate($this->var['pagination']->sort, $this->var['pagination']->offset,$this->var['pagination']->limit);
	}
	
	function add() {
		$sys_role = new sys_role();
		$this->var['role'] = $sys_role->find_all();

		if (common::setPost('save')=="Save") {
			$sys_user = new sys_user();
			if ($sys_user->save()==false) {
				$this->var['errors'] = $sys_user->get_errors();
			} else {
				$password = sha1(LTP2).md5(common::setPost('pssword').LTP2.LTP);
				$new_user = $sys_user->Create('sys_user',array('email'=>common::setPost('email'),
					'sys_role_id'=>common::setPost('role'),'username'=>common::setPost('username'),
					'password'=>$password,'title'=>'','first_name'=>common::setPost('first_name'),'last_name'=>common::setPost('last_name'),
					'dob'=>reformat_date(common::setPost('dob')),'mobile'=>'','suspend'=>0,'blocked'=>0,'activated'=>1,
					'no_delete'=>0,'deleted'=>0,
					'date_created'=>date("Y-m-d H:m:s"),"created_by"=>$_SESSION['auth']->id,
					'date_modified'=>date("Y-m-d H:m:s"),"modified_by"=>$_SESSION['auth']->id
				));
				$new_user->update_me();
				
				Common::Redirect($this->group.'/'.$this->controller,'index');
			}
		}
	}
	
	function edit(){
		if (empty($this->param[0])) { Common::Redirect('auth','pagenotfound'); }
		$sys_user = new sys_user();
		$this->var['user'] = $sys_user->find_by_id($this->param[0]);
		$this->var['user'] = array_pop($this->var['user']);
		if (empty($this->var['user']) || $this->var['user']->deleted==1 || $this->var['user']->id==$_SESSION['auth']->id ) { 
			 common::Redirect('auth','pagenotfound');
		}
		
		$sys_role = new sys_role();
		$this->var['role'] = $sys_role->find_all();

		if(common::setPost('save')=="Save"){
			$this->var['user']->suspend = onOff(common::setPost('suspend'));
			$email = $this->var['user']->email;
			if($this->var['user']->save_edit($email) == false){
				$this->var['errors'] = $this->var['user']->get_errors();
			}else{
				$this->var['user']->role = common::setPost('role');
				if(common::setPost('pssword')!='') { $this->var['user']->password = sha1(LTP2).md5(common::setPost('pssword').LTP2.LTP); }
				$this->var['user']->first_name = common::setPost('first_name');
				$this->var['user']->last_name = common::setPost('last_name');
				$this->var['user']->dob = reformat_date(common::setPost('dob'));
				$this->var['user']->date_modified = date("Y-m-d H:m:s");
				$this->var['user']->modified_by = $_SESSION['auth']->id;
				$this->var['user']->update_me();
				
				Common::Redirect($this->group.'/'.$this->controller,'index');
			}
		}
	}
	
	function unblocked(){
		if(isset($this->param[0]) && !empty($this->param[0])){
			$sys_user = new sys_user();
			$this->var['user'] = $sys_user->find_by_id($this->param[0]);
			$this->var['user'] = array_pop($this->var['user']);
			if(!empty($this->var['user']) && $this->var['user']->deleted==0){
				$this->var['user']->blocked = 0;
				$this->var['user']->date_modified = date("Y-m-d H:m:s");
				$this->var['user']->modified_by = $_SESSION['auth']->id;
				$this->var['user']->update_me();

				Common::Redirect($this->group.'/'.$this->controller,'index');
			}else{
				Common::Redirect('auth','pagenotfound');
			}
		}else{
			Common::Redirect('auth','pagenotfound');
		}
	}
	
	function delete(){
		if(isset($this->param[0]) && !empty($this->param[0])){
			$sys_user = new sys_user();
			$this->var['user'] = $sys_user->find_by_id($this->param[0]);
			$this->var['user'] = array_pop($this->var['user']);
			if(!empty($this->var['user']) && $this->var['user']->deleted==0){
				$this->var['user']->deleted = 1;
				$this->var['user']->date_modified = date("Y-m-d H:m:s");
				$this->var['user']->modified_by = $_SESSION['auth']->id;
				$this->var['user']->update_me();

				Common::Redirect($this->group.'/'.$this->controller,'index');
			}else{
				Common::Redirect('auth','pagenotfound');
			}
		}else{
			Common::Redirect('auth','pagenotfound');
		}
	}
}
?>