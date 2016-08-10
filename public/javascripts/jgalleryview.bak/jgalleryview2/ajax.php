<?php
require_once(realpath(dirname(__FILE__).'/../../').'/config/config.inc.php');
require_once(dirname(__FILE__).'/jgalleryview2.php');

if (!$id = Tools::getValue('id'))
	die();

$module = new JGalleryView2();
echo $module->_getFormItem(intval($id), true);

?>