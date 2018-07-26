<?php

require_once(__DIR__ . '/source/pagarme/Pagarme.php');

$pagarme = new Pagarme(false);

//cria um novo cliente na api
//id de retorno da chamada do método = 667074
//$customer = $pagarme->createCustomer('id123','Paulo Pimentel', 'individual', 'br', 'paulo.h.g.pimentel@gmail.com', '1987-09-04', '64324348820', '+5511999999999');

//resgata dados de um cliente na api do pagarme
$customerPaulo = $pagarme->getCustomer('ID_CLIENTE_AQUI');
//var_dump($customerPaulo);

//cria um cartão de crédito na api do pagar.me,
/*
* @var $cardNumber = número do cartão de crédito fictício aceito pela api do pagar.me
* @var $cardHoldeName = nome impresso no cartão
* @var $cardCvv = código de 3 digitos do cartãoptimize
* $var $cardExpirationDate = data de expiração do cartão - formato de dois digitos para mês e ano
*/
//$creditCard = $pagarme->createCreditCard('4111111111111111', 'Paulo Pimentel', '123', '1019');
//apenas um debug da classe para resgatar o id do cartão gerado pois ele será necessário para gerer um compra, o mesmo pode ser recuperado pelo painel admin da pagarme
//id = card_cjirn2x9r04txlz6dz2y4ysbl
//var_dump($creditCard);

//resgata informações de um cartão de crédito
$creditCardPaulo = $pagarme->getCreditCard('ID_CARTAO_AQUI');
//var_dump($creditCardPaulo);

//seta o pagamento como forma de boleto
//$pagarme->billet();

//faz a requisição de pagamento na api da pagarme
/*
* @var $amount = 5000 é equivalente a 50,00 na api
* @var $installments = número de parcelas, valor default é 1
* @ $async = informa se a transação vai ser do tipo assíncrono or síncrono
*/
$payment = $pagarme->paymentRequest('8000', '1', false);

var_dump($customerPaulo, $creditCardPaulo, $payment);
