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

if (!isset($_SESSION)) session_start();


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
    //storing all the session value
if (isset($_SESSION)){
    $error = array();
    $data = array();

    if (!empty($_SESSION['name'])){
        $data['name'] = $_SESSION['name'];
    }
    if (!empty($_SESSION['error_name'])){
        $error['name_error'] = $_SESSION['error_name'];
    }

    if (!empty($_SESSION['email'])){
        $data['email'] = $_SESSION['email'];
    }
    if (!empty($_SESSION['error_email'])){
        $error['email_error'] = $_SESSION['error_email'];
    }

    if (!empty($_SESSION['subject'])){
        $data['subject'] = $_SESSION['subject'];
    }
    if (!empty($_SESSION['error_subject'])){
        $error['subject_error'] = $_SESSION['error_subject'];
    }

    if (!empty($_SESSION['message'])){
        $data['message'] = $_SESSION['message'];
    }
    if (!empty($_SESSION['error_message'])){
        $error['message_error'] = $_SESSION['error_message'];
    }

    if (!empty($_SESSION['mail_sent'])){
        $error['mail_sent'] = $_SESSION['mail_sent'];
    }
    //making the $_SESSION  an empty array for the next session
    $_SESSION = array();
}

    ob_start();
    ?>
    <div>
<!--        --><?php //var_dump($data); die(); ?>
        <form id="contact_form" method="POST" action="<?= $current_url?>">
            <label for="name">Full Name:</label>
            <input type="text" name="name" id="name" value="<?php if (!empty($data['name'])) echo $data['name']; ?>" />
            <label for="name_error"><?php if (!empty($error['name_error'])) echo $error['name_error']; ?></label>

            <label for="email">Email:</label>
            <input type="email" name="email"  value="<?php if (!empty($data['email'])) echo $data['email']; ?>" />
            <label for="email_error"><?php if (!empty($error['email_error'])) echo $error['email_error']; ?></label>

            <label for="subject">Subject:</label>
            <input type="text" name="subject" value="<?php if (!empty($data['subject'])) echo $data['subject']; ?>"/>
            <label for="subject_error"><?php if (!empty($error['subject_error'])) echo $error['subject_error']; ?></label>


            <label for="message">Message:</label>
            <textarea rows="4" cols="50" name="message"></textarea>
            <label for="message_error"><?php if (!empty($error['message_error'])) echo $error['message_error']; ?></label>

            <input type="hidden" name="actionpage" value="<?= $current_url?>" />
            <button type="submit" name="cbxform_submit" id="cbxform_submit" value="1"> Submit </button>
        </form>
    </div>

    <?php
    $form = ob_get_contents();
    ob_end_clean();
    ?>
    <!--<script>
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
    </script>-->


<?php

    return $form;
}

add_shortcode('contact', 'cbx_contact');

$data = array();
function my_page_template_redirect()
{

    //necessary array for the messages
    $output = array();
    $output['validation'] = 1;
    $output['success_messages'] = array();
    $output['validation_messages'] = array();

    $validation_messages  = array();
    $success_messages  = array();

    if(isset($_POST['cbxform_submit']) && intval($_POST['cbxform_submit']) == 1){
        $form_valid = true;
        //get the fields here, do some operation and then return to previous page
        //validation for the name field
        if (isset($_POST['name'])) {
            $data['name'] = $_POST['name'];
            if (empty($data['name'])) {
                $validation_messages['error_name'] =  'Name cannot be empty';
                $output['validation'] = 0;
                $form_valid = false;
            }
        }
        //validation for the email field
        if (isset($_POST['email'])) {
            $data['email'] = $_POST['email'];
            if (empty($data['email'])) {
                $validation_messages['error_email'] =  'Email is empty, give your email';
                $output['validation'] = 0;
                $form_valid = false;
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $validation_messages['error_email'] =  'Invalid email';
                $output['validation'] = 0;
                $form_valid = false;
            }
        }

        //validation for the subject field
        if (isset($_POST['subject'])) {
            $data['subject'] = $_POST['subject'];
            if (empty($data['subject'])) {
                $validation_messages['error_subject'] =  'Subject cannot be empty';
                $output['validation'] = 0;
                $form_valid = false;
            }
        }

        //validation for the message field
        if (isset($_POST['message'])) {
            $data['message'] = $_POST['message'];
            if (empty($data['message'])) {
                $validation_messages['error_message'] =  'Enter your message';
                $output['validation'] = 0;
                $form_valid = false;
            }
        }

        //if the form is valid then send the mail
        if ($form_valid){
            wp_mail ( $data['email'], $data['subject'], $data['message'] );
            $success_messages['mail_sent'] = 'Mail has been sent successfully.';
            $_SESSION = $success_messages;
        }else{
            //all the validation message is stored in the $_SESSION array
//            $_SESSION = $validation_messages;
            $_SESSION = array_merge($validation_messages, $data);
//            var_dump($_SESSION);
//            die();
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

//add_action('init');





