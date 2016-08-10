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
		$this->locked();
		$this->seo();
		$this->var['mainHighlight'] = '';
	}

	function index() {
		$blog_content = new blog_content();
		$this->var['content'] = $blog_content->find_by_alias('index');
		$this->var['content'] = array_pop($this->var['content']);
		
		$this->var['sliderimg'] = array();
		$this->var['slidertitle'] = array();
		$this->var['slidercontent'] = array();

		if (!empty($this->var['content']) && $this->var['content']->deleted==0 && $this->var['content']->archived==0
		&&$this->var['content']->published==1 || $this->var['content']->datediff >=0) {
			$blog_content_item = new blog_content_item();
			$this->var['sliderimg'] = $blog_content_item->find_custom_byName($this->var['content']->system_code,'slideimage');
			$slidertitle = $blog_content_item->find_custom_byName($this->var['content']->system_code,'slideimg_title');
			if(!empty($slidertitle)){
				foreach($slidertitle as $k=>$v){
					array_push($this->var['slidertitle'],$v->value);
				}
			}
			
			$slidercontent = $blog_content_item->find_custom_byName($this->var['content']->system_code,'slideimg_content');
			if(!empty($slidercontent)){
				foreach($slidercontent as $k=>$v){
					array_push($this->var['slidercontent'],$v->value);
				}
			}

		}
	}
	
	function show(){
		if (empty($this->param[0])) { common::Redirect('auth','pagenotfound'); }
		$blog_content = new blog_content();
		$this->var['content'] = $blog_content->find_by_alias($this->param[0]);
		$this->var['content'] = array_pop($this->var['content']);
		if (empty($this->var['content']) || $this->var['content']->deleted==1 || $this->var['content']->archived==1
		|| $this->var['content']->published==0 || $this->var['content']->datediff <0
		) { 
			common::Redirect('auth','pagenotfound'); 
		}else{
			if($this->var['content']->metakey!=''){$this->var['mkmetakey']=$this->var['content']->metakey;}
			if($this->var['content']->metadesc!=''){$this->var['mkmetadesc']=$this->var['content']->metadesc;}
		}
		//check if reach publish date, and category available
		$this->var['mainHighlight'] = $this->param[0];
		$template_path = VIEW_PATH.'home/pages/';
		if($this->var['content']->template!=''){
			if(is_file($template_path.$this->var['content']->template)){
				include($template_path.$this->var['content']->template);
				exit;
			}
		}
		include($template_path.$this->action.'.html');
		exit;
	}
	
	function indexpopup(){
		$this->var['slider'] = '';
		$blog_content = new blog_content();
		$homeslider = $blog_content->find_by_alias('homeslider');
		$homeslider = array_pop($homeslider);
		if (!empty($homeslider) && $homeslider->deleted==0 && $homeslider->archived==0&&$homeslider->published==1 || $homeslider->datediff >=0) {
			$blog_content_item = new blog_content_item();
			$this->var['slider'] = $blog_content_item->find_all_byType($homeslider->system_code,'slideimage');
		}
		include(VIEW_PATH.$this->group.'/'.$this->controller.'/'.$this->action.'.html');
		exit;
	}

}
?>
