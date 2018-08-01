<?php
// as credenciais da api moip podem ser encontradas neste endereço: https://conta-sandbox.moip.com.br/configurations/api_credentials
require_once __DIR__ . '/source/moip/Moip.php';

$moip = new Moip(false);
// cadastra um novo cliente na api do moip
// $moip->createCustomer(
//   '1',
//   'Paulo H.G.Pimentel',
//   'paulo.h.g.pimentel@gmail.com',
//   '1987-09-04',
//   '10013390023',
//   '5511991296067',
//   'Guarulhos',
//   'Pimentas',
//   'Rua vinte e cinco',
//   '138',
//   '07263725',
//   'SP',
//   'BRA'
// ); // id de cadastrado retornado pela api - id = CUS-OL9W5UK9T72S
//var_dump($moip->getCallback());

// recupera dados de um cliente cadastrado na api
//$getCustomer = $moip->getCustomer('CUS-OL9W5UK9T72S');
//var_dump($getCustomer);

// cria e associa um cartão de crédito a um usuário
//$creditCardPaulo = $moip->createCreditCard('CUS-OL9W5UK9T72S', '05', '22', '4012001037141112', '123', 'Paulo Pimentel', '1990-10-22', '22288866644', '551155552266');
//var_dump($creditCardPaulo);
// id de retorno do cartão gerado - id = CRC-66T0X2ULYC22

// criar pedido na api do moip
//$createOrder = $moip->createOrder('id_order_1', 'CUS-OL9W5UK9T72S', 1500, 'Descrição do pedido', 'Categoria do pedido', 1, 'Fifa 18', 1000);
//var_dump($createOrder);
// id de retorno do pedido gerado na api - id = ORD-PZKW3ZZ2X0QF

// recupera informações de um pedido feito na api
//$gerOrder = $moip->getOrder('ORD-PZKW3ZZ2X0QF');
//var_dump($gerOrder);

$creditCardHash = 'dP+h8RONWlOGNvo4+dwe1c3HxfAsiNuRyLm5DpPZQ2NqGZxoV9HU0Tx7IBbMZxuVNaTaDH+iiQ+6Z4Y4IzAAyOyJ/FKmesAh7zL583ZkTaI/HNbf9QZqFekg8hji3SEY5TGO0bn972dQd9bmLSqssUam9Gw0koZ2WcLNxl3qW7BpP+EhKnm5ZXGWh965x5MNb9BjHdbP51bbPc3+mNj/iU/el1r+Msu1IO+QPGfjx+qYo23xfoZBgTugodxGyDQ9rAzGuqxT4rJ1aKwbSWUtznKnKBr7YjPFswOlYeg2InwHPM4Mx11ll8/BNjmVsmN+F7XEopWxqPALLD525AwDsQ==';
$createPayment = $moip->createPayment('ORD-PZKW3ZZ2X0QF', $creditCardHash, 'site.com.br', 'CREDIT_CARD', 'Paulo H.G.Pimentel', '1987-09-04', '33333333333', '551124849639');
var_dump($createPayment);
// id de pagamento recuperado - id = PAY-VT1JP087DJS2

// consultar pagamento
$getPayment = $moip->getPayment('PAY-VT1JP087DJS2');
var_dump($getPayment);
?>
<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>MOIP - Checkout Transparente</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
  <link rel="stylesheet" href="source/moip/css/style.css">
</head>
<body>

  <div class="container">

    <div class="row content">

      <div class="col-md-12">
        <h2>Criptografia de cartão de crédito</h2>
      </div>

      <div class="col-md-12">
        <form class="" action="" method="post">

          <div class="form-group">
            <label>Card Número:</label>
            <input type="text" class="form-control" id="number" value="5555666677778884" placeholder="Credit card number" />
          </div>

          <div class="form-group">
            <label>Card CVC:</label>
            <input type="text" class="form-control" id="cvc" value="123" placeholder="CVC" />
          </div>

          <div class="form-group">
            <label>Card Mês de Expiração:</label>
            <input type="text" class="form-control" id="month" value="06" placeholder="Month" />
          </div>

          <div class="form-group">
            <label>Card Ano de Expiração:</label>
            <input type="text" class="form-control" id="year" value="22" placeholder="Year" />
          </div>

          <div class="form-group">
            <label>Chave pública aqui:</label>
            <textarea rows="6" cols="80" class="form-control" id="public_key">
              -----BEGIN PUBLIC KEY-----
              Chave pública deve ser inserida aqui !
              A chave pública pode ser encontrada neste endereço: https://conta-sandbox.moip.com.br/configurations/api_credentials
              -----END PUBLIC KEY-----
            </textarea>
          </div>

          <div class="form-group">
            <label>Hash gerada para o cartão de crédito:</label>
            <textarea rows="6" cols="80" class="form-control" id="encrypted_value"></textarea>
          </div>

          <button type="button" class="btn btn-primary" id="encrypt">Encrypt</button>
        </form>
      </div>

    </div>

  </div>

  <script src="source/moip/_cdn/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  <script type="text/javascript" src="//assets.moip.com.br/v2/moip.min.js"></script>
  <script src="source/moip/js/script.js"></script>
</body>
</html>
