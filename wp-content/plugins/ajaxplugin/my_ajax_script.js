jQuery(document).ready(function ($) {
    console.log('javascript running...');
    var $contact_form = $('#contact_form');

    $contact_form.on('submit', function (e) {
        e.preventDefault();

        var $contact_form_data = $contact_form.serialize();
        $.ajax({
            url: my_ajax_object.ajax_url,
            data: 'action=my_test_ajax&' + $contact_form_data,
            // data: $contact_form_data,
            type: 'post',
            dataType: 'json',
            success: function(data){
                console.log(data)

            },
                // for printing the error
            error: function (err) {
                console.log(err);
            }
        });

    });




//*****************************************************************************
//            ajax request for the contact form
//            var $contact_form = $('#contact_form');
//            $contact_form.on('submit', function (event) {
//                event.preventDefault();
// //                console.log('Contact form submitted, sending ajax request');
//                var $contact_form_data = $contact_form.serialize();
// //                console.log($contact_form_data);
//                var current_dir = $('#currentdir').val();
// //                console.log(current_url + "contact.php");
// //                beginning of ajax method
//                $.ajax({
//                    type: "POST",
//                    url:  current_dir + "contact.php",
//                    data: $contact_form_data,
//                    dataType: 'json',
//                    beforeSend: function () {
//
//                    },
//                    cache: false,
//                    success: function (data) {
//                        console.log(data);
//                    },
//                    error: function (err) {
//                        console.log(err);
//                    }
//                }); // end of ajax method
//
//            });
//*****************************************************************************


//            this is the jquery validation process
//            $('#contact_form').validate({
//                rules: {
//                    name: {required: true, minlength:5},
//                    email: {required:true, email:true},
//                    subject: {required:true},
//                    message: {required:true, minlength:7}
//                },
//                messages: {
//                    name: {
//                        required: "name is required",
//                        minlength: "Minimum {0} char"
//                    },
//                    email: {
//                        required: "Email field is required",
//                        email: "Must be a valid email"
//                    },
//                    subject: {required:"subject cannot be empty"},
//                    message: {
//                        required:"Enter your message",
//                        minlength: "Minimum 7 charter required"
//                    }
//                }
//            });//end of jQuery validation function

});//end of jQuery ready function