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
Class pagesController Extends baseController {
	function beforeFilter() { 
		$this->loginRequired();
		$this->buildMenu();
		$this->var['errors'] = '';
	}

	function index() {
		$blog_content = new blog_content();
		$this->var['keyword']='';
		$this->var['status']='all';
		
		if(isset($this->param[1])){
			if($this->param[1]!=''){
				$this->var['status']= urldecode($this->param[1]);
			}
			unset($this->param[1]);
		}
		
		if(isset($this->param[2])){
			if($this->param[2]!=''){
				$this->var['keyword']= urldecode($this->param[2]);
			}
			unset($this->param[2]);
		}
		
		if(common::setPost('search')=='Search'){
			$this->var['keyword'] = common::setPost('keyword');
			$this->var['status'] = common::setPost('status');
		}
		
		$this->var['pagination'] = common::paginate($blog_content->find_count($this->var['keyword'],$this->var['status']), 30, $this->param, 'cat.title, c.title');
		$this->var['blog_content'] = $blog_content->find_all_paginate($this->var['pagination']->sort, $this->var['pagination']->offset, $this->var['pagination']->limit,$this->var['keyword'],$this->var['status']);
	}
	
	function category() {
		$blog_category = new blog_category();
		$this->var['pagination'] = common::paginate($blog_category->find_count(), 30, $this->param, 'id');
		$this->var['listing'] = $blog_category->find_all_paginate('id', $this->var['pagination']->offset, $this->var['pagination']->limit);
	}
	
	function add_category(){
		$template_path = VIEW_PATH.'home/categories';
		$this->var["templates"] = get_template($template_path);

		if(common::setPost('save')=='Save'){
			$new_category = new blog_category();
			$new_category = $new_category->Create('blog_category',array('title'=>common::setPost('title'),
				'alias'=>common::setPost('alias'),'template'=>common::setPost('template'),
				'metakey'=>common::setPost('metakey'),'metadesc'=>common::setPost('metadesc'),
				'active'=>onOff(common::setPost('active')),'deleted'=>0,
				'date_created'=>date('Y-m-d H:m:s'),'created_by'=>$_SESSION['auth']->id
			));
			if($new_category->validate($this->var['templates'])==false){
				$this->var['errors'] = $new_category->get_errors();
			}else{
				common::Redirect($this->group.'/'.$this->controller,'category');
			}
		}
	}
	
	function add(){
		$this->var['post_arr'] = array();
		$blog_content = new blog_content();
		$template_path = VIEW_PATH.'home/pages';
		$this->var["templates"] = get_template($template_path);
		
		$this->var['category'] = new blog_category();
		$this->var['category'] = $this->var['category']->find_all();
		
		$date = date('Y-m');
		//$temp_path = IMAGE_UPLOAD_PATH.'upload/'.$date.'/';
		//$temp_display_path = IMAGE_PATH.'upload/'.$date.'/';
		
		/*if(common::setPost("submit") == "Upload"){ //UPLOAD PHOTO and store in the session for future use.
			$userfile = $_FILES['myfile'];
			$img_name = $_FILES['myfile']['name'];
			$userfile_type = $_FILES['myfile']['type'];
			$filesize = $_FILES['myfile']['size'];
			$fileContents = "";
			$ext = strtolower(substr(strrchr($img_name, "."), 1));

			$fileupload = $blog_content->Create('blog_content',array('file_name'=>$img_name,'file_type'=>$userfile_type,'ext'=>$ext,'file_size'=>$filesize));
			if($fileupload->validate_photo()==false){
				$this->var['errors'] = $fileupload->get_errors();
			}else{
				if(!is_dir($temp_path)){
					mkdir($temp_path);
				}
					$my_var = new StdClass();
					$my_var->path = $temp_display_path.$img_name;
					$my_var->name = $img_name;
					$date = date('y-m');
					if(move_uploaded_file($_FILES["myfile"]["tmp_name"],$temp_path.$img_name)==false){
						$fileupload->add_error('thumbnail',"File upload failed. Please try again.");
						$this->var['errors'] = $fileupload->get_errors();
					}else{
						$_SESSION['p_photo'] = $my_var;
						unset($_FILES);
						unset($_POST);
					}

			}
		}*/
		
		if(common::setPost("submit") == "Remove Thumbnail"){
			if(isset($_SESSION['p_photo'])){
				unset($_SESSION['p_photo']);
			}
		}

		if(common::setPost('save')=='Save as draft' || common::setPost('save')=='Publish'|| common::setPost("submit") == "Remove Thumbnail"){
			foreach($_POST as $key=>$value){
				if(preg_match('/itemtype_/',$key)){
					$no = preg_replace('/itemtype_/','',$key);

					if(preg_match('/^[0-9]{1,2}$/',$no)){
						array_push($this->var['post_arr'] ,$no);
					}
				}
			}
		}
		
		if(common::setPost('save')=='Save as draft' || common::setPost('save')=='Publish' ){
			if(common::setPost('save')=='Publish'){$published=1;}else{$published=0;}

			$new_page = new blog_content();
			$new_page = $new_page->Create('blog_content',array('title'=>common::setPost('title'),
				'alias'=>common::setPost('alias'),'blog_category_id'=>common::setPost('category'),'template'=>common::setPost('template'),
				'short_desc'=>common::setPost('short_desc'),'content'=>common::setPost('content'),
				'published'=>$published,'date_published'=>reformat_date(common::setPost('publish_date')),
				'archived'=>0,'deleted'=>0,'position'=>common::setPost('position'),
				'date_created'=>date('Y-m-d H:m:s'),'created_by'=>$_SESSION['auth']->id,
				'date_modified'=>date('Y-m-d H:m:s'),'modified_by'=>$_SESSION['auth']->id
			));
			if($new_page->validate($this->var['templates'],$this->var['post_arr'])==false){
				$this->var['errors'] = $new_page->get_errors();
			}else{
				$sys_prefix = new sys_prefix();
				$sys_prefix = $sys_prefix->find_by_refCode('page'); 
				$sys_prefix = array_pop($sys_prefix);
				$systemCode = $sys_prefix->prefix.$sys_prefix->system_code;

				if(isset($_SESSION['p_photo']) && isset($_SESSION['p_photo']->path) && !empty($_SESSION['p_photo']->path)){
					$new_page->thumbnail = $_SESSION['p_photo']->path;
				}
				$new_page->system_code = $systemCode; 
				$new_page->update_me();
				
				$sys_prefix->current_index = $sys_prefix->system_code;
				$sys_prefix->update_me();
				
				$blog_content_item = new blog_content_item();
				foreach($this->var['post_arr'] as $key=>$value){
					$item_type = common::setPost('itemtype_'.$value);
					$value_name = common::setPost('value_name_'.$value);
					$value1 = common::setPost('value1_'.$value);
					$value2 = common::setPost('value2_'.$value);
   
					if($item_type!=''&& $value1!=''){
						if($item_type!='slideimage' && $item_type!='custom'){$value2='';}
						if($item_type!='custom'){$value_name='';}
					
						$new_item = $blog_content_item->Create('blog_content_item',array('system_code'=>$systemCode,
							'item_type'=>$item_type,'value'=>$value1,'value2'=>$value2,'value_name'=>$value_name
						));
						$new_item->update_me();
					}
				}
				common::Redirect($this->group.'/'.$this->controller,'index');
			}
		}
		
		if(common::setPost('submit')=='New Page'){
			unset($_SESSION['p_photo']);
		}
	}
	
	function edit_category(){
		$template_path = VIEW_PATH.'home/categories';
		$this->var["templates"] = get_template($template_path);

		if (!isset($this->param[0]) || empty($this->param[0])) { common::Redirect('auth','pagenotfound'); }
		$blog_category = new blog_category();
		$this->var['category'] = $blog_category->find_by_id($this->param[0]);
		$this->var['category'] = array_pop($this->var['category']);
		if (empty($this->var['category'])) { common::Redirect('auth','pagenotfound'); }

		if(common::setPost('save')=='Save'){
			$this->var['category']->active = onOff(common::setPost('active'));
			if($this->var['category']->validate_edit($this->var['templates'])==false){
				$this->var['errors'] = $this->var['category']->get_errors();
			}else{
				$this->var['category']->title = common::setPost('title');
				$this->var['category']->alias = common::setPost('alias');
				$this->var['category']->template = common::setPost('template');
				$this->var['category']->date_modified = date('Y-m-d H:m:s');
				$this->var['category']->modified_by = $_SESSION['auth']->id;
				$this->var['category']->update_me();

				common::Redirect($this->group.'/'.$this->controller,'category');
			}
		}
	}
	
	function edit() {
		if (empty($this->param[0])) { common::Redirect('auth','pagenotfound'); }
		$blog_content = new blog_content();
		$this->var['blog_content'] = $blog_content->find_by_id($this->param[0]);
		$this->var['blog_content'] = array_pop($this->var['blog_content']);
		if (empty($this->var['blog_content']) || $this->var['blog_content']->deleted==1 || $this->var['blog_content']->archived==1) { 
			common::Redirect('auth','pagenotfound'); 
		}
		
		$this->var['post_arr'] = array();
		$template_path = VIEW_PATH.'home/pages';
		$this->var["templates"] = get_template($template_path);

		$this->var['category'] = new blog_category();
		$this->var['category'] = $this->var['category']->find_all();

		$blog_content_item = new blog_content_item();
		if($this->var['blog_content']->thumbnail!=''){ 
			$my_var = new StdClass();
			$my_var->path = $this->var['blog_content']->thumbnail;
			$my_var->name = $this->var['blog_content']->thumbnail;
			$this->var['thumbnail'] = $my_var;
		}

		if(common::setPost("submit") == "Remove Thumbnail"){
			$this->var['blog_content']->thumbnail  = '';
			$this->var['blog_content']->update_me();
			$this->var['thumbnail']='';
		}

		
		$this->var['items'] = $blog_content_item->find_except_thumbnail($this->var['blog_content']->system_code);

		$date = date('Y-m');
		$temp_path = IMAGE_UPLOAD_PATH.'upload/'.$date.'/';
		$temp_display_path = IMAGE_PATH.'upload/'.$date.'/';
		
		if(common::setPost('save')=='Archive'){
			$this->var['blog_content']->archived = 1;
			$this->var['blog_content']->date_modified = date('Y-m-d H:m:s');
			$this->var['blog_content']->modified_by = $_SESSION['auth']->id;
			$this->var['blog_content']->update_me();
			common::Redirect($this->group.'/'.$this->controller, 'index');
		}

		if(common::setPost('save')=='Save as draft' || common::setPost('save')=='Publish' || common::setPost("submit") == "Remove Thumbnail"){
			foreach($_POST as $key=>$value){
				if(preg_match('/itemtype_/',$key)){
					$no = preg_replace('/itemtype_/','',$key);

					if(preg_match('/^[0-9]{1,2}$/',$no)){
						array_push($this->var['post_arr'] ,$no);
					}
				}
			}
			
			if(common::setPost('save')=='Publish'){$published=1;}else{$published=0;}

			$this->var['blog_content']->title = common::setPost('title');
			$this->var['blog_content']->content = common::setPost('content');
			$this->var['blog_content']->alias = common::setPost('alias');
			$this->var['blog_content']->short_desc = common::setPost('short_desc');
			$this->var['blog_content']->blog_category_id = common::setPost('category');
			$this->var['blog_content']->template = common::setPost('template');
			$this->var['blog_content']->position = common::setPost('position');
			$this->var['blog_content']->published = $published;
			$this->var['blog_content']->date_published = reformat_date(common::setPost('publish_date'));
		}

		if(common::setPost('save')=='Save as draft' || common::setPost('save')=='Publish' ){
			if ($this->var['blog_content']->validate_edit($this->var['templates'],$this->var['post_arr'])==false) {
				$this->var['errors'] = $this->var['blog_content']->get_errors();//print_R($this->var['errors']);
			} else {
				$this->var['blog_content']->date_modified = date('Y-m-d H:m:s');
				$this->var['blog_content']->modified_by = $_SESSION['auth']->id;
				$this->var['blog_content']->update_me();
				
				$blog_content_item->delete_except_thumbnail($this->var['blog_content']->system_code);
				foreach($this->var['post_arr'] as $key=>$value){
					$item_type = common::setPost('itemtype_'.$value);
					$value_name = common::setPost('value_name_'.$value);
					$value1 = common::setPost('value1_'.$value);
					$value2 = common::setPost('value2_'.$value);

					if($item_type!=''&& $value1!=''){
						if($item_type!='slideimage' && $item_type!='custom'){$value2='';}
						if($item_type!='custom'){$value_name='';}

						$new_item = $blog_content_item->Create('blog_content_item',array('system_code'=>$this->var['blog_content']->system_code,
							'item_type'=>$item_type,'value'=>$value1,'value2'=>$value2,'value_name'=>$value_name
						));
						$new_item->update_me();
					}
				}
				common::Redirect($this->group.'/'.$this->controller, 'index');
			}
		}
	}	
	
	function delete_category(){
		if (!isset($this->param[0]) || empty($this->param[0])) { common::Redirect('auth','pagenotfound'); }
		$blog_category = new blog_category();
		$this->var['category'] = $blog_category->find_by_id($this->param[0]);
		$this->var['category'] = array_pop($this->var['category']);
		if (empty($this->var['category'])) { common::Redirect('auth','pagenotfound'); }
		
		$this->var['category']->deleted = 1;
		$this->var['category']->date_modified = date('Y-m-d H:m:s');
		$this->var['category']->modified_by = $_SESSION['auth']->id;
		$this->var['category']->update_me();
		
		common::Redirect($this->group.'/'.$this->controller,'category');
	}
	
	function view() {
		if (empty($this->param[0])) { common::Redirect('auth','pagenotfound'); }
		$blog_content = new blog_content();
		$this->var['blog_content'] = $blog_content->find_by_id($this->param[0]);
		$this->var['blog_content'] = array_pop($this->var['blog_content']);
		if (empty($this->var['blog_content']) || $this->var['blog_content']->deleted==11) { 
			common::Redirect('auth','pagenotfound'); 
		}
		$blog_content_item = new blog_content_item();
		$this->var['items'] = $blog_content_item->find_except_thumbnail($this->var['blog_content']->system_code);
	}
	
	function delete() {
		if (empty($this->param[0])) { common::Redirect('auth','pagenotfound'); }
		$blog_content = new blog_content();
		$this->var['blog_content'] = $blog_content->find_by_id($this->param[0]);
		$this->var['blog_content'] = array_pop($this->var['blog_content']);
		if (empty($this->var['blog_content']) || $this->var['blog_content']->deleted==1 || $this->var['blog_content']->archived==1 || $this->var['blog_content']->published==1) { 
			common::Redirect('auth','pagenotfound'); 
		}else{
   			$this->var['blog_content']->deleted=1;
			$this->var['blog_content']->date_modified = date('Y-m-d H:m:s');
			$this->var['blog_content']->modified_by = $_SESSION['auth']->id;
			$this->var['blog_content']->update_me();
			common::Redirect($this->group.'/'.$this->controller,'index');
		}
		exit;
	}
	
	function upload_thumb(){
		$close = false;
		$blog_content = new blog_content();
		$content = array();$file1_display='';
		if(isset($this->param[0]) && $this->param[0]!=''){
			$blog_content = $blog_content->find_by_id($this->param[0]);
			$blog_content = array_pop($blog_content);
			if (!empty($blog_content) && $blog_content->deleted==0  && $blog_content->archived==0) { 
				$content = $blog_content;
			}
		}

		if(common::setPost("upload") == "Upload"){ //UPLOAD PHOTO and store in the session for future use.
			$userfile = $_FILES['myfile'];
			$img_name = $_FILES['myfile']['name'];
			$userfile_type = $_FILES['myfile']['type'];
			$filesize = $_FILES['myfile']['size'];
			$fileContents = "";
			$ext = strtolower(substr(strrchr($img_name, "."), 1));

			$fileupload = $blog_content->Create('blog_content',array('file_name'=>$img_name,'file_type'=>$userfile_type,'ext'=>$ext,'file_size'=>$filesize));
			if($fileupload->validate_photo()==false){
				$this->var['errors'] = $fileupload->get_errors();
			}else{
				$date = date('Y-m');
				$temp_path = IMAGE_UPLOAD_PATH.'upload/'.$date.'/';
				$temp_display_path = IMAGE_PATH.'upload/'.$date.'/';
				if(!is_dir($temp_path)){
					mkdir($temp_path,0777);
				}
				chmod($temp_path, 0777); 
				
				$file1_display = $temp_display_path.$img_name;

				$my_var = new StdClass();
				$my_var->path = $temp_display_path.$img_name;
				$my_var->name = $img_name;
				if(empty($content)){
					if(move_uploaded_file($_FILES["myfile"]["tmp_name"],$temp_path.$img_name)==false){
						$fileupload->add_error('thumbnail',"File upload failed. Please try again.");
						$this->var['errors'] = $fileupload->get_errors();
					}else{
						$_SESSION['p_photo'] = $my_var;
						unset($_FILES);
						$close = true;
					}
				}else{//edit
					if(move_uploaded_file($_FILES["myfile"]["tmp_name"],$temp_path.$img_name)==false){
						$fileupload->add_error('thumbnail',"File upload failed. Please try again.");
						$this->var['errors'] = $fileupload->get_errors();
					}else{
						$content->thumbnail = $temp_display_path.$img_name;
						$content->update_me();
						$close = true;
					}
				}
				
			}
		}
		
		include(VIEW_PATH.'/'.$this->group.'/'.$this->controller.'/upload_thumb.html');
		exit;
	}

	function upload_image(){
		$close=false;$param='';$item_type='image';$file1='';$file2='';$file1_display='';$file2_display='';
		if(isset($this->param[0]) && $this->param[0]!=''){
			$param = $this->param[0];
		}
		if(isset($this->param[1]) && $this->param[1]!='image'){
			$item_type = $this->param[1];
		}

		if(common::setPost('upload')=='Upload and Insert'){
			$files = $_FILES['newimage'];
			$names = array( 'name' => 1, 'type' => 1, 'tmp_name' => 1, 'error' => 1, 'size' => 1);
			foreach ($files as $key => $part) {
				// only deal with valid keys and multiple files
				$key = (string) $key;
				if (isset($names[$key]) && is_array($part)) {
					foreach ($part as $position => $value) {
						$files[$position][$key] = $value;
					}
					// remove old key reference
					unset($files[$key]);
				}
			}

			$new_image = new blog_content();
			if($new_image->validate_newslideimage($files,$item_type)==false){
				$this->var['errors'] = $new_image->get_errors();
			}else{
				$date = date('Y-m');
				$image_path = IMAGE_UPLOAD_PATH.'upload/'.$date.'/';
				$image_display_path = IMAGE_PATH.'upload/'.$date.'/';
				if(!is_dir($image_path)){
					mkdir($image_path,0777);
				}
				chmod($image_path, 0777); 

				$file1 = $image_path.$files[0]['name'];
				if(move_uploaded_file($files[0]["tmp_name"],$file1)==false){
					$new_image->add_error('error1',"Display image upload failed. Please try again.");
					$this->var['errors'] = $new_image->get_errors();
				}else{
					$file1_display = $image_display_path.$files[0]['name'];
				}
				
				if($item_type=='slideimage'){
					if(common::setPost('createThumb')=='on'){
						$stop = strrpos($files[0]['name'], ".");
						$newname = substr($files[0]['name'],0,$stop);
						$file2 = $image_path.$newname.'_thumbnail.jpg';
						copy($file1,$file2);
   
						$thumbwidth = common::setPost('width');
						$thumbheight = common::setPost('height');
   
						list($width, $height, $type, $attr) = getimagesize($file2);
						$imageOutput = imagecreatetruecolor($thumbwidth, $thumbheight);
						$imageSource = imagecreatefromjpeg($file2);
						$result = imagecopyresampled($imageOutput, $imageSource,0, 0, 0, 0,$thumbwidth, $thumbheight, $width,$height);
						$result = imagejpeg($imageOutput, $file2, 100);
						if($result==false){
							$new_image->add_error('error',"Error occurs.");
							$this->var['errors'] = $new_image->get_errors();
						}else{
							$file2_display = $image_display_path.$newname.'_thumbnail.jpg';
							unset($_POST);
						}
					}else{
						$stop = strrpos($files[1]['name'], ".");
						$newname = substr($files[1]['name'],0,$stop);
						$file2 = $image_path.$newname.'_thumbnail.jpg';
						//$file2 = $image_path.$files[1]["name"].'_thumbnail';
   
						if(move_uploaded_file($files[1]["tmp_name"],$file2)==false){
							$new_image->add_error('error1',"Thumbnail image upload failed. Please try again.");
							$this->var['errors'] = $new_image->get_errors();
						}else{
							$file2_display = $image_display_path.$newname.'_thumbnail.jpg';
						}
					}
				}
				if($this->var['errors']==null){
					$close=true;
				}
			}
		}

		include(VIEW_PATH.'/'.$this->group.'/'.$this->controller.'/upload_image.html');
		exit;
	}
	
	function library_slideimage(){
		$close=false;$param='';$item_type='image';$param_folder='';$file1='';$file2='';$file1_display='';$file2_display='';
		if(isset($this->param[0]) && $this->param[0]!=''){
			$param = $this->param[0];
		}
		if(isset($this->param[1]) && $this->param[1]!='image'){
			$item_type = $this->param[1];
		}
		if(isset($this->param[2]) && $this->param[2]!=''){
			$param_folder = $this->param[2];
		}
		
		$image_path = IMAGE_UPLOAD_PATH.'upload/';
		/* read dir */
		$this->var["folder"] = array();
		$this->var["file"] = array();

		if ($handle = opendir($image_path)) {
			while (false !== ($file = readdir($handle))) {
				if($file!='.' && $file!='..'){	
					if(is_dir($image_path.$file)){
						array_push($this->var['folder'],$file);
					}
				}
			}
			closedir($handle);
		}
		ksort($this->var['folder']);


		/* read dir */
		if($param_folder!=''){
			$folder_path = $image_path.$param_folder.'/';
			$folder_display_path = IMAGE_PATH.'upload/'.$param_folder.'/';
		}else{
			$folder_path = $image_path;
			$folder_display_path = IMAGE_PATH.'upload/';
		}
		if(is_dir($folder_path)){
			if ($handle = opendir($folder_path)) {
				while (false !== ($file = readdir($handle))) {
					if($file!='.' && $file!='..'){	
						if(!is_dir($folder_path.$file)){
							list($width, $height, $type, $attr) = getimagesize($folder_path.$file);
							$this->var['file']['file'.$file] = array("name"=>$file,'width'=>$width,'height'=>$height,'link'=>$folder_display_path.$file);
						}
					}
				}
				closedir($handle);
			}
			ksort($this->var['file']);
		}
		include(VIEW_PATH.'/'.$this->group.'/'.$this->controller.'/library_slideimage.html');
		exit;
	}
}
?>