var edit_mode=true;
var record_row=0;
//var prev_row=0;
//var clicks=0;

function storeAsCell(){ 
	//processClick
	prepareSubmit();
	
	for(var i=1;i<=38;i++){
		if($('#field_'+i).val()==""){
			$('#field_'+i).val("&nbsp;")
		}
		
		//if(i==2){
			
			
		//}
		$('#field_'+i).closest("tr").find("td:nth-child("+i+")").click(function(){  processClick($(this)); });
		$('#field_'+i).closest("tr").find("td:nth-child("+i+")").html($('#field_'+i).val());
	}
	
}
function enableInput(element){
	//no processClick
	var insertHTML="";
	var field_value="";
	
	for(var i=1;i<=38;i++){
		var tdHTML="";
		field_value=($(element).closest("tr").find("td:nth-child("+i+")").html()).replace("&nbsp;","");
		tdHTML+="<input style='width:100%;border:1px solid red' name='field_"+i+"' id='field_"+i+"' onclick='focusField(this)'  onkeyup='processKey(this,event,"+i+")'	value='"+field_value+"' type='text' />";
		$(element).closest("tr").find("td:nth-child("+i+")").html(tdHTML);
	}
	$(element).find("input:text").focus();	
	$(element).find("input:text").css('border','2px solid black');
	
	$('#field_2').keydown(function(){
		$(this).css('border','2px solid red');
	});
	/*
	$.ajax({
		type:'post',
		url:'processing_2.php?ts=Y'
	
	})
	.done(function(data){
		var selectHTML="<select>";
		selectHTML+="</select>";
		$(element).closest("tr").find("td:nth-child(2)").html(selectHTML);
		$find(option:selected).text()
	});
	*/
	//var ava=["Colorado","Denver"];
	//$('#field_2').autocomplete({
	//	source: ava 
	//});
/*	
	$('#field_2').on('keydown',function(){
		$('#test').autocomplete({
			source: ["Colorado","Denver"] 
		});
	});
*/	
//	enableAuto();
	
	setRecordRow(element);
}

function setRecordRow(element){ 
//	record_row=$('#entry tr').index($(element).closest('tr'))*1+1;
	record_row=$('#entry tr').index($(element).closest('tr'));
	
}
function getRecordRow(){

	return record_row;
}


function createRow(){
	//similar to enableInput
	var newRow="<tr>";
	
	for(var i=1;i<=38;i++){
//		if(i==2){
//			newRow+="<td><select></select></td>";
//		}
//		else {
			newRow+="<td><input style='width:100%;border:1px solid red' name='field_"+i+"' id='field_"+i+"'  onclick='focusField(this)' onkeyup='processKey(this,event,"+i+")' type='text' /></td>";
//		}
	}
	newRow+="<td><a href='#' onclick='removeRow(this)' >X</a></td>";
	newRow+="</tr>";

	$('#entry').append(newRow);	
	$('#field_1').focus();
	$('#field_1').css('border','2px solid black');	
	setRecordRow($('#field_1'));
	
}

function removeRow(element){
	var check=confirm("This will delete the record. Continue?");
	if(check){
		setRecordRow($(element));

		$('#action').val('remove');
		prepareSubmit();
		$('#action').val('');
	
		$(element).closest("tr").remove();
	}
}	

function addNewEntry(){ 
	storeAsCell();

	createRow();
	//no processClick
	toggleAction("add");
	
	$('#add_link').css('visibility','hidden');
	$('#finish_button').css('visibility','visible');	
}
function finish(){ 
	storeAsCell();
	
	record_row=0;
	$('#add_link').css('visibility','visible');
	$('#finish_button').css('visibility','hidden');	
}
//Keyboard and Mouse event
function processKey(element,event,sequence){ 
	//Enter event
	if(event.keyCode==13){
		if(sequence==35){
			storeAsCell();
			createRow();
			toggleAction("add");
		}
		else {
			$(element).css('border','1px solid red');
			$('#field_'+(sequence+1)).focus();
			$('#field_'+(sequence+1)).css('border','2px solid black');
		}
	}
	//F2 event
	else if(event.keyCode==113){
		if(edit_mode==true){
			toggleEdit(false);	
			$(element).css('border','2px solid blue');
			
		}
		else {
			toggleEdit(true);
			$(element).css('border','2px solid black');
			
		}
	}
	//Left Right keys
	else if(event.keyCode==39){
		if(edit_mode==false){
			if(sequence==35){
			}
			else {
				//focus change
				$(element).css('border','1px solid red');

				$('#field_'+(sequence+1)).focus();
				$('#field_'+(sequence+1)).css('border','2px solid blue');				
			}

		}
	}
	else if(event.keyCode==37){
		if(edit_mode==false){
			if(sequence==1){
			}
			else {
				//focus change
				$(element).css('border','1px solid red');

				$('#field_'+(sequence-1)).focus();
				$('#field_'+(sequence-1)).css('border','2px solid blue');				
				
			}
		}
	}
	//Up Down keys
	else if(event.keyCode==38){
		if(edit_mode==false){
			if($(element).closest('tr').is(':nth-child(5)')){
			}
			else {
				storeAsCell();
				enableInput($('#entry tr:nth-child('+(getRecordRow()*1+1)+')').prev().find('td:nth-child('+sequence+')'));

				toggleAction("edit");
			}
		}	
	}
	else if(event.keyCode==40){		
		if(edit_mode==false){
			if($(element).closest('tr').is(':last-child')){
			}
			else {
				storeAsCell();
				enableInput($('#entry tr:nth-child('+(getRecordRow()*1+1)+')').next().find('td:nth-child('+sequence+')'));
				
				toggleAction("edit");
			}
		}	
	}
	//compute sum for later columns
	else {
		var a=new Array();
		var b=new Array();
		var c=new Array();
		
		
		a=[5,11,12,16,20,21];
		b=[6,7,8,13,17,22];
		c=[10,15,19,24];
			
		if($.inArray(sequence,a)>-1){
			var sum=0;					
			for(var i=0;i<a.length;i++){				
				sum+=$('#field_'+a[i]).val()*1;				
			}						
			$('#field_36').val(sum);
		}
		else if($.inArray(sequence,b)>-1){
			var sum=0;
			for(var i=0;i<b.length;i++){
				sum+=$('#field_'+b[i]).val()*1;
			}
			$('#field_37').val(sum);
		}
		else if($.inArray(sequence,c)>-1){
			var sum=0;
			for(var i=0;i<c.length;i++){
				sum+=$('#field_'+c[i]).val()*1;
			}
			$('#field_38').val(sum);
		}
	}
}

function focusField(element){
	$(element).closest('tr').find('td input:text').css('border','1px solid red');
	$(element).css('border','2px solid black');

}



function processClick(element){
	storeAsCell();
	enableInput(element);
	toggleAction("edit");
}


function toggleEdit(action){
	edit_mode=action;
}

function toggleAction(action){ 
	$('#action').val(action);	
}

function prepareSubmit(){
	var dar_data=$('#dar_form').serialize();
	var rec_id=getRecordRow();
	
	if($('#action').val()=="edit"){
		dar_data += "&dar_id="+encodeURIComponent($('#entry tr:nth-child('+(rec_id*1+1)+')').attr('class'));
		//$('#entry').find('tr:nth-child('+(rec_id*1+1)+')').find('td:nth-child(2)').html("AAA");
		dar_data += "&rec_id="+rec_id;
	}
	else if($('#action').val()=="remove"){
		dar_data += "&dar_id="+encodeURIComponent($('#entry tr:nth-child('+(rec_id*1+1)+')').attr('class'));
		dar_data += "&field_2=NA";
	}
	else {
		dar_data += "&rec_id="+rec_id;
	}
	executeAjax(dar_data);
	
}

function executeAjax(dar_data){
	$.ajax({
	  type: "POST",
	  url: "processing_2.php",
	  data: dar_data
	}).done(function(data){
		if(data==""){
		}
		else {
			var jrec=JSON.parse(data);
			
			if(jrec.action=='add'){
				var rec_row=jrec.rec_id*1+1;
				$('#entry').find('tr:nth-child('+(rec_row*1)+')').attr('class',"dar_id_"+jrec.dar_id);
			}
	//		$('#entry').find('tr:nth-child('+(rec_row*1)+')').find('td:nth-child(2)').html(jrec.dar_id);
		}
	});
	
}	
