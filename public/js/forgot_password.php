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
        }, "<?php echo trans('message.your_e_mail_is_wrong'); ?>");


        $("form").validate({
            errorElement: 'div',
            rules: {
                email: {
                    required: true,
                    emailExt: true,
                },
            },
            messages: {
                email: {
                    required: "<?php echo trans('message.please_enter_email'); ?>",
                    emailExt: "<?php echo trans('message.please_enter_valid_email'); ?>",
                },  
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