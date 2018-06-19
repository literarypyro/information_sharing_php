function getXHTML(){
	var xmlHttp;	
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlHttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	return xmlHttp;


}

function makeajax(url,testFunction){
	var xmlHttp;
	var ajaxHTML;
	xmlHttp=getXHTML();
	xmlHttp.onreadystatechange=function()
	{
		if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{ 	

			ajaxHTML=xmlHttp.responseText;
			window[testFunction](ajaxHTML);
			//return ajaxHTML;
		}
	}  	

	xmlHttp.open("POST",url,true); 
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");	
	xmlHttp.send();	

}

function eventFire(el, etype){
  if (el.fireEvent) {
   (el.fireEvent('on' + etype));
  } else {
    var evObj = document.createEvent('Events');
    evObj.initEvent(etype, true, false);
    el.dispatchEvent(evObj);
  }
}

function PasaCLC(){
	var idz = document.getElementById('AddNewEntryCLC');
	if(idz==null){
		alert("Click 'Get Records' first!");	
	}
	else{
	eventFire(idz,'click');
	}
}