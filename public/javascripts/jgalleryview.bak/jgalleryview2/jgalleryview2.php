<?php

/**
 * Module jGalleryView2
 * uses JavaScript from http://spaceforaname.com/galleryview
 * Creation author: Joel Gaujard
 * management inspired by module LinkSlideText by www.devedition.com
 *
 **/

class JGalleryView2 extends Module
{
	protected $maxImageSize = 307200;
	protected $imageDir = 'slides/';

	protected $_defaultLanguage;
	protected $_languages;
	protected $_xml;

	public function __construct()
	{
		$this->name = 'jgalleryview2';
		$this->tab = 'Home';
		$this->version = '1.3.2'; /* compatible PS 1.2.x, 1.3.x */

		parent::__construct();

		$this->page = basename(__FILE__,'.php');
		$this->displayName = $this->l('jGallery View 2');
		$this->description = $this->l('Add a jGallery View on your homepage.');

		/* initiate values for translation */
		$this->_defaultLanguage = intval(Configuration::get('PS_LANG_DEFAULT'));
		$this->_languages = Language::getLanguages();
		/* put xml in cache */
		$this->_xml = $this->_getXml();
	}

	public function install()
	{
		if (!parent::install()
				OR !$this->registerHook('home')
				OR !$this->registerHook('header')
			)
			return false;
		return true;
	}

	public function getContent()
	{
		$this->_html = '<h2>'.$this->displayName.' - '.$this->l('version').' '.$this->version.'</h2>';
		$this->_html .= $this->_postProcess();
		$this->_html .= $this->_displayForm();
		$this->_html .= $this->_displayCredits();
		return $this->_html;
	}

	protected function putContent($xml_data, $key, $field)
	{
		$field = stripslashes(htmlspecialchars($field,ENT_QUOTES,"UTF-8"));
		if (!$field)
			return 0;
		return ("\n\t\t<".$key.">".$field."</".$key.">");
	}

	private function _postProcess()
	{
		if (Tools::isSubmit('submitUpdate'))
		{
			$newXml = '<'.'?'.'xml version="1.0" encoding="utf-8" '.'?'.'>';
			$newXml .= "\n<items>";
			$i = 0;
			foreach (Tools::getValue('item') AS $item)
			{
				$newXml .= "\n\t<item>";
				foreach ($item AS $key => $field)
				{
					if ($line = $this->putContent($newXml, $key, $field))
						$newXml .= $line;
				}
				if (isset($_FILES['item_'.$i.'_img']) AND isset($_FILES['item_'.$i.'_img']['tmp_name']) AND !empty($_FILES['item_'.$i.'_img']['tmp_name']))
				{
					Configuration::set('PS_IMAGE_GENERATION_METHOD', 1);
					if ($error = checkImage($_FILES['item_'.$i.'_img'], $this->maxImageSize))
						return $error;
					elseif (!$tmpName = tempnam(_PS_TMP_IMG_DIR_, 'PS') OR !move_uploaded_file($_FILES['item_'.$i.'_img']['tmp_name'], $tmpName))
						return false;
					elseif (!imageResize($tmpName, dirname(__FILE__).'/'.$this->imageDir.'slide'.$i.'.jpg'))
						return $this->displayError($this->l('An error occurred during the image upload.'));
					unlink($tmpName);
				}
				if ($line = $this->putContent($newXml, 'img', $this->imageDir.'slide'.$i.'.jpg'))
					$newXml .= $line;
				$newXml .= "\n\t</item>\n";
				$i++;
			}
			$newXml .= "\n</items>\n";

			if ($fd = @fopen(dirname(__FILE__).'/'.$this->getXmlFilename(), 'w'))
			{
				if (!@fwrite($fd, $newXml))
					return $this->displayError($this->l('Unable to write to the editor file.'));
				if (!@fclose($fd))
					return $this->displayError($this->l('Can\'t close the editor file.'));
			}
			else
				return $this->displayError($this->l('Unable to update the editor file. Please check the editor file\'s writing permissions.'));

			/* refresh XML */
			$this->_xml = $this->_getXml();
			return $this->displayConfirmation($this->l('Items updated.'));
		}
	}

	static private function getXmlFilename()
	{
		return 'data.xml';
	}

	private function _getXml()
	{
		$file = dirname(__FILE__).'/'.$this->getXmlFilename();
		if (file_exists($file))
		{
			if ($xml = @simplexml_load_file($file))
			{
				return $xml;
			}
		}
		return false;
	}

	public function _getFormItem($i, $last)
	{
		$divLangName = 'title'.$i.'&curren;cpara'.$i;
		$output = '
			<div class="item" id="item'.$i.'">
				<h3>'.$this->l('Item #').($i+1).'</h3>
				<label>'.$this->l('Label').'</label>
				<div class="margin-form">';

		foreach ($this->_languages as $language)
		{
			$output .= '
					<div id="title'.$i.'_'.$language['id_lang'].'" style="display:'.($language['id_lang'] == $this->_defaultLanguage ? 'block' : 'none').';float: left;">
						<input type="text" name="item['.$i.'][title_'.$language['id_lang'].']" id="item_title_'.$i.'_'.$language['id_lang'].'" size="64" value="'.(isset($this->_xml->item[$i]->{'title_'.$language['id_lang']}) ? stripslashes(htmlspecialchars($this->_xml->item[$i]->{'title_'.$language['id_lang']})) : '').'" />
					</div>';
		}
		$output .= $this->displayFlags($this->_languages, $this->_defaultLanguage, $divLangName , 'title'.$i, true);

		$output .= '
					<div class="clear"></div>
				</div>
				<label>'.$this->l('Text').'</label>
				<div class="margin-form">';

		foreach ($this->_languages as $language)
		{
			$output .= '
					<div id="cpara'.$i.'_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $this->_defaultLanguage ? 'block' : 'none').';float: left;">
						<textarea cols="64" rows="3" name="item['.$i.'][text_'.$language['id_lang'].']" id="item_text_'.$i.'_'.$language['id_lang'].'">'.(isset($this->_xml->item[$i]-> {'text_'.$language['id_lang']}) ? stripslashes(htmlspecialchars($this->_xml->item[$i]-> {'text_'.$language['id_lang']})) : '').'</textarea>
					</div>';
		}
		$output .= $this->displayFlags($this->_languages, $this->_defaultLanguage, $divLangName , 'cpara'.$i, true);

		$output .= '
					<div class="clear"></div>
				</div>
				<label>'.$this->l('Picture').'</label>
				<div class="margin-form">
					<img src="'.$this->_path.$this->imageDir.'slide'.$i.'.jpg" alt="" title="" style="" />
					<br /><input type="file" name="item_'.$i.'_img" />
					<p style="clear: both"></p>
				</div>
				<label>'.$this->l('Link').'</label>
				<div class="margin-form">
					<input type="text" name="item['.$i.'][url]" size="64" value="'.(isset($this->_xml->item[$i]->url) ? stripslashes(htmlspecialchars($this->_xml->item[$i]->url)) : '').'" />
					<p style="clear: both"></p>
				</div>
				<div class="clear pspace"></div>
				'.($i > 0 ? '<a href="javascript:{}" onclick="removeDiv(\'item'.$i.'\')" style="color:#EA2E30"><img src="'._PS_ADMIN_IMG_.'delete.gif" alt="'.$this->l('delete').'" />'.$this->l('Delete this item').'</a>' : '').'
				<hr/>
				'.($last ? '<a id="clone'.$i.'" href="javascript:cloneIt(\'clone'.$i.'\')" style="color:#488E41"><img src="'._PS_ADMIN_IMG_.'add.gif" alt="'.$this->l('add').'" /><b>'.$this->l('Add a new item').'</b></a>' : '').'
			</div>';
		return $output;
	}

	public function _displayForm()
	{

		$output = '';

		$xml = false;
		if (!$xml = $this->_xml)
			$output .= $this->displayError($this->l('Your data file is empty.'));

		$output .= '
		<script type="text/javascript">
		function removeDiv(id)
		{
			$("#"+id).fadeOut("slow");
			$("#"+id).remove();
		}
		function cloneIt(cloneId) {
			var currentDiv = $("#"+cloneId).parent(".item");
			var id = $(currentDiv).attr("id").match(/[0-9]+/gi);
			var nextId = parseInt(id) + 1;
			$.get("'._MODULE_DIR_.$this->name.'/ajax.php?id="+nextId, function(data) {
				$(currentDiv).after(data);
			});
			$("#"+cloneId).remove();
		}
		</script>
		<form method="post" action="'.$_SERVER['REQUEST_URI'].'" enctype="multipart/form-data">
			<fieldset style="width: 900px;">
				<legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->displayName.'</legend>';

		$i = 0;
		foreach ($xml->item as $item)
		{
			$last = ($i == (count($xml->item)-1) ? true : false);
			$output .= $this->_getFormItem($i, $last);
			$i++;
		}
		$output .= '
				<div class="margin-form clear">
					<input type="submit" name="submitUpdate" value="'.$this->l('Save').'" class="button" />
				</div>
			</fieldset>
		</form>';
		return $output;
	}

	function hookHeader($params)
	{
		return $this->display(__FILE__, 'header.tpl');
	}

	function hookHome($params)
	{
		if ($xml = $this->_xml)
		{
			global $cookie, $smarty;
			$smarty->assign(array(
				'xml' => $xml,
				'title' => 'title_'.$cookie->id_lang,
				'text' => 'text_'.$cookie->id_lang
			));
			return $this->display(__FILE__, $this->name.'.tpl');
		}
		return false;
	}

	private function _displayCredits()
	{
		$sf_url = 'https://sourceforge.net/projects/psjgalleryview/';
		$ps_url = 'http://www.prestashop.com/forums/viewthread/48180/';
		$output = '
		<br class="clear" /><br/>
		<form action="#" method="post" style="width: 95%;">
			<fieldset class="widthfull">
				<legend>'.$this->l('Credits').'</legend>
				<p>
					'.$this->l('This module is hosted on').'
					<a href="'.$sf_url.'"><b>source forge</b> - '.$sf_url.'</a>.
				</p>
				<p>
					<a href="'.$ps_url.'" style="text-decoration: underline;">
						'.$this->l('Please send feedback, bugs or other stuff on related thread in PrestaShop forum.').'
					</a>
				</p>
				'.$this->_displayTranslators().'
			</fieldset>
		</form>';
		return $output;
	}

	private function _displayTranslators()
	{
		$translators = array(
			"lt" => array(
				"fullname" => "Evaldas Belevicius",
				"url" => "mailto:evaboy@gmail.com"
			)
			,"tj" => array(
			//"fa" => array(
				"fullname" => "Roozbeh Aghabaighi",
				"url" => "http://www.embed.ir/eshop"
			)
			,"it" => array(
				"fullname" => "Di Giuseppe Luigi",
				"url" => "http://www.libridipsicologia.it/"
			)
			,"pl" => array(
				"fullname" => "Jakub Gzyl",
				"url" => "mailto:jakub6@gmail.com"
			)
			,"tr" => array(
				"fullname" => "Safa AL",
				"url" => "mailto:safa_al18@hotmail.com"
			)
			,"fr" => array(
				"fullname" => "Joël Gaujard",
				"url" => "http://www.joelgaujard.info"
			)
		);
		if (empty($translators) OR count($translators) < 1)
			return '';
		$output = '
		<p>
			<h3>'.$this->l('Translators list!').'</h3>
			<ul>';
		foreach ($translators AS $lang_iso => $translator)
		{
			$output .= '
			<li style="line-height: 24px;">
				<img src="http://www.google.com/images/flags/'.$lang_iso.'_flag.png" alt="'.$lang_iso.'" title="'.$lang_iso.'" width="16" height="11" style="vertical-align: baseline;" />
				<a target="_blank" href="'.$translator["url"].'"><b>'.$translator["fullname"].'</b> - '.preg_replace('/^mailto:/', '',$translator["url"]).'</a>';
			$output .= '</li>';
		}
		$output .= '</ul>
		</p>
		<p>
			'.$this->l('If you translate this module in your language, please upload your language file on PrestaShop forum. Many thanks in anticipation!').'
		</p>
		';
		return $output;
	}

}

?>