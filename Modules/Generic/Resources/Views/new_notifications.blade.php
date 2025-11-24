{{--<script>--}}

    {{--(function ($) {--}}
        {{--$.extend({--}}
            {{--playSound: function () {--}}
                {{--return $(--}}
                    {{--'<audio class="sound-player" autoplay="autoplay" style="display:none;">'--}}
                    {{--+ '<source src="' + arguments[0] + '" />'--}}
                    {{--+ '<embed src="' + arguments[0] + '" hidden="true" autostart="true" loop="false"/>'--}}
                    {{--+ '</audio>'--}}
                {{--).appendTo('body');--}}
            {{--},--}}
            {{--stopSound: function () {--}}
                {{--$(".sound-player").remove();--}}
            {{--}--}}
        {{--});--}}
    {{--})(jQuery);--}}

    {{--var last_order_time = last_ticket_time = 0;--}}
    {{--if ($('#last_time_to_get_notification').html() != "0" || $('#last_time_to_get_notification').html() != "") {--}}
        {{--last_order_time = last_ticket_time = $('#last_time_to_get_notification').html();--}}
    {{--}--}}

    {{--var new_orders = new_tickets = false;--}}
    {{--setInterval(function () {--}}
        {{--var url = "{{route('getNewAdminNotifications')}}";--}}
        {{--url += '?last_order_time=' + last_order_time;--}}
        {{--url += '&last_ticket_time=' + last_ticket_time;--}}
        {{--$.ajax({--}}
            {{--url: url,--}}
            {{--processData: false,--}}
            {{--type: 'GET',--}}
            {{--success: function (response) {--}}
                {{--var list = '';--}}
                {{--if (response.status) {--}}
                    {{--$.playSound('resources/assets/notifi.mp3');--}}
                    {{--response.data.orders.forEach(function (order) {--}}
                        {{--list += '<li>'--}}
                            {{--+ '<a href="' + order.route + '">'--}}
                            {{--+ '<span class="details">'--}}
                            {{--+ '<span class="label label-sm label-icon label-success md-skip">'--}}
                            {{--+ '<i class="fa fa-plus"></i>'--}}
                            {{--+ '</span> New Order Added. </span>'--}}
                            {{--+ '<span class="time">' + order.created_at + '</span>'--}}
                            {{--+ '</a>'--}}
                            {{--+ '</li>';--}}
                        {{--new_orders = true;--}}
                        {{--last_order_time = order.date;--}}
                    {{--});--}}

                    {{--response.data.tickets.forEach(function (ticket) {--}}
                        {{--list += '<li>' +--}}
                            {{--'<a href="' + ticket.route + '">' +--}}
                            {{--'<span class="details">' +--}}
                            {{--'<span class="label label-sm label-icon label-success md-skip">' +--}}
                            {{--'<i class="fa fa-plus"></i>' +--}}
                            {{--'</span> New Ticket Added. </span>' +--}}
                            {{--'<span class="time">' + ticket.created_at + '</span>' +--}}
                            {{--'</a>' +--}}
                            {{--'</li>';--}}
                        {{--new_tickets = true;--}}
                        {{--last_ticket_time = ticket.date;--}}
                    {{--});--}}

                    {{--if (new_tickets) {--}}
                        {{--$("#new_tickets_badge").removeClass('hidden');--}}
                    {{--}--}}
                    {{--if (new_orders) {--}}
                        {{--$("#new_orders_badge").removeClass('hidden');--}}
                    {{--}--}}
                {{--}--}}
{{--//                    $('#notification_bar_list').prepend(list);--}}
                {{--$('#last_time_to_get_notification').html(response.data.last_time_to_get_notification);--}}
                {{--$('#last_time_to_get_notification').trigger('change');--}}
            {{--},--}}
            {{--error: function (jqXHR, textStatus, errorThrown) {--}}
                {{--console.log(textStatus + " " + errorThrown);--}}
            {{--}--}}
        {{--});--}}
    {{--}, 30000);--}}

{{--</script>--}}