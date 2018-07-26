<?php

require_once('source/pagseguro/PagSeguro.php');

$pagseguro = new PagSeguro(false);

//array com informações para criação de um plano no pagseguro
$plan = [
    'plan_id' => 1,
    'plan_code_pagseguro' => 'BEBE0D320E0E5DE004986F9EECCBA315', //CODIGO DO PLANO AQUI
    'plan_title' => 'Meu Plano Teste',
    'plan_price' => '250.00',
    'plan_active' => 1,
    'plan_recurrency' => 'MONTHLY'
];

//Chamada do método para criação do plano
//$pagseguro->createPlan('1', $plan['plan_title'], $plan['plan_recurrency'], $plan['plan_price']);
//var_dump($pagseguro); //código do plano: BA9FFF9789893B5334871F9C5BF4E7C2

//array com informações que são necessárias para realizar operação no pagseguro
$user = [
    'user_name' => 'Paulo Pimentel',
    'user_document' => '91309581304',
    'user_phone' => '48988888888',
    'user_email' => 'c68305762980499844605@sandbox.pagseguro.com.br', //EMAIL DO SEU CLIENTE DE TESTE (DISPONÍVEL NA DASH DO PAGSEGURO)
    'user_addr_street' => 'Rua vinte e cinco',
    'user_addr_number' => '0',
    'user_addr_complement' => 'Cj. Marcos Freire',
    'user_addr_district' => 'Pimentas',
    'user_addr_city' => 'Guarulhos',
    'user_addr_state' => 'SP',
    'user_addr_country' => 'BRA',
    'user_addr_postalcode' => '07263725',
];

//array com informações de um cartão de teste para realizar operações em sandbox
$card = [
    'card_number' => '4111111111111111',
    'card_brand' => 'visa',
    'card_token' => 'ae6dbbfedecf40c39a8e315f17c6b49d', //TOKEN DO CARTAO DE CREDITO
    'card_cvv' => '123',
    'card_expiration_month' => '12',
    'card_expiration_year' => '2018',
    'card_holder_name' => 'Paulo Pimentel',
    'card_holder_birth' => '04/09/1987',
    'card_holder_phone' => '48988888888'
];

//associa um usuário a um plano
// $pagseguro->createMemberShip(
//     $plan['plan_code_pagseguro'],
//     '1',
//     $user['user_name'],
//     $user['user_email'],
//     $user['user_document'],
//     $user['user_phone'],
//     $user['user_addr_street'],
//     $user['user_addr_number'],
//     $user['user_addr_complement'],
//     $user['user_addr_district'],
//     $user['user_addr_city'],
//     $user['user_addr_state'],
//     $user['user_addr_country'],
//     $user['user_addr_postalcode'],
//     $card['card_token'],
//     $card['card_holder_name'],
//     $card['card_holder_birth'],
//     $card['card_holder_phone']
// );

//retorna o objeto de assinatura no pagseguro
//$pagseguro->getMemberShip('A44EB859F6F6A00DD4E21F9D72FD7D01');

//retorna o objeto de transação do pagSeguro
$pagseguro->getTransaction('0F984F92C03648F2AAB312436AD9146F');

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
    </form>

    <script src="source/pagseguro/_cdn/jquery-3.3.1.min.js"></script>
    <script src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
    <!-- <script src="source/pagseguro/js/script.js"></script> -->
</body>
</html>
