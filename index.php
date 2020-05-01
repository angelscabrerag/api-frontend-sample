<!DOCTYPE html>
<html class="supports-animation supports-columns svg no-touch no-ie no-oldie no-ios supports-backdrop-filter as-mouseuser" lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=1024">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="format-detection" content="telephone=no">
        
        <title>E-commerce</title>
        
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="jumbotron mt-3 mb-3">
                    <h1>Formulario mercado pago</h1>
                </div>
                <div class="col-md-12">
                    <form class="" action="/procesar_pago" method="post" id="pay" name="pay" >
                        <div class="form-row">
                            <fieldset>
                                <div class="form-group col-md-12">
                                    <label for="description">Descripción</label>                        
                                    <input class="form-control" type="text" name="description" id="description" value="Ítem seleccionado"/>
                                </div>                    
                                <div class="form-group col-md-12">
                                    <label for="transaction_amount">Monto a pagar</label>                        
                                    <input class="form-control" name="transaction_amount" id="transaction_amount" value="100"/>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="cardNumber">Número de la tarjeta</label>
                                    <input class="form-control" type="text" id="cardNumber" data-checkout="cardNumber" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="cardholderName">Nombre y apellido</label>
                                    <input class="form-control" type="text" id="cardholderName" data-checkout="cardholderName" />
                                </div>                                    
                                <div class="form-group col-md-12">
                                    <label for="cardExpirationMonth">Mes de vencimiento</label>
                                    <input class="form-control" type="text" id="cardExpirationMonth" data-checkout="cardExpirationMonth" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="cardExpirationYear">Año de vencimiento</label>
                                    <input class="form-control" type="text" id="cardExpirationYear" data-checkout="cardExpirationYear" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="securityCode">Código de seguridad</label>
                                    <input class="form-control" type="text" id="securityCode" data-checkout="securityCode" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
                                </div>
                                <div class="form-group col-md-12">
                                   <label for="installments">Cuotas</label>
                                   <select class="form-control" id="installments" class="form-control" name="installments"></select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="docType">Tipo de documento</label>
                                    <select class="form-control" id="docType" data-checkout="docType"></select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="docNumber">Número de documento</label>
                                    <input class="form-control" type="text" id="docNumber" data-checkout="docNumber"/>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="email">E-mail</label>
                                    <input class="form-control" type="email" id="email" name="email" value="test@test.com"/>
                                </div>  

                                <input type="hidden" name="payment_method_id" id="payment_method_id"/>

                                <button type="submit" class"btn btn-block">Pagar</button>
                            </fieldset>
                        </div>
                    </form>
                 </div>
             </div>
        </div>
            
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.slim.min.js" integrity="sha256-MlusDLJIP1GRgLrOflUQtshyP0TwT/RHXsI1wWGnQhs=" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
        <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>

        <script>
            window.Mercadopago.setPublishableKey("YOUR_PUBLIC_KEY");

            window.Mercadopago.getIdentificationTypes();

            document.getElementById('cardNumber').addEventListener('keyup', guessPaymentMethod);
            document.getElementById('cardNumber').addEventListener('change', guessPaymentMethod);

            function guessPaymentMethod(event) {
                let cardnumber = document.getElementById("cardNumber").value;

                if (cardnumber.length >= 6) {
                    let bin = cardnumber.substring(0,6);
                    window.Mercadopago.getPaymentMethod({
                        "bin": bin
                    }, setPaymentMethod);
                }
            };

            function setPaymentMethod(status, response) {
                if (status == 200) {
                    let paymentMethodId = response[0].id;
                    let element = document.getElementById('payment_method_id');
                    element.value = paymentMethodId;
                    getInstallments();
                } else {
                    alert(`payment method info error: ${response}`);
                }
            }

            function getInstallments(){
                window.Mercadopago.getInstallments({
                    "payment_method_id": document.getElementById('payment_method_id').value,
                    "amount": parseFloat(document.getElementById('transaction_amount').value)

                }, function (status, response) {
                    if (status == 200) {
                        document.getElementById('installments').options.length = 0;
                        response[0].payer_costs.forEach( installment => {
                            let opt = document.createElement('option');
                            opt.text = installment.recommended_message;
                            opt.value = installment.installments;
                            document.getElementById('installments').appendChild(opt);
                        });
                    } else {
                        alert(`installments method info error: ${response}`);
                    }
                });
            }    

            doSubmit = false;
            document.querySelector('#pay').addEventListener('submit', doPay);

            function doPay(event){
                event.preventDefault();
                if(!doSubmit){
                    var $form = document.querySelector('#pay');

                    window.Mercadopago.createToken($form, sdkResponseHandler);

                    return false;
                }
            };

            function sdkResponseHandler(status, response) {
                if (status != 200 && status != 201) {
                    alert("verify filled data");
                }else{
                    var form = document.querySelector('#pay');
                    var card = document.createElement('input');
                    card.setAttribute('name', 'token');
                    card.setAttribute('type', 'hidden');
                    card.setAttribute('value', response.id);
                    form.appendChild(card);
                    doSubmit=true;
                    form.submit();
                }
            };    
        </script>            
        
     </body>
</html>
