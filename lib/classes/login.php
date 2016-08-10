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
class loginController{ 
	private $site_path;
	public $group;
	public $controller;
	public $action;

	public function loginRequired() {
		$sys_user = new sys_user();
		$error = '';
		$user_role = "admin";
		$this->group = OTGROUP;
		$this->controller = OTCONTROLLER;
		$this->action = OTACTION;

		$validLtext = "";
		if(isset($_SESSION['auth']) && !empty($_SESSION['auth']->username) && !empty($_SESSION['auth']->id)){
			$validLtext = md5($_SESSION['auth']->username.'}'.'56282445}active=1');
		}else{
			unset($_SESSION['auth']);
		}

		if(!isset($_SESSION['auth']) || !isset($_COOKIE["auth"])|| empty($_COOKIE["auth"]) || ($_COOKIE["auth"]!=$validLtext) || $this->group=='login'){
			unset($_SESSION['auth']);
			setcookie ("auth", "", time() - 3600);

			if(common::setPost('submit')=="Login"){ 

				$search = $sys_user->Create("sys_user");
				if($search->valid_login()==false){
					$error = $search->get_errors();
				}else{
					$user = $sys_user->find_by_username(common::setPost('username'));
					$user = array_pop($user);
					if(!empty($user) && $user->blocked==0 && $user->suspend==0 && $user->deleted==0 && $user->activated==1){
						/*SET SESSION AND COOKIE*/
						$cookievalue = md5($user->username.'}'.'56282445}active=1');
						$cookieexpire = 36000;//one hour
						setcookie("auth", $cookievalue,time() + $cookieexpire,"/"); 

						$mysessionvar = new StdClass();
						$mysessionvar->id = $user->id;
						$mysessionvar->name = $user->first_name.' '.$user->last_name;
						$mysessionvar->username = $user->username;
						$mysessionvar->date_format = "d/m/Y";
						$_SESSION['auth'] = $mysessionvar;

						if($this->group =='login'){$this->group = 'admin';} 
						if(empty($this->controller)){
							if(!empty($this->group) && $this->group=='admin' ){
								$this->controller = "dashboard";
							}else{
								$this->controller = "pages";
							}
						}
						if(empty($this->action)){$this->action = "index";}

						$permission = $sys_user->FindByPermission($user->id, $this->controller, $this->action, $this->group);
						$permission = array_pop($permission);
						if(!empty($permission)){
							$file_path = $this->site_path .'/';
							$file_path = "";
							if(!empty($this->group)){$file_path .= $this->group .'/';}
							$file_path .= $this->controller;

							/*update_password?*/
							$days_change = 30;
							$sys_settings = new sys_settings();
							$sys_settings = $sys_settings->FindAll('sys_settings','',1);
							$sys_settings = array_pop($sys_settings);
							if(!empty($sys_settings)){
								$days_change = $sys_settings->password_expiry;
							}

							if (formatDate($user->date_pass_modified)=='' ||  (dateRange($user->date_pass_modified,date("Y-m-d H:m:s")) > $days_change)  ){
								common::redirect('auth','password_expired');
							}else{
								common::redirect($file_path,$this->action.'/'.OTPARAMS);
							}
						}else{
							common::redirect('auth','pagenotfound');
						}
					}else{
						common::redirect('auth','pagenotfound');
					}
				}
			}
			include(VIEW_PATH ."login/login.html");
			exit;
		}else{
			$permission = $sys_user->FindByPermission($_SESSION['auth']->id, $this->controller, $this->action, $this->group);
			$permission = array_pop($permission);
			if(empty($permission)){
				common::redirect('auth','pagenotfound');
			}
		}
	}

	public function frontloginRequired() { 
		$lock_reason = 'System Maintenance';

		$sys_user = new sys_user();
		$settings = new sys_settings();	
		$settings = $settings->find_all();
		$settings = array_pop($settings);
		if(!empty($settings)){
			$lock_reason = $settings->lock_reason;
		}

		$error = '';
		$this->group = 'home';
		$this->controller = OTCONTROLLER;
		$this->action = OTACTION;

		$validLtext = "";
		if(isset($_SESSION['auth']) && isset($_SESSION['auth']->username)){
			$validLtext = md5($_SESSION['auth']->username.'}'.'56282445}active=1');
		}
		if(!isset($_SESSION['auth']) || !isset($_COOKIE["auth"])|| empty($_COOKIE["auth"]) || ($_COOKIE["auth"]!=$validLtext)){
			unset($_SESSION['auth']);
			setcookie ("auth", "", time() - 3600);

			if(common::setPost('submit')=="Login"){

				$search = $sys_user->Create("sys_user");
				if($search->valid_login()==false){
					$error = $search->get_errors();
				}else{
					$user = $sys_user->find_by_username(common::setPost('username'));
					$user = array_pop($user);
					if(!empty($user) && $user->blocked==0 && $user->suspend==0 && $user->deleted==0){
						/*SET SESSION AND COOKIE*/
						$cookievalue = md5($user->username.'}'.'56282445}active=1');
						$cookieexpire = 36000;//one hour
						setcookie("auth", $cookievalue,time() + $cookieexpire,"/"); 

						$mysessionvar = new StdClass();
						$mysessionvar->id = $user->id;
						$mysessionvar->username = $user->username;
						$mysessionvar->name = $user->first_name.' '.$user->last_name;
						$mysessionvar->email = $user->email;
						$mysessionvar->date_format = "d/m/Y";

						$_SESSION['auth'] = $mysessionvar;

						if(empty($this->controller)){$this->controller = "pages";}
						if(empty($this->action)){$this->action = "index";}

						$permission = $sys_user->FindByPermission($user->id, 'pages', 'index', $this->group);
						$permission = array_pop($permission);
						if(!empty($permission)){
							$file_path = 'home/'.$this->controller;

							common::redirect($file_path,$this->action.'/'.OTPARAMS);
						}else{
							$sys_user->add_error('username',"You do not have permission to view this page.");
							$error = $sys_user->get_errors();
						}
					}else{
						common::redirect('auth','pagenotfound');
					}
				}
			}
			include(VIEW_PATH ."login/front_login.html");
			exit;
		}else{
			$permission = $sys_user->FindByPermission($_SESSION['auth']->id, 'pages', 'index', $this->group);
			$permission = array_pop($permission);
			if(empty($permission)){
				$sys_user->add_error('error',"You do not have permission to view this page.");
				$error = $sys_user->get_errors();

				include(VIEW_PATH ."login/front_login.html");
				exit;
			}
		}
	}
}
?>