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


    $("#change_password_form").validate({
        errorElement: 'div',
        rules: {
            old_password: {
                required: true,
            },
            new_password: {
                required: true,
            },
            cnew_password : {
                required : true,
                equalTo : "#new_password"
            }
        },
        messages: {
            old_password: {
                required: "<?php echo trans('message.please_enter_old_password'); ?>",
            },
            new_password: {
                required: "<?php echo trans('message.please_enter_new_password'); ?>",
            }, 
            cnew_password : {
                required : "<?php echo trans('message.please_enter_confirm_password'); ?>",
                equalTo : "<?php echo trans('message.new_password_and_confirm_password_must_be_the_same'); ?>"
            } 
        },
        errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($("#" + name + "_validate"));
        },
        submitHandler: function (form) { // <- pass 'form' argument in
            $(".submit-change-password").attr("disabled", true);
            form.submit(); // <- use 'form' argument here.
        },
        highlight: function(element) {
            $(element).addClass("field-error");
        },
        unhighlight: function(element) {
            $(element).removeClass("field-error");
        }
    });

    $("#user_details_form").validate({
        errorElement: 'div',
        rules: {
            email: {
                required: true,
                emailExt: true,
            },
            name: {
                required: true,
            },
            aboutme: {
                required: true,
            },
            user_type: {
                required: true,
            },
            gender: {
                required: true,
            },
            profile: {
                extension:'jpg|jpeg|png|JPG|JPEG|PNG'
            },
            timezone: {
                require: true,
            }
        },
        messages: {
            email: {
                required: "<?php echo trans('message.please_enter_email'); ?>",
                emailExt: "<?php echo trans('message.please_enter_valid_email'); ?>",
            },
            name: {
                required: "<?php echo trans('message.please_enter_name'); ?>",
            },
            user_type: {
                required: "<?php echo trans('message.please_select_user_type'); ?>",
            },
            gender: {
                required: "<?php echo trans('message.please_select_gender'); ?>",
            },
            aboutme: {
                required: "<?php echo trans('message.please_enter_about_me'); ?>",
            },
            profile:{
                extension: "<?php echo trans('message.please_select_valid_file_format'); ?>",
            },
            timezone:{
                require: "<?php echo trans('message.please_select_timezone'); ?>",
            }
        },
        errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($("#" + name + "_validate"));
        },
        submitHandler: function (form) { // <- pass 'form' argument in
            $(".submit-user-details").attr("disabled", true);
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