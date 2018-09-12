jQuery(document).ready(function ($) {
    console.log('main.js file is running...');

    $('.title-faq').on('click', function () {

        $(this).next().slideToggle();

    });


});//end of jQuery ready function