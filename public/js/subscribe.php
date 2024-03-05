<?php
    header('Content-type: application/javascript');
    /* no store cache in browser */
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache"); 
?>
$(document).ready(function() {
    var stripe = Stripe('<?php echo env('STRIPE_KEY'); ?>');
    var elements = stripe.elements();
    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };
    var card = elements.create('card', {hidePostalCode: true,
        style: style});
    card.mount('#card-element');
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
    const cardHolderName = document.getElementById('card_holder_name');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;
    cardButton.addEventListener('click', async (e) => {
        console.log("attempting");
        const { setupIntent, error } = await stripe.confirmCardSetup(
            clientSecret, {
                payment_method: {
                    card: card,
                    billing_details: { name: cardHolderName.value }
                }
            }
            );
        if (error) {
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message;
        } else {
            paymentMethodHandler(setupIntent.payment_method);
        }
    });
    function paymentMethodHandler(payment_method) {
        var form = document.getElementById('subscribe-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'payment_method');
        hiddenInput.setAttribute('value', payment_method);
        form.appendChild(hiddenInput);
        form.submit();
    }

    $("form").validate({
        errorElement: 'div',
        rules: {
            plan: {
                required: true,
            },
            card_holder_name: {
                required: true,
            },
        },
        messages: {
            plan: {
                required: "<?php echo trans('message.please_select_plan'); ?>",
            },
            card_holder_name: {
                required: "<?php echo trans('message.please_enter_card_holder_name'); ?>",
            },
        },
        errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($("#" + name + "_validate"));
        },
        submitHandler: function (form) { // <- pass 'form' argument in
            form.submit(); // <- use 'form' argument here.
        },
        highlight: function(element) {
            $(element).addClass("field-error");
        },
        unhighlight: function(element) {
            $(element).removeClass("field-error");
        }
    });
} );