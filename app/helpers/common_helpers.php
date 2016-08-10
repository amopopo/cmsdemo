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
function shorter_name($value,$len){
	if(strlen($value)>$len){
		$n = substr($value, 0, $len);
		return $n."...";
	}else{
		return $value;
	}
}

function yesNo($value){
	if($value==1){return 'Yes';}else{return 'No';}
}

function onOff($value){
	if($value=='on'){return 1;}else{return 0;}
}

function formatDate($value){
	if(empty($value) || $value == "0000-00-00" || $value == "0000-00-00 00:00:00" ){
		return "";	
	}else{
		if(isset($_SESSION['auth']->date_format) && !empty($_SESSION['auth']->date_format)){
			return  date($_SESSION['auth']->date_format,strtotime("$value")); 
		}else{
			return  date('Y-m-d',strtotime("$value")); 
		}
	}
}

function formatSize( $data ) {
	if( $data < 1024 ) {// bytes
		return $data . " bytes";
	}else if( $data < 1048576 ) { // kilobytes
		return round( ( $data / 1024 ), 1 ) . " KB";
	}else if( $data < 1073741824){// megabytes
		return round( ( $data /1048576), 1 ) . " MB";
	}else{// gigabytes
	   return round( ( $data /1073741824 ), 1 ) . " GB";
	}  
}

function dateRange($date1,$date2){
	return ((strtotime($date2)-strtotime($date1))/86400);
}

function js_formatDate($format){
	switch($format){
		case 'd/m/Y':
			return "dd/MM/yyyy";
			break;
		case 'm/d/Y':
			return "MM/dd/yyyy";
			break;
		case 'Y/m/d':
			return "yyyy/MM/dd";
			break;
		case 'd-m-Y':
			return "dd-MM-yyyy";
			break;
		case 'm-d-Y':
			return "MM-dd-yyyy";
			break;
		case 'Y-m-d':
			return "yyyy-MM-dd";
			break;
		default:
			return "dd/MM/yyyy";
			break;
	}
}

function reformat_date($value){
	if(empty($value)){ return "";	}
	$day = "";
	$month = "";
	$year = "";
	$m = "";

	if(substr_count($value, '/') ==2){
		list($day,$month,$year) = explode("/",$value);
	}
	$yr = $year."-".$month."-".$day.' '.date("H:i:s");
	return date("Y-m-d H:i:s",strtotime("$yr"));
}

function randomLetters($length){
	$code = '';
	$baseList = '0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	for( $i=0; $i<$length; $i++ ) {
	   $actChar = substr($baseList, rand(0, strlen($baseList)-1), 1);
	   $code .= $actChar;
	}
	return $code;
}

/* FUNCTIONS FOR FILE */ 
function GetFLVDuration($file){
	if (file_exists($file)){
		$handle = fopen($file, "r");
		$contents = fread($handle, filesize($file));
		fclose($handle);

		if (strlen($contents) > 3){
			if (substr($contents,0,3) == "FLV"){
				$taglen = hexdec(bin2hex(substr($contents,strlen($contents)-3)));
				if (strlen($contents) > $taglen){
					$duration = hexdec(bin2hex(substr($contents,strlen($contents)-$taglen,3)));
					return $duration;
				}
			}
		}
	}
	return false;
}

function msecConvert($data){
	$msec_mm = 1000 * 60;          // millisecs per minute
	$msec_ss = 1000;               // millisecs per second

	$balance = $data % $msec_mm;

	$mins = ($data - ( $data % $msec_mm ) )/$msec_mm;
	if($mins < 10){
		$mins = "0".$mins;
	}

	$secs = round(($data % $msec_mm) / $msec_ss);
	if($secs < 10 ){
		$secs = "0".$secs;
	}

	return  $mins.":".$secs;
}

function removeDIR($target_path){
	if(file_exists($target_path)){
		if ($handle = opendir($target_path)) {
			while (false !== ($file = readdir($handle))) {
				if($file!='.' && $file!='..'){
					if(is_dir($target_path.'/'.$file)){
						removeDIR($target_path.'/'.$file);
						rmdir($target_path.'/'.$file);
					}else{
						unlink($target_path.'/'.$file);
					}
				}
			}
			closedir($handle);
		}
	}
}
function tomorrow(){
		$tmr = mktime(0, 0, 0, date("m"), date('d') + 1,   date("Y"));

	return date('d/m/Y',$tmr);
}
?>