// Bestätigungsfenster
// Wird aufgerufen mit: dialog('message', function(){//yesCallback}, function(){//noCallback})

function dialog(message, yesCallback, noCallback) {
   if(message == null) {
   		message = 'Sind Sie sicher?'
   }

	popup("<h2>	" + message + "</h2> <button type='button' id='btnYes' value='yes'>OK</button><button type='button' id='btnNo' value='No'>Abbrechen</button>", 'dialog', 'Achtung!');
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

// Center-Function

jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()) + "px");
    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft()) + "px");
    return this;
}

// change color of placeholder option 

$(document).ready(function() {
    var $select = $('select');
    $select.each(function() {
        $(this).addClass($(this).children(':selected').attr('class'));
    }).on('change', function(ev) {
        $(this).attr('class', '').addClass($(this).children(':selected').attr('class'));
    });
});