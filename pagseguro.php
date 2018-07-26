<?php

require_once('source/pagseguro/PagSeguro.php');

$pagseguro = new PagSeguro(false);

$payment = [
    'method' => 'creditCard',
    'user_name' => 'Paulo Pimentel',
    'user_email' => 'c68305762980499844605@sandbox.pagseguro.com.br',
    'user_phone' => '11991296067',
    'user_document' => '38684383869',
    'hash' => $pagseguro->getSessionId(),
    'token' => '3ce54cb614764248a8da39023daa0438',
    'reference' => 'id123',
    'user_addr_stret' => 'Rua vinte e cinco',
    'user_addr_number' => '138',
    'user_addr_complement' => 'Cj. Marcos Freire',
    'user_addr_district' => 'Pimentas',
    'user_addr_city' => 'Guarulhos',
    'user_addr_state' => 'SP',
    'user_addr_country' => 'BRA',
    'user_addr_postalCode' => '07263725',
    'amount' => 999,
    'quantity' => 1,
    'value' => 999,
    'card_holder_name' => 'Paulo Pimentel',
    'card_holder_birth' => '04/09/1987',
    'card_holder_phone' => '1124849639',
];

$pagseguro->payment(
    $payment['method'],
    $payment['user_name'],
    $payment['user_email'],
    $payment['user_phone'],
    $payment['user_document'],
    $payment['hash'],
    $payment['token'],
    $payment['reference'],
    $payment['user_addr_stret'],
    $payment['user_addr_number'],
    $payment['user_addr_complement'],
    $payment['user_addr_district'],
    $payment['user_addr_city'],
    $payment['user_addr_state'],
    $payment['user_addr_country'],
    $payment['user_addr_postalCode'],
    $payment['amount'],
    $payment['quantity'],
    $payment['value'],
    $payment['card_holder_name'],
    $payment['card_holder_birth'],
    $payment['card_holder_phone']
);

var_dump($pagseguro->getCallback());

?>
<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Integração PagSeguro</title>
</head>
<body>

    <form class="" action="" method="post">
        <!-- campo responsável por armazenar o id da sessão -->
        <input type="hidden" id="id_session" name="id_session" value="<?= $pagseguro->getSessionId() ?>">

        <!-- <select class="" id="id_installments" name="installments">
            <option value="">Selecione a quantidade de parcelas</option>
        </select> -->
    </form>

    <script src="source/pagseguro/_cdn/jquery-3.3.1.min.js"></script>
    <script src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
    <!-- <script src="source/pagseguro/js/script.js"></script> -->
</body>
</html>
