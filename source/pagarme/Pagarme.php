<?php

/**
* Classe Responsável por manipular transações com a api do pagar.me
*/

class Pagarme
{
  /* Service Config */
  private $service;
  private $endPoint;
  private $apiKey;

  /* Params API */
  private $preset;
  private $params;
  private $transaction;

  /* Return and Callback */
  private $callback;

  /**
  * <b>Construtor</b>: Énecessário fazer instância desse método.
  * Responsabilidade de setar os parâmetros de autenticação com o WebService
  * além de definir em qual ambiente a classe irá refletir, (Homologação || Produção)
  * @param BOOLEANO $live = true para ambiente de produção e false homologação
  */
  public function __construct($live = true)
  {
    $this->service = 'https://api.pagar.me';
    $this->transaction = [];
    $this->apiKey = ($live == true ? '' : 'API_KEY_AQUI'); //token recuperado no ambiente de sandbox

    $this->preset = [
      'api_key' => $this->apiKey,
    ];
  }

  /**
  * <b>paymentRequest</b>: Faz a requisição de pagamento na api da pagar.me
  */
  public function paymentRequest($amount, $installments = 1, $async = true)
  {
    $this->endPoint = '/1/transactions';

    $this->transaction += [
      'installments' => $installments,
      'amount' => $amount,
      'async' => $async
    ];

    $this->params = $this->transaction;

    $this->post();

    return $this->callback;
  }

  /**
  * <b>createCustomer</b>: Cria um novo cliente na api da pagar.me
  */
  public function createCustomer($externalId, $userName, $type, $country, $userEmail, $userBirth, $userDocument, $userPhone)
  {
    $this->endPoint = '/1/customers';

    $this->params = [
      'external_id' => $externalId,
      'name' => $userName,
      'type' => $type,
      'country' => $country,
      'email' => $userEmail,
      'documents' => [
        ['type' => 'cpf', 'number' => $userDocument]
      ],
      'phone_numbers' => [
        $userPhone
      ],
      'birthday' => $userBirth
    ];

    $this->post();

    $this->setTransactionCustomer();

    return $this->callback;
  }

  /**
  * <b>getCustomer</b>: Método responsável por resgatar informções de um cliente na api pagarm.me
  */
  public function getCustomer($userCode)
  {
    $this->endPoint = "/1/customers/{$userCode}";
    $this->get();
    $this->setTransactionCustomer();
    return $this->callback;
  }

  /**
  * <b>createCreditCard</b>: Método responsável por criar um cartão para um cliente na api da pagar.me
  */
  public function createCreditCard($cardNumber, $cardHoldeName, $cardCvv, $cardExpirationDate)
  {
    $this->endPoint = '/1/cards';

    $this->params = [
      'card_number' => $cardNumber,
      'card_expiration_date' => $cardExpirationDate,
      'card_cvv' => $cardCvv,
      'card_holder_name' => $cardHoldeName
    ];

    $this->post();
    $this->setTransactionCreditCard();
    return $this->callback;
  }

  /**
  * <b>getCreditCard</b>: Método responsável por resgatar informções de um cartão de crédito
  */
  public function getCreditCard($cardCod)
  {
    $this->endPoint = "/1/cards/$cardCod";

    $this->get();
    $this->setTransactionCreditCard();
    $this->transaction;
    return $this->callback;
  }

  /**
  * <b>billet</b>: Seta o transação de pagamento como forma de pagamento em boleto
  */
  public function billet()
  {
    $this->transaction += [
      'payment_method' => 'boleto'
    ];
  }

  /**
  * <b>setTransacationCustomer</b>: Alimenta a transação assim que um customer é criado
  */
  private function setTransactionCustomer()
  {
    $this->transaction += [
      'customr' => [
        'id' => $this->callback->id,
        'name' => $this->callback->name,
        'email' => $this->callback->email,
        'documents' => [
          'type' => 'cpf',
          'number' => $this->callback->documents[0]->number
        ]
      ]
    ];
  }

  /**
  * <b>setTransacationCustomer</b>: Alimenta a transação assim que um customer é criado
  */
  private function setTransactionCreditCard()
  {
    $this->transaction += [
      'card_id' => $this->callback->id,
      'payment_method' => 'credit_card'
    ];
  }

  /**
  * <b>post</b>: Método responsável por inputar informações na comunicação REST
  */
  private function post()
  {
    $url = $this->service . $this->endPoint;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array_merge($this->params, $this->preset)));
    curl_setopt($curl, CURLOPT_HTTPHEADER, []);
    $this->callback = json_decode(curl_exec($curl));
    curl_close($curl);
  }

  /**
  * <b>get</b>: Método responsável por resgatar informações da comunicação REST
  */
  private function get()
  {
    $url = $this->service . $this->endPoint;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->preset));
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    $this->callback = json_decode(curl_exec($curl));
    curl_close($curl);
  }
}
