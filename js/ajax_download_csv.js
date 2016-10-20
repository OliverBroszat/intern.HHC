function download_csv(id){

	popup(null, 'edit');

	var data = new FormData();

	var search_text = document.getElementById('text-box').value
	console.log('search_text: '+search_text);
	data.append('search_text', search_text);

	var ressorts = document.getElementsByClassName('filtercheckbox_ressort');
	var ressort_checklist = new Array();
	for (i = 0; i < ressorts.length; i++) { 
		if (ressorts[i].checked) {
			ressort_checklist.push(ressorts[i].value);
		}
	}
	console.log('ressort_checklist: '+ressort_checklist);
	data.append('ressort_list', ressort_checklist);


	var positions = document.getElementsByClassName('filtercheckbox_position');
	var position_checklist = new Array();
	for (i = 0; i < positions.length; i++) { 
		if (positions[i].checked) {
			position_checklist.push(positions[i].value);
		}
	}
	console.log('position_checklist: '+position_checklist);
	data.append('position_list', position_checklist);


	var statuss = document.getElementsByClassName('filtercheckbox_status');
	var status_checklist = new Array();
	for (i = 0; i < statuss.length; i++) { 
		if (statuss[i].checked) {
			status_checklist.push(statuss[i].value);
		}
	}
	console.log('status_checklist: '+status_checklist);
	data.append('status_list', status_checklist);


	var unis = document.getElementsByClassName('filtercheckbox_uni');
	var uni_checklist = new Array();
	for (i = 0; i < unis.length; i++) { 
		if (unis[i].checked) {
			uni_checklist.push(unis[i].value);
		}
	}
	console.log('uni_checklist: '+uni_checklist);
	data.append('uni_list', uni_checklist);

	//sortierinput als Objekt erstellen
	var sort = document.getElementById('sort').value;
	console.log('sort: '+sort);
	data.append('sort', sort);


	var order = document.getElementById('order').value;
	console.log('order: '+order);
	data.append('order', order);

  	var templateDirectory = document.getElementById('templateDirectory').value; 

	$.ajax({
  		url: templateDirectory+'/functions/download_csv.php',
	  	data: data,
		processData: false,
		contentType: false,
		type: 'POST',
		success: function(data){
			alert(data);
			popup_close();
		}
	});
}