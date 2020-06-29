jQuery(function ($) {
    $(document).ready(function () {

        var base_url = 'https://cors-anywhere.herokuapp.com/http://85.90.245.31:8091';
        var dates = [];
        var hospitalDays = [];
        var bookingToAdd = {
            cityId: 0,
            date: '',
            deleted: false,
            id: 0,
            listingId:'',
            name: '',
            offerIDs: [],
            phoneNumber: '',
            time: '',
            userId: 0,
        };

        //getting the selected day time slots
        function getDaTimeSlots(date) {

            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            var dayName = days[date.getDay()];

            let allowedTimes = []

            $.each(dates, function (index, data) {
                if (data.dayOfWeek.toLowerCase() == dayName.toLowerCase()) {
                    let startTime = new Date(date.getFullYear(), date.getMonth(), date.getDay(), data.startTime, 0, 0);
                    let endTime = new Date(date.getFullYear(), date.getMonth(), date.getDay(), data.endTime, 0, 0);
                    allowedTimes.push(startTime.getHours() + ":" + startTime.getMinutes());
                    while (startTime < endTime) {
                        startTime = new Date(startTime.getTime() + 30*60000);
                        allowedTimes.push(startTime.getHours() + ":" + startTime.getMinutes());
                    }
                }
            });

            return allowedTimes;
        }

        function getFormattedDate(date) {
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            return year + '-' + month + '-' +  day;
        }

        function getFormattedTime(date) {
            var hours = date.getHours();
            var minutes = date.getMinutes();
            return hours + ':' + minutes ;
        }

        //selecting city id  and populating the  hospital field
        $(document).on('change ', 'select[name="city"]', function () {
            var val = $(this).val();
            var hospital = $('select[name="hospital"]');

            if (val === '') {
                return;
            } else {
                bookingToAdd.cityId = parseInt(val);
                $.ajax({
                    beforeSend: function(){
                        // Show image container
                        $('[name="hospital"]').html("<option> Loading hospitals ... </option>").show();
                    },
                    contentType: false,
                    processData: false,
                    url: `${base_url}/listing/city/${val}/type/3`,
                    type: 'GET',
                    success: function (response) {
                        var hospitals = Object.values(response.content);
                        $.each(hospitals, function(i, p) {
                            $('#hospital').append($('<option></option>').val(p.id).html(p.name));
                        });
                        if (hospitals.length === 0){
                            $('#specialist').append("<option> No hospitals found </option>").show();
                        }

                    },
                    complete:function(){
                        // Hide image container
                        $('#hospital').find("option:eq(0)").remove();
                        var hospitalId = $("#hospital").find("option:first-child").val();
                        bookingToAdd.listingId = hospitalId;

                        $.ajax({
                            beforeSend: function(){
                                // Show image container
                                $('[name="specialist"]').html("<option> Loading offers ... </option>").show();
                            },
                            contentType: false,
                            processData: false,
                            url: `${base_url}/offer/listing/${hospitalId}`,
                            type: 'GET',
                            success: function (response) {
                                var specialist = Object.values(response);
                                // enableBookNow();
                                $.each(specialist, function(i, p) {
                                    $('#specialist').append($('<option></option>').val(p.id).html(p.nameEn));
                                });
                                if (specialist.length === 0){
                                    $('#specialist').append("<option> No offers found </option>").show();
                                }

                            },
                            complete:function(){
                                // Hide image container
                                $('#specialist').find("option:eq(0)").remove();
                                var specialistId = $("#hospital").find("option:first-child").val();
                                bookingToAdd.offerIDs.push(parseInt(specialistId));


                                $.ajax({
                                    contentType: false,
                                    processData: false,
                                    url: `${base_url}/schedule/getScheduleByOffer?listingId=${bookingToAdd.listingId}&offerId=${specialistId}`,
                                    type: 'GET',
                                    success: function (response) {
                                        dates = [];
                                        var date = Object.values(response);
                                        dates = Object.values(dates.concat(date));
                                        console.log(dates);
                                        $.each(specialist, function(i, p) {
                                            days = p.dayOfWeek;
                                        });
                                        $('.book-now').prop('disabled', false);
                                    },
                                    error: function (error) {
                                        console.log('Failure', error);
                                        return false;
                                    }
                                });
                            },
                            error: function (error) {
                                console.log('Failure', error);
                                return false;
                            }
                        });

                    },
                    error: function (error) {
                        console.log('Failure', error);
                        return false;
                    }
                });
            }

        });

        //selecting hospital id  and populating the specialist field
        $(document).on('change ', 'select[name="hospital"]', function () {
            var val = $(this).val();
            var specialist = $('select[name="specialist"]');

            if (val === '') {
                return;
            } else {
                bookingToAdd.listingId = val;
                $.ajax({
                    beforeSend: function(){
                        // Show image container
                        $('[name="specialist"]').html("<option> Loading offers ... </option>").show();
                    },
                    contentType: false,
                    processData: false,
                    url: `${base_url}/offer/listing/${val}`,
                    type: 'GET',
                    success: function (response) {
                        var specialist = Object.values(response);
                        // enableBookNow();
                        $.each(specialist, function(i, p) {
                            $('#specialist').append($('<option></option>').val(p.id).html(p.nameEn));
                        });
                        if (specialist.length === 0){
                            $('#specialist').append("<option> No offers found </option>").show();
                        }
                        // else if(specialist.length > 0){
                        //     $('[name="specialist"]').html("<option> Select Now </option>").show();
                        // }
                    },
                    complete:function(){
                        // Hide image container
                        $('#specialist').find("option:eq(0)").remove();
                        // $('#specialist').find("option:eq(${p.id})").show();
                    },
                    error: function (error) {
                        console.log('Failure', error);
                        return false;
                    }
                });
            }
        });

        $('#specialist').change(function(){
                $(this).parent().siblings().find('.book-now').prop('disabled', false);
        });

        //selecting hospital id  and populating the specialist field
        $(document).on('change ', 'select[name="specialist"]', function () {
            var val = $(this).val();
            var date = $('select[name="specialist"]');

            if (val === '') {
                return;
            } else {
                bookingToAdd.offerIDs.push(parseInt(val));
                $.ajax({
                    contentType: false,
                    processData: false,
                    url: `${base_url}/schedule/getScheduleByOffer?listingId=${bookingToAdd.listingId}&offerId=${val}`,
                    type: 'GET',
                    success: function (response) {
                        dates = [];
                        var date = Object.values(response);
                        dates = Object.values(dates.concat(date));
                        console.log(dates);
                        $.each(specialist, function(i, p) {
                            days = p.dayOfWeek;
                        });
                    },
                    error: function (error) {
                        console.log('Failure', error);
                        return false;
                    }
                });
            }

        });

        //getting the input from tand storing the input from the name field
        $(document).on('change focusout', 'input[name="name"]', function () {
            var val = $(this).val();

            if (val && val !== '') {
                bookingToAdd.name = val;
            }
        });

        //getting the input from tand storing the input from the number field
        $(document).on('change focusout', 'input[name="number"]', function () {
            var val = $(this).val();

            if (val && val !== '') {
                bookingToAdd.phoneNumber = val;
            }
        });

        //getting the input from tand storing the input from the date field
        $(document).on('change focusout', 'input[name="date"]', function () {
            var val = $(this).val();
            if (val && val !== '') {
             var time = Date.parse(val);
                bookingToAdd.date = getFormattedDate(new Date(val));
                bookingToAdd.time = getFormattedTime(new Date(val));
            }

        });

        //getting the days from the api and bluring non-existent days
        $(document).on('click', 'input[name="modalButton"]',function () {
            $('#complete-booking').prop('disabled', true);
            $.each(dates, function(i, p) {
                switch (p.dayOfWeek) {
                    case 'Sunday':
                        hospitalDays = hospitalDays.concat([0]);
                        break;
                    case 'Monday':
                        hospitalDays = hospitalDays.concat([1]);
                        break;
                    case 'Tuesday':
                        hospitalDays = hospitalDays.concat([2]);
                        break;
                    case 'Wednesday':
                        hospitalDays = hospitalDays.concat([3]);
                        break;
                    case 'Thursday':
                        hospitalDays = hospitalDays.concat([4]);
                        break;
                    case 'Friday':
                        hospitalDays = hospitalDays.concat([5]);
                        break;
                    default :
                        hospitalDays = hospitalDays.concat([6]);
                        break;
                }
            });

            //initializing the datepicker
            $("#booking-date").datetimepicker({
                format:'Y-m-d H:i ',
                formatTime:"h:i a",
                allowTimes: getDaTimeSlots(new Date),
                onChangeDateTime: function(dp, input) {
                    this.setOptions({
                        allowedTimes: getDaTimeSlots(new Date(input.val()))
                    });
                },
                beforeShowDay: function(days) {
                    var day = days.getDay();
                    if($.inArray(day,hospitalDays) != -1) return [true];
                    return [false];
                }
            });
        });

        //getting the values fors date and time to store them
        $(document).on('change','#booking-date', function(){
            if (!(bookingToAdd.name == '' && bookingToAdd.phoneNumber == '' && bookingToAdd.date == '')){
                $('#complete-booking').prop('disabled', false);
            }
        });

        //posting the data to the server
        $(document).on('click', 'input[name="save-changes"]',function () {
            $.ajax({
                headers: {
                    'Content-Type': 'application/json',
                },
                // dataType: 'json',
                type: 'POST',
                url: `${base_url}/booking/`,
                data: JSON.stringify(bookingToAdd),
                success: function (response) {
                    if (response == 'Successfully registered booking'){
                        $.MessageBox(`Hi ${bookingToAdd.name}! You successfully booked for an appointment <br/>  for ${bookingToAdd.date}   at  ${bookingToAdd.time}`);
                    }else {
                        $.MessageBox(`Hi ${bookingToAdd.name} You booking was not successful. Try again please!`);
                    }
                },
                error: function (error) {
                    console.log('Failure', error);
                    return false;
                }
            });
        });

        //returning a message based on response gotten from the server
        $(document).on('click','.messagebox_buttons_button', function(){
            $(".gifted-booking  select").val("");
            $('.book-now').prop('disabled', true);
        });

    });

    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    });

});