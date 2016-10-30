// Bestätigungsfenster
// Wird aufgerufen mit: dialog('message', function(){//yesCallback}, function(){//noCallback})

function dialog(message, yesCallback, noCallback) {
   if(message == null) {
		message = 'Sind Sie sicher?'
   }

	popup(`
		<h2>` + message + `</h2>
		<button type='button' id='btnYes' value='yes' class='ui icon labeled red button' >
			<i class='checkmark icon '></i>OK
			</button>
		<button type='button' id='btnNo' value='No' class='ui icon labeled button'>
			<i class='ban icon '></i>Abbrechen
		</button>
	`, 'dialog', 'Achtung!');
	// var dialog = $('.dialog').dialog();

	$('#btnYes').click(function() {
		// dialog.dialog('close');
		yesCallback();
	});
	$('#btnNo').click(function() {
		// dialog.dialog('close');
		noCallback();
	});

}

function showDiv(elem) {
	var hiddenDiv = $('#hidden_div-'+elem.id)
	if(elem.value == 'other') {
		hiddenDiv.show();
		hiddenDiv.prop( 'disabled', false );
	} else {
		hiddenDiv.hide();
		hiddenDiv.prop( 'disabled', true );
	}
}

// Center-Function

// jQuery.fn.center = function () {
//     this.css("position","absolute");
//     this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()) + "px");
//     this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft()) + "px");
//     return this;
// }

// change color of placeholder option 

function placeholder_color(){
	var $select = $('select');
	$select.each(function() {
		$(this).addClass($(this).children(':selected').attr('class'));
	}).on('change', function(ev) {
		$(this).attr('class', '').addClass($(this).children(':selected').attr('class'));
	});
}

$(document).ready(function() {
	placeholder_color();
});


function select_option_exl(id_base, data, key) {
	// for each Studienprofil
	for (var i = 0; i < data.length; i++) {
		
		var id = id_base + (i + 2);
		var value = data[i][key];                   
		var options = $('#' + id + ' option');
		var inList = false;
		
		// for each option in the select-input
		for (var j = 1; j < options.length; j++) {      
			// check value
			if (options[j].value == value ) {
				// set value
				$('#' + id).val(options[j].value);
				inList = true;
			}
		}
		
		// value is not one of the options
		if (value == '' || value == null) {
			$('#' + id).val(null);
		}
		else if (!inList) {
			// set value to 'other'
			$('#' + id).val('other');
			
			// show hiddenDiv with value
			var hiddenDiv = $('#hidden_div-'+ id)
			hiddenDiv.val(value);
			hiddenDiv.show();
			hiddenDiv.prop( 'disabled', false );
		}
	}
};

function select_option(id_base, data, key) {
		
	var id = id_base;
	var value = data;                   
	var options = $('#' + id + ' option');
	var inList = false;
	
	// for each option in the select-input
	for (var j = 1; j < options.length; j++) {      
		// check value
		if (options[j].value == value ) {
			// set value
			$('#' + id).val(options[j].value);
			inList = true;
		}
	}
	
	// value is not one of the options
	if (value == '' || value == null) {
		$('#' + id).val(null);
	}
	else if (!inList) {
		// set value to 'other'
		$('#' + id).val('other');
		
		// show hiddenDiv with value
		var hiddenDiv = $('#hidden_div-'+ id)
		hiddenDiv.val(value);
		hiddenDiv.show();
		hiddenDiv.prop( 'disabled', false );
	}

};


function getScrollBarWidth() {
	var $outer = $("<div>").css({visibility: "hidden", width: 100, overflow: "scroll"}).appendTo("body"), 
		widthWithScroll = $("<div>").css({width: "100%"}).appendTo($outer).outerWidth();
	$outer.remove();
	return 100 - widthWithScroll;
};

function scrollToTarget(target) {
	$('html, body').animate({
        scrollTop: $(target).offset().top
    }, 300);
};