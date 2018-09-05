<?php
//wp_mail ( $_POST['email'], $_POST['subject'], $_POST['message'] );
$form_valid = true;
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

echo json_encode($data);

