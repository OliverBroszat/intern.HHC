function ajax_search_suggestions(input) {


	if (input.length==0) { 
		$('#suggestions').html('');
		$('#suggestions').toggleClass("show",false); 
		return;
	}
	else {
		var data = new FormData();
		data.append('search_text', input);

		// var templateDirectory = $('#templateDirectory').value;
		var templateDirectory = document.getElementById('templateDirectory').value;

		var focus = false;
		if ($("#text-box").is(":focus")) {
			focus = true;
		}
		

		$.ajax({
			url: templateDirectory+'/functions/suchfunktion/search_suggestions.php',
			data: data,
			processData: false,
			contentType: false,
			type: 'POST',
			success: function(result){
				setTimeout(function(){
					$('#suggestions').html(result);
					
					if (result!=0 && focus) {
						$('#suggestions').toggleClass("show",true);
					}else{
						$('#suggestions').toggleClass("show",false); 
					}

				}, 0);
			}
		});
	}	
}



function add_to_search_box(new_value){
	$('#suggestions').toggleClass("show",false); 
	$('#text-box').val(new_value);
	$('#text-box').focus();
	// ajax_post();
}


$(document).ready(function() {
	$( "#text-box" ).blur(function() {
		setTimeout(function(){
			$('#suggestions').toggleClass("show",false); 
		}, 100);
	})
});
