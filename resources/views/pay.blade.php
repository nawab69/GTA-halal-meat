<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Payment</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('css/developer.css') }}" rel="stylesheet" type="text/css">
    <!-- Styles -->

    <script
        src="https://code.jquery.com/jquery-3.3.1.js"
        integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>

<section class="pricing py-5">
    <div class="container">
        <form action="{{ route('dopay') }}" method="POST" id="subscribe-form" onsubmit="return submitpayment()" >
            @csrf

            <div style="text-align: center; margin-bottom: 20px">
                <span id="alert-danger" class="alert alert-danger d-none"></span>
                <span id="alert-success" class="alert alert-success d-none"></span>
            </div>
            <div class="row justify-content-center">

                <div class="col-6">
                    <div class="card mb-5 mb-lg-0">
                        <div class="card-body">
                            <h5 class="card-title text-muted text-uppercase text-center">Payment</h5>
                            <h6 class="card-price text-center">${{Session::get('order')->grand_total}}</h6>
                            <hr>
                            <input type="submit" id="submit-btn-1" name="submit-btn" value="Pay" class="btn btn-block btn-success text-uppercase" />
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="order_no" value="{{Session::get('order')->id}}">
            <input type="hidden" name="amount" id="amount" value="" />
            <input type="hidden" name="plan" id="plan" value="" />
            <input type="hidden" name="stripeToken" id="stripeToken" value="" />
            <input type="hidden" name="publishableKey" id="publishableKey" value="{{config('settings.stripe_key')}}"/>
        </form>
    </div>
    <br/><br/><br/>
</section>
</div>
</body>


<script src="https://checkout.stripe.com/checkout.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>


<script type="text/javascript">
    function showProcessing() {
        $('.subscribe-process').show();
    }
    function hideProcessing() {
        $('.subscribe-process').hide();
    }

    // Handling and displaying error during form submit.
    function subscribeErrorHandler(jqXHR, textStatus, errorThrown) {
        try {
            var resp = JSON.parse(jqXHR.responseText);
            if ('error_param' in resp) {
                var errorMap = {};
                var errParam = resp.error_param;
                var errMsg = resp.error_msg;
                errorMap[errParam] = errMsg;
            } else {
                var errMsg = resp.error_msg;
                $("#alert-danger").addClass('alert alert-danger').removeClass('d-none').text(errMsg);
            }
        } catch (err) {
            $("#alert-danger").show().text("Error while processing your request");
        }
    }

    // Forward to thank you page after receiving success response.
    function subscribeResponseHandler(responseJSON) {
//window.location.replace(responseJSON.successMsg);
        if (responseJSON.state == 'success') {
            $("#alert-success").addClass('alert alert-success').removeClass('d-none').text(responseJSON.message);
            $("#alert-danger").addClass('d-none');
        }
        if (responseJSON.state == 'error') {
            $("#alert-danger").addClass('alert alert-danger').removeClass('d-none').text(responseJSON.message);
            $("#alert-success").addClass('d-none');
        }

    }
    let stripekey = $('#publishableKey').val();
    var handler = StripeCheckout.configure({
//Replace it with your stripe publishable key
        key: stripekey,
        image: '{{asset('public/storage/'.config('settings.site_logo'))}}', // add your company logo here
        allowRememberMe: false,
        token: handleStripeToken
    });

    function submitpayment() {
        var form = $("#subscribe-form");
        if (parseInt($("#amount").val()) <= 0) {
            return false;
        }
        handler.open({
            name: ' Stripe Payment',
            description: $("#plan").val()+' Plan',
            amount: ($("#amount").val() * 100)
        });
        return false;
    }

    function handleStripeToken(token, args) {
        form = $("#subscribe-form");
        $("input[name='stripeToken']").val(token.id);
        var options = {
            beforeSend: showProcessing,
            // post-submit callback when error returns
            error: subscribeErrorHandler,
            // post-submit callback when success returns
            success: subscribeResponseHandler,
            complete: hideProcessing,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'json'
        };

        form.ajaxSubmit(options);
        return false;
    }

    $("#submit-btn-1").click(function(){
        $("#amount").val('{{Session::get('order')->grand_total}}');
        $("#plan").val('Payment');
    });
</script>
</html>
