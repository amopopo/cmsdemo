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
class sys_company Extends OBJ{
    function save_edit(){
        if($this->validate_emptiness_of('name',"Name cannot be empty.")==true){
            if($this->validate_alphanumeric_of('name',"No symbol is allowed.")==true){
                $this->validate_length_of('name',100,"Name exceeded maximum length.");
            }
        }
        
        if($this->validate_emptiness_of('phone_1',"Phone 1 cannot be empty.")==true){
            $this->validate_length_of('phone_1',16,"Phone 1 exceeded maximum length.");
        }
        
        if(common::setPost('phone_2')!=''){
            $this->validate_length_of('phone_2',16,"Phone 2 exceeded maximum length.");
        }
        
        if(common::setPost('fax')!=''){
            $this->validate_length_of('fax',16,"Fax exceeded maximum length.");
        }
        
        if(common::setPost('email')!=''){
            if($this->validate_email_of('email',"Invalid email.")==true){
                $this->validate_length_of('email',255,"Email exceeded maximum length.");
            }
        }
        
        if($this->validate_emptiness_of('address1',"Address 1 cannot be empty.")==true){
            $this->validate_length_of('address1',150,"Address 1 exceeded maximum length.");
        }
        
        if(common::setPost('address2')!=''){
            $this->validate_length_of('address2',150,"Address 2 exceeded maximum length.");
        }
        
        if(common::setPost('city')!=''){
            $this->validate_length_of('city',150,"City exceeded maximum length.");
        }
        
        if(common::setPost('state')!=''){
            $this->validate_length_of('state',150,"State exceeded maximum length.");
        }
        
         if(common::setPost('postcode')!=''){
       // if($this->validate_emptiness_of('postcode',"Postal code cannot be empty.")==true){
            $this->validate_length_of('postcode',15,"Postal code exceeded maximum length.");
        }
        
        if($this->validate_emptiness_of('country',"Please select a country.")==true){
           $country = new sys_country();
           $country = $country->find_by_iso(common::setPost('country'));
           $country = array_pop($country);
           if(empty($country)){
            $this->add_error('country',"Country you've selected does not exists.");
           }
        }
        
        $error = $this->get_errors();
        if(empty($error)){
            return true;
        }else{
            return false;
        }
    }

    function update_me(){
        return parent::save();
    }
    
    function find_all(){
        return $this->FindBySql('sys_company',"SELECT c.*,coun.printable_name as country FROM sys_company c, sys_country coun WHERE c.sys_country_id=coun.iso LIMIT 1");
    }
}

?>
