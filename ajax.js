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

function executeAJAX(url){
	var xmlHttp;
	var ajaxHTML;
	
	xmlHttp=getXHTML();
	xmlHttp.onreadystatechange=function()
	{
		if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{
			ajaxHTML=xmlHttp.responseText;
			
			return ajaxHTML;
		}
	} 
	

	xmlHttp.open("GET",url,true);
	xmlHttp.send();	

}
function testAlert(text){
	alert(text);

}