var KT= {
	AutoCompleteDiv:function(divIdName,fieldName,path,updateFunction){
		var au = '';
		//~ au += "<div id='autocomplete_choices_"+divIdName+"' class='auto_complete'></div>";
		//~ au += "<script type='text/javascript'>var A_"+divIdName+" = new Ajax.Autocompleter('"+fieldName+"', 'autocomplete_choices_"+divIdName+"', '"+path+"', {afterUpdateElement: "+updateFunction+"});</script>";
		return au;
	},

	removeItem:function(parentNode,value){ 
		var d = $(parentNode); 
		var olddiv = $(value); 
		d.removeChild(olddiv);
	}
}