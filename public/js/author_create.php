<?php
    header('Content-type: application/javascript');
    /* no store cache in browser */
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache"); 
?>
Dropzone.autoDiscover = false;

$(document).ready(function () {
    $.validator.addMethod("emailExt", function (value, element, param) {
        return value.match(/^[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/);
    }, 'Your E-mail is wrong');

    $("#author_form").validate({
        errorElement: 'div',
        rules: {
            email: {
                required: true,
                emailExt: true,
                remote: {
                    url: '<?php echo url('author_email_check'); ?>',
                    type: "post",
                    data: {
                        email: function() { 
                            return $(':input[name="email"]').val();
                        },
                        id: function() {
                            return ($("#hdauthorId").val() > 0 ? $("#hdauthorId").val() : 0);
                        },
                        _token: $('input[name=_token]').val(),
                    
                    }
                },
            },
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            type: {
                required: true,
            },
            country: {
                required: true,
            },
            birthdate: {
                required: true,
            },
        },
        messages: {
            email: {
                required: "<?php echo trans('message.please_enter_email'); ?>",
                emailExt: "<?php echo trans('message.please_enter_valid_email'); ?>",
                remote: "<?php echo trans('message.this_email_address_already_used'); ?>"
            },
            first_name: {
                required: "<?php echo trans('message.please_enter_first_name'); ?>",
            },
            last_name: {
                required: "<?php echo trans('message.please_enter_last_name'); ?>",
            },
            type: {
                required: "<?php echo trans('message.please_select_user_type'); ?>",
            },
            country: {
                required: "<?php echo trans('message.please_select_country'); ?>",
            },
            birthdate:{
                required: "<?php echo trans('message.please_select_birthdate'); ?>"
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
    $('#birthdate').daterangepicker({
        autoUpdateInput: false,
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1970,
        maxYear: parseInt(moment().format('YYYY'),10),
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
    $('#birthdate').on('apply.daterangepicker', function(ev, picker) {
        var myvaldate = picker.startDate.format('YYYY-MM-DD');
        $('#birthdate').val(myvaldate);
    });
    Dropzone.autoDiscover = false;
    file_up_names = [];
    var myDropzone = new Dropzone("#myDropzone", {
        url: '<?php echo route('authorFileUpload'); ?>',
        paramName: "attached_file",
        maxFilesize: 20,
        //maxFiles: 10,
        //acceptedFiles: "application/pdf",
        thumbnailWidth:50,
        thumbnailHeight:50,
        addRemoveLinks: true,
        previewsContainer: ".dropzone-previews",
        previewTemplate: document.getElementById('preview-template').innerHTML,
        dictRemoveFile: '<i class="fas fa-times-circle"></i>',
        init: function() {
            //dzClosure = this; // Makes sure that 'this' is understood inside the functions below.

            // for Dropzone to process the queue (instead of default form behavior):
            // document.getElementById("submit-all").addEventListener("click", function(e) {
            //     // Make sure that the form isn't actually being sent.
            //     e.preventDefault();
            //     e.stopPropagation();
            //     dzClosure.processQueue();
            // });

            //send all the form data along with the files:
            this.on("sending", function(data, xhr, formData) {
                formData.append("_token", $('input[name=_token]').val());
                formData.append("hdauthorId", $("#hdauthorId").length > 0 ? $("#hdauthorId").length : '');
                formData.append("authorId", $("#hdauthorId").length > 0 ? $("#hdauthorId").val() : '');
                
            });

            this.on("success", function (file, responseText) {
                var temp = JSON.parse(responseText);

                if(temp.id != "") {
                    var currentUnixTime2 = temp.id;
                } else {
                    var currentUnixTime2 = new Date().getTime();
                }
                file.previewElement.id = currentUnixTime2;
                if(temp.status == true){
                    var fileArr = [];
                    fileArr[0] = temp.response[0];
                    fileArr[1] = currentUnixTime2;
                    file_up_names.push(fileArr);
                    $('<input>').attr({
                                        type: 'hidden',
                                        id: 'uploaded_files',
                                        name: 'attached_file[]',
                                        value: responseText,
                                        class: 'uploaded_files file_'+currentUnixTime2
                                    }).appendTo('form.form-validate-jquery');

                    $('#'+currentUnixTime2).find('.dz-remove').attr('href', 'javascript:void(0);');
                    if($("#hdauthorId").length > 0) {
                        $('#'+currentUnixTime2).find('.dz-remove').remove();
                        removeIcon = '<i class="fas fa-times-circle removeFile" data-id="'+currentUnixTime2+'" title="close" style="background-color: #e9e6e6; border-radius: 2px; padding: 1px 5px; text-align: center; color: #000; cursor: pointer;"></i>';
                        $("#"+currentUnixTime2).append(removeIcon);
                        $("#"+currentUnixTime2).attr('data-att-id', currentUnixTime2);
                        $("#"+currentUnixTime2).attr('id','fileparent'+currentUnixTime2);
                    }
                }
            });
            
            this.on("error", function(file, message) { 
                //console.log(file);
                //var message = 'Are you sure you want to delete?';
                alertify.alert('File Upload Error', message, function() {}).set({transition:'fade'});
                file.previewElement.remove();
            });
        },
        removedfile: function(file) {
            var deleteTemp = JSON.parse(file.xhr.response);
            //console.log(file, file.xhr.response, deleteTemp.file,'delete');
            /*x = confirm('Do you want to delete?');
            if(!x)  return false;*/
            var message = 'Are you sure you want to delete?';
                
                alertify.confirm( 'Delete Item', message , function (responce) {
                    for(var i=0;i<file_up_names.length;++i){
                            //console.log(file_up_names[i][0],file_up_names[i][1], 'asasd');
                        if(file_up_names[i][0]==deleteTemp.response[0]) {
                            var uploadFileClass = file_up_names[i][1];
                            //console.log(uploadFileClass);
                            $.post('<?php echo route('authorFileUnlink'); ?>', 
                                {
                                    file_name:file_up_names[i][0],
                                    _token: $('input[name=_token]').val(),
                                    hdauthorId: $("#hdauthorId").length > 0 ? $("#hdauthorId").length : ''
                                },
                                function(data, status){
                                    file.previewElement.remove();
                                    $('.file_'+uploadFileClass).remove();
                                    //alert('file deleted');
                                }
                            );
                        }
                    }//End for
            }, function() {}).set({transition:'fade'}); //end alertify
        }
    });
    $(document).on("click",".removeFile", function(e){
        
        var id = $(this).attr('data-id');
        var message = "<?php echo trans('message.are_you_sure_you_want_to_delete'); ?>";
            
            alertify.confirm("<?php echo trans('message.delete_author_file'); ?>", message , function (responce) {
                    if($("#fileparent"+id).length > 0){
                        var isSuccess = false;
                        var msg = "<?php echo trans('message.something_went_wrong'); ?>";
                        $.ajax({
                            url: '<?php echo route('removeAuthorFiles'); ?>',
                            type: 'post',
                            async: false,
                            dataType: "json",
                            data: {
                                _token: $('input[name=_token]').val(),
                                'fileId':id
                            },
                            success: function(result) {
                                if(result && result.status && result.status == true){
                                    isSuccess = true;
                                } else {
                                    if (result.message && result.message.msg){
                                        msg = result.message.msg;
                                    } else {
                                        msg = result.message;
                                    }
                                }
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                
                            }
                        });
                        if(isSuccess == true){
                            $("#fileparent"+id).remove();
                            alertify.success("<?php echo trans('message.file_successfully_deleted'); ?>");
                        } else {
                            alertify.error(msg);
                        }
                    }//end if
            }, function() {}).set({transition:'fade'}); //end alertify
        return false;   
    });
})