'use strict';

function getSelectedItems(idOrderItem) {
    var selectedItems = [];

    if (parseInt(idOrderItem) > 0) {
        selectedItems.push(idOrderItem);

        return selectedItems;
    }

    $('.item-check').each(function(){
        if ($(this).prop('checked') === true) {
            selectedItems.push($(this).val());
        }
    });

    return selectedItems;
}

function createTriggerUrl(idOrder, eventName) {
    var url = '/oms/trigger/trigger-event-for-order';
    var parameters = {
        event: eventName,
        'id-sales-order': idOrder,
        redirect: '/sales/details?id-sales-order=' + idOrder
    };

    parameters.items = getSelectedItems();

    var finalUrl = url + '?' + $.param(parameters);

    return decodeURIComponent(finalUrl);
}

$(document).ready(function() {
    $('.trigger-order-single-event').click(function(e){
        e.preventDefault();

        var idOrder = $(this).data('id-sales-order');
        var eventName = $(this).data('event');
        var idOrderItem = $(this).data('id-item');

        window.location = createTriggerUrl(idOrder, eventName, idOrderItem);
    });

    $('.trigger-order-event').click(function(e){
        e.preventDefault();

        var idOrder = $(this).data('id-sales-order');
        var eventName = $(this).data('event');

        window.location = createTriggerUrl(idOrder, eventName);
    });

    $('.item-check').click(function(){
        var countChecked = $(".item-check[type='checkbox']:checked").length;
        var totalCheckboxItems = $('.item-check').length;

        if (totalCheckboxItems === countChecked) {
            $('#check-all-orders').prop('checked', true);

            return true;
        }

        $('#check-all-orders').prop('checked', false);

        return true;
    });

    $('#check-all-orders').click(function(){
        if ($(this).prop('checked') === true) {
            var checked = true;
        } else {
            var checked = false;
        }

        $('.item-check').each(function(){
            $(this).prop('checked', checked);
        });
    });
});