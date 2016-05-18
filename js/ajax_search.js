function ajax_post() {
	// http://stackoverflow.com/questions/9713058/sending-post-data-with-a-xmlhttprequest
	// Gute Infoquelle für ein POST Beispiel mit Ajax
	// Siehe vor allem die zweite Antwort mit FormData


	//endvariable, die als POST weitergegeben wird
	var data = new FormData();


	data.append('search_text', document.getElementById('text-box').value);

	var ressorts = document.getElementsByClassName('filtercheckbox_ressort');
	var ressort_checklist = new Array();
	for (i = 0; i < ressorts.length; i++) { 
		if (ressorts[i].checked) {
			ressort_checklist.push(ressorts[i].value);
		}
	}
	console.log(ressort_checklist);
	data.append('ressort_list', ressort_checklist);


	var positions = document.getElementsByClassName('filtercheckbox_position');
	var position_checklist = new Array();
	for (i = 0; i < positions.length; i++) { 
		if (positions[i].checked) {
			position_checklist.push(positions[i].value);
		}
	}
	console.log(position_checklist);
	data.append('position_list', position_checklist);


	var statuss = document.getElementsByClassName('filtercheckbox_status');
	var status_checklist = new Array();
	for (i = 0; i < statuss.length; i++) { 
		if (statuss[i].checked) {
			status_checklist.push(statuss[i].value);
		}
	}
	console.log(status_checklist);
	data.append('status_list', status_checklist);


	var unis = document.getElementsByClassName('filtercheckbox_uni');
	var uni_checklist = new Array();
	for (i = 0; i < unis.length; i++) { 
		if (unis[i].checked) {
			uni_checklist.push(unis[i].value);
		}
	}
	console.log(uni_checklist);
	data.append('uni_list', uni_checklist);

	//sortierinput als Objekt erstellen
	data.append('sort', document.getElementById('sort').value);

	//Einfügen aller erstellten Objekte in das "data" Objekt

	var b = document.getElementById('list-container');
	b.className += " modal";


	// var templateDirectory = $('#templateDirectory').value;
	var templateDirectory = document.getElementById('templateDirectory').value;

	$.ajax({
	  url: templateDirectory+'/functions/suchfunktion/AcceptAjax.php',
	  data: data,
	  processData: false,
	  contentType: false,
	  type: 'POST',
	  success: function(result){
	  	setTimeout(function(){
				document.getElementById('list-container').classList.remove('modal');
				$('#list-container').html(result);
			}, 100);
	  }
	});
}