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
Class settingsController Extends baseController {
	function beforeFilter(){
		$this->loginRequired();
		$this->buildMenu();
		$this->var['errors'] = '';
	}

	function index() {
		$sys_settings = new sys_settings();
		$this->var['settings'] = $sys_settings->find_all();
		$this->var['settings'] = array_pop($this->var['settings']);
		
		$sys_company = new sys_company();
		$this->var['company'] = $sys_company->find_all();
		$this->var['company'] = array_pop($this->var['company']);
	}
	
	function update_company(){
		$sys_company = new sys_company();
		$this->var['company'] = $sys_company->find_all();
		$this->var['company'] = array_pop($this->var['company']);
		if(!empty($this->var['company'])){
			$this->var['country'] = new sys_country();
			$this->var['country'] = $this->var['country']->find_all();
			if(common::setPost('save')=="Save"){
				if($this->var['company']->save_edit() == false){
					$this->var['errors'] = $this->var['company']->get_errors();
				}else{
					$this->var['company']->name = common::setPost('name');
					$this->var['company']->phone_1 = common::setPost('phone_1');
					$this->var['company']->phone_2 = common::setPost('phone_2');
					$this->var['company']->fax = common::setPost('fax');
					$this->var['company']->email = common::setPost('email');
					$this->var['company']->address1 = common::setPost('address1');
					$this->var['company']->address2 = common::setPost('address2');
					$this->var['company']->city = common::setPost('city');
					$this->var['company']->state = common::setPost('state');
					$this->var['company']->postcode = common::setPost('postcode');
					$this->var['company']->sys_country_id = common::setPost('country');
					$this->var['company']->update_me();
					
					common::Redirect($this->group.'/'.$this->controller,'index');
				}
			}
		}else{
			common::Redirect('auth',"pagenotfound");
		}
	}
	
	function update_settings(){
		$sys_settings = new sys_settings();
		$this->var['settings'] = $sys_settings->find_all();
		$this->var['settings'] = array_pop($this->var['settings']);
		if(!empty($this->var['settings'])){
			$this->var['country'] = new sys_country();
			$this->var['country'] = $this->var['country']->find_all();
		
			if(common::setPost('save')=="Save"){
				$this->var['settings']->lock_website = onOff(common::setPost('lock_website'));
				if($this->var['settings']->save_edit() == false){
					$this->var['errors'] = $this->var['settings']->get_errors();
				}else{
					$this->var['settings']->default_country = common::setPost('country');
					$this->var['settings']->lock_reason = common::setPost('lock_reason');
					$this->var['settings']->contact_email = common::setPost('contact_email');
					$this->var['settings']->reply_email = common::setPost('reply_email');
					$this->var['settings']->trackingcode = common::setPost('trackingcode');
					$this->var['settings']->metakey = common::setPost('metakey');
					$this->var['settings']->metadesc = common::setPost('metadesc');
					$this->var['settings']->date_modified = date("Y-m-d H:m:s");
					$this->var['settings']->modified_by = $_SESSION['auth']->id;
					$this->var['settings']->update_me();
					
					common::Redirect($this->group.'/'.$this->controller,'index');
				}
			}
		}else{
			common::Redirect('auth',"pagenotfound");
		}
	}
	
	function search_replace(){
		$this->var['success'] = "";
		$sys_settings = new sys_settings();
		if(common::setPost('change')=="Replace"){
			if( $sys_settings->search_replace()==false){
				$this->var['errors'] =  $sys_settings->get_errors();
			}else{
				$search = common::setPost('search_value');
				$replace = common::setPost('replace_value');
				$sys_settings->replace_dbdata($search,$replace);
				$this->var['success'] = "Value replaced.";
			}
		}
	}
}
?>