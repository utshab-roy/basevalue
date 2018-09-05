<?php
/*
Plugin Name: My Plugin
Plugin URI: http://www.google.com
description: a plugin to create awesomeness and spread joy
Version: 1.0
Author: Mr. Utshab
Author URI: http://www.github.com/utshab-roy
License: GPL2
*/

//
//if (isset($_POST['form_submit']) && (intval($_POST['form_submit'])) == '1'){
//    echo '<pre>';
//    var_dump($_POST);
////    var_dump(get_post());
//    echo '</pre>';
//}




//Lorem demo
function lorem_function()
{
    return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nec nulla
        Nam ullamcorper elit id magna hendrerit sit amet dignissim elit sodales. Aenean accumsan
        consectetur rutrum.';
}

add_shortcode('lorem', 'lorem_function');


function random_picture($atts)
{
//method one not preferable
    /*extract(shortcode_atts(array(
    'width' => 400,
    'height' => 200,
    ), $atts));*/

//method two, this is preferable
    $args = shortcode_atts(array(
        'width' => 500,
        'height' => 200,
    ), $atts);

    $width = $args['width'];
    $height = $args['height'];

    return '<img src="https://via.placeholder.com/' . $width . 'x' . $height . '" />';
}

add_shortcode('picture', 'random_picture');


//contact us form
function cbx_contact(){

//    global $wp;
    //$current_url =  home_url( $wp->request );

    $current_url = $_SERVER['REQUEST_URI'];

//    var_dump($current_url);

    $form =
        '<form id="contact_form" method="POST" action="'.$current_url.'">'.
        'Full Name: <input type="text" name="name" id="name" /><br>'.
        'Email: <input type="email" name="email"/><br>'.
        'Subject: <input type="text" name="subject"/><br>'.
        'Message: <textarea rows="4" cols="50" name="message"></textarea><br>'.
        '<input type="hidden" name="actionpage" value="'.$current_url.'" />'.
        '<button type="submit" name="cbxform_submit" id="cbxform_submit" value="1"> Submit </button>'.
    '</form>';

    ?>
    <script>
        jQuery(document).ready(function ($) {
//            console.log('sdfsdf');
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
            });


        });
    </script>


<?php

    return $form;
}

add_shortcode('contact', 'cbx_contact');


function my_page_template_redirect()
{
    if(isset($_POST['cbxform_submit']) && intval($_POST['cbxform_submit']) == 1){
        $form_valid = true;
        //get the fields here, do some operation and then return to previous page
        $data = array();

        if (isset($_POST['name'])) {
            if (!empty($_POST['name'])) {
                $data['name'] = $_POST['name'];
            }else{
                $form_valid = false;
            }
        }

        if (isset($_POST['email'])) {
            if (!empty($_POST['email'])) {
                $data['email'] = $_POST['email'];
            }else{
                $form_valid = false;
            }
        }

        if (isset($_POST['subject'])) {
            if (!empty($_POST['subject'])) {
                $data['subject'] = $_POST['subject'];
            }else{
                $form_valid = false;
            }
        }

        if (isset($_POST['message'])) {
            if (!empty($_POST['message'])) {
                $data['message'] = $_POST['message'];
            }else{
                $form_valid = false;
            }
        }

        //if the form is valid then send the mail
        if ($form_valid){
            wp_mail ( $data['email'], $data['subject'], $data['message'] );
        }else{
            echo 'need to fill up the form';
        }

        $actionpage_url = isset($_POST['actionpage'])? esc_url($_POST['actionpage']): esc_url(home_url());
        wp_redirect( $actionpage_url );
        die;
    }
}
add_action( 'template_redirect', 'my_page_template_redirect' );


function mailtrap($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host = 'smtp.mailtrap.io';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 2525;
    $phpmailer->Username = 'a8708d956d2e34';
    $phpmailer->Password = '01579f1934d78c';
}

add_action('phpmailer_init', 'mailtrap');


// Add scripts and stylesheets
function basevalue_scripts() {
    wp_enqueue_script( 'validation', plugin_dir_url( __FILE__ ). 'jquery.validate.js', array( 'jquery' ), '3.3.6', true );
}

add_action( 'wp_enqueue_scripts', 'basevalue_scripts' );





