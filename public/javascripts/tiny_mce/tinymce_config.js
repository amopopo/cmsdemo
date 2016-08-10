tinyMCE.init({
		// General options
		mode : "exact",
		elements : "editor",
		theme : "advanced",
		convert_urls : 0,
		relative_urls : 0,
		remove_script_host : 0,
		plugins : "autolink,lists,layer,table,save,advhr,advimage,advlink,inlinepopups,searchreplace,contextmenu,paste,directionality",


		// Theme options
		theme_advanced_buttons1 : "code,|,search,replace,pastetext,|,bold,italic,underline,strikethrough,charmap,sub,sup,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,outdent,indent",
		theme_advanced_buttons2 : "forecolor,backcolor,styleselect,formatselect,fontselect,fontsizeselect,|,link,unlink,anchor,image",
		theme_advanced_buttons3 : "tablecontrols,|,removeformat,visualaid",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		//content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});