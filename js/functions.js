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