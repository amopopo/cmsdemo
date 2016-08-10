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
class Captcha{
	public $width = 120;
	public $height = 30;
	public $code = '';

	function __construct($width,$height){
		$this->width = $width;
		$this->height = $height;
	}
	
	function randomLetters($length){
		$code = '';
		$baseList = '0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if($length <=4){
			$length = 4;
		}
		
		for( $i=0; $i<$length; $i++ ) {
		   $actChar = substr($baseList, rand(0, strlen($baseList)-1), 1);
		   $code .= $actChar;
		}
		return $code;
	}
	
	function captchaImage($code){
		$image = @imagecreate($this->width, $this->height) or die('Error');
		$background = imagecolorallocate($image, 117,170,179);
		/*set image background colors and lines*/
		for( $i=0; $i<10; $i++ ) {
		    /*line color 1*/
		    imageline($image, 
				 mt_rand(0,$this->width), mt_rand(0,$this->height), 
				 mt_rand(0,$this->width), mt_rand(0,$this->height), 
				 imagecolorallocate($image, 89,118,66));
				 
		    /*line color 2*/
			imageline($image, 
				 mt_rand(0,$i), mt_rand(0,$i*$this->height), 
				 mt_rand(0,$this->width), mt_rand(0,$this->height), 
				 imagecolorallocate($image, 143,143,143));

			/*line color 3*/
			imageline($image, 
				 mt_rand(0,$i), mt_rand(0,$i*$this->height), 
				 mt_rand(0,$this->width), mt_rand(0,$this->height), 
				 imagecolorallocate($image, 129,192,236));
		}
		/*set image characters*/
		for( $i=0,$x=0; $i< strlen($code); $i++ ) {
			$actChar = substr($code,$i,1);
			$x += 10 + mt_rand(0,10);
			imagechar($image, 200, $x, 5, $actChar,imagecolorallocate($image,255, 255,255));
		}

		header('Content-type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);
	}
}

?>