$(function ($) {
    $(".favor").click(function () {
        $('.reserved').removeClass('active');
        $(this).addClass('active');
        $('.reserved_div').addClass('res_fav_div');
        $('.favorite_div').removeClass('res_fav_div');
    }); // end toggle

    $(".reserved").click(function () {
        $('.favor').removeClass('active');
        $(this).addClass('active');
        $('.reserved_div').removeClass('res_fav_div');
        $('.favorite_div').addClass('res_fav_div');
    }); // end toggle

    $.initializeSlider = {
        init: function (cat_id) {
            $('#categories_' + cat_id).microfiche({bullets: false, cyclic: true, autoplay: 3, autopause: true});
        }
    };

    $(".btn_book_reserve").click(function () {
        $('.book-title').addClass('reserve_hide');
        $('.book-description').addClass('reserve_hide');
        $('.reserve_div').removeClass('reserve_hide');
        $(this).addClass('reserve_hide');

    })


});