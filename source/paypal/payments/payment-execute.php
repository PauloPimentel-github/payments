<?php

$getPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($getPost)) {
    $setPost = array_map('strip_tags', $getPost);
    $post = array_map('trim', $setPost);

    require_once '../Paypal.php';
    $paypal = new \Paypal\Paypal(false);
    $pay = $paypal->paymentExecute($post['paymentID'], $post['payerID']);

    $json['pay'] = $pay;

    echo json_encode($json);
}
