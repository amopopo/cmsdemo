Ajax.Responders.register({
  onCreate: function() {
    if($('busy') && Ajax.activeRequestCount>0)
      Effect.Appear('busy',{duration:0.5,queue:'end'});
  },
  onComplete: function() {
    if($('busy') && Ajax.activeRequestCount==0)
      Effect.Fade('busy',{duration:0.5,queue:'end'});
  }
});

function showLoader(loader){
   new Effect.Appear($(loader), {duration: 0.5});
}

function hideLoader(loader){
   new Effect.Fade($(loader), {duration: 0.5});
}

function getFormData(form){
	var getstr = "";

	for (i=0; i<form.length; i++) { 
		if (form[i].type == "text" || form[i].type == 'textarea') {
			getstr += form[i].name + "=" + escape(form[i].value) + "&";
		}else if (form[i].type == "checkbox") {
		   if (form[i].checked) {
		      getstr += form[i].name + "=" + escape(form[i].value) + "&";
		   } else {
		      getstr += form[i].name + "=&";
		   }
		}else if (form[i].type == "radio") {
		   if (form[i].checked) {
		      getstr += form[i].name + "=" + escape(form[i].value) + "&";
		   }
	        }else if (form[i].tagName == "SELECT") {
		        var sel = form[i];
			if(sel.options.length > 0){ 
				getstr += sel.name + "=" + escape(sel.options[sel.selectedIndex].value) + "&";
			}
		}
        }
	return getstr;
}
/*duplicate start*/
function total_row(){
	var total_row = 0;
	if (!document.getElementsByTagName || !document.createTextNode) return;
	var rows = document.getElementById('show_area').getElementsByTagName('tbody')[0].getElementsByTagName('tr');
	var r = document.getElementById('show_area').getElementsByTagName('tbody')[0];
	var tr = r.firstChild;
	while(tr){
		if(tr.id && (tr.id=='addField') ){
			total_row ++;
		}
		tr = tr.nextSibling;
	}
	return rows.length-total_row;
}/*
function total_row(){
	var total_row = 0;
	if (!document.getElementsByTagName || !document.createTextNode) return;
	var rows = document.getElementById('show_area').getElementsByTagName('tbody')[0].getElementsByTagName('tr');
	for(var i in rows){
		if(rows[i].id && rows[i].id == 'addField'){
			total_row ++;
		}
	}
	return rows.length - total_row;
}
*/

function add(){
	open_div('loading_status_bar');
	var add_num = $('ajax_add_num').value;

	if (!add_num.match(/^[0-9]{1,2}$/) || add_num == '' || add_num == 0  ) {
		add_num = 1;
	}

	for(var i=0;i<add_num;i++){	
		addRow();
	}
	 hideLoader('loading_status_bar');
}

function start(num){
	for(var i=0;i<num;i++){
		addRow();
	}
}

function removeRow(value){ 
	open_div('ocaria_status_bar');
	var parent = document.getElementById('show_area').getElementsByTagName('tbody')[0];
	var olddiv = $(value); 
	parent.removeChild(olddiv);

	var rows = document.getElementById('show_area').getElementsByTagName('tbody')[0].getElementsByTagName('tr');
	var total = 0;
	for (var i=0; i< rows.length; i++) {
		if(rows[i].id &&  $('count_'+rows[i].id) ){
			total++;
			var count = $('count_'+rows[i].id).innerHTML;
			$('count_'+rows[i].id).innerHTML = total;
		}
	}
	 hideLoader('loading_status_bar');
}
/*duplicate end*/