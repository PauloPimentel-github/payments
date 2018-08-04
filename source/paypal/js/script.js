$(function () {

    $('html').on('submit', 'form', function (event) {
        event.preventDefault();
        var form = $(this);

        $('#modal').addClass('zoomInUp');
        $('#modal').modal('show');

        paypal.Button.render({
            // Configure environment
            env: 'sandbox',
            client: {
                sandbox: 'AZT4TTW1SF34ILv7h2GP4MBJsJ-S3wqlBDbpQ34CMycX8zeAjPOZ3Q9GsFSx65hx99HrRmX7wH78eb4Q',
                // production: 'demo_production_client_id'
            },
            // Customize button (optional)
            locale: 'en_US',
            style: {
                size: 'small',
                color: 'gold',
                shape: 'pill',
            },
            // Set up a payment
            payment: function(data, actions) {
                // 2. Make a request to your server
                return paypal.request.post('source/paypal/payments/payment-create.php', {
                    'invoice_number': $('#invoice_number').val(),
                    'item_name': $('#item_name').val(),
                    'item_price': $('#item_price').val(),
                    'item_quantity': $('#item_quantity').val(),
                    'item_description': $('#item_description').val(),
                    'purchase_description': $('#purchase_description').val(),
                    'payer_description': $('#payer_description').val(),
                    'name': $('#name').val(),
                    'state': $('#state').val(),
                    'city': $('#city').val(),
                    'postal_code': $('#postal_code').val(),
                    'district': $('#district').val(),
                    'street': $('#street').val(),
                    'number': $('#number').val(),
                    'phone': $('#phone').val()
                }).then(function (response) {
                    if (response.success) {
                        return response.success
                    } else {
                        console.log(response.error);
                    }
                });
            },
            // Execute the payment
            onAuthorize: function (data, actions) {
                return paypal.request.post('source/paypal/payments/payment-execute.php', {
                    paymentID: data.paymentID,
                    payerID: data.payerID
                }).then(function (response) {
                    console.log(response);
                    alert('Pagamento realizado com sucesso!!');
                }).catch(function (error) {
                    console.log('Error', error);
                });
            }
        }, '#paypal-button');
    });
});
