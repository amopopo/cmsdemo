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

 /*** error reporting on ***/
 //error_reporting(E_STRICT);
 error_reporting(E_ALL);

 session_set_cookie_params(115200);
 session_start();

 define("LTP","^%&&*287984_w$#+89484dfdsf");
 define("LTP2","//98155648%)*dsfgf57sds12");
 date_default_timezone_set('Asia/Singapore');

/*** define the site path ***/
 include('lib/config/config.php');
  $root_dir = dirname($_SERVER['PHP_SELF']); // echo $root_dir;
  if ($root_dir == "/" OR $root_dir == "\\") {$root_dir = "";}
  //if ($root_dir != ""){$rootpath = $_SERVER['DOCUMENT_ROOT'] . $root_dir . "/";}else{$rootpath = $_SERVER['DOCUMENT_ROOT'] . "/";}

 define ('ROOT_PATH', $root_dir.'/');
 define ('CLASSES_PATH', 'lib/classes/');
 define ('CONTROLLER_PATH', 'app/controller/');
 define ('MODEL_PATH', 'app/model/');
 define ('HELPER_PATH', 'app/helpers/');
 define ('VIEW_PATH', 'app/views/');
 define ('IMAGE_UPLOAD_PATH', 'public/images/');

 //if(!empty($_SERVER['HTTPS'])){ $url = "https://".$_SERVER['SERVER_NAME'];}else{  
 $url = "http://".$_SERVER['SERVER_NAME'];
 //}
 define ('HTTP_PATH', $url.ROOT_PATH);
 
 define ('PUBLICFILE_PATH', HTTP_PATH.'public/');
 define ('JAVASCRIPT_PATH', PUBLICFILE_PATH.'javascripts/');
 define ('CSS_PATH', PUBLICFILE_PATH.'stylesheets/');
 define ('IMAGE_PATH', PUBLICFILE_PATH.'images/');



 define('MYACTIVERECORD_CONNECTION_STR', "mysql://$dbusername:$dbpass@$dbhost/$dbname");

 /*** include class files ***/
 include CLASSES_PATH.'DBconfig.php';
 $DBconfig = new OBJ();
 $DBconfig->connection();
 
 include CLASSES_PATH.'common.php';
 include CLASSES_PATH.'login.php';
 include CLASSES_PATH.'controller_base.php';
 include CLASSES_PATH.'router.php';
 include CLASSES_PATH.'template.php';
 include CLASSES_PATH.'captcha.php';
 
 if(file_exists(HELPER_PATH.'common_helpers.php')==true){require_once( HELPER_PATH.'common_helpers.php');}

 /*** auto load model classes ***/
 function __autoload($class_name) {
        $filename = strtolower($class_name) . '.php';
        $file = MODEL_PATH .$filename;
        if (file_exists($file) == false){ return false; } include ($file);
 } 
?>