
function scanBarcodeManual() {
    let value = $('#scan_barcode_manual').val();
    barcode_scanner(value);

}


on_scanner();

function on_scanner() {
    let is_event = false;
    let input_global = document.getElementById('barcode_input_global');
    input_global.addEventListener("keypress", function (e) {
        setTimeout(function () {
            if (e.keyCode == 13) {
                barcode_scanner(input_global.value);
                input_global.select();
            }
        }, 500);
    });

    document.addEventListener("keypress", function (e) {
        if (e.target.tagName !== "INPUT") {
            input_global.focus();
        }
    });
}
function load_new_posts(){
    loading=false;

}
function barcode_scanner(value) {
    if (value.length < 4)
        return;
    var mycode = value;
    $.ajax({
        url: member_attendees_url,
        type: "get",
        data: {
            code: mycode
        }, beforeSend: function () {
            // $("#global-loader").show();
        }, success: (data) => {
            // $("#global-loader").hide();
            // console.log(data.member);
            var data = data;

            $('#modalAttends').modal('show');
            load_new_posts();

            if(data.member) {
                $('#myData').show();

                $('#client_name').text(data.member.name);
                $('#client_address').text(data.member.address);
                $('#client_phone').text(data.member.phone);
                $('#client_img').attr('src',  data.member.image);
                $('#client_amount_remaining').text(data.member.member_subscription_info.amount_remaining);
                // var partsDate = data.member.member_subscriptions.expire_date.split('T');
                $('#client_expire_date').text(data.member.member_subscription_info.expire_date);
                $('#client_workouts').text(data.member.member_subscription_info.remain_workouts);
                $('#client_membership').text(data.member.member_subscription_info !== null ? data.member.member_subscription_info.subscription.name : trans_old_membership);

                if(data.status === true){
                    $('#p_messages').html('');
                    $('#div_renew').html('');
                    $('#icon_model').html(' <i class="fa fa-check\n mg-b-20 tx-50 text-success"></i>');
                    setTimeout(function() {$('#modalAttends').modal('hide');}, 2000);


                }else{
                    $('#p_messages').text(data.msg);
                    $('#icon_model').html(' <i class="fa fa-times mg-b-20 tx-50 text-danger " ></i>');
                    $('#div_renew').html('<a class=" mg-t-10 btn btn-primary btn-block  text-white" id="' + data.member.id + '"  >'+ trans_renew_membership +'</a>');
                }

            } else {
                $('#myData').hide();
                $('#div_renew').html('');
                $('#icon_model').html(' <i class="fa fa-times mg-b-20 tx-50 text-danger"></i>');
                $('#p_messages').text(data.msg);
            }
            $('#scan_barcode_manual').val('');

            if (data.member.member_subscription_info.status === 0) activeSetup(data.member.member_subscription_info.subscription !== null ? data.member.member_subscription_info.subscription.sound_active : null);
            else if (data.member.member_subscription_info.status === 1) expiredSetup(data.member.member_subscription_info.subscription !== null ? data.member.member_subscription_info.subscription.sound_expired : null);
            else if (data.member.member_subscription_info.status === 2) activeSetup(data.member.member_subscription_info.subscription !== null ? data.member.member_subscription_info.subscription.sound_active : null);


        },
        error: (reject) => {

            var response = $.parseJSON(reject.responseText);
            console.log(response);

        }


    });
}