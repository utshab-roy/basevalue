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
                console.log(data);
                $('#alert-message div').remove();
                if (data.validation == 0) {
                    //highlight the error fields and show error messages
                    var $validation_messages = data.validation_messages;
                    $.each($validation_messages, function (index, value) {
                        console.log('Index:' + index + ' value:' + value);
                        // $alert.html('<div id="'+ index +'" class="alert alert-danger" role="alert">'+value+'</div>').append();
                        $alert.append('<div id="'+ index +'" class="alert alert-danger" role="alert">'+ value +'</div>');
                        // $alert.html('<div class="alert alert-danger" role="alert">'+value+'</div>').show();

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
                }
            },  //end of success option
                // for printing the error
            error: function (err) {
                console.log(err);
            }
        });

    });






});//end of jQuery ready function