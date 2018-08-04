<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Paypal - Checkout Transparente</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
    <link rel="stylesheet" href="source/paypal/css/style.css">
</head>
<body>

    <div class="container">
        <div class="content">

            <form class="" method="post">
                <div class="row">
                    <div class="col-md-12">
                        <h1>Dados da compra</h1>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Invoice Number:</label>
                            <input type="number" class="form-control" id="invoice_number" name="invoice_number" value="" placeholder="Número da fatura (unique), o valor deve ser alterado sempre" />
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Item name:</label>
                            <input type="text" class="form-control" id="item_name" name="item_name" value="Motal Kombat" placeholder="Nome do item" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Item Price:</label>
                            <input type="text" class="form-control" id="item_price" name="item_price" value="50" placeholder="Preço do item" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Item Quantity:</label>
                            <input type="number" class="form-control" id="item_quantity" name="item_quantity" value="2" placeholder="Quantidade de itens" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Item Description:</label>
                            <textarea class="form-control" id="item_description" name="item_description" rows="3" cols="80" placeholder="Descrição do item">Eleito o jogo ano</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Purchase Description:</label>
                            <textarea class="form-control" id="purchase_description" name="purchase_description" rows="3" cols="80" placeholder="Descrição da compra">Decrição da compra aqui !!</textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Payer Description:</label>
                            <textarea class="form-control" id="payer_description" name="payer_description" rows="4" cols="80" placeholder="Descrição do pagador">Descrição do pagador aqui !!</textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <hr>
                    </div>

                    <div class="col-md-12">
                        <h2>Dados de entrega, endereço !</h2>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="Comprador Teste Paypal" placeholder="Nome do destinatário" />
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>State:</label>
                            <select class="form-control" id="state" name="state">
                                <option value="SP" selected>SP</option>
                                <option value="RJ">RJ</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>City:</label>
                            <input type="text" class="form-control" id="city" name="city" value="Guarulhos" placeholder="Cidade" />
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>CEP:</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" value="07263777" placeholder="Cep" />
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Line 1:</label>
                            <input type="text" class="form-control" id="district" name="district" value="Pimentas, Cj. Marcos Freire" placeholder="Bairro, conjunto, apto e etc." />
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Line 2:</label>
                            <input type="text" class="form-control" id="street" name="street" value="Rua Baltazar" placeholder="Rua da residência ou etc." />
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Number:</label>
                            <input type="number" class="form-control" id="number" name="number" value="100" placeholder="Número da residência ou etc." />
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Phone:</label>
                            <input type="number" class="form-control" id="phone" name="phone" value="11991296633" placeholder="Telefone de contato" />
                        </div>
                    </div>

                    <div class="col-md-12">
                        <input type="submit" class="btn btn-primary" value="Form Submit" title="Enviar formulário" />
                    </div>

                </div>

            </form>
        </div>

        <!-- MODAL -->
        <div class="modal animated" id="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar Pagamento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Clique no botão e insira seus dados do paypal para finalizar a compra.</p>
                        <div id="paypal-button"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="source/paypal/_cdn/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
    <script src="source/paypal/js/script.js"></script>
</body>
</html>
