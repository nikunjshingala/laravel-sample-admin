<?php
    header('Content-type: application/javascript');
    /* no store cache in browser */
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache"); 
?>
$(document).ready(function () {
    $.validator.addMethod("emailExt", function (value, element, param) {
        return value.match(/^[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/);
    }, 'Your E-mail is wrong');


    $("form").validate({
        errorElement: 'div',
        rules: {
            email: {
                required: true,
                emailExt: true,
            },
            password: {
                required: true,
            },
            password_confirmation : {
                required : true,
                equalTo : "#password"
            }
        },
        messages: {
            email: {
                required: "<?php echo trans('message.please_enter_email'); ?>",
                emailExt: "<?php echo trans('message.please_enter_valid_email'); ?>",
            },
            password: {
                required: "<?php echo trans('message.please_enter_password'); ?>",
            }, 
            password_confirmation : {
                required : "<?php echo trans('message.please_enter_confirm_password'); ?>",
                equalTo : "<?php echo trans('message.password_and_confirm_password_must_be_the_same'); ?>"
            } 
        },
        errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($("#" + name + "_validate"));
        },
        submitHandler: function (form) { // <- pass 'form' argument in
            $(".submit").attr("disabled", true);
            form.submit(); // <- use 'form' argument here.
        },
        highlight: function(element) {
            $(element).addClass("field-error");
        },
        unhighlight: function(element) {
            $(element).removeClass("field-error");
        }
    });
})