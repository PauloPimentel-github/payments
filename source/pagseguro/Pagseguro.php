<?php

/*
* Created by Atom
* @author Paulo Pimentel
* 14/07/2018 as 15:34:00
*/

class Pagseguro
{
    /** Parâmetros de Autenticação */
    private $service;
    private $email;
    private $token;

    /** Parâmetros do REST */
    private $endPoint;
    private $callback;
    private $params;

    private $headers;

    /** Atributo de sessão da conexão */
    private $sessionId;

    /**
    * <b>Construtor</b>: Não é necessário fazer instância desse método.
    * Responsabilidade de setar os parâmetros de autenticação com o WebService
    */
    public function __construct($live = true)
    {
        if ($live === true) {
            $this->service = '';
            $this->email = '';
            $this->token = ''; //token produção
        } else {
            $this->service = 'https://ws.sandbox.pagseguro.uol.com.br';
            $this->email = 'paulo.h.g.pimentel@gmail.com';
            $this->token = 'TOKEN_SANDBOX';
        }

        $this->headers = [
            'Content-type: application/json',
            'Accept: application/vnd.pagseguro.com.br.v3+json;charset=ISO-8859-1'
        ];
    }

    /**
    * <b>createPlan</b>: Método responsável por fazer a criação de um novo plano
    * no PagSeguro utilizando o WebService
    * @param INT $ref = ID do plano no banco de dados
    * @param STRING $name = Nome do plano
    * @param STRING $period = Periodicidade de cobrança [WEEKLY, MONTHLY, BIMONTHLY, TRIMONTHLY, SEMIANNUALLY ou YEARLY]
    * @param DECIMAL $amount = Valor do plano com duas casas decimais separadas por ponto (100.00)
    */
    public function createPlan($ref, $name, $period, $amount)
    {
        $this->endPoint = '/pre-approvals/request';

        $this->params = [
            'reference' => $ref,
            'preApproval' => [
                'name' => $name,
                'charge' => 'AUTO',
                'period' => $period,
                'amountPerPayment' => $amount,
            ],
        ];

        $this->post();
    }

    /**
    * <b>createMemberShip</b>: Método responsável por fazer a adesão de um
    * cliente ao plano.
    * @param STRING $plan = Código do plano junto ao PagSeguro
    * @param STRING $ref = ID da assinatura no banco de dados
    * @param STRING $name = Nome do Cliente
    * @param STRING $email = E-mail do Cliente
    * @param STRING $document = CPF do Cliente
    * @param STRING $phone = Telefone de contato junto com o DDD (Sem pontuação)
    * @param STRING $street = Endereço do Cliente
    * @param STRING $number = Número do Endereço
    * @param STRING $complement = Complemento do Endereço
    * @param STRING $district = Bairro do Endereço
    * @param STRING $city = Cidade do Endereço
    * @param STRING $state = Estado do Endereço
    * @param STRING $country = Sigla do País com 3 letras (BRA)
    * @param STRING $postalCode = CEP do Endereço
    * @param STRING $token = Token do cartão de crédito
    * @param STRING $holderName = Nome do Titular do Cartão
    * @param DATE $holderBirth = Data de Nascimento do Titular do Cartão no formado DD/MM/AAAA
    * @param STRING $holderPhone = Telefone de contato junto com o DDD do Titular do Cartão (Sem pontuação)
    */
    public function createMemberShip($plan, $ref, $name, $email, $document, $phone, $street, $number, $complement, $district, $city, $state, $country, $postalCode, $token, $holderName, $holderBirth, $holderPhone)
    {
        $this->endPoint = '/pre-approvals';

        $this->params = [
            'plan' => $plan, //código do plano ao qual a assinatura será vinculada
            'reference' => $ref, //id re relacionamento entre o cliente e o plano aderido
            'sender' => [
                'name' => $name,
                'email' => $email,
                'ip' => '1.1.1.1',
                'phone' => [
                    'areaCode' => substr($phone, 0, 2),
                    'number' => substr($phone, 2)
                ],
                'address' => [
                    'street' => $street,
                    'number' => $number,
                    'complement' => $complement,
                    'district' => $district,
                    'city' => $city,
                    'state' => $state,
                    'country' => $country,
                    'postalCode' => $postalCode
                ],
                'documents' => [
                    ['type' => 'CPF', 'value' => $document],
                ],
            ],
            'paymentMethod' => [
                'type' => 'CREDITCARD',
                'creditCard' => [
                    'token' => $token,
                    'holder' => [
                        'name' => $holderName,
                        'birthDate' => $holderBirth,
                        'documents' => [
                            ['type' => 'CPF', 'value' => '91309581304']
                        ],
                        'phone' => [
                            'areaCode' => substr($holderPhone, 0, 2),
                            'number' => substr($holderPhone, 2)
                        ],
                    ],
                ],
            ],
        ];

        $this->post();
    }

    public function payment($method, $name, $email, $phone, $document, $hash, $token, $ref, $street, $number, $complement, $district, $city, $state, $country, $postalCode, $amount, $quantity, $value, $holderName, $holderBirth, $holderPhone) //
    {
        $this->endPoint = '/v2/transactions';

        $payment = array();

        $payment['mode'] = 'default';
        $payment['method'] = $method;
        $payment['sender'] = array('name' => $name, 'email' => $email, 'phone' => ['areaCode' => substr($phone, 0, 2), 'number' => substr($phone, 2)], 'documents' => ['document' => ['type' => 'CPF', 'value' => $document]]);
        $payment['currency'] = 'BRL';
        $payment['notificationURL'] = '';
        $payment['items'] = array('item' => ['id' => '1', 'description' => 'Descrição do item 1', 'quantity' => 1, 'amount' => number_format($amount, 2, '.', '.')]);
        $payment['extraAmount'] = '0.00';
        $payment['reference'] = $ref;
        $payment['shipping'] = array('address' => ['street' => $street, 'number' => $number, 'complement' => $complement, 'district' => $district, 'city' => $city, 'state' => $state, 'country' => $country, 'postalCode' => $postalCode]);
        $payment['type'] = 3;
        $payment['cost'] = '0.00';
        $payment['creditCard'] = array('token' => $token, 'installment' => ['quantity' => $quantity, 'value' => number_format($value, 2, '.', '.')], 'holder' => ['name' => $holderName,
        'documents' => ['document' => ['type' => 'CPF', 'value' => $document]], 'birthDate' => $holderBirth, 'phone' => ['areaCode' => substr($holderPhone, 0, 2), 'number' => substr($holderPhone, 2)]],
        'billingAddress' => ['street' => $street, 'number' => $number, 'complement' => $complement, 'district' => $district, 'city' => $city, 'state' => $state, 'country' => $country, 'postalCode' => $postalCode]);

        # Criando um objeto XML com a classe SimpleXMLElement
        $xml = new SimpleXMLElement('<payment/>');
        $this->arrayToXML($payment, $xml);
        $this->params = $xml->asXML();

        $this->postPayment();
    }

    private function postPayment()
    {
        $url = $this->service . $this->endPoint . "?email={$this->email}&token={$this->token}";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->params);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/xml; charset=ISO-8859-1', 'application/xml; charset=ISO-8859-1'));
        $this->callback = simplexml_load_string(curl_exec($curl));
        curl_close($curl);
    }

    /**
    * <b>arrayToXML</b>: Método recursivo rensável por transformar um array com informações de pagamento em um obj XML
    * @param ARRAY $array = Array contendo todas as informações necessárias para realizar um pagamento
    * @param OBJECT $xml = Objeto xml que será alimentado pelo array
    */
    private function arrayToXML($array, &$xml)
    {
        foreach($array as $key => $item) {

            if(is_array($item)) {
                $prefix = (is_numeric($key)) ? 'item' : '';
                $subNo = $xml->addChild($prefix . $key);
                $this->arrayToXML($item, $subNo);
            } else {
                $prefix = (is_numeric($key)) ? 'item' : '';
                $xml->addChild($prefix . $key, $item);
            }
        }
    }

    /**
    * <b>getSessionId</b>: Método utilizado para resgatar o ID da sessão para que possa consumir as informações
    * do javascript nos métodos que manipulam o cartão de crédito.
    */
    public function getSessionId()
    {
        $endPoint = '/v2/sessions';
        $params = [
            'email' => $this->email,
            'token' => $this->token
        ];

        $curl = curl_init($this->service . $endPoint);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = simplexml_load_string(curl_exec($curl));
        curl_close($curl);
        //recupera o id de retorno
        $this->sessionId = $result->id;
        return $this->sessionId;
    }

    /**
    * <b>getCallback</b>: Método responsável por retornar o objeto da comunicação REST
    */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
    * <b>getMemberShip</b>: Método responsável por retornar o objeto de assinatura do PagSeguro
    * @param STRING $code = Código retornado pela API de notificação do PagSeguro
    */
    public function getMemberShip($code)
    {
        $this->endPoint = "/pre-approvals/{$code}";
        $this->get();
    }

    /**
    * <b>getTransaction</b>: Método responsável por retornar o objeto de transação do PagSeguro
    * @param STRING $code = Código retornado pela API de notificação do PagSeguro
    */
    public function getTransaction($code)
    {
        $this->endPoint = "/v2/transactions/{$code}";
        $this->get();
    }

    /**
    * <b>get</b>: Método responsável por resgatar informações da comunicação REST
    */
    private function get()
    {
        $url = $this->service . $this->endPoint . "?email={$this->email}&token={$this->token}";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        $this->callback = json_decode(curl_exec($curl));
        curl_close($curl);

        if(empty($this->callback)) {
            $url = $this->service . $this->endPoint . "?email={$this->email}&token={$this->token}";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Accept: application/xml;charset=ISO-8859-1'));
            $this->callback = simplexml_load_string(curl_exec($curl));
            curl_close($curl);
        }
    }

    /**
    * <b>post</b>: Método responsável por inputar informações na comunicação REST
    */
    private function post()
    {
        $url = $this->service . $this->endPoint . "?email={$this->email}&token={$this->token}";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        $this->callback = json_decode(curl_exec($curl));
        curl_close($curl);
    }
}
