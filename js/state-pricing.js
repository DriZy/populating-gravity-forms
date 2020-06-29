jQuery(function ($) {
    $(document).ready(function () {
        //selecting state   and populating the  rest of the fields
        $(document).on('change ', 'select[name="input_1"]', function () {
            var state = $(this).val();
            console.log(pricingAjax.ajaxurl);

            if (state === '') {
                return;
            } else {
                $.ajax({
                    beforeSend: function(){
                        // Show image container
                        $('[name="input_7"]').html("<option> Select license type </option>").show();
                    },
                    type: "POST",
                    dataType: "json",
                    url: pricingAjax.ajaxurl,
                    data:{
                        state:state,
                        action: 'state_pricing_get_values'
                    },
                    success: function (response) {
                        $.each(response, function(i, p) {
                            $('#input_1_7').append($('<option></option>').val(p).html(p));
                        });
                    },
                    error: function (error) {
                        console.log('Failure', error);
                        return false;
                    }
                });
            }

        });

        // selecting license type to populate company type

        $(document).on('change ', 'select[name="input_7"]', function () {
            var license = $(this).val();
            var state = $('select[name="input_1"]').val();

            if (license === '') {
                return;
            } else {
                $.ajax({
                    beforeSend: function(){
                        // Show image container
                        $('[name="input_8"]').html("<option> Select company type  </option>").show();
                    },
                    type: "POST",
                    dataType: "json",
                    url: pricingAjax.ajaxurl,
                    data:{
                        license:license,
                        state:state,
                        action: 'license_pricing_get_values'
                    },
                    success: function (response) {
                        $.each(response, function(i, p) {
                            $('#input_1_8').append($('<option></option>').val(p).html(p));
                        });
                    },
                    error: function (error) {
                        console.log('Failure', error);
                        return false;
                    }
                });
            }

        });

        // selecting company type to populate the price

        $(document).on('change ', 'select[name="input_8"]', function () {
            var company = $(this).val();
            var state = $('select[name="input_1"]').val();
            var license = $('select[name="input_7"]').val();

            if (company === '') {
                return;
            } else {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: pricingAjax.ajaxurl,
                    data:{
                        license:license,
                        state:state,
                        company:company,
                        action: 'company_pricing_get_values'
                    },
                    beforeSend: function(){
                        // Show image container
                        $('.ginput_total_1').html("$0.00").show();
                    },
                    success: function (response) {
                        $('.ginput_total_1').html("$"+response.price);
                    },
                
                    error: function (error) {
                        console.log('Failure', error);
                        return false;
                    }
                });
            }

        });
    });
});