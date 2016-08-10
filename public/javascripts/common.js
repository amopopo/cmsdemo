function expand(menu){
var target = document.getElementById(menu);
if (target.style.display == "none"){
if (menu == 'css'){
Set_Cookie('css', 'block', 30,'/');	
}
target.style.display = "block";
}else{
if (menu == 'css'){
Set_Cookie('css', 'none', 30,'/');	
}
target.style.display = "none";
}
}

function close_div(id){
	if(document.getElementById(id)){
		ID = document.getElementById(id); 
		ID.style.display = "none"; 
	}
}

function open_div(id){
	if(document.getElementById(id)){
		ID = document.getElementById(id); 
		ID.style.display = "block"; 
	}
}

function toggle_groupDiv(arr,id){
	for(var i in arr){  if(id==arr[i]){ open_div(arr[i]); }else{ close_div(arr[i]);}  }
}


function get_radio_value(value){
	var rad_val='';
	for (var i=0; i < value.length; i++){
	   if (value[i].checked){ rad_val = value[i].value;}
   }
   return rad_val;
}

function checkAllFields(ref,name,checkall)
{
var chkAll = document.getElementById(checkall);
var checks = document.getElementsByName(name);
var boxLength = checks.length;
var allChecked = false;
var totalChecked = 0;
if ( ref == 1 )
{
if ( chkAll.checked == true )
{
for ( i=0; i < boxLength; i++ )
checks[i].checked = true;
}
else
{
for ( i=0; i < boxLength; i++ )
checks[i].checked = false;
}
}
else
{
for ( i=0; i < boxLength; i++ )
{
if ( checks[i].checked == true )
{
allChecked = true;
continue;
}
else
{
allChecked = false;
break;
}
}
if ( allChecked == true )
chkAll.checked = true;
else
chkAll.checked = false;
}
for ( j=0; j < boxLength; j++ )
{
if ( checks[j].checked == true )
totalChecked++;
}
}
function URLDecode(encoded){var HEXCHARS="0123456789ABCDEFabcdef";var plaintext="";var i=0;while(i<encoded.length){var ch=encoded.charAt(i);if(ch=="+"){plaintext+=" ";i++;}else if(ch=="%"){if(i<(encoded.length-2)&&HEXCHARS.indexOf(encoded.charAt(i+1))!=-1&&HEXCHARS.indexOf(encoded.charAt(i+2))!=-1){plaintext+=unescape(encoded.substr(i,3));i+=3;}else{alert('Bad escape combination near ...'+encoded.substr(i));plaintext+="%[ERROR]";i++;}}else{plaintext+=ch;i++;}}
var newstring=plaintext.replace(/&amp;/,"&");return newstring;};
function URLEncode(input){var keyStr="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";var output="";var chr1,chr2,chr3;var enc1,enc2,enc3,enc4;var i=0;do{chr1=input.charCodeAt(i++);chr2=input.charCodeAt(i++);chr3=input.charCodeAt(i++);enc1=chr1>>2;enc2=((chr1&3)<<4)|(chr2>>4);enc3=((chr2&15)<<2)|(chr3>>6);enc4=chr3&63;if(isNaN(chr2)){enc3=enc4=64;}else if(isNaN(chr3)){enc4=64;}
output=output+keyStr.charAt(enc1)+keyStr.charAt(enc2)+
keyStr.charAt(enc3)+keyStr.charAt(enc4);}while(i<input.length);return output;}
function PopupCenter(pageURL,w,h) { var left = (screen.width/2)-(w/2);var top = (screen.height/2)-(h/2);var targetWin = window.open (pageURL, '', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);}

function setSelectedIndex(ddl, value) {
	if (!ddl) { return null; }
	for (var i=0; i<ddl.options.length; i++) {
		if (ddl.options[i].value == value) {
			if (ddl.options[i].selected==false) ddl.options[i].selected = true;
		}
	}
}
