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
class sys_settings Extends OBJ{
    function save_edit(){
        if(common::setPost('lock_reason')!=''){
            $this->validate_length_of('lock_reason',100,"Reason exceed maximum length.");
        }

        if($this->validate_emptiness_of('contact_email',"Email cannot be empty.")==true){
            if($this->validate_email_of('contact_email',"Invalid email.")==true){
                $this->validate_length_of('contact_email',255,"Email exceeded maximum length.");
            }
        }
		
		if($this->validate_emptiness_of('country', 'Please choose a country.')==true){
				$result = new sys_country();
				$result = $result->find_by_iso(common::setPost('country'));
				$result = array_pop($result);
				if(empty($result)){
					$this->add_error("country","Country you've selected does not exists.");
				}
		}

        if($this->validate_emptiness_of('reply_email',"Email cannot be empty.")==true){
            if($this->validate_email_of('reply_email',"Invalid email.")==true){
                $this->validate_length_of('reply_email',255,"Email exceeded maximum length.");
            }
        }
 
        $error = $this->get_errors();
        if(empty($error)){
            return true;
        }else{
            return false;
        }
    }
    
	function contactUsValidation() { // boolean
		if ($this->validate_emptiness_of('txtName', 'Name cannot be empty.')==true){ 
			if ($this->validate_length_of('txtName', 91, 'Name exceeded maximum length.'));
		}
		
		if ($this->validate_emptiness_of('txtEmail', 'E-mail cannot be empty.')==true){ 
			if ($this->validate_length_of('txtEmail', 45, 'E-mail exceeded maximum length.')==true){ 
				$this->validate_email_of('txtEmail', 'Invalid e-mail.');
			}
		}
		
		if(common::setPost('txtContactNumber')!=''){
			$this->validate_length_of('txtContactNumber', 45, 'Contact number exceeded maximum length.');
		}
        
		$this->validate_emptiness_of('txtMessage', 'Message cannot be empty.');

		if ($this->validate_emptiness_of('txtCaptcha', 'Please type the characters above.')==true){
			if(isset($_SESSION['captchaCode']) ){
				if($_SESSION['captchaCode'] != md5(common::setPost('txtCaptcha')) ){
					$this->add_error('txtCaptcha', 'The characters you entered didn&acute;t match the characters showned.<br />Please try again. ');
				}
			}
		}
        
		if ($this->get_errors()==null) {
			return true;
		} else {
			return false;
		}
	}
	
	function search_replace(){
		$this->validate_emptiness_of('search_value', 'Search value cannot be empty.');
		$this->validate_emptiness_of('replace_value', 'Replace with cannot be empty.');
		if ($this->get_errors()==null) {return true;} else {return false;}
	}

    function update_me(){
        return parent::save();
    }
    
    function find_all(){
		return $this->FindBySql('sys_settings',"SELECT s.*, coun.printable_name AS country, coun.currency FROM sys_settings s, sys_country coun WHERE s.default_country=coun.iso");
    }
	
	function replace_dbdata($search,$replace){
		$this->Query("UPDATE blog_content_item SET value = REPLACE( value,  '".$search."', '". $replace."' );");
		$this->Query("UPDATE blog_content_item SET value2 = REPLACE( value2,  '".$search."', '". $replace."' );");
		$this->Query("UPDATE blog_content SET content = REPLACE( content,  '".$search."', '". $replace."' );");
		$this->Query("UPDATE blog_content SET thumbnail = REPLACE( thumbnail,  '".$search."', '". $replace."' );");
		$this->Query("UPDATE blog_content SET short_desc = REPLACE( short_desc,  '".$search."', '". $replace."' );");
	}
}

?>
