@extends('backend.main')
@section('title', 'Create User - FDD')

@section('styles')
@endsection

@push('css')
@endpush



@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Make Any Transaction</h4>
                            </div>
                        </div>
                        <div class="iq-card-body px-4">
                            <form role="form" action="{{ route('stripe.post') }}" method="post"
                                class="require-validation" data-cc-on-file="false"
                                data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form"> @csrf
                                {{ @method_field('POST') }}

                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3 required">
                                        <label for="acc_no" class="required">Account Number</label>
                                        <input type="text" class="form-control" name="acc_no" placeholder="9009998790"
                                            size='4'>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3 required">
                                        <label for="acc_no" class="required">Amount</label>
                                        <input type="text" class="form-control" name="amount" placeholder="34"
                                            size='4'>
                                    </div>


                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3 required">
                                        <label for="name" class="required">Name on Card</label>
                                        <input type="text" class="form-control" name="" placeholder="e.g. John"
                                            size='4'>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3 card required">
                                        <label for="name" class="">Card Number</label>
                                        <input size='20' type='text' class="form-control card-number" name=""
                                            placeholder="42424242" size='4'>
                                    </div>


                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-sm-12 mb-3 cvc required">
                                        <label for="name" class="">CVC</label>
                                        <input size='20' type='text' class="form-control card-cvc" name=""
                                            placeholder="686" size='4'>
                                    </div>
                                    <div class="col-md-4 col-sm-12 mb-3 expiration required">
                                        <label for="name" class="">Expiration Month</label>
                                        <input size='20' type='text' class="form-control card-expiry-month"
                                            name="" placeholder="MM" size='4'>
                                    </div>
                                    <div class="col-md-4 col-sm-12 mb-3 expiration required">
                                        <label for="name" class="">Expiration Year</label>
                                        <input size='20' type='text' class="form-control card-expiry-year"
                                            name="" placeholder="YYYY" size='4'>
                                    </div>

                                </div>

                                <div class='form-row row'>
                                    <div class='col-md-12 error form-group d-none'>
                                        <div class='alert-danger alert'>Please correct the errors and try
                                            again.</div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mr-3">Pay Now</button>
                                <a href="{{ route('users.index') }}" class="btn iq-bg-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection


@section('scripts')

@endsection

@push('js')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script type="text/javascript">
        $(function() {
            var $form = $(".require-validation");
            $('form.require-validation').bind('submit', function(e) {
                var $form = $(".require-validation"),
                    inputSelector = ['input[type=email]', 'input[type=password]',
                        'input[type=text]', 'input[type=file]',
                        'textarea'
                    ].join(', '),
                    $inputs = $form.find('.required').find(inputSelector),
                    $errorMessage = $form.find('div.error'),
                    valid = true;
                $errorMessage.addClass('hide');

                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error');
                        $errorMessage.removeClass('d-none');
                        e.preventDefault();
                    }
                });

                if (!$form.data('cc-on-file')) {
                    e.preventDefault();
                    Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                }

            });

            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.error')
                        .removeClass('d-none')
                        .find('.alert')
                        .text(response.error.message);
                } else {
                    // token contains id, last4, and card type
                    var token = response['id'];
                    // insert the token into the form so it gets submitted to the server
                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $form.get(0).submit();
                }
            }

        });
    </script>
@endpush
