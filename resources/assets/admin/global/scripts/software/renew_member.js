$('.select2_renew').select2({
    placeholder: 'اختر',
    searchInputPlaceholder: 'Search'
});
$('.select2_renew-no-search').select2({
    minimumResultsForSearch: Infinity,
    placeholder: 'Choose one'
});

function activeSetup(active) {
    if (active != null) {
        var activeMp3 = new Audio();
        activeMp3.autoplay = true;
        if (lang === 'ar')
            activeMp3.src = navigator.userAgent.match(/Firefox/) ? 'active.ogg' : path_mp3 + '/' + active;
        else
            activeMp3.src = navigator.userAgent.match(/Firefox/) ? 'active.ogg' : path_mp3 + '/' + active;
        activeMp3.play();
    }
}


function expiredSetup(expire) {
    if (expire != null) {
        var expiredMp3 = new Audio();
        expiredMp3.autoplay = true;
        if (lang === 'ar')
            expiredMp3.src = navigator.userAgent.match(/Firefox/) ? 'expired.ogg' : path_mp3 + '/' + expire;
        else
            expiredMp3.src = navigator.userAgent.match(/Firefox/) ? 'expired.ogg' : path_mp3 + '/' + expire;

        expiredMp3.play();
    }
}

$("#modalAttends").on('hide.bs.modal', function () {
    var myinput = document.getElementById('barcode_input_global');
    myinput.value = '';
});

$("#modelRenew").on('hide.bs.modal', function () {
    var myinput = document.getElementById('barcode_input_global');
    myinput.value = '';
});

$('#div_renew').off("click").on('click', function (e) {
    $("#modalScanner").modal('hide');
    e.preventDefault();
    var that = $('#div_renew a');
    var attr_id = that.attr('id');
    var url = member_subscription_renew_url;
    var myurl = url.replace(':id', attr_id);

    $.ajax({
        url: myurl,
        type: "get",
        success: (data) => {
            // console.log(data);
            var data = data;
            $('#modalAttends').modal('hide');
            var output = '';
            var data_length = data.membership.length;
            for (var i = 0; i < data_length; i++) {
                var d = new Date();

                d.setDate(d.getDate() + parseInt(data.membership[i]['period']));
                var nd = new Date(d);
                var expire_attr = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
                output += '<option expire_date="' + expire_attr + '" IsChangeable="' + data.membership[i]['is_expire_changeable'] + '"  title="' + data.membership[i]['price'] + '" value="' + data.membership[i]['id'] + '"  >' + data.membership[i]['name'] + ' </option>';
            }
            $('#select_membership').html(output);
            getPriceMemberShip();
            $('#modelRenew').modal('show');
        },
        error: (reject) => {

            var response = $.parseJSON(reject.responseText);
            console.log(response);
        }
    });

    return false;
});

$('#btn_renew_membership').off("click").on('click', function (e) {
    e.preventDefault();
    var that = $('#div_renew a');
    var attr_id = that.attr('id');
    var url = member_subscription_renew_store_url;
    var myurl = url.replace(':id', attr_id);

    var membership_id = $("#select_membership option:selected").attr('value');
    var renew_amount_paid = $("#renew_amount_paid").val();
    var mydata = {'membership_id': membership_id, 'amount_paid': renew_amount_paid};

    $('#amount_paid_error').text('');

    $('#error_expire_date').text('');
    if ($('#myDivExpireRenewModal').html().length > 0) {
        var CustomExpire_date = $("#customExpireDate").val();
        let now = new Date();
        let custom_expire_date_format = new Date(CustomExpire_date);
        if (custom_expire_date_format.getTime() < now.getTime()) {
            $('#error_expire_date').text(trans_expire_date_must_after_today);
            return false;
        }
        mydata = {
            'membership_id': membership_id,
            'custom_expire_date': CustomExpire_date,
            'amount_paid': renew_amount_paid
        };
        // console.log('in:' + mydata);

    }

    selectedMembershipPrice = 0;
    $.each($("#select_membership option:selected"), function () {

        selectedMembershipPrice = selectedMembershipPrice + (parseFloat($(this).attr('title')));

    });
    if (renew_amount_paid > selectedMembershipPrice) {
        $('#amount_paid_error').text(trans_amount_paid_must_less_membership);
        return false;
    }
    $('#modelRenew').modal('hide');
    $.ajax({
        url: myurl,
        type: "get",
        data: mydata,
        beforeSend: function () {
            // $("#global-loader").show();
        }, success: (data) => {
            console.log(data);
            if (data.status === true) {
                // $("#global-loader").hide();
                // alert('ss');
                $('#modelRenew').modal('hide');
                var lang = 'ar';
                var isRtl = (lang === 'ar');

                swal({
                    title: trans_done,
                    text: trans_successfully_processed,
                    type: "success",
                    timer: 4000,
                    confirmButtonText: 'Ok',
                });

            } else {
                // alert('ee');
                // $("#global-loader").hide();

                $('#modelRenew').modal('show');
                if (data.code === "amount_paid")
                    $('#amount_paid_error').text(data.msg);
                else if (data.code === "custom_expire_date")
                    $('#error_expire_date').text(data.msg);

            }


        },
        error: (reject) => {

            var response = $.parseJSON(reject.responseText);
            console.log(response);

        }


    });
    return false;
});


var selectedMembershipPrice = 0;

$('#select_membership').change(function () {
    getPriceMemberShip();

});

$("#renew_amount_paid").change(function () {
    selectedMembershipPrice = 0;
    $.each($("#select_membership option:selected"), function () {

        selectedMembershipPrice = selectedMembershipPrice + (parseFloat($(this).attr('title')));

    });
    let valueAmountPaid = $('#renew_amount_paid').val();
    $('#renew_amount_remaining').val(selectedMembershipPrice - valueAmountPaid);
});

function getPriceMemberShip() {
    selectedMembershipPrice = 0;
    $.each($("#select_membership option:selected"), function () {

        selectedMembershipPrice = selectedMembershipPrice + (parseFloat($(this).attr('title')));

    });
    $('#renew_amount_paid').val(selectedMembershipPrice);
    $('#renew_amount_remaining').val(0);
    setUpInputExpire();
    $('#myTotalModel').text(trans_price + " = " + selectedMembershipPrice);
}

function setUpInputExpire() {
    var selectedMembership = $('#select_membership').children("option:selected");

    var myDivExpire = '<div class="input-group input-medium date date-picker" data-date-format="yyyy-mm-dd" data-date-start-date="+0d">\n' +
        '                        <input id="customExpireDate" class="form-control form-control-inline input-medium fc-datepicker" autocomplete="off" title="تاريخ الانتهاء" placeholder="تاريخ الانتهاء" name="expire_date" value="' + selectedMembership.attr('expire_date') + '" type="text">\n' +
        '                        <span class="input-group-btn">\n' +
        '<button class="btn default" type="button"><i class="fa fa-calendar"></i></button>\n' +
        '</span>\n'+
        ''+
        '                    </div>';
    // var myDivExpire = '<div class="input-group ">\n' +
    //     '                                <div class="input-group-prepend">\n' +
    //     '                                    <div class="input-group-text">\n' +
    //     '                                        <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>\n' +
    //     '                                    </div>\n' +
    //     '                                </div>\n' +
    //     '                                <input id="customExpireDate" class="form-control fc-datepicker" title="تاريخ الانتهاء" placeholder="تاريخ الانتهاء" name="expire_date" value="' + selectedMembership.attr('expire_date') + '" type="text">\n' +
    //     '                            </div><span id="error_expire_date" class="text-danger"></span>';
    if (selectedMembership.attr('ischangeable') === '1') {


        $('#myDivExpireRenewModal').html(myDivExpire);

        $('.fc-datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true

        });
    } else
        $('#myDivExpireRenewModal').html('');

}