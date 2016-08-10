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
class sys_user Extends OBJ {
	function myaccountEditValidate($email) {
		if ((common::setPost('pssword')!='' || common::setPost('Cpassword')!='')) {
			if ($this->validate_emptiness_of('pssword', 'Password cannot be empty.') 
			&& $this->validate_emptiness_of('Cpassword', 'Confirm password cannot be empty.')) 
			{
				if ($this->validate_length_of('pssword', 45, 'Password exceeded maximum length.', 5, 'Password must be more than 5 letters.')
				&& $this->validate_length_of('Cpassword', 45, 'Confirm password exceeded maximum length.', 5, 'Confirm password must be more than 5 letters.')) 
				{
					if($this->get_error('pssword')=='' && $this->get_error('Cpassword')==''){
						if (!$this->validate_similarity_of('pssword', 'Cpassword', 'Password and confirm password does not match.'));
					}
				}
			}
		}

		if ($this->validate_emptiness_of('first_name', 'First name cannot be empty.')) {
			if ($this->validate_length_of('first_name', 45, 'First name exceeded maximum length.'))  {
				$this->validate_alphanumeric_of('first_name', 'No symbol is allowed.');
			}
		}

		if ($this->validate_emptiness_of('last_name', 'Last name cannot be empty.')) {
			if ($this->validate_length_of('last_name', 45, 'Last name exceeded maximum length.'))  {
				$this->validate_alphanumeric_of('last_name', 'No symbol is allowed.');
			}
		}

		if (common::setPost('dob')!='') {
	//	if ($this->validate_emptiness_of('dob', 'Date of birth cannot be empty.')) { 
			if (!common::validate_date('dob')) {
				$this->add_error('dob', 'Invalid Date');
			}
		}
		
		if($this->validate_emptiness_of('email',"Email address cannot be empty.")==true){
			if($this->validate_email_of('email',"Invalid email address.")==true){
				if($this->validate_length_of('email',255,"Invalid email address.")==true){
					if($email != common::setPost('email')){
						$result = $this->find_by_email(common::setPost('email'),'Admin');
						$result = array_pop($result);
						if(!empty($result)){
							$this->add_error('email',"Email address is not unique.");
						}
					}
				}
			}
		}

		if ($this->get_errors() == null) {return true;} else {return false;}
	}
	
	function validateChangePassword() {
		if ($this->validate_emptiness_of('password', 'Password cannot be empty.') 
		&& $this->validate_emptiness_of('r_password', 'Retype password cannot be empty.')) 
		{
			if ($this->validate_length_of('password', 45, 'Password exceeded maximum length.', 5, 'Password must be more than 5 letters.')
			&& $this->validate_length_of('r_password', 45, 'Retype password exceeded maximum length.', 5, 'Confirm password must be more than 5 letters.')) 
			{
				if($this->get_error('password')=='' && $this->get_error('r_password')==''){
					if (!$this->validate_similarity_of('password', 'r_password', 'Password and retype password does not match.'));
				}
			}
		}

		if ($this->get_errors() == null) {return true;} else {return false;}
	}

    function save(){
		if($this->validate_emptiness_of('username',"Username cannot be empty.")==true){
			if($this->validate_nosymbol_of('username',"No symbol allowed.")==true){
				if($this->validate_length_of('username',45,"Username exceed maximum length.")==true){
						$result = $this->find_by_username(common::setPost('username'),'Admin');
						$result = array_pop($result);
						if(!empty($result)){
							$this->add_error('username',"Username is not available.");
						}
				}
			}
		}

		if($this->validate_emptiness_of('email',"Email address cannot be empty.")==true){
			if($this->validate_email_of('email',"Invalid email address.")==true){
				if($this->validate_length_of('email',255,"Invalid email address.")==true){
					$result = $this->find_by_email(common::setPost('email'),'Admin');
					$result = array_pop($result);
					if(!empty($result)){
						$this->add_error('email',"Email address is not unique.");
					}
				}
			}
		}
        
        if($this->validate_emptiness_of('role',"Please select a role.")==true){
            $result = new sys_role();
            $result = $result->find_by_id(common::setPost('role'),'Admin');
                $result = array_pop($result);
                if(empty($result)){
                    $this->add_error('role',"Role you've selected does not exists.");
                }
        }     
        
       if($this->validate_emptiness_of('pssword',"Password cannot be empty.") ==true){
          $this->validate_length_of('pssword',45,"Password exceeded maximum length.",5,"Password must be more than 5 letters.");
          $this->validate_emptiness_of('Cpassword',"Confirm password cannot be empty.");   
          
          if($this->get_error('pssword')=='' && $this->get_error('Cpassword')==''){
                $this->validate_similarity_of('pssword','Cpassword',"Password and confirm password does not match.");
          }
       }
       
       if($this->validate_emptiness_of('first_name',"First name cannot be empty.")==true){
           if($this->validate_alphanumeric_of('first_name',"No symbol is allowed.")==true){
                 $this->validate_length_of('first_name',45,"First name exceeded maximum length.");
           }
        }
        
        if($this->validate_emptiness_of('last_name',"Last name cannot be empty.")==true){
           if($this->validate_alphanumeric_of('last_name',"No symbol is allowed.")==true){
                 $this->validate_length_of('last_name',45,"Last name exceeded maximum length.");
           }
        }
        
       if(common::setPost('dob')!='' && common::validate_date('dob')==false){
              $this->add_error('dob',"Invalid Date");
       }

       $error = $this->get_errors();
        if(empty($error)){
            return true;
        }else{
            return false;
        }
    }
    
    function save_edit($email){
		if($this->validate_emptiness_of('role',"Please select a role")==true){
			$result = new sys_role();
			$result = $result->find_by_id(common::setPost('role'), 'Admin');
			$result = array_pop($result);
			if(empty($result)){
				$this->add_error('role',"Role you've selected does not exists.");
			}
		}
		if($this->validate_emptiness_of('email',"Email address cannot be empty.")==true){
			if($this->validate_email_of('email',"Invalid email address.")==true){
				if($this->validate_length_of('email',255,"Invalid email address.")==true){
					if($email != common::setPost('email')){
						$result = $this->find_by_email(common::setPost('email'),'Admin');
						$result = array_pop($result);
						if(!empty($result)){
							$this->add_error('email',"Email address is not unique.");
						}
					}
				}
			}
		}
	
       if((common::setPost('pssword')!='' || common::setPost('Cpassword')!='')) {
		  $this->validate_length_of('pssword',45,"Password exceeded maximum length.",5,"Password must be more than 5 letters.");
		  $this->validate_emptiness_of('Cpassword',"Confirm password cannot be empty.");   
   
		  if($this->get_error('pssword')=='' && $this->get_error('Cpassword')==''){
			$this->validate_similarity_of('pssword','Cpassword',"Password and confirm password does not match.");
		  }
       }

       if($this->validate_emptiness_of('first_name',"First name cannot be empty.")==true){
           if($this->validate_alphanumeric_of('first_name',"No symbol is allowed.")==true){
                 $this->validate_length_of('first_name',45,"First name exceeded maximum length.");
           }
        }

        if($this->validate_emptiness_of('last_name',"Last name cannot be empty.")==true){
           if($this->validate_alphanumeric_of('last_name',"No symbol is allowed.")==true){
                 $this->validate_length_of('last_name',45,"Last name exceeded maximum length.");
           }
        }

        if(common::setPost('dob')!='' && common::validate_date('dob')==false){
			  $this->add_error('dob',"Invalid Date");
	   }

       $error = $this->get_errors();
        if(empty($error)){
            return true;
        }else{
            return false;
        }
    }

	function update_pass(){ 
		 if($this->validate_emptiness_of('old_pword',"Current password cannot be empty.") ==true){
			 if($this->password != sha1(LTP2).md5(common::setPost('old_pword').LTP2.LTP)){
			     $this->add_error('old_pword',"Invalid current password");
			 }
		}
			
		 if($this->validate_emptiness_of('pword',"New password cannot be empty.") ==true){
		     $this->validate_length_of('pword',45,"New password exceeded maximum length.",5,"New password must be more than 5 letters.");
		     $this->validate_emptiness_of('cpword',"Confirm password cannot be empty.");   

		     if($this->get_error('pword')=='' && $this->get_error('cpword')==''){
			  $this->validate_similarity_of('pword','cpword',"Password and confirm password does not match.");
		     }
		 }

		$error = $this->get_errors(); 

		if(!empty($error)){
		    return false;
		}else{
		    return true;
		}
	}

    function valid_login(){ 
        if($this->validate_emptiness_of('username',"Username cannot be empty.")==true){
                if($this->validate_emptiness_of('password',"Password cannot be empty.")==true){
                    $user = $this->find_by_username(common::setPost('username'));
                    $user = array_pop($user); 
                    if(empty($user)){
                        $this->add_error("username","Invalid username or password.");
                    }else{
						if($user->activated == 1){
							if($user->suspend != 1){
								if($user->blocked != 1){
									if($user->password != sha1(LTP2).md5(common::setPost('password').LTP2.LTP)){
									   if (!isset($_SESSION['AttemptsCounter'])){
											$_SESSION['AttemptsCounter'] = 1;
										}else{
											$_SESSION['AttemptsCounter']++;
										}
										if ($_SESSION['AttemptsCounter'] > 3){			
											$user->blocked = 1;
											$user->update_me();
											$this->add_error('username','Maximum invalid login breach. Account Blocked');
											$_SESSION['AttemptsCounter'] = 1;						
										}else{
											$this->add_error('username','Invalid username or password.');					
										}
									}else{
										$user->last_login = date("Y-m-d H:m:s");
										$user->update_me();					
									}
								}else{
									$this->add_error('username','Maximum invalid login breach. Account Blocked');
								}
							}else{
								$this->add_error('username','Account has been suspended.');
							}
						}else{
						 	$this->add_error('username','Account not activated. Please contact our customer service for activation.');
						}
                    }
                }   
        } 
        $error = $this->get_errors(); 
   
        if(!empty($error)){
            return false;
        }else{
            return true;
        }
    }
    
    function update_me(){
        return parent::save();
    }
	
    function find_count(){
        $sql = "SELECT count(a.id) as id FROM sys_role r, sys_user a WHERE a.sys_role_id=r.id AND a.no_delete=0 AND a.deleted=0 AND r.deleted=0 ";
			
        $result = $this->FindBySql("sys_user",$sql);
        $result = array_pop($result);
        if(!empty($result)){
            return $result->id;
        }else{
            return 0;
        }
    }
    
    function find_all_paginate($sort,$offset, $limit){
        $sql = "SELECT a.*,r.name as role FROM sys_role r, sys_user a 
            WHERE a.sys_role_id=r.id AND a.no_delete=0 AND a.deleted=0 AND r.deleted=0 ORDER BY " . $sort . " LIMIT " . $offset . "," . $limit;

        return $this->FindBySql("sys_user",$sql);
    }

    function find_by_id($id){
        return $this->FindBySql('sys_user', 'SELECT a.*, r.name as role, r.max_photo, r.max_video FROM sys_user a, sys_role r 
				WHERE a.sys_role_id=r.id AND r.deleted=0 AND a.id=\''.mysql_real_escape_string($id).'\' LIMIT 1');
    }

    function find_by_username($username) {
        return $this->FindBySql("sys_user","SELECT u.* FROM sys_user u WHERE u.deleted=0 AND u.username='".mysql_real_escape_string($username)."' LIMIT 1 ");
    }
	
	function find_by_email($email) {
		return $this->FindBySql("sys_user","SELECT u.* FROM sys_user u WHERE u.deleted=0 AND u.email='".mysql_real_escape_string($email)."' LIMIT 1 ");
	}
    
    function FindByPermission ($userid, $controller, $action, $group=null){
        $sql = "SELECT * FROM sys_user a, sys_role_permission c, sys_permission p 
            WHERE c.sys_role_id = a.sys_role_id AND c.sys_permission_id = p.id AND a.id = '" . mysql_real_escape_string($userid) . "' 
            AND p.controller = '" . mysql_real_escape_string($controller) . "' ";
            
        if(!empty($group)){
            $sql .=" AND p.group='".mysql_real_escape_string($group)."' ";
        }
        $sql .= " AND (p.action = '" . mysql_real_escape_string($action) . "' OR p.permission = 'all' OR p.permission ='none') LIMIT 1";
        
        return $this->FindBySql('sys_user', $sql);
    }
	
	function FindSpecificPermission ($userid, $controller, $action, $group=null){ 
		$sql = "SELECT p.*,rand() as id,c.sys_role_id,c.sys_permission_id FROM sys_user a, sys_role_permission c, sys_permission p 
			WHERE c.sys_role_id = a.sys_role_id AND c.sys_permission_id = p.id AND a.id = '" . mysql_real_escape_string($userid) . "' 
			AND p.controller = '" . mysql_real_escape_string($controller) . "' ";

		if(!empty($group)){
			$sql .=" AND p.group='".mysql_real_escape_string($group)."' ";
		}
		$sql .= " AND p.action = '" . mysql_real_escape_string($action) . "'  LIMIT 1";
		return $this->FindBySql('sys_user', $sql);
	}
    
    function FindAllPermission($userid,$group=null){
        $sql = "SELECT p.*,coalesce(p.menu_grp,'none') as menu_grp FROM sys_user a, sys_role_permission c, sys_permission p 
            WHERE c.sys_role_id = a.sys_role_id AND c.sys_permission_id = p.id AND a.id = '" . mysql_real_escape_string($userid) . "' ";

        if(!empty($group)){
            $sql .=" AND p.group='".mysql_real_escape_string($group)."' ";
        }
        $sql .= " AND (p.permission = 'all' OR p.permission ='none') 
                ORDER BY menu_order ";

        return $this->FindBySql('sys_permission', $sql);
    }
    
    function count_by_role($role_id){
        $result = $this->FindBySql("sys_user","SELECT count(a.id) as id FROM sys_user a, sys_role r 
        WHERE a.sys_role_id=r.id AND a.deleted=0 AND r.id='".mysql_real_escape_string($role_id)."' ");
        $result = array_pop($result);
        if(!empty($result)){
            return $result->id;
        }else{
            return 0;
        }
    }
    
}

?>
