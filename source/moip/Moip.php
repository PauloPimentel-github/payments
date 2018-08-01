<?php

class Moip
{

  private $service;
  private $endPoint;
  private $authorization;

  /* Atributo responsável por setar valores no header das requisições */
  private $headers;
  private $params;
  private $callback;

  public function __construct($live = true)
  {
    if ($live == true) {
      $this->service = ''; // url de serviço em produção
      $this->authorization = ''; // autorização formato - Authorization: Basic BASE64(MOIP_API_TOKEN:MOIP_API_KEY)
    } else {
      $this->service = 'https://sandbox.moip.com.br'; // url de serviço em sandbox
      $this->authorization = 'Basic QzdWUVpNSE5KVkVDU0ZVR01SVEdRQjJPWTE4UUZITU06RFFPWlpEUUJNOFdERlpIQVFCTFNNQVZFVlBMUlNOTklMQ09XNFpNNA==';
    }

    $this->headers = [
      'Content-Type: application/json',
      "Authorization: {$this->authorization}"
    ];
  }

  public function createPayment($orderId, $creditCardHash, $statementDescriptor, $method, $cardHolderName, $cardBirth, $documentNumber, $phone)
  {
    $this->endPoint = "/v2/orders/{$orderId}/payments";

    $this->params = [
      'statementDescriptor' => $statementDescriptor,
      'fundingInstrument' => [
        'method' => $method,
        'creditCard' => [
          'hash' => $creditCardHash,
          'store' => true,
          'holder' => [
            'fullname' => $cardHolderName,
            'birthdate' => $cardBirth,
            'taxDocument' => [
              'type' => 'CPF',
              'number' => $documentNumber
            ],
            'phone' => [
              'countryCode' => substr($phone, 0, 2),
              'areaCode' => substr($phone, 2, 4),
              'number' => substr($phone, 4)
            ]
          ]
        ]
      ],
      'device' => [
        'ip' => '127.0.0.1'
      ]
    ];

    $this->post();

    return $this->callback;
  }

  /**
  * <b>getPayment</b>: Consultar pagamento na pai do moip
  */
  public function getPayment($paymentId)
  {
    $this->endPoint = "/v2/payments/{$paymentId}";
    $this->get();
    return $this->callback;
  }

  /**
  * <b>createCustomer</b>: Método responsável por cadastrar um novo cliente na api do moip
  */
  public function createCustomer($customerId, $customerName, $customerEmail, $customerBirth, $customerDocumentNumber, $customerPhone, $city, $district, $street, $streetNumber, $postalCode, $state, $country)
  {
    $this->endPoint = '/v2/customers/';

    $this->params = [
      'ownId' => $customerId,
      'fullname' => $customerName,
      'email' => $customerEmail,
      'birthDate' => $customerBirth,
      'taxDocument' => [
        'type' => 'CPF',
        'number' => $customerDocumentNumber
      ],
      'phone' => [
        'countryCode' => substr($customerPhone, 0, 2),
        'areaCode' => substr($customerPhone, 2, 4),
        'number' => substr($customerPhone, 4)
      ],
      'shippingAddress' => [
        'city' => $city,
        'district' => $district,
        'street' => $street,
        'streetNumber' => $streetNumber,
        'zipCode' => $postalCode,
        'state' => $state,
        'country' => $country
      ]
    ];

    $this->post();

    return $this->callback;
  }

  /**
  * <b>getCustomer</b>: Método responsável por retornar os dados de um cliente no formato de obj
  */
  public function getCustomer($customerId)
  {
    $this->endPoint = "/v2/customers/{$customerId}";
    $this->get();
    return $this->callback;
  }

  /**
  * <b>createCreditCard</b>: Método responsável por cadastrar um cartão e associar a um cliente
  */
  public function createCreditCard($customerId, $cardExpirationMonth, $cardExpirationYear, $cardNumber, $cardCvc, $cardHolderName, $cardBirth, $documentNumber, $phone)
  {
    $this->endPoint = "/v2/customers/{$customerId}/fundinginstruments";

    $this->params = [
      'method' => 'CREDIT_CARD',
      'creditCard' => [
        'expirationMonth' => $cardExpirationMonth,
        'expirationYear' => $cardExpirationYear,
        'number' => $cardNumber,
        'cvc' => $cardCvc,
        'holder' => [
          'fullname' => $cardHolderName,
          'birthdate' => $cardBirth,
          'taxDocument' => [
            'type' => 'CPF',
            'number' => $documentNumber
          ],
          'phone' => [
            'countryCode' => substr($phone, 0, 2),
            'areaCode' => substr($phone, 2, 4),
            'number' => substr($phone, 4)
          ],
        ]
      ],
    ];

    $this->post();

    return $this->callback;
  }

  /**
  * <b>createOrder</b>: Cria e associa um pedido a um cliente na api do moip
  */
  public function createOrder($orderId, $customerId, $shippingValue, $product, $category, $quantity, $detail, $price)
  {
    $this->endPoint = '/v2/orders';

    $this->params = [
      'ownId' => $orderId,
      'amount' => [
        'currency' => 'BRL',
        'subtotals' => [
          'shipping' => $shippingValue
        ]
      ],
      'items' => [
        [
          'product' => $product,
          'category', $category,
          'quantity' => $quantity,
          'detail' => $detail,
          'price' => $price
        ]
      ],
      'customer' => [
        'id' => $customerId
      ]
    ];

    $this->post();

    return $this->callback;
  }

  /**
  * <b>getOrder</b>: Recupera informações de um pedido
  */
  public function getOrder($orderId)
  {
    $this->endPoint = "/v2/orders/{$orderId}";
    $this->get();
    return $this->callback;
  }

  /**
  * <b>getCallback</b>: Método responsável por retornar o objeto da comunicação REST
  */
  public function getCallback()
  {
    return $this->callback;
  }

  /**
  * <b>post</b>: Método responsável por inputar informações na comunicação REST
  */
  private function post()
  {
    $url = $this->service . $this->endPoint;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->params));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
    curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
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
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    $this->callback = json_decode(curl_exec($curl));
    curl_close($curl);
  }
}
