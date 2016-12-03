// Notifications
function msgBar(title, content, color) {
  $('body').append(`
    <div class="ui message `+ color + `" id="msgBar" style='display:none'>
      <div class="header">
        `+ title + `
      </div>
      <p>`+ content + `</p>
    </div>
  `);
  $('#msgBar').transition('jiggle');

  setTimeout(function() {
    $('#msgBar').transition('scale');
  }, 4000);
};


// Toggle loading animation on solution submit
$(document).on("click", "button[name='solution']", function(e) {
  $("#solutions").toggleClass('loading');
});


// enable semantic ui dropdown
$(function() {
  $('.selection.dropdown').dropdown();
});
