<?php
require_once('../source/pagseguro/Pagseguro.php');
$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$pagseguro = new Pagseguro(false);
$pagseguro->payment(number_format($post['amount'], 2, '.', '.'), number_format($post['installmentValue'], 2, '.', '.'), $post['token']);

var_dump($pagseguro->getCallback());

echo json_encode($post);
