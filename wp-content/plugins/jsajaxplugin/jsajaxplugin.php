<?php
/*
Plugin Name: JS validation AJAX submission
Plugin URI: http://www.google.com
description: This plugin will validate the form using js-validation and submit the form using ajax
Version: 1.0
Author: Mr. Utshab
Author URI: http://www.github.com/utshab-roy
License: GPL2
*/

if (!isset($_SESSION)) session_start();

//contact us form
function cbx_contact(){

//    global $wp;
    //$current_url =  home_url( $wp->request );

    $current_url = $_SERVER['REQUEST_URI'];
    //storing all the session value
    if (isset($_SESSION)){
//        var_dump($_SESSION);
//        die();
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
            $notice = $_SESSION['mail_sent'];
            echo '<label for="notice">'. $notice .'</label>';
        }

    }

    ob_start();
    ?>
    <div>
        <div id="alert-message"></div>
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

    //making the $_SESSION  an empty array for the next session
    $_SESSION = array();

    ob_end_clean();
    ?>
    <script>
        jQuery(document).ready(function ($) {
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

$data = array();



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

    wp_enqueue_script( 'ajax_script', plugin_dir_url( __FILE__ ). 'my_ajax_script.js', array( 'jquery' ), '3.3.6', true );
    wp_localize_script( 'ajax_script', 'my_ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

add_action( 'wp_enqueue_scripts', 'basevalue_scripts' );

add_action( 'wp_ajax_my_test_ajax', 'my_test_ajax' );
add_action( 'wp_ajax_nopriv_my_test_ajax', 'my_test_ajax' );


//function responsible for the ajax submission
function my_test_ajax(){
    //necessary array for the messages
    $output = array();
    $output['validation'] = 1;
    $output['success_messages'] = array();
    $output['validation_messages'] = array();

    $validation_messages  = array();
    $success_messages  = array();


//    echo json_encode($_POST['cbxform_submit']);
//    die();

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
        wp_send_json($_SESSION);

}




