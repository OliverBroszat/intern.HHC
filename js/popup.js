function popup(content, name, title) {

    if (!$("body").hasClass("popup-on")) {
        $("body").addClass("popup-on");
        $("body").css("padding-right", getScrollBarWidth());
    }

    $(".popup-blende").remove();

    var id = $(".popup").length + 1;

    if (content == null) {
        // Warten, bis Content nachträglich hinzugefügt wird über $(".popup-content").html()
        content = "<div class='modal'></div>";
    }

    if (name == null) { 
        name == "";
    }

    var hide = "";
    if (title == null) { 
        hide = "hide";    
    }

    $("body").append(`
        <div id='popup-` + id + `' class='popup ` + name + `'>
            <div class='popup-blende' style='display:none;'></div>
            <div class='popup-wrapper'>
                <div class='popup-content-outer panel'>
                    <div class="popup-title ` + hide + `">` + title + `</div>
                    <div class='popup-content'>
                        ` + content + `
                    </div>
                </div>
            </div>
        </div>
    `);

    if (id == 1) { 
        $("#popup-" + id + " .popup-blende").fadeIn(100); 
    }
    else{ 
        $("#popup-" + id + " .popup-blende").show(); 
    }

    $("#popup-" + id + " .popup-wrapper").fadeIn(100);

    // $("#popup-" + id + " .popup-content-outer").center();

    // Popup kann mit der Maus bewegt werden. Funktioiniert nicht mit Touch. Ist noch etwas buggy in Kompination mit CSS-Transformations
    // $("#popup-" + id + " .popup-content-outer").draggable();
}

function popup_close() {
    var id = $(".popup").length;

    // Blende das letzte Popup (ohne Blende) aus und entferne es
    var id_active = "#popup-" + id;
    $(id_active  + " .popup-wrapper").fadeOut(100, function() { 

        $(this).remove(); 

        // prüfe, ob es sich um das letzte Popup handelt
        if (id == 1) {
            // Blende das gesamte Popup (die Blende) aus und entferne sie
            $(id_active).fadeOut(100, function() { $(this).remove(); });
            $("body").removeClass("popup-on");
            $("body").css("padding-right", "0");
        } 
        else {
            // entferne das gesamte Popup (mit Blende)
            $(id_active).remove();
            // Füge die Blende wieder hinter das vorletzte Popup ein 
            var id_before = "#popup-" +  (id - 1);
            $(id_before).prepend("<div class='popup-blende'></div>");
        }
    });
    
}

function popup_close_dialog(message) {
    
    dialog(
        message,
        function() {
            // Schließe den Dialog
            popup_close();
            // Schließe das eigentliche Popup
            setTimeout(function() {
                popup_close();
            }, 0);
        },
        function() {
            // Schließe nur das Popup
            popup_close();
        }
    );
}


function image_popup(href, event) {   
    event.preventDefault();
    popup("<img src='" + href + "'><div class='close' onclick='popup_close()'>&#215;</div>", "image");
    $(".popup.image .popup-content").toggleClass("modal");
    setTimeout(function() {
        $(".popup.image .popup-content").toggleClass("modal");
    }, 300);
}