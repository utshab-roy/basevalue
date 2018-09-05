<?php
/*
Plugin Name: Ajax Plugin
Plugin URI: http://www.google.com
description: a plugin to create awesomeness and spread joy
Version: 1.0
Author: Mr. Utshab
Author URI: http://www.github.com/utshab-roy
License: GPL2
*/

//contact us form
function cbx_contact(){

//    global $wp;
    //$current_url =  home_url( $wp->request );

    $current_url = $_SERVER['REQUEST_URI'];

//    var_dump($current_url);

    $form =
        '<form id="contact_form" method="POST" action="'.$current_url.'">'.
        'Full Name: <input type="text" name="name" id="name" /><br>'.
        'Email: <input type="email" name="email" id="email"/><br>'.
        'Subject: <input type="text" name="subject" id="subject"/><br>'.
        'Message: <textarea rows="4" cols="50" name="message" id="message"></textarea><br>'.
        '<input type="hidden" name="actionpage" id="actionpage" value="'.$current_url.'" />'.
        '<input type="hidden" name="currentdir" id="currentdir" value="'.plugin_dir_url(__FILE__).'" />'.
        '<button type="submit" name="cbxform_submit" id="cbxform_submit" value="1"> Submit </button>'.
        '</form>';

//    echo plugin_dir_url(__FILE__);
//    echo '<br>';
//    echo $current_url;
//    die();

    ?>
    <script></script>


    <?php

    return $form;
}

add_shortcode('contact', 'cbx_contact');

// Add scripts and stylesheets
function basevalue_scripts() {
    wp_enqueue_script( 'validation', plugin_dir_url( __FILE__ ). 'jquery.validate.js', array( 'jquery' ), '3.3.6', true );

    wp_enqueue_script( 'ajax_script', plugin_dir_url( __FILE__ ). 'my_ajax_script.js', array( 'jquery' ), '3.3.6', true );
    wp_localize_script( 'ajax_script', 'my_ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

add_action( 'wp_enqueue_scripts', 'basevalue_scripts' );

//add action for configure the mail
function mailtrap($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host = 'smtp.mailtrap.io';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 2525;
    $phpmailer->Username = 'a8708d956d2e34';
    $phpmailer->Password = '01579f1934d78c';
}

add_action('phpmailer_init', 'mailtrap');


add_action( 'wp_ajax_my_test_ajax', 'my_test_ajax' );
add_action( 'wp_ajax_nopriv_my_test_ajax', 'my_test_ajax' );

function my_test_ajax()
{
    $data = array();
    $form_valid = true;

    //necessary array for the messages
    $output = array();
    $output['validation'] = 1;
    $output['success_messages'] = array();
    $output['validation_messages'] = array();

    $validation_messages  = array();
    $success_messages  = array();



    if (isset($_POST['name'])) {
        $data['name'] = $_POST['name'];
        if (empty($data['name'])) {
            $validation_messages['name'] =  'Name cannot be empty';
            $output['validation'] = 0;
        }
    }



    if (isset($_POST['email'])) {
        $data['email'] = $_POST['email'];
        if (empty($data['email'])) {
            $validation_messages['email'] =  'Email is empty, give your email';
            $output['validation'] = 0;
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $validation_messages['email'] =  'Invalid email';
            $output['validation'] = 0;
        }
    }

    if (isset($_POST['subject'])) {
        $data['subject'] = $_POST['subject'];
        if (empty($data['subject'])) {
            $validation_messages['subject'] =  'Subject cannot be empty';
            $output['validation'] = 0;
        }
    }


    if (isset($_POST['message'])) {
        $data['message'] = $_POST['message'];
        if (empty($data['message'])) {
            $validation_messages['message'] =  'Enter your message';
            $output['validation'] = 0;
        }
    }



//if the form is valid then send the mail
    if (intval($output['validation']) == 1) {
        wp_mail ( $data['email'], $data['subject'], $data['message'] );
        $success_messages['mail_sent'] = 'mail has been sent successfully.';
        $output['success_messages'] =  $success_messages  ;
    } else {
//        echo 'need to fill up the form';
        $output['validation_messages'] = $validation_messages;
    }

//    echo json_encode($data);
    wp_send_json($output);

    exit();

}

