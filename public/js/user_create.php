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
        ignore: "",
        rules: {
            email: {
                required: true,
                emailExt: true,
            },
            name: {
                required: true,
            },
            gender: {
                required: true,
            },
            user_type: {
                required: true,
            },
        },
        messages: {
            email: {
                required: "<?php echo trans('message.please_enter_email'); ?>",
                emailExt: "<?php echo trans('message.please_enter_valid_email'); ?>",
            },
            name: {
                required: "<?php echo trans('message.please_enter_name'); ?>",
            },
            gender: {
                required: "<?php echo trans('message.please_select_gender'); ?>",
            },
            user_type: {
                required: "<?php echo trans('message.please_select_user_type'); ?>",
            },
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
        },
        invalidHandler: function(e, validator){
            if(validator.errorList.length)
                $('.nav-tabs a[href="#' + $(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
        }
    });
    //check all check box view permission of all menu
    $("#chkAll").click(function() {
        $(".chkPermissionAll").prop('checked', $(this).prop('checked'));        
        //
        if (!$(this).prop('checked')) {
            $("#chkView").prop('checked', false);
            /*$("#chkView").attr('disabled', true);*/
            
            $("#chkAdd").prop('checked', false);
            $("#chkAdd").attr('disabled', true);
            
            $("#chkEdit").prop('checked', false);
            $("#chkEdit").attr('disabled', true)
            
            $("#chkDelete").prop('checked', false);
            $("#chkDelete").attr('disabled', true);
            
            $("#chkSearch").prop('checked', false);
            $("#chkSearch").attr('disabled', true);
            
            $("#chkEmail").prop('checked', false);
            $("#chkEmail").attr('disabled', true);
            
            //this is used for uniform library's checkbox update
            //
            $(document).find('.main-tr').find(".chkPermissionView").attr('disabled', false);
            $(document).find('.main-tr').find(".chkPermissionView").prop('checked', false);
            
            $(document).find('.main-tr').find(".chkPermissionAdd").attr('disabled', true);
            $(document).find('.main-tr').find(".chkPermissionAdd").prop('checked', false);
            
            $(document).find('.main-tr').find(".chkPermissionEdit").attr('disabled', true)
            $(document).find('.main-tr').find(".chkPermissionEdit").prop('checked', false);
            
            $(document).find('.main-tr').find(".chkPermissionDelete").attr('disabled', true);
            $(document).find('.main-tr').find(".chkPermissionDelete").prop('checked', false);
            
            $(document).find('.main-tr').find(".chkPermissionSearch").attr('disabled', true);
            $(document).find('.main-tr').find(".chkPermissionSearch").prop('checked', false);
            
            $(document).find('.main-tr').find(".chkPermissionEmail").attr('disabled', true);
            $(document).find('.main-tr').find(".chkPermissionEmail").prop('checked', false);
            
            
        } else {
            $("#chkView").prop('checked', true);
            
            $("#chkAdd").attr('disabled', false);
            $("#chkAdd").prop('checked', true);
            
            $("#chkEdit").attr('disabled', false)
            $("#chkEdit").prop('checked', true);
            
            $("#chkDelete").attr('disabled', false);
            $("#chkDelete").prop('checked', true);
            
            $("#chkSearch").attr('disabled', false);
            $("#chkSearch").prop('checked', true);
            
            $("#chkEmail").attr('disabled', false);
            $("#chkEmail").prop('checked', true);
            
            $(document).find('.main-tr').find(".chkPermissionView").attr('disabled', false);
            $(document).find('.main-tr').find(".chkPermissionView").prop('checked', true);
            
            $(document).find('.main-tr').find(".chkPermissionAdd").attr('disabled', false);
            $(document).find('.main-tr').find(".chkPermissionAdd").prop('checked', true);
            
            $(document).find('.main-tr').find(".chkPermissionEdit").attr('disabled', false)
            $(document).find('.main-tr').find(".chkPermissionEdit").prop('checked', true);
            
            $(document).find('.main-tr').find(".chkPermissionDelete").attr('disabled', false);
            $(document).find('.main-tr').find(".chkPermissionDelete").prop('checked', true);
            
            $(document).find('.main-tr').find(".chkPermissionSearch").attr('disabled', false);
            $(document).find('.main-tr').find(".chkPermissionSearch").prop('checked', true);
            
            $(document).find('.main-tr').find(".chkPermissionEmail").attr('disabled', false);
            $(document).find('.main-tr').find(".chkPermissionEmail").prop('checked', true);
            
            
        }
    });
    $("#chkView").click(function() {
        $(".chkPermissionView").prop('checked', $(this).prop('checked')).change();
        
        if (!$(this).prop('checked')) {
            $("#chkAdd").prop('checked', false);
            $("#chkAdd").attr('disabled', true);
            
            $("#chkEdit").prop('checked', false);
            $("#chkEdit").attr('disabled', true)
            
            $("#chkDelete").prop('checked', false);
            $("#chkDelete").attr('disabled', true);
            
            $("#chkSearch").prop('checked', false);
            $("#chkSearch").attr('disabled', true);
            
            $("#chkEmail").prop('checked', false);
            $("#chkEmail").attr('disabled', true);
            
            //this is used for uniform library's checkbox update
            //
        } else {
            if(user_is_main == 1) {
                $("#chkAdd").attr('disabled', true);
                
            }
            else {
                $("#chkAdd").attr('disabled', false);
                
            }
            $("#chkEdit").attr('disabled', false)
            
            $("#chkDelete").attr('disabled', false);
            
            $("#chkSearch").attr('disabled', false);
            
            $("#chkEmail").attr('disabled', false);
            
        }
        changeAllPermissionCheckbox();
    });
    //check all check box add permission of all menu
    $("#chkAdd").click(function() {
        $(".chkPermissionAdd").not(":disabled").prop('checked', $(this).prop('checked'));
        
        //this is used for uniform library's checkbox update
        //
        changeAllPermissionCheckbox();
    });
    //check all check box edit permission of all menu
    $("#chkEdit").click(function() {
        $(".chkPermissionEdit").not(":disabled").prop('checked', $(this).prop('checked'));
        
        //this is used for uniform library's checkbox update
        //
        changeAllPermissionCheckbox();
    });
    //check all check box delete permission of all menu
    $("#chkDelete").click(function() {
        $(".chkPermissionDelete").not(":disabled").prop('checked', $(this).prop('checked'));
        
        //this is used for uniform library's checkbox update
        //
        changeAllPermissionCheckbox();
    });
    //check all check box email permission of all menu
    $("#chkEmail").click(function() {
        $(".chkPermissionEmail").not(":disabled").prop('checked', $(this).prop('checked'));
        
        //this is used for uniform library's checkbox update
        //
        changeAllPermissionCheckbox();
    });
    //check if all checkbox check or not, if 
    $(".chkPermissionAll").change(function() {
        if ($(this).is(":checked")) {
            $(this).closest('tr').find(".chkPermissionAdd").attr('disabled', false);
            $(this).closest('tr').find(".chkPermissionEdit").attr('disabled', false);
            $(this).closest('tr').find(".chkPermissionDelete").attr('disabled', false);
            $(this).closest('tr').find(".chkPermissionSearch").attr('disabled', false);
            $(this).closest('tr').find(".chkPermissionEmail").attr('disabled', false);
            $(this).closest('tr').find(".chkPermissionView").prop('checked', this.checked);
            $(this).closest('tr').find(".chkPermissionAdd").prop('checked', this.checked);
            $(this).closest('tr').find(".chkPermissionEdit").prop('checked', this.checked);
            $(this).closest('tr').find(".chkPermissionDelete").prop('checked', this.checked);
            $(this).closest('tr').find(".chkPermissionEmail").prop('checked', this.checked);
            //this is used for uniform library's checkbox update
            
        } else {
            // VIEW CHECKBOX UNCHECKED THEN DISABALE THE ALL CHECKBOX AND UNCHECK ALL CHECKBOX
            $(this).closest('tr').find(".chkPermissionView").prop('checked', this.checked);
            $(this).closest('tr').find(".chkPermissionAdd").prop('checked', this.checked);
            $(this).closest('tr').find(".chkPermissionEdit").prop('checked', this.checked);
            $(this).closest('tr').find(".chkPermissionDelete").prop('checked', this.checked);
            $(this).closest('tr').find(".chkPermissionEmail").prop('checked', this.checked);
            $(this).closest('tr').find(".chkPermissionAdd").attr('disabled', true);
            $(this).closest('tr').find(".chkPermissionEdit").attr('disabled', true);
            $(this).closest('tr').find(".chkPermissionDelete").attr('disabled', true);
            $(this).closest('tr').find(".chkPermissionEmail").attr('disabled', true);
            //this is used for uniform library's checkbox update
            
        }
        
        

        changeAllPermissionCheckbox($(this));
    });
    //check if view checkbox check or not, if 
    $(".chkPermissionView").change(function() {
        if ($(this).is(":checked")) {
            if ($('.chkPermissionView:checked').length == $('.chkPermissionView').length) {
                $('#chkView').prop('checked', true);
                if(user_is_main == 1) {
                    $('#chkAdd').prop('checked', true);
                }
            } else {
                $('#chkView').prop('checked', false);
                if(user_is_main == 1) {
                    $('#chkAdd').prop('checked', false);
                }
            }
            if(user_is_main == 1) {
                $(this).closest('tr').find(".chkPermissionAdd").prop('checked', this.checked);
                $(this).closest('tr').find(".chkPermissionEdit").prop('checked', this.checked);
            }
            $(this).closest('tr').find(".chkPermissionAdd").attr('disabled', false);
            $(this).closest('tr').find(".chkPermissionEdit").attr('disabled', false);
            $(this).closest('tr').find(".chkPermissionDelete").attr('disabled', false);
            $(this).closest('tr').find(".chkPermissionSearch").attr('disabled', false);
            $(this).closest('tr').find(".chkPermissionEmail").attr('disabled', false);
            //this is used for uniform library's checkbox update
            
        } else {
            // VIEW CHECKBOX UNCHECKED THEN DISABALE THE ALL CHECKBOX AND UNCHECK ALL CHECKBOX
            
            $('#chkView').prop('checked', false);
            $('#chkAdd').prop('checked', false);
            $('#chkDelete').prop('checked', false);
            $('#chkEdit').prop('checked', false);

            $(this).closest('tr').find(".chkPermissionAdd").prop('checked', this.checked);
            $(this).closest('tr').find(".chkPermissionEdit").prop('checked', this.checked);
            $(this).closest('tr').find(".chkPermissionDelete").prop('checked', this.checked);
            $(this).closest('tr').find(".chkPermissionEmail").prop('checked', this.checked);
            $(this).closest('tr').find(".chkPermissionAdd").attr('disabled', true);
            $(this).closest('tr').find(".chkPermissionEdit").attr('disabled', true);
            $(this).closest('tr').find(".chkPermissionDelete").attr('disabled', true);
            $(this).closest('tr').find(".chkPermissionEmail").attr('disabled', true);
            //this is used for uniform library's checkbox update
            
        }
        //chkSkuTransferModule()
        changeAllPermissionCheckbox($(this));
    });

    //used for if all add checkbox checked then top chkAdd checkbox checked
    $(".chkPermissionAdd").change(function() {
        var chkPermissionAddCheckbox = $("input[type='checkbox'].chkPermissionAdd");
        if (chkPermissionAddCheckbox.length == chkPermissionAddCheckbox.filter(":checked").length) {
            $("#chkAdd").attr('disabled', false);
            $('#chkAdd').prop('checked', true);
            
        } else {
            $('#chkAdd').prop('checked', false);
            
        }
        changeAllPermissionCheckbox($(this));
    });

    //used for if all edit checkbox checked then top chkEdit checkbox checked
    $(".chkPermissionEdit").change(function() {
        var chkPermissionEditCheckbox = $("input[type='checkbox'].chkPermissionEdit");
        if (chkPermissionEditCheckbox.length == chkPermissionEditCheckbox.filter(":checked").length) {
            $("#chkEdit").attr('disabled', false);
            $('#chkEdit').prop('checked', true);
            
        } else {
            $('#chkEdit').prop('checked', false);
            
        }
        changeAllPermissionCheckbox($(this));
    });
    $(".chkPermissionDelete").change(function() {
        var chkPermissionDeleteCheckbox = $("input[type='checkbox'].chkPermissionDelete");
        if (chkPermissionDeleteCheckbox.length == chkPermissionDeleteCheckbox.filter(":checked").length) {
            $("#chkDelete").attr('disabled', false);
            $('#chkDelete').prop('checked', true);
            
        } else {
            $('#chkDelete').prop('checked', false);
            
        }
        changeAllPermissionCheckbox($(this));
    });
    $(".chkPermissionEmail").change(function() {
        var chkPermissionEmailCheckbox = $("input[type='checkbox'].chkPermissionEmail");
        if (chkPermissionEmailCheckbox.length == chkPermissionEmailCheckbox.filter(":checked").length) {
            $("#chkEmail").attr('disabled', false);
            $('#chkEmail').prop('checked', true);
            
        } else {
            $('#chkEmail').prop('checked', false);
            
        }
        changeAllPermissionCheckbox($(this));
    });
    //used for if all view checkbox checked then top checkAll checkbox checked
    var chkPermissionAllCheckbox = $("input[type='checkbox'].chkPermissionAll");
    if (chkPermissionAllCheckbox.length == chkPermissionAllCheckbox.filter(":checked").length) {
        $('#chkAll').prop('checked', true);
        //
    }
    //used for if all view checkbox checked then top checkview checkbox checked
    var chkPermissionViewCheckbox = $("input[type='checkbox'].chkPermissionView");
    if (chkPermissionViewCheckbox.length == chkPermissionViewCheckbox.filter(":checked").length) {
        $('#chkView').prop('checked', true);
        //
    }
    //used for if all add checkbox checked then top chkAdd checkbox checked
    var chkPermissionAddCheckbox = $("input[type='checkbox'].chkPermissionAdd");
    if (chkPermissionAddCheckbox.length == chkPermissionAddCheckbox.filter(":checked").length) {
        $('#chkAdd').prop('checked', true);
        //
    } else {
        // $("#chkAdd").attr('disabled', true);
        
    }
    //used for if all edit checkbox checked then top chkEdit checkbox checked
    var chkPermissionEditCheckbox = $("input[type='checkbox'].chkPermissionEdit");
    if (chkPermissionEditCheckbox.length == chkPermissionEditCheckbox.filter(":checked").length) {
        $('#chkEdit').prop('checked', true);
        //
    } else {
        $("#chkEdit").attr('disabled', true)
        
    }
    //used for if all delete checkbox checked then top chkDelete checkbox checked
    var chkPermissionDeleteCheckbox = $("input[type='checkbox'].chkPermissionDelete");
    if (chkPermissionDeleteCheckbox.length == chkPermissionDeleteCheckbox.filter(":checked").length) {
        $('#chkDelete').prop('checked', true);
        //
    } else {
        $("#chkDelete").attr('disabled', true);
        
    }
    //used for if all email checkbox checked then top chkDelete checkbox checked
    var chkPermissionEmailCheckbox = $("input[type='checkbox'].chkPermissionEmail");
    if (chkPermissionEmailCheckbox.length == chkPermissionEmailCheckbox.filter(":checked").length) {
        $('#chkEmail').prop('checked', true);
        //
    } else {
        $("#chkEmail").attr('disabled', true);
        
    }
    // Styled checkboxes, radios
    // $('.styled').uniform({
    //     wrapperClass: 'border-primary text-primary'
    // });
})
function changeAllPermissionCheckbox(mythis = "") {
    if (mythis == "") {
        $(document).find('.chkPermissionAll').prop('checked', false);
        if ($('.permission_chk_box:not(.chkPermissionAll):checked').length == $('.permission_chk_box:not(.chkPermissionAll)').length) {
            $('.chkPermissionAll').prop('checked', true);
        } else {
            $('.chkPermissionAll').prop('checked', false);
        }
    } else {
        if ($(mythis).closest('tr').find('.permission_chk_box:not(.chkPermissionAll):checked').length == $(mythis).closest('tr').find('.permission_chk_box:not(.chkPermissionAll)').length) {
            $(mythis).closest('tr').find('.chkPermissionAll').prop('checked', true);
        } else {
            $(mythis).closest('tr').find('.chkPermissionAll').prop('checked', false);
        }
    }
    if ($(document).find('.chkPermissionAll:checked').length == $(document).find('.chkPermissionAll').length) {
        $("#chkAll").prop('checked', true);
    } else {
        $("#chkAll").prop('checked', false);
    }
}