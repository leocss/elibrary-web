$(function ($) {
    //========================================================SIDEBAR_NAV===================================
          $(".favor").click(function () {
            $('.reserved').removeClass('active');
            $(this).addClass('active');
          }); // end toggle

          $(".reserved").click(function () {
            $('.favor').removeClass('active');
            $(this).addClass('active');
          }); // end toggle
    //========================================================SIDEBAR_NAV===================================


    //========================================================MICROFICHE SLIDER===================================
          $.initializeSlider = {
            init: function (cat_id) {
              $('#categories_' + cat_id).microfiche({bullets: false, cyclic: true, autoplay: 3, autopause: true});
            }
          };
    //========================================================END MICROFICHE SLIDER===================================

    //========================================================COUNTER ===================================
        function increCounter(value) {
                value+=1;
                return(value);
        };
    //========================================================END COUNTER===================================

    //========================================================COUNTER ===================================
    function decreCounter(value) {
        value-=1;
        return(value);
    };
    //========================================================END COUNTER===================================
});