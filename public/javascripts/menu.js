var menuwidth='165px'
var menubgcolor='#ffffff'
var disappeardelay=120
var hidemenu_onclick="yes"
var ie4=document.all
var tab
var ns6=document.getElementById&&!document.all
if(ie4||ns6)
document.write('<div id="dropmenudiv" style="visibility:hidden;width:'+menuwidth+';background-color:'+menubgcolor+'" onMouseover="clearhidemenu()" onMouseDown="dynamichide(event)"></div>')
function getposOffset(what,offsettype){var totaloffset=(offsettype=="left")?what.offsetLeft:what.offsetTop;var parentEl=what.offsetParent;while(parentEl!=null){totaloffset=(offsettype=="left")?totaloffset+parentEl.offsetLeft:totaloffset+parentEl.offsetTop;parentEl=parentEl.offsetParent;}
return totaloffset;}
function showhide(obj,e,visible,hidden,menuwidth){if(ie4||ns6)
dropmenuobj.style.left=dropmenuobj.style.top="-500px"
if(menuwidth!=""){dropmenuobj.widthobj=dropmenuobj.style
dropmenuobj.widthobj.width=menuwidth}
if(e.type=="click"&&obj.visibility==hidden||e.type=="mouseover")
obj.visibility=visible
else if(e.type=="click")
obj.visibility=hidden}
function iecompattest(){return(document.compatMode&&document.compatMode!="BackCompat")?document.documentElement:document.body}
function clearbrowseredge(obj,whichedge){var edgeoffset=0
if(whichedge=="rightedge"){var windowedge=ie4&&!window.opera?iecompattest().scrollLeft+iecompattest().clientWidth-15:window.pageXOffset+window.innerWidth-15
dropmenuobj.contentmeasure=dropmenuobj.offsetWidth
if(windowedge-dropmenuobj.x<dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure-obj.offsetWidth}
else{var topedge=ie4&&!window.opera?iecompattest().scrollTop:window.pageYOffset
var windowedge=ie4&&!window.opera?iecompattest().scrollTop+iecompattest().clientHeight-15:window.pageYOffset+window.innerHeight-18
dropmenuobj.contentmeasure=dropmenuobj.offsetHeight
if(windowedge-dropmenuobj.y<dropmenuobj.contentmeasure){edgeoffset=dropmenuobj.contentmeasure+obj.offsetHeight
if((dropmenuobj.y-topedge)<dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.y+obj.offsetHeight-topedge}}
return edgeoffset}
function populatemenu(what){if(ie4||ns6)
dropmenuobj.innerHTML=what.join("")}
function dropdownmenu(obj,e,menucontents,menuwidth,name){if(window.event)event.cancelBubble=true
else if(e.stopPropagation)e.stopPropagation()
clearhidemenu()
dropmenuobj=document.getElementById?document.getElementById("dropmenudiv"):dropmenudiv
populatemenu(menucontents)
if(ie4||ns6){showhide(dropmenuobj.style,e,"visible","hidden",menuwidth)
clear_allmenu();
tab=document.getElementById(name)
tab.style.backgroundColor="#6bb8d8";


dropmenuobj.x=getposOffset(obj,"left")
dropmenuobj.y=getposOffset(obj,"top")
dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj,"rightedge")+"px"
dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj,"bottomedge")+obj.offsetHeight+"px"}
return clickreturnvalue()}
function clickreturnvalue(){if(ie4||ns6)return false
else return true}
function contains_ns6(a,b){while(b.parentNode)
if((b=b.parentNode)==a)
return true;return false;}
function dynamichide(e){if(ie4&&!dropmenuobj.contains(e.toElement))
delayhidemenu()
else if(ns6&&e.currentTarget!=e.relatedTarget&&!contains_ns6(e.currentTarget,e.relatedTarget))
delayhidemenu()
}
function hidemenu(e){if(typeof dropmenuobj!="undefined"){if(ie4||ns6)
if (tab){tab.style.backgroundColor=""}

dropmenuobj.style.visibility="hidden"
}}
function delayhidemenu(){if(ie4||ns6)
delayhide=setTimeout("hidemenu()",disappeardelay)
}
function clearhidemenu(){if(typeof delayhide!="undefined")
clearTimeout(delayhide)
}
if(hidemenu_onclick=="yes")
document.onclick=hidemenu
function popup(div_id){var el=document.getElementById(div_id);if(el.style.display=='none'){el.style.display='block';}
else{el.style.display='none';}}
function onBlurPopup(div_id){setTimeout("close_popup('"+div_id+"')",250);}
function close_popup(div_id){var el=document.getElementById(div_id);if(el.style.display=='block'){el.style.display='none';}}
function clear_allmenu(){
var primary_tabs = document.getElementById("primary_tabs"); 
var tabs_li = primary_tabs.getElementsByTagName("a"); 
	for (var i = 0; i < tabs_li.length; i++) { 
		tabs_li[i].style.backgroundColor = "";
	}
}