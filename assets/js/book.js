$(function($){
    $(".favor").click(
        function() {
            $('.reserved').removeClass('active');
            $(this).addClass('active');
        }); // end toggle
    $(".reserved").click(
        function() {
            $('.favor').removeClass('active');
            $(this).addClass('active');
        }); // end toggle
    //$('{{'#categories_'~ cat }}').microfiche({ bullets: false, cyclic: true, autoplay: 3, autopause: true});
    /**
     *
     * @param cat_id
     */
    $.initializeSlider = {
        init:function(cat_id){
            $('#categories_' + cat_id ).microfiche({ bullets: false, cyclic: true, autoplay: 3, autopause: true});
            //console.log("log");
        }
    };


})(jQuery);