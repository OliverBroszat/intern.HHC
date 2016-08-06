function ajax_post() {
	$('#suggestions').toggleClass("show",false); 
	
	$( "#list-container" ).addClass( "modal" );

	var form = $( "#form-suche" )
	var url = form.attr( "action" );

	$.post( url, form.serialize(), function( result ) {	
		setTimeout(function(){
			var data = $.parseJSON(result);

			$( "#list-container" ).html( data['html'] );
			$( "#list-container" ).removeClass( "modal" );
			$( "#search-results-title" ).html( "Suchergebnisse (" + data['number'] + ")" );
		}, 100);
	});
}