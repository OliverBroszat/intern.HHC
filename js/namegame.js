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
  // mark selected button
  $(this).addClass('teal');
  //loading animation
  $(".img-container").addClass('loading');
  // disable all buttons
  $("#solutions .button").addClass('disabled');
});


// Toggle loading on start/cancel button
$(document).on("click", "#game-start, #game-cancel", function() {
  // disable button and toggle loading animation
  $(this).addClass('loading disabled');
  // disable buttons and image
  $(".img-container .segment, #solutions .button").addClass('disabled');
})


// enable semantic ui dropdown
$(function() {
  $('.selection.dropdown').dropdown();
});
