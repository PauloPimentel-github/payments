<?php

namespace Paypal;

/**
* Classe Responsável por manipular transações com a api do paypal
*/
class Paypal
{

    /* Service Config */
    private $service;
    private $endPoint;
    private $clientId;
    private $clientSecret;
    private $authorization;

    /* Atributo responsável por setar valores no header das requisições */
    private $headers;

    /* Params API */
    private $params;

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
        if ($live == true) {
            // code...
        } else {
            $this->service = 'https://api.sandbox.paypal.com';
            $this->clientId = 'CLIENT_ID_AQUI';
            $this->clientSecret = 'CLIENT_SECRET_AQUI';
            $this->authorization = base64_encode($this->clientId . ':' . $this->clientSecret);
            $this->headers = [
                'Content-Type: application/json',
                'Accept: application/json',
                'Accept-Language: en_US',
                "Authorization: Basic {$this->authorization}"
            ];
        }
    }

    /**
    * <b>paymentExecute</b>: Executa o pagamento na api do paypal após a aprovação do cliente
    */
    public function paymentExecute($paymentId, $payerId)
    {
        $this->endPoint = "/v1/payments/payment/{$paymentId}/execute";

        $this->params = [
            'payer_id' => $payerId
        ];

        $this->post();
        return $this->callback;
    }

    /**
    * <b>payment</b>: Cria um pagamento na api do paypal
    */
    public function payment($invoiceNumber, $total, $subTotal, $intemDescriptor, array $items, $description, $name, $district, $street, $number, $city, $state, $postalCode, $phone, $clientDescription, $redirectUrl)
    {
        $this->endPoint = '/v1/payments/payment';

        $this->params = [
            'intent' => 'sale',
            'payer' => [
                'payment_method' => 'paypal'
            ],
            'transactions' => [[
                'amount' => [
                    'total' => $total,
                    'currency' => 'BRL',
                    'details' => [
                        'subtotal' => $subTotal,
                        'tax' => '0.07',
                        'shipping' => '0.03',
                        'handling_fee' => '1.00',
                        'shipping_discount' => '-1.00',
                        'insurance' => '0.01'
                    ]
                ],
                'description' => $description,
                'custom' => 'EBAY_EMS_90048630024435',
                'invoice_number' => $invoiceNumber,
                'payment_options' => [
                    'allowed_payment_method' => 'INSTANT_FUNDING_SOURCE'
                ],
                'soft_descriptor' => $intemDescriptor,
                'item_list' => [
                    'items' => $items,
                    'shipping_address' => [
                        'recipient_name' => $name,
                        'line1' => $district,
                        'line2' => $street . ', ' . $number,
                        'city' => $city,
                        'country_code' => 'BR',
                        'postal_code' => $postalCode,
                        'phone' => $phone,
                        'state' => $state
                    ]
                ]
                ]],
                'note_to_payer' => $clientDescription,
                'redirect_urls' => [
                    'return_url' => $redirectUrl,
                    'cancel_url' => $redirectUrl
                ]
            ];

            $this->post();

            return $this->callback;
    }

    /**
    * <b>getPayments</b>: Listar pagamentos
    */
    public function getPayments($count, $startIndex, $sortOrder)
    {
        $this->endPoint = "/v1/payments/payment?";

        $this->params = [
            'count' => $count,
            'start_index' => $startIndex,
            'sort_by' => 'create_time',
            'sort_order' => $sortOrder
        ];

        $this->endPoint = $this->endPoint . http_build_query($this->params);

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
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->params));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
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
        $this->callback = json_decode(curl_exec($curl));
        curl_close($curl);
    }
}
