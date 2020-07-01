jQuery(function ($) {
    $(document).ready(function () {
        //selecting state   and populating the  rest of the fields
        $(document).on('change ', `select[name="input_${pricingAjax.state_field_id}"]`, function () {
            var state = $(this).val();
            console.log(state);
            if (state === '') {
                return;
            } else {
                $.ajax({
                    beforeSend: function(){
                        $(`[name="input_${pricingAjax.l_type_field_id}"]`).html("<option> Select license type </option>").show();
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
                            $(`#input_${pricingAjax.form_id}_${pricingAjax.l_type_field_id}`).append($('<option></option>').val(p).html(p));
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
        $(document).on('change ', `select[name="input_${pricingAjax.l_type_field_id}"]`, function () {
            var license = $(this).val();
            var state = $(`select[name="input_${pricingAjax.state_field_id}"]`).val();

            if (license === '') {
                return;
            } else {
                $.ajax({
                    beforeSend: function(){
                        $(`[name="input_${pricingAjax.bus_type_field_id}"]`).html("<option> Select company type  </option>").show();
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
                            $(`#input_${pricingAjax.form_id}_${pricingAjax.bus_type_field_id}`).append($('<option></option>').val(p).html(p));
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
        $(document).on('change ', `select[name="input_${pricingAjax.bus_type_field_id}"]`, function () {
            var company = $(this).val();
            var state = $(`select[name="input_${pricingAjax.state_field_id}"]`).val();
            var license = $(`select[name="input_${pricingAjax.l_type_field_id}"]`).val();

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
                        // $(`.ginput_total_${pricingAjax.form_id}`).html("$0.00").show();
                    },
                    success: function (response) {
                        $(`.ginput_total_${pricingAjax.form_id}`).html("$"+response.price);
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
