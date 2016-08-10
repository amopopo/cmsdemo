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
class sys_role Extends OBJ{
    function save(){
        if($this->validate_emptiness_of('name',"Name cannot be empty.")==true){
            if($this->validate_alphanumeric_of('name',"No symbol is allowed.")==true){
                if($this->validate_length_of('name',45,"Name exceeded maximum length.")==true){
                    $result = $this->find_by_name(common::setPost('name'),'Admin');
                    $result = array_pop($result);
                    if(!empty($result)){
                        $this->add_error('name',"Name is not unique.");
                    }
                }
            }
        }
        $error = $this->get_errors();
        if(!empty($error)){
            return false;
        }else{
            return parent::save();
        }
    }
    
    function save_package(){
         if($this->validate_emptiness_of('name',"Name cannot be empty.")==true){
            if($this->validate_nosymbol_of('name',"No symbol is allowed.")==true){
                if($this->validate_length_of('name',45,"Name exceeded maximum length.")==true){
                    $result = $this->find_by_name(common::setPost('name'),'Agent');
                    $result = array_pop($result);
                    if(!empty($result)){
                        $this->add_error('name',"Name is not unique.");
                    }
                }
            }
        }
        
        if($this->validate_emptiness_of('max_photo',"Max photos allowed cannot be empty.")==true){
            if($this->validate_numeric_of('max_photo',"Only numbers allowed.")==true){
                if(common::setPost('max_photo') < 0){
                     $this->add_error('max_photo',"Only positive numbers allowed.");
                }
            }
        }
        
        if($this->validate_emptiness_of('max_video',"Max videos allowed cannot be empty.")==true){
            if($this->validate_numeric_of('max_video',"Only numbers allowed.")==true){
                if(common::setPost('max_video') < 0){
                     $this->add_error('max_video',"Only positive numbers allowed.");
                }
            }
        }
        
        $error = $this->get_errors();
        if(!empty($error)){
            return false;
        }else{
            return parent::save();
        }
    }
    
    function save_edit($name){
        if($this->validate_emptiness_of('name',"Name cannot be empty.")==true){
            if($this->validate_nosymbol_of('name',"No symbol is allowed.")==true){
                if($this->validate_length_of('name',45,"Name exceeded maximum length.")==true){
                    if($this->name!=$name){
                        $result = $this->find_by_name(common::setPost('name'));
                        $result = array_pop($result);
                        if(!empty($result)){
                            $this->add_error('name',"Name is not unique.");
                        }
                    }
                }
            }
        }
        $error = $this->get_errors();
        if(!empty($error)){
            return false;
        }else{
            return parent::save();
        }
    }
    
    function save_edit_package($name){
        if($this->validate_emptiness_of('name',"Name cannot be empty.")==true){
            if($this->validate_nosymbol_of('name',"No symbol is allowed.")==true){
                if($this->validate_length_of('name',45,"Name exceeded maximum length.")==true){
                    if($this->name!=$name){
                        $result = $this->find_by_name(common::setPost('name'));
                        $result = array_pop($result);
                        if(!empty($result)){
                            $this->add_error('name',"Name is not unique.");
                        }
                    }
                }
            }
        }
        
        if($this->validate_emptiness_of('max_photo',"Max photos allowed cannot be empty.")==true){
            if($this->validate_numeric_of('max_photo',"Only numbers allowed.")==true){
                if(common::setPost('max_photo') < 0){
                     $this->add_error('max_photo',"Only positive numbers allowed.");
                }
            }
        }

        if($this->validate_emptiness_of('max_video',"Max videos allowed cannot be empty.")==false){
            if($this->validate_numeric_of('max_video',"Only numbers allowed.")==false){
                if(common::setPost('max_video') < 0){
                     $this->add_error('max_video',"Only positive numbers allowed.");
                }
            }
        }
        
        $error = $this->get_errors();
        if(!empty($error)){
            return false;
        }else{
            return parent::save();
        }
    }
    
    function update_me(){
        return parent::save();
    }
    
    function update_permission($role_id,$permission_id){
       $this->Query( "INSERT IGNORE INTO sys_role_permission VALUES('".mysql_real_escape_string($role_id)."','".mysql_real_escape_string($permission_id)."') ");
    }
    
    function delete_permission($role_id){
        $this->Query("DELETE c FROM sys_role_permission c,sys_permission p WHERE c.sys_role_id = '".mysql_real_escape_string($role_id)."' AND c.sys_permission_id=p.id AND p.set_default=0 ");
    }
    
    function find_all(){
        return $this->FindAll('sys_role',array('deleted'=>0));
    }
    
    function find_by_name($name){
        return $this->FindBySql("sys_role","SELECT r.* FROM sys_role r WHERE name LIKE '".mysql_real_escape_string($name)."' AND r.deleted=0 LIMIT 1");
    }
    
    function find_by_id($id){
        return $this->FindBySql("sys_role","SELECT r.* FROM sys_role r WHERE id='".mysql_real_escape_string($id)."' AND r.deleted=0 LIMIT 1");
    }
      
    function find_permission_nonDefault($role_id){
        $sql = "SELECT c.*,p.*,rand() as id FROM sys_role r, sys_role_permission c, sys_permission p 
            WHERE c.sys_role_id = r.id AND c.sys_permission_id = p.id AND r.id = '" . mysql_real_escape_string($role_id) . "' 
            AND p.set_default=0 AND (p.permission = 'all' OR p.permission ='none') 
            ORDER BY menu_order ";

        return $this->FindBySql('sys_role_permission', $sql);
    }
}

?>
