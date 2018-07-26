$(document).ready(function () {

    //recupera o id de sessão
    var id_session = $('form').find('#id_session').val();
    //console.log(id_session);
    //alimenta o método do pagseguro com o id
    PagSeguroDirectPayment.setSessionId(id_session);

    cardNumber = '4111111111111111';
    cardBrand = '';

    var json = {
        amount: 500,
        installmentValue: 500,
        token: '3ce54cb614764248a8da39023daa0438'
    }

    //recupera a bandeira do cartão
    PagSeguroDirectPayment.getBrand({
        cardBin: cardNumber,
        success: function (response) {
            console.log(response);
            cardBrand = response.brand.name;

            //cria o token para o cartão
            PagSeguroDirectPayment.createCardToken({
                cardNumber: cardNumber,
                brand: cardBrand,
                cvv: '123',
                expirationMonth: '12',
                expirationYear: '2030',
                success: function (response) {
                    console.log(response);
                },
                error: function (response) {
                    console.log(response);
                }
            });

            //obter formas de pagamento
            PagSeguroDirectPayment.getPaymentMethods({
                amount: 500.00,
                success: function(response) {
                    console.log(response);
                    $('img').attr('src', 'https://stc.pagseguro.uol.com.br/public/img/payment-methods-flags/68x30/amex.png');
                    //obter opções de parcelamento
                    PagSeguroDirectPayment.getInstallments({
                        amount: 500.00,
                        brand: cardBrand,
                        maxInstallmentNoInterest: 3,
                        success: function(response) {
                            //opções de parcelamento disponível
                            console.log(response);

                            // $.post('ajax/ajax.php', json, function (response) {
                            //     console.log(response);
                            // }, 'json');

                            var options = '';

                            $.each(response.installments.visa, function (key, value) {
                                options += "<option value='"+ key +"'>" + "Parcelas: "+ value.quantity + " x R$ " + value.installmentAmount + " Total à prazo: " + value.totalAmount + "</option>";
                            });

                            $('#id_installments').html(options);
                        },
                        error: function(response) {
                            //tratamento do erro
                            console.log(response);
                        }
                    });
                },
                error: function(response) {
                    console.log(response);
                }
            });

        },
        error: function (response) {
            console.log(response);
        }
    });

});
