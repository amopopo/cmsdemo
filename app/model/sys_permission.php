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
class sys_permission Extends OBJ{
   
    function update_me(){
        return parent::save();
    }
    
    function find_all($group){
        $sql = "SELECT s.* FROM sys_permission s ";
        if($group=='admin'){
            $sql .= " WHERE (s.group='".mysql_real_escape_string($group)."' OR s.group='home')";
        }else{
            $sql .= " WHERE s.group='".mysql_real_escape_string($group)."' ";
        }
        $sql .= " ORDER BY menu_grp,menu_order";
        return $this->FindBySql("sys_permission",$sql);
    }
}

?>
