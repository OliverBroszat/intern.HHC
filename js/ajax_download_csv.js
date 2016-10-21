function download_csv(id){
  	
	// extract the ids from the list entries	
	var ids = '';
	$('.contact-id').each(function(i) {
		ids += '&id[]=' + ($(this).html().split( "ID: " ).pop());
	});
	ids = ids.substring(1, ids.length)

	// goto to file -> download the file
	var templateDirectory = document.getElementById('templateDirectory').value; 
	location.href = templateDirectory+'/functions/download_csv.php?' + ids;
	
}