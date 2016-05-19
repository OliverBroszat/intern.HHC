function edit(id){
	$('body').toggleClass("popup");
	$('#popup-blende').fadeToggle(300);
	$('#popup-edit').fadeToggle(50);
	$('#popup-edit').toggleClass("modal",true);

	var data = new FormData();
	data.append('id', id);

  	var templateDirectory = document.getElementById('templateDirectory').value; 

	$.ajax({
  		url: templateDirectory+'/functions/edit/edit.php',
	  	data: data,
		processData: false,
		contentType: false,
		type: 'POST',
		success: function(data){
			setTimeout(function(){
				$('#popup-edit').toggleClass("modal",false);
				$('#popup-edit #popup-content').html(data);
			}, 600);
		}
	});
}

function popup_close(){
	$('body').toggleClass("popup", false);
	$('#popup-blende').fadeToggle(0);
	$('#popup-edit').fadeToggle(0);
}
