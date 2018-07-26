<?php

require_once(__DIR__ . '/source/cielo/Cielo.php');

$cielo = new Cielo(false);

/* Cria um novo cartão */
//$card = $cielo->createCreditCard('Paulo Pimentel', '4024007153763191', 'Paulo Pimentel', '12/2020');
/* Token do cartão recuperado ao executar o método de criação de um cartão */
//cardToken: 76dac4fd-ccc2-4a63-812d-ae22a4299ef3, a9d28798-03a1-4cb8-9e2c-462d6ae9137d, 24856804-c3e6-4e3a-a64b-7decd76d7bb7
/* Recupera um cartão passando o token como argumento */
//$card = $cielo->getCreditCard('a9d28798-03a1-4cb8-9e2c-462d6ae9137d');
/* Realiza uma transação com o cielo e efetua o pagamento */
//$transaction = $cielo->paymentRequest('123', '1000', 1, '76dac4fd-ccc2-4a63-812d-ae22a4299ef3', true);
/* Recupera uma transação passando o id de pagamento como argumento */
$transaction = $cielo->getTransaction("53c30381-13de-4737-9b27-e9b0200c7c24");
var_dump($transaction);
