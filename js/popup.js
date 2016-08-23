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
            <div class='popup-blende' style="display: none;"></div>
            <div class='popup-wrapper' style="display: none;">
                <div class='popup-content-outer panel'>
                    <div class='pupup-close-button-top popup-close'>&#215;</div>
                    <div class="popup-title ` + hide + `">` + title + `</div>
                    <div class='popup-content'>
                        ` + content + `
                    </div>
                </div>
            </div>
        </div>
    `);

    if (id == 1) { 
        // first popup
        $("#popup-" + id + " .popup-blende").fadeIn(50); 
    }
    else{ 
        // not first popup
        $("#popup-" + id + " .popup-blende").show(); 
    }

    $("#popup-" + id + " .popup-wrapper").fadeIn(50);
}

function popup_close() {
    var id = $(".popup").length;
    var id_active = "#popup-" + id;

    if (id == 1) {
        // last Popup
        $(id_active).fadeOut(0, function() { 
            // remove popup + blende 
            $(this).remove();
            // reset body
            $("body").removeClass("popup-on");
            $("body").css("padding-right", "0");
        });
    } 
    else {
        // not last popup
        $(id_active  + " .popup-wrapper").fadeOut(0, function() { 
            // remove popup
             $(id_active).remove();
        });
        //remove popup blende
        $(id_active  + " .popup-blende").remove();       
        // set Blende for popup before
        var id_before = "#popup-" +  (id - 1);
        $(id_before).prepend("<div class='popup-blende' onclick='popup_close()'></div>");
    }  
};


$(document).on("click", ".popup-close, .popup-blende", function() {
    popup_close();
});

$(document).on("click", ".reload-form .popup-close, .reload-form .popup-blende", function() {
    ajax_post();
});


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
    var array = href.href.split('/');
    var src = array[array.length - 1];
    if (src != '#') {
        popup("<img src='" + href + "'>", "image");
        $(".popup.image .popup-content").toggleClass("modal");
        setTimeout(function() {
            $(".popup.image .popup-content").toggleClass("modal");
        }, 300);
    }
}