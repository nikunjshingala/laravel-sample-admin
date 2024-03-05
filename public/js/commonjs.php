<?php
header('content-type: ', 'text/javascript');
?>
    $(document).ready(function () {

    });
$(document).on('change', '.status-switch', function (e) {
    var doStatus = (this.checked) ? 'Active' : 'Inactive';
    var this_item = this;
    var dataId = $(this).attr("data-id");
    var _token = $(document).find('input[name="_token"]').val();
    console.log(doStatus, dataId);
    var checkModule = $(this).attr("data-check-module");

    if (typeof checkModule === "undefined") {
        var redirectUrl = window.route_toggle_status;
    } else {
        var redirectUrl = $(this).attr("data-toggle-url");
    }
    if (doStatus == 'Active') {
        message = "<?php echo trans('message.are_you_sure_you_want_to_active_this_record'); ?>";
    }
    else {
        message = "<?php echo trans('message.are_you_sure_you_want_to_inactive_this_record'); ?>";
    }
    url = redirectUrl;
    $.ajax({
        type: "POST",
        url: url,
        data: { "DataId": dataId, "doStatus": doStatus, _token: _token },
        success: function (result) {
            if (result['status'] == '1') {
                oTable.draw();
                alertify.success(result['message']);
            } else {
                alertify.error(result['message']);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error(xhr.responseJSON.message);
        }
    });
});
$(document).on('click', '.actionStatus', function (e) {

    var doStatus = ($(this).hasClass("active")) ? 'Active' : 'Inactive';
    var DataId = $(this).attr("data-id");
    var this_item = this;
    var dataTitle = $(this).attr("data-title");
    var dataNewMessage = $(this).attr("data-new-msg");
    var checkModule = $(this).attr("data-check-module");
    if (typeof checkModule === "undefined") {
        var redirectUrl = window.route_toggle_status;
    } else {
        var redirectUrl = $(this).attr("data-toggle-url");
    }

    var message = '';
    var _token = $(document).find('input[name="_token"]').val();
    var reloadDT = (typeof isReload === "undefined") ? 'no' : 'yes';

    url = redirectUrl;
    $.ajax({
        type: "POST",
        url: url,
        data: { "DataId": DataId, "doStatus": doStatus, _token: _token },
        success: function (result) {
            if (result['status'] == '1') {
                oTable.draw();
                alertify.success(result['message']);
            } else {
                alertify.error(result['message']);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error(xhr.responseJSON.message);
        }
    });
});

$(document).on('click', '.deleteAction', function (e) {
    var extraMsg = $(this).attr('data-deletemsg');
    extraMsg = (typeof (extraMsg) !== "undefined" && extraMsg !== '' ? extraMsg : '');
    var deleteMessages = typeof ($('#deleteMessages').val()) == "undefined" ? "" : $('#deleteMessages').val();
    var message = deleteMessages + ' ' + "<?php echo trans('message.are_you_sure_you_want_to_delete_this_record'); ?>" + ' ' + extraMsg;
    message = message.trim();

    var DataId = $(this).attr("data-id");

    alertify.confirm('Delete', message, function () {
        $('.deleteAction' + DataId).submit();
    }, function () { }).set({ transition: 'fade' });
});

function updateLiveTime(timeCurrent) {
  var newtime = moment(timeCurrent).add('1','second').format('YYYY-MM-DD HH:mm:ss');
  $('#livetimer').html(newtime);
  $('#livetimerfooter').html(newtime);
}

setInterval(function() {
    updateLiveTime($('#livetimerfooter').text());
}, 1000);