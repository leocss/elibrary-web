/**
 * Created by DAKO on 10/4/14.
 */
$(function(){
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
});
