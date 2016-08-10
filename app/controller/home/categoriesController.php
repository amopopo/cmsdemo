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
Class categoriesController Extends baseController {
	function beforeFilter() {
		$this->locked();
		$this->var['errors'] = '';
		$this->seo();
		$this->var['mainHighlight'] = '';
	}

	function show(){
		if (empty($this->param[0])) { common::Redirect('auth','pagenotfound'); }
		$blog_category = new blog_category();
		$this->var['category'] = $blog_category->find_by_alias($this->param[0]);
		$this->var['category'] = array_pop($this->var['category']);
		if (empty($this->var['category']) || $this->var['category']->deleted==1 || $this->var['category']->active==0) { 
			common::Redirect('auth','pagenotfound'); 
		}else{
			$this->var['mainHighlight'] = $this->param[0];
			if($this->var['category']->metakey!=''){$this->var['mkmetakey']=$this->var['category']->metakey;}
			if($this->var['category']->metadesc!=''){$this->var['mkmetadesc']=$this->var['category']->metadesc;}
		}
		$blog_content = new blog_content();
		$this->var['listing'] = $blog_content->find_by_category($this->var['category']->id);
		$template_path = VIEW_PATH.'home/categories/';

		if($this->var['category']->template!=''){
			if(is_file($template_path.$this->var['category']->template)){
				include($template_path.$this->var['category']->template);
				exit;
			}
		}
		include($template_path.$this->action.'.html');
		exit;
	}
}
?>
