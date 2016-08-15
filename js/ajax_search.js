function ajax_post() {
	$('#suggestions').toggleClass("show",false); 
	
	$( "#list-container" ).html( "<div class='modal'></div>" );

	var form = $( "#form-suche" )
	var url = form.attr( "action" );

	$.post( url, form.serialize(), function( result ) {	
		var data = $.parseJSON(result);

		console.log(data['debug']);

		$( "#search-results-title" ).html( "Suchergebnisse (" + data['number'] + ")" );
		$( "#list-container" ).html(data['html'] );
		$( "#list-container .modal" ).remove();
	});
}