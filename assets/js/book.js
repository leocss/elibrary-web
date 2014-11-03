$(function ($) {
  $(".favor").click(function () {
    $('.reserved').removeClass('active');
    $(this).addClass('active');
  }); // end toggle

  $(".reserved").click(function () {
    $('.favor').removeClass('active');
    $(this).addClass('active');
  }); // end toggle

  $.initializeSlider = {
    init: function (cat_id) {
      $('#categories_' + cat_id).microfiche({bullets: false, cyclic: true, autoplay: 3, autopause: true});
    }
  };
});