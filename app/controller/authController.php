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
 
Class authController Extends baseController {        
        function pagenotfound(){
             include(VIEW_PATH. "auth/pagenotfound.html");
             exit;
        } 
        
        function password_expired(){
                $errors = '';
                $sys_user = new sys_user();
                $user = $sys_user->find_by_id($_SESSION['auth']->id);
                $user = array_pop($user);
                if(common::setPost('submit')=='Update'){
                     if($user->update_pass()==false){
                          $errors = $user->get_errors();
                     }else{
                           $password = sha1(LTP2).md5(common::setPost('pword').LTP2.LTP);
                           $user->password = $password;
                           $user->date_pass_modified = date("Y-m-d H:m:s");
                           $user->date_modified = date("Y-m-d H:m:s");
                           $user->modified_by = $_SESSION['auth']->id;
                           $user->update_me();
                           common::redirect('admin/dashboard','index');
                      }
                }
                include(VIEW_PATH. "auth/password_expired.html");
                exit;
        }
        
        function logout(){
               // Common::logthis('access','LOGOUT', '');
                setcookie('auth', '', mktime(12,0,0,1,1,1990), '/');
                unset($_SESSION);
                @session_destroy();
                common::DefaultMapping();	
        }
}
?>