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
Class blog_category Extends OBJ {
	function validate($template_arr) {
		if($this->validate_emptiness_of('title', 'Title cannot be empty.')==true){
			$this->validate_length_of('title',150, 'Title exceed maximum length.');
		}
		
		if($this->validate_emptiness_of('alias', 'Alias cannot be empty.')==true){
			if($this->validate_length_of('alias',45, 'Alias exceed maximum length.')==true){
				if($this->validate_alphanumeric_of('alias', 'Only alphanumeric accepted.')==true){
					$result = $this->find_by_alias(common::setPost('alias'));
					$result = array_pop($result);
					if(!empty($result)){
						$this->add_error('alias','Alias not unique.');
					}
				}
			}
		}
		
		if(common::setPost('template')!=''){
			if(!empty($template_arr)){
				if(!isset($template_arr['file'.common::setPost('template')])){
					$this->add_error('template',"Template you've selected does not exists.");
				}
			}
		}
		return parent::save();
	}
	
	function validate_edit($template_arr) {
		if($this->validate_emptiness_of('title', 'Title cannot be empty.')==true){
			$this->validate_length_of('title',150, 'Title exceed maximum length.');
		}

		if($this->validate_emptiness_of('alias', 'Alias cannot be empty.')==true){
			if($this->validate_length_of('alias',45, 'Alias exceed maximum length.')==true){
				if($this->validate_nosymbol_of('alias', 'Only alphanumeric accepted.')==true){
					if($this->alias!=common::setPost('alias')){
						$result = $this->find_by_alias(common::setPost('alias'));
						$result = array_pop($result);
						if(!empty($result)){
							$this->add_error('alias','Alias not unique.');
						}
					}
				}
			}
		}

		if(common::setPost('template')!=''){
			if(!empty($template_arr)){
				if(!isset($template_arr['file'.common::setPost('template')])){
					$this->add_error('template',"Template you've selected does not exists.");
				}
			}
		}
		if($this->get_errors()==null){return true;}else{return false;}
	}

	function update_me(){ return parent::save();}

	function find_count() {
		$sql = 'SELECT count(c.id) as id FROM blog_category c WHERE c.deleted=0';
		$result = $this->FindBySql('blog_content', $sql);
		$result = array_pop($result);
		if (!empty($result)) {
			return $result->id;
		} else {
			return 0;
		}
	}

	function find_all_paginate($sort, $offset, $limit) {
		$sql = 'SELECT c.* FROM blog_category c WHERE c.deleted=0 ORDER BY '.$sort.' LIMIT '.$offset.','.$limit;
		return $this->FindBySql('blog_content', $sql);
	}
	
	function find_all(){
		return $this->FindBySql('blog_content', 'SELECT c.* FROM blog_category c WHERE c.deleted=0 and c.active=1');
	}
	
	function find_by_alias($alias){
		return $this->FindBySql('blog_category',"SElECT * FROM blog_category c WHERE c.alias='".mysql_real_escape_string($alias)."' AND c.deleted=0 LIMIT 1");
	}
	
	function find_by_id($id){
		return $this->FindBySql('blog_category',"SElECT * FROM blog_category c WHERE c.id='".mysql_real_escape_string($id)."' AND c.deleted=0 LIMIT 1");
	}
}

?>