<?php

/*
* Created by Atom
* @author Paulo Pimentel
* 13/07/2018 as 23:18:00
*/

class Cielo
{
    /* Parâmetros de Autenticação */
    private $merchantId;
    private $merchantKey;
    /* Atributo responsável por armazenar url base em recursos utilizando o verbo HTTP [GET] */
    private $apiUrlQuery;
    /* Atributo responsável por armazenar url base em recursos utilizando o verbo HTTP [POST] */
    private $apiUrl;
    /* Atributo responsável por setar valores no header das requisições */
    private $headers;
    /* Atributo responsável por manipular endpoints de forma dinâmica e flexível */
    private $endPoint;
    /* Atributo responsável por armazenar dados em um array para envio em requisoções */
    private $params;
    /* Atributo responsável por recuperar e armazenar valores de retorno em requisições */
    private $callback;

    /**
    * <b>Construtor</b>: Recebe um valor booleano true ou false.
    * Responsabilidade de setar os parâmetros de autenticação com o WebService
    * @param BOOLEANO $live = Habilita o ambiente no qual serão feitas as requisições
    */
    public function __construct($live = true)
    {
        if ($live === true) {
            //PRODUÇÃO CRENDENCIAIS
            $this->merchantId = '';
            $this->merchantKey = '';
            $this->apiUrlQuery = '';
            $this->apiUrl = '';
        } else {
            //SANDBOX CRENDENCIAIS
            $this->merchantId = '';
            $this->merchantKey = '';
            $this->apiUrlQuery = 'https://apiquerysandbox.cieloecommerce.cielo.com.br';
            $this->apiUrl = 'https://apisandbox.cieloecommerce.cielo.com.br';
        }

        $this->headers = [
            'Content-Type: application/json',
            "MerchantId: {$this->merchantId}",
            "MerchantKey: {$this->merchantKey}"
        ];
    }

    /**
    * <b>createCreditCard</b>: Método responsável por criar um cartão e receber um token para de acesso para utilizar o mesmo
    * @param STRING $name = Nome do comprador
    * @param STRING $cardNumber = Número do cartão
    * @param STRING $cardHolderName = Nome incluso no cartão de crédito
    * @param STRING $cardExpirationDate = Data de vencimento no formato de mês e ano
    */
    public function createCreditCard($name, $cardNumber, $cardHolderName, $cardExpirationDate)
    {
        $brand = $this->getCreditCardData($cardNumber);
        $this->endPoint = '/1/card';

        $this->params = [
            'CustomerName' => $name,
            'CardNumber' => $cardNumber,
            'Holder' => $cardHolderName,
            'ExpirationDate' => $cardExpirationDate,
            'Brand' => $brand->Provider,
        ];

        $this->post();

        return $this->callback;
    }

    /**
     * <b>getCreditCard</b>: Método responsável dados de um cartão
     * @param STRING $cardToken = Token de acesso recuperado no momento da criação de credit card
    */
    public function getCreditCard($cardToken)
    {
        $this->endPoint = "/1/card/{$cardToken}";
        $this->get();
        return $this->callback;
    }

    /**
     * <b>paymentRequest</b>: Método responsável por realizar pagamento
     * @param STRING $orderId = Id do pedido
     * @param STRING $amount = Valor de cobrança
     * @param INT $installments = Parcelas, caso não informe  o valor padrão é 1
     * @param STRING $cardToken = Token do cartão de crédito
     * @param BOOLEANO $capture = Em um ambiente real seria realmente debitado da conta cliente e transferido para a conta do cielo
    */
    public function paymentRequest($orderId, $amount, $installments = 1, $cardToken, $capture = true)
    {
        $this->endPoint = "/1/sales";

        $this->params = [
            'MerchantOrderId' => $orderId,
            'Payment' => [
                'Type' => 'CreditCard',
                'Amount' => $amount,
                'Installments' => $installments,
                'SoftDescriptor' => 'PauloWeb',
                'Capture' => $capture,
                'CreditCard' => [
                    'CardToken' => $cardToken
                ],
            ]
        ];

        $this->post();

        return $this->callback;
    }

    /**
     * <b>getTransaction</b>: Método responsável por resgatar informações de uma transação
     */
    public function getTransaction($transaction)
    {
        $this->endPoint = "/1/sales/{$transaction}";
        $this->get();
        return $this->callback;
    }

    ##################################### PRIVATE METHODS #####################################

    /**
     * <b>getCreditCardData</b>: Método responsável por recuperar bandeira do cartão
     * @param STRING $cardNumber = Número do cartão, apenas os 6 primeiros caracteres
    */
    private function getCreditCardData($cardNumber)
    {
        $cardNumber = substr($cardNumber, 1, 6);
        $this->endPoint = "/1/cardBin/{$cardNumber}";
        $this->get();
        return $this->callback;
    }

    /**
     * <b>post</b>: Método responsável por inputar informações na comunicação REST
     */
    private function post()
    {
        $url = $this->apiUrl . $this->endPoint;
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
        $url = $this->apiUrlQuery . $this->endPoint;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        $this->callback = json_decode(curl_exec($curl));
        curl_close($curl);
    }
}
