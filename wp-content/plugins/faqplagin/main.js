jQuery(document).ready(function ($) {
    console.log('main.js file is running...');

    $('.title-faq').on('click', function () {

        $(this).next().slideToggle();

    });

    var $wporg_field_date = $('#wporg_field_date');

    $wporg_field_date.datepicker({
        dateFormat : 'dd-mm-yy',
        showOtherMonths: true,
        selectOtherMonths: true,
        orientation: "top"
    });

    var $wporg_field_time = $('#wporg_field_time');
    $wporg_field_time.timepicker({});

    var $wporg_field_datetime = $('#wporg_field_datetime');
    $wporg_field_datetime.datetimepicker({
        dateFormat: 'dd-mm-yy',
        timeFormat: 'hh:mm:ss tt'
    });

    var $wporg_field_color = $('#wporg_field_color');
    $wporg_field_color.wpColorPicker({});





});//end of jQuery ready function