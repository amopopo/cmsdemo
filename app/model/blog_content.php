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
class blog_content Extends OBJ{
	function validate($template,$post_arr) {
		if($this->validate_emptiness_of('title', 'Title cannot be empty.')==true){
			$this->validate_length_of('title',150, 'Title exceed maximum length.');
		}
		
		if($this->validate_emptiness_of('alias', 'Alias cannot be empty.')==true){
			if($this->validate_length_of('alias',45, 'Alias exceed maximum length.')==true){
				if($this->validate_alphanumeric_of('alias', 'Only alphanumric, - , _ and space accepted.')==true){
					$result = $this->find_by_alias(common::setPost('alias'));
					$result = array_pop($result);
					if(!empty($result)){
						$this->add_error('alias','Alias not unique.');
					}
				}
			}
		}
		
		if($this->blog_category_id!=''){
			$result = new blog_category();
			$result = $result->find_by_id(common::setPost('category'));
			$result = array_pop($result);
			if(empty($result)){
				$this->add_error('category',"The category you've selected does not exists.");
			}
		}
		
		if(common::setPost('template')!=''){
			if(!empty($template_arr)){
				if(!isset($template_arr['file'.common::setPost('template')])){
					$this->add_error('template',"Template you've selected does not exists.");
				}
			}
		}
		
		if(common::setPost('position')!=''){
			$this->validate_numeric_of('position','Only numbers accepted.');
		}
			
		if($this->published==1){
			$this->validate_emptiness_of('publish_date', 'Publish date cannot be empty.');
		}
		
		if(common::setPost('publish_date')!='' && common::validate_date('publish_date')==false){
			  $this->add_error('publish_date',"Invalid Date");
	   }
		
		if(isset($post_arr) && !empty($post_arr)){
			foreach($post_arr as $key=>$value){
				$item_type = common::setPost('itemtype_'.$value);
				$value_name = common::setPost('value_name_'.$value);
				$value1 = common::setPost('value1_'.$value);
				$value2 = common::setPost('value2_'.$value);
				
				if($item_type!='slideimage' || $value_name!='' || $value1!='' || $value2!=''){
					$this->validate_emptiness_of('itemtype_'.$value, 'Type cannot be empty.');
					
					$this->validate_emptiness_of('value1_'.$value, 'Value cannot be empty.');
					
					if($item_type=='slideimage'){
						$this->validate_emptiness_of('value2_'.$value, 'Please enter value 2 for thumbnail image.');
					}
				
					if($item_type=='custom'){
						if($this->validate_emptiness_of('value_name_'.$value, 'Please enter field for value name.')==true){
							if($this->validate_length_of('value_name_'.$value,45,'Please enter field for value name.')==true){
								$this->validate_alphanumeric_of('value_name_'.$value,'Only alphanumric, - , _ and space accepted.');
							}
						}
					}
				}
			}
		}
		if ($this->get_errors()==null) {return true;} else {return false;}
	}
	
	function validate_edit($template,$post_arr) {
		if($this->validate_emptiness_of('title', 'Title cannot be empty.')==true){
			$this->validate_length_of('title',150, 'Title exceed maximum length.');
		}

		if($this->validate_emptiness_of('alias', 'Alias cannot be empty.')==true){
			if($this->validate_length_of('alias',45, 'Alias exceed maximum length.')==true){
				if($this->validate_alphanumeric_of('alias', 'Only alphanumric, - , _ and space accepted.')==true){
					if(common::setPost('alias')!= $this->alias){
						$result = $this->find_by_alias(common::setPost('alias'));
						$result = array_pop($result);
						if(!empty($result)){
							$this->add_error('alias','Alias not unique.');
						}
					}
				}
			}
		}

		if($this->blog_category_id!=''){
			$result = new blog_category();
			$result = $result->find_by_id(common::setPost('category'));
			$result = array_pop($result);
			if(empty($result)){
				$this->add_error('category',"The category you've selected does not exists.");
			}
		}

		if(common::setPost('template')!=''){
			if(!empty($template_arr)){
				if(!isset($template_arr['file'.common::setPost('template')])){
					$this->add_error('template',"Template you've selected does not exists.");
				}
			}
		}
		
		if(common::setPost('position')!=''){
			$this->validate_numeric_of('position','Only numbers accepted.');
		}

		if($this->published==1){
			$this->validate_emptiness_of('publish_date', 'Publish date cannot be empty.');
		}
		if(common::setPost('publish_date')!='' && common::validate_date('publish_date')==false){
			  $this->add_error('publish_date',"Invalid Date");
	    }

		if(isset($post_arr) && !empty($post_arr)){
			foreach($post_arr as $key=>$value){
				$item_type = common::setPost('itemtype_'.$value);
				$value_name = common::setPost('value_name_'.$value);
				$value1 = common::setPost('value1_'.$value);
				$value2 = common::setPost('value2_'.$value);

				if($item_type!='slideimage' || $value_name!='' || $value1!='' || $value2!=''){
					$this->validate_emptiness_of('itemtype_'.$value, 'Type cannot be empty.');

					$this->validate_emptiness_of('value1_'.$value, 'Value cannot be empty.');

					if($item_type=='slideimage'){
						$this->validate_emptiness_of('value2_'.$value, 'Please enter value 2 for thumbnail image.');
					}

					if($item_type=='custom'){
						if($this->validate_emptiness_of('value_name_'.$value, 'Please enter field for value name.')==true){
							if($this->validate_length_of('value_name_'.$value,45,'Please enter field for value name.')==true){
								$this->validate_alphanumeric_of('value_name_'.$value,'Only alphanumric, - , _ and space accepted.');
							}
						}
					}
				}
			}
		}
		if ($this->get_errors()==null) {return true;} else {return false;}
	}
	
	function validate_photo(){ 
		if(!empty($this->file_name) && !empty($this->file_type)){
			$type_array = array('jpg','jpeg');
			$mime_array = array('image/jpeg');

			if(!in_array($this->file_type,$mime_array) && !in_array($this->ext,$type_array)){
				$this->add_error( 'thumbnail','Only jpeg and jpg file is acceptable.');
			}else{
				$maxsize = '1048576';

				if($this->file_size > $maxsize){
					$Size = formatSize($maxsize);
					$this->add_error('thumbnail','Maximum image size is '.$Size);
				}
			}
		}else{
			$this->add_error('thumbnail','Please select a photo to be uploaded.');
		}

		$error = $this->get_errors();
		if(empty($error)){return true;}else{return false;}
	}
	
	function validate_newslideimage($files,$item_type) {
		if(!isset($_FILES['newimage']) || empty($_FILES['newimage'])){
			$this->add_error('error',"Please choose an image to upload.");
		}else{
			if($item_type=='slideimage'){$dis ='display image';}else{$dis ='image';}
			if($files[0]['name']==null){
				$this->add_error('error',"Please choose a ".$dis." to upload.");
			}else{
				$main_image = $files[0];
				if ($main_image['type']!='image/jpeg' && $main_image['type']!='image/pjpeg') {
					$this->add_error('error', 'Only jpeg/jpg file type acceptable for '.$dis.'.');
				} 

				if ($main_image['size']>800000) {
					$this->add_error('error', ucwords($dis).' exceeded 800kb.');
				}
			}

			if($item_type=='slideimage'){
				if(common::setPost('createThumb')=='on'){
					//specify thumbnail size
					if ($this->validate_emptiness_of('width', 'Please specify thumbnail width.')) {
						$this->validate_numeric_of('width', 'Only integers are accepted for width.');
					}
					if ($this->validate_emptiness_of('height', 'Please specify thumbnail height.')) {
						$this->validate_numeric_of('height', 'Only integers are accepted for height.');
					}
				}else{
					//upload thumbnail
					if(!isset( $files[1]['name']) || $files[1]['name']==null){
						$this->add_error('error2',"Please choose a thumbnail to upload or checked <b>auto create thumbnail</b> to specify thumbnail size.");
					}else{
						$thumb_image = $files[0];
						if ($thumb_image['type']!='image/jpeg' && $thumb_image['type']!='image/pjpeg') {
							$this->add_error('error2', 'Only jpeg/jpg file type acceptable for thumbnail image.');
						} 
   
						if ($thumb_image['size']>800000) {
							$this->add_error('error2', 'Thumbnail image exceeded 800kb.');
						}
					}
				}
			}
		}

		if ($this->get_errors()==null) { return true; } else { return false;}
	}
	
	function update_me(){
		return parent::save();
	}

	function find_count($keyword,$status) {
		$sql = 'SELECT count(c.id) as id FROM blog_content c
		LEFT JOIN blog_category cat ON(c.blog_category_id=cat.id AND cat.deleted=0 AND cat.active=1)
		WHERE c.deleted=0 ';
		if(!empty($keyword)){
			$sql .= " AND (c.title LIKE '%".mysql_real_escape_string($keyword)."%' 
			or cat.title LIKE '%".mysql_real_escape_string($keyword)."%' 
			or c.template LIKE '%".mysql_real_escape_string($keyword)."%'  
			)";
		}
		
		if(!empty($status) && $status!='all'){
			switch($status){
				case 'draft':
					$sql .= " AND c.archived=1 ";
					break;
				case 'published':
					$sql .= " AND c.archived=0 AND c.published=1 ";
					break;
				case 'draft':
				default:
					$sql .= " AND c.archived=0 AND c.published=0 ";
					break;
			}
		}else{
			$sql .= " AND c.archived=0 ";
		}
		
		$result = $this->FindBySql('blog_content', $sql);
		$result = array_pop($result);
		if (!empty($result)) {
			return $result->id;
		} else {
			return 0;
		}
	}
	
	function find_all_paginate($sort, $offset, $limit,$keyword,$status) {
		$sql = 'SELECT c.*,COALESCE(cat.title,\'None\') as category
		FROM blog_content c LEFT JOIN blog_category cat ON(c.blog_category_id=cat.id AND cat.deleted=0 AND cat.active=1)
		WHERE c.deleted=0 ';
		
		if(!empty($keyword)){
			$sql .= " AND (c.title LIKE '%".mysql_real_escape_string($keyword)."%' 
			or cat.title LIKE '%".mysql_real_escape_string($keyword)."%' 
			or c.template LIKE '%".mysql_real_escape_string($keyword)."%' 
			)";
		}

		if(!empty($status) && $status!='all'){
			switch($status){
				case 'archived':
					$sql .= " AND c.archived=1 ";
					break;
				case 'published':
					$sql .= " AND c.archived=0 AND c.published=1 ";
					break;
				case 'draft':
				default:
					$sql .= " AND c.archived=0 AND c.published=0 ";
					break;
			}
		}else{
			$sql .= " AND c.archived=0 ";
		}
		
		$sql .= ' ORDER BY '.$sort.' LIMIT '.$offset.','.$limit;

		return $this->FindBySql('blog_content', $sql);
	}
	
	function find_by_id($id) {
		return $this->FindBySql('blog_content', 'SELECT c.*,cat.title as category FROM blog_content c 
		LEFT JOIN blog_category cat ON(c.blog_category_id=cat.id AND cat.deleted=0 AND cat.active=1)
		WHERE c.id=\''.mysql_real_escape_string($id).'\' LIMIT 1');
	}
	
	function find_by_alias($alias) {
		return $this->FindBySql('blog_content', 'SELECT c.*,DATEDIFF(NOW(),c.date_published) as datediff 
		FROM blog_content c WHERE c.alias=\''.mysql_real_escape_string($alias).'\' LIMIT 1');
	}
	
	function find_by_category($category) {
		return $this->FindBySql('blog_content', "SELECT c.* FROM blog_content c WHERE c.blog_category_id='".mysql_real_escape_string($category)."' 
		AND c.deleted=0 AND c.archived=0 AND c.published=1 AND DATEDIFF(NOW(),c.date_published)>=0 ORDER BY c.position ");
	}
	
	function find_by_categoryAlias($category_alias) {
		return $this->FindBySql('blog_content', "SELECT c.* FROM blog_content c,blog_category cat
		WHERE c.blog_category_id=cat.id AND cat.deleted=0 AND cat.active=1 AND cat.alias='".mysql_real_escape_string($category_alias)."' 
		AND c.deleted=0 AND c.archived=0 AND c.published=1 AND DATEDIFF(NOW(),c.date_published)>=0 ORDER BY c.position ");
	}
}

?>