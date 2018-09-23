jQuery(document).ready(function ($) {
    console.log('main.js file is running...');

    $('.title-notice').on('click', function (e) {
        e.preventDefault();
        // console.log('title clicked !');

        var $title = $(this).text();
        // console.log($title);

        var $content = $(this).next('.content-notice').html();

        $('#cbx_notice_dialog').dialog({
            title: $title,
            modal:true,
            buttons: {
                Ok: function() {
                    $( this ).dialog( "close" );
                }
            },
            open: function () {
                $(this).html($content);
            },
            close: function (e) {
                $(this).empty();
                $(this).dialog('destroy');
            }
        });


    });//end of on click title-notice method

});//end of jQuery ready function