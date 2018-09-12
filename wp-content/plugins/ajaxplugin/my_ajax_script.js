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
                $alert = $('#alert-message');
                // console.log(data);
                $('#alert-message div').remove();
                if (data.validation == 0) {
                    //highlight the error fields and show error messages
                    var $validation_messages = data.validation_messages;
                    $.each($validation_messages, function (index, value) {
                        console.log('Index:' + index + ' value:' + value);
                        // $alert.html('<div id="'+ index +'" class="alert alert-danger" role="alert">'+value+'</div>').append();
                        $alert.append('<div id="'+ index +'" class="alert alert-danger" role="alert">'+value+'</div>');

                    });
                }
                else {
                    //highlight the success fields and show the messages
                    var $success_messages = data.success_messages;
                    // console.log($success_messages);
                    $.each($success_messages, function (index, value) {
                        if (index === 'mail_sent'){
                            $alert.html('<div class="alert alert-success" role="alert">'+value+'</div>').show();
                        }else{
                            $alert.html('<div class="alert alert-danger" role="alert">'+value+'</div>').show();
                        }
                    });
                    $('#sign_up_form').remove();
                }
            },  //end of success option
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


           // this is the jquery validation process
           $('#contact_form').validate({
               rules: {
                   name: {required: true, minlength:5},
                   email: {required:true, email:true},
                   subject: {required:true},
                   message: {required:true, minlength:7}
               },
               messages: {
                   name: {
                       required: "name is required",
                       minlength: "Minimum {0} char"
                   },
                   email: {
                       required: "Email field is required",
                       email: "Must be a valid email"
                   },
                   subject: {required:"subject cannot be empty"},
                   message: {
                       required:"Enter your message",
                       minlength: "Minimum 7 charter required"
                   }
               }
           });//end of jQuery validation function

});//end of jQuery ready function