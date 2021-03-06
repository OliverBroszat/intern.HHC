function ajax_post() {
	// close search suggestions
	$('#suggestions').toggleClass("show",false); 
	
	// Insert loading animation
	$( "#list-container" ).html( "<div class='modal'></div>" );

	// get form and url
	var form = $( "#form-suche" );
	var url = form.attr( "action" );

	// sent form data via AJAX to url (/functions/search/sql_search.php)
	$.post( url, form.serialize(), function( result ) {	

		var data = $.parseJSON(result);
		// Insert HTML result
		$( "#list-container" ).html(data['html'] );
		// Insert number of search results in title
		$( "#search-results-title" ).html( "Suchergebnisse (" + data['number'] + ")" );

		// Remove loading animation
		$( "#list-container .modal" ).remove();
		
		// Semantic UI
		$('.ui.checkbox').checkbox();
		$('.ui.dropdown').dropdown();
		$('.ui.menu .item').tab();
	});
}


function member_details(id){

	popup(null, "member-details", "Deatils");

	var data = new FormData();
	data.append('id', id);

  	var templateDirectory = document.getElementById('templateDirectory').value; 

	$.ajax({
  		url: templateDirectory+'/functions/search/member_details.php',
	  	data: data,
		processData: false,
		contentType: false,
		type: 'POST',
		success: function(data){
			setTimeout(function(){
				
				$('.member-details .popup-content').html(data);
				placeholder_color();
		
				// check image buttons
				if ($(".edit-image-image a").attr('href') != '#') {
					$('#edit-upload-image').hide();
					$('#edit-delete-image').show();
				}

			}, 0);
		}
	});
}
