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
class blog_content_item Extends OBJ{
  function update_me(){
  	return parent::save();
  }
  
  function delete_except_thumbnail($system_code){
    $this->Query("DELETE FROM blog_content_item WHERE system_code='".mysql_real_escape_string($system_code)."' ");
  } 
  /*
  function find_thumbnail($system_code){
    return $this->FindBySql('blog_content_item',"SELECT i.* FROM blog_content_item i WHERE i.system_code='".mysql_real_escape_string($system_code)."' AND i.item_type='thumbnail' LIMIT 1 ");
  }*/
  
  function find_except_thumbnail($system_code){
    return $this->FindBySql('blog_content_item',"SELECT i.* FROM blog_content_item i WHERE i.system_code='".mysql_real_escape_string($system_code)."' AND i.item_type!='thumbnail'");
  }
  
  function find_all_byType($system_code,$item_type,$limit=null){
    $sql = "SELECT i.* FROM blog_content_item i WHERE i.system_code='".mysql_real_escape_string($system_code)."' AND i.item_type='".mysql_real_escape_string($item_type)."'";
    if($limit >0){
      $sql .=" LIMIT ".mysql_real_escape_string($limit);
    }
    
    return $this->FindBySql('blog_content_item',$sql);
  }
  
   function find_custom_byName($system_code,$value_name,$limit=null){
    $sql = "SELECT i.* FROM blog_content_item i WHERE i.system_code='".mysql_real_escape_string($system_code)."' AND i.value_name='".mysql_real_escape_string($value_name)."' ";
    if($limit >0){
      $sql .=" LIMIT ".mysql_real_escape_string($limit);
    }
    return $this->FindBySql('blog_content_item',$sql);
  }
}

?>
