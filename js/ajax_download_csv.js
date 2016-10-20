function download_csv(id){


  	var templateDirectory = document.getElementById('templateDirectory').value; 

	// $.ajax({
 //  		url: templateDirectory+'/functions/download_csv.php',
	//   	data: $("#form-suche").serialize(),
	// 	processData: false,	
	// 	contentType: false,
	// 	async: true,
	// 	type: 'POST',
	// 	success: function(result){
	// 		$('#list-container').html(result);

	// 		// location.href = result
	// 	}
	// });

	$.post(
		templateDirectory+'/functions/download_csv.php', 
		$("#form-suche").serialize(), 
		function( result ) {	
			
			// $('#list-container').html(result);
			location.href = templateDirectory+'/functions/'+result;

		}
	);
}