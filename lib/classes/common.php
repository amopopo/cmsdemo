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
class common{
	/*PREVENT EMPTY VALUE*/
	static public function setPost($value){
		if(isset($_POST[$value])){
			return $_POST[$value];
		} else{
			return '';
		}
	}
	
	static public function setField($value,$org_value){
		if(isset($_POST[$value])){
			return $_POST[$value];
		} else{
			return $org_value;
		}
	}
	
	/*VALIDATIONS */
	static public function validate_emptiness($value){
		if(isset($_POST[$value]) && $_POST[$value]!=''){
			return true;
		} else{
			return false;
		}
	}
	
	static public function validate_nosymbol($value){
		if(isset($_POST[$value]) && $_POST[$value]!=''){
			$pattern = "/^[a-zA-Z0-9\ ]+$/";
			if(preg_match($pattern,$_POST[$value])==true){
				return true;
			}else{
				return false;
			}
		} else{
			return false;
		}
	}
	
	static public function validate_alphanumeric($value){
		if(isset($_POST[$value]) && $_POST[$value]!=''){
			$pattern = "/^[a-zA-Z0-9\ ]+$/";
			if(preg_match($pattern,$_POST[$value])==true){
				return true;
			}else{
				return false;
			}
		} else{
			return false;
		}
	}
	
	static public function validate_email($value){
		if(isset($_POST[$value]) && $_POST[$value]!=''){
			$pattern = "/^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,3})$/";
			if(preg_match($pattern,$_POST[$value])==true){
				return true;
			}else{
				return false;
			}
		} else{
			return false;
		}
	}
	
	static public function validate_numeric($value){
		if(isset($_POST[$value]) && $_POST[$value]!=''){
			$pattern = "/^[-]?[0-9]+$/";
			if(preg_match($pattern,$_POST[$value])==true){
				return true;
			}else{
				return false;
			}
		} else{
			return false;
		}
	}
	
	static public function validate_float($value){
		if(isset($_POST[$value]) && $_POST[$value]!=''){
			$pattern = "[-]?\b[0-9]*\.?[0-9]+\b";
			if(preg_match($pattern,$_POST[$value])==true){
				return true;
			}else{
				return false;
			}
		} else{
			return false;
		}
	}
	
	static public function validate_date($value){
		$date_format = "d/m/Y";
		$day = "";$month = "";$year = "";
	
		$value = common::setPost($value);
		$my_array = array ("d/m/Y" => "/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}/");
		$pattern = $my_array[$date_format];
		
		if(!preg_match($pattern,$value)){
			return false;
		}else{
			if(substr_count($value, '/') ==2){
				list($month,$day,$year) = explode("/",$value);
			}
			return common::validDate($day,$month,$year);
		}
	}
	
	static public function validDate($month,$day,$year){
		if (!preg_match('/^[0-9]{1,2}$/', $day)){return false;}
		if (!preg_match('/^[0-9]{1,2}$/', $month)){return false;}
		if (!preg_match('/^[0-9]{2,4}$/', $year)){return false;}
	
		return checkdate($month,$day,$year);
	}
	
	/*LINKS & REDIRECT*/
	static public function Redirect($controller,$action,$arguement=array()){
		$file_path = HTTP_PATH .$controller."/".$action;
		if(!empty($arguement)){
			foreach($arguement as $k=>$v){
				if(!empty($v)){
					$file_path .= "/".$v;
				}
			}
		}
		header("Location:". $file_path);
	}
	
	static public function LinkTo($controller,$action,$value,$arguement=array(),$other_arguement=null,$title=null){
		$file_path = HTTP_PATH .$controller."/".$action;
		if(!empty($arguement)){
			foreach($arguement as $k=>$v){
				if(!empty($v)){
					$file_path .= "/".$v;
				}
			}
		}
		$output = "<a href='".$file_path."' title='".$title."'";
		if(!empty($other_arguement)){
			$output .= $other_arguement;
		}
		$output .= ">".$value."</a>";
		return $output;
	}
	
	static public function Path($controller,$action,$arguement=array()){
		$file_path = HTTP_PATH .$controller."/".$action;
		if(!empty($arguement)){
			foreach($arguement as $k=>$v){
				if(!empty($v)){
					$file_path .= "/".$v;
				}
			}
		}
		return $file_path;
	}
	
	static public function imageLinkTo($controller,$action,$image,$arguement=array(),$other_arguement=null){
		$file_path = HTTP_PATH .$controller."/".$action;
		if(!empty($arguement)){
			foreach($arguement as $k=>$v){
				if(!empty($v)){
					$file_path .= "/".$v;
				}
			}
		}
		$output = "<a href='".$file_path."' title='".ucfirst($action)."' ";
		if(!empty($other_arguement)){
			$output .= $other_arguement;
		}
		$output .= "><img src='".IMAGE_PATH.$image."'></a>";
		return $output;
	}
	
	static public function imagePath($image,$title=null){
		echo "<img src='".IMAGE_PATH.$image."' title='".$title."' align='bottom'>";
	}

	static public function formAction($controller,$action,$arguement=array()){
		$path = "action='".HTTP_PATH .$controller."/".$action;
		if(!empty($arguement)){
			foreach($arguement as $k=>$v){
				if(!empty($v)){
					$path .= "/".$v;
				}
			}
		}
		$path .= "'";
		echo $path;
	}
	
	static public function DefaultMapping(){
		header("Location:". HTTP_PATH);
	}
	
	/*PAGINATION*/
	static public function paginate($total_records,$limit,$param,$sort){ 
		$cur_page = 0; $record_start = 0;
		$pattern = "/^[0-9]+$/";
		$arr_paginate = new StdClass();
		$arr_paginate->records = $total_records;
		$arr_paginate->sort = $sort;
		$arr_paginate->offset = 0;
		$arr_paginate->limit = $limit;
		$arr_paginate->page = 1;
		if(!empty($param) && is_array($param)){
			if(isset($param[0]) && !empty($param[0]) && preg_match($pattern,$param[0])==true){
				$arr_paginate->page = $param[0];
			} 
		}
		$arr_paginate->offset = (($arr_paginate->limit * ($arr_paginate->page -1 )) ) ;
		if($arr_paginate->page!=1){
			//$arr_paginate->offset += 1;
		}
		$arr_paginate->page_no = ceil($arr_paginate->records/$arr_paginate->limit);
		return $arr_paginate;
	}
	
	static public function page($total_records,$total_page,$cur_page,$group,$controller,$action,$param=array()){
		$links = '';
		$output = "<div id='pagination'><div id='left'>Total ".$total_records." record(s)</div>";
		$pattern = "/^[0-9]+$/";
		$curpage = 1;
		$path = common::path($group.'/'.$controller,$action).'/';
		$param_path = '';
		if(!empty($param)){
			foreach($param as $k=>$v){
				if(!empty($v)){
					$param_path .= "/".$v;
				}
			}
		}
		if(preg_match($pattern,$cur_page)==true){
			$curpage = $cur_page;
		}

		if(preg_match($pattern,$total_page)==true && $total_page > 1){
			$output .= "<div id='right'>Go to Page ";
			
			$output .= "<select onChange=\"document.location=this.value+'".$param_path."'\">";
				for($i=1;$i<=$total_page;$i++){
					if($cur_page==$i){
						$output .= "<option value='".$path.$i."' selected='selected'>".$i."</option>";
					}else{
						$output .= "<option value='".$path.$i."' >".$i."</option>";
					}
				}
			$output .= "</select></div>";
			
			
		}

		$output .= "<div class='clear'></div></div>";
		return $output;
	}
	
	static public function numpage($total_records,$total_page,$cur_page,$group,$controller,$action,$param=array()){
		$links = '';
		$output = "<div id='num_pagination'><ul>";
		$pattern = "/^[0-9]+$/";
		$curpage = 1;
		if(!empty($group)){
			$path = common::path($group.'/'.$controller,$action).'/';
		}else{$path = common::path($controller,$action).'/';}
		$param_path = '';
		if(!empty($param)){
			foreach($param as $k=>$v){
				if(!empty($v)){
					$param_path .= "/".$v;
				}
			}
		}
		if(preg_match($pattern,$cur_page)==true){
			$curpage = $cur_page;
		}

		if($total_page >1){
			$prev = '';$first='';$last = '';$next = '';
			if($cur_page) {
				if($cur_page >1){ 
					$prev = '<li><a href="'.$path.($cur_page -1).$param_path.'"><img src="'.IMAGE_PATH.'page-previous.jpg" alt="Previous" /></a></li>'; 
					$first = '<li><a href="'.$path.'1'.$param_path.'"><img src="'.IMAGE_PATH.'page-first.jpg" alt="Previous" /></a></li>'; 
				}
			}
			if($cur_page <$total_page){ 
				$next = ' <li><a href="'.$path.($cur_page+1).$param_path.'"><img src="'.IMAGE_PATH.'page-next.jpg" alt="Previous" /></a></li>'; 
				$last = ' <li><a href="'.$path.$total_page.$param_path.'"><img src="'.IMAGE_PATH.'page-last.png" alt="Previous" /></a></li>';
			}
			$output.= $first;
			$output.= $prev;
   
			/*calculate number*/
			$a=0;$b = 0;
			$startx = $cur_page - 5;
			if($startx < 1){
				$a = 1 - $startx;
				$startx = 1;
			}
			$stopx = $cur_page + 4 + $a;
			if($stopx > $total_page){ //echo $stopx;
				$b = $stopx - $total_page;
				$stopx = $total_page;
			}
   
			$startx -= $b;
			if($startx < 1){ $startx = 1;}
   
			for($i=$startx;$i<$stopx;$i++){
				if($i==$cur_page){
					$output.= '<li class="bordered"><span>'.$i.'</span></li>';
				}else{
					$output.= '<li class="bordered"><a href="'.$path.$i.$param_path.'">'.$i.'</a></li>';
				}
			}
			if($i==$cur_page){
				$output.= '<li><b>'.$i.'</b></li>';
			}else{
				$output.= '<li><a href="'.$path.$i.$param_path.'">'.$i.'</a></li>';
			}
   
			$output.= $next;
			$output.= $last;
		}
		$output.="</ul>";
		//$output .= "<div id='total_records'>Total ".$total_records." record(s)</div>";
		$output .= "<div class='clear'></div></div>";

		return $output;
	}

/****** end build pagination links ******/


	/*ERROR MESSAGE*/
	static public function error_box($error,$field){
		if(!empty($error) && is_array($error) && !empty($field) && isset($error[$field])){
			echo "class='errorbox'";
		}
	}

	static public function error_msg($error,$field){
		if(!empty($error) && is_array($error) && !empty($field) && isset($error[$field])){
			echo "<div class='errormsg'>".$error[$field]."</div>";
		}
	}
}

?>