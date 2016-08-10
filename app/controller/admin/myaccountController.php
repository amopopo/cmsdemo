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
Class myaccountController Extends baseController {
	function beforeFilter() { 
		$this->loginRequired();
		$this->buildMenu();
		$this->var['errors'] = null;
	}

	function index() {
		$sys_user = new sys_user();
		$this->var['user'] = $sys_user->find_by_id($_SESSION['auth']->id, 'Admin');
		$this->var['user'] = array_pop($this->var['user']);
		if (empty($this->var['user'])) { common::Redirect('auth', 'logout'); }
	}

	function edit() {
		$sys_user = new sys_user();
		$this->var['user'] = $sys_user->find_by_id($_SESSION['auth']->id, 'Admin');
		$this->var['user'] = array_pop($this->var['user']);
		if (empty($this->var['user'])) { 
			common::Redirect('auth','logout'); 
		} else { 
			$email = $this->var['user']->email;
			if (common::setPost('save')=='Save') {
				if ($this->var['user']->myaccountEditValidate($email)==false) {
					$this->var['errors'] = $this->var['user']->get_errors();
				} else {
					if (common::setPost('pssword')!='') { $this->var['user']->password = sha1(LTP2).md5($password.LTP2.LTP); }
					$this->var['user']->first_name = common::setPost('first_name');
					$this->var['user']->last_name = common::setPost('last_name');
					$this->var['user']->dob = reformat_date(common::setPost('dob'));
					$this->var['user']->email = common::setPost('email');
					$this->var['user']->date_modified = date('Y-m-d H:m:s');
					$this->var['user']->modified_by = $_SESSION['auth']->id;
					$this->var['user']->update_me();
					common::Redirect($this->group.'/'.$this->controller, 'index');
				}
			}
		}
	}
}
?>