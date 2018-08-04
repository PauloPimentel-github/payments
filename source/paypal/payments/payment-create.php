<?php

$getPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($getPost)) {
    $setPost = array_map('strip_tags', $getPost);
    $post = array_map('trim', $setPost);

    require_once '../Paypal.php';
    $paypal = new \Paypal\Paypal(false);

    $json = [];

    $items = array('items' => [
        [
            'name' => $post['item_name'],
            'description' => $post['item_description'],
            'quantity' => $post['item_quantity'],
            'price' => $post['item_price'],
            'tax' => '0.01',
            'sku' => '2',
            'currency' => 'BRL'
        ]
    ]);

    $subTotal = number_format(($items['items'][0]['quantity'] * $items['items'][0]['price']), 2, '.', '.');
    $total = $subTotal + 0.11;

    $payment = $paypal->payment(
        $post['invoice_number'],
        $total,
        $subTotal,
        $post['purchase_description'],
        $items['items'],
        $post['payer_description'],
        $post['name'],
        $post['district'],
        $post['street'],
        $post['number'],
        $post['city'],
        $post['state'],
        $post['postal_code'],
        $post['phone'],
        'Descrição do cliente',
        'https://localhost/upinside-play/play_integracao_pagamentos/'
    );

    if (!empty($payment)) {
        $json['success'] = $payment->id;
    } else {
        $json['error'] = 'Error';
    }

    echo json_encode($json);
}
