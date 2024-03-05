<?php
    header('Content-type: application/javascript');
    /* no store cache in browser */
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache"); 
?>
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
    var startDate = endDate = usertype = status = '';
    $('#example thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#example thead');
    oTable = $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':not(.notexport)'
                },
            },{
                extend: 'excel',
                exportOptions: {
                    columns: ':not(.notexport)'
                }
            },{
                extend: 'pdf',
                exportOptions: {
                    columns: ':not(.notexport)'
                }
            },
            'pageLength',
        ],
        orderCellsTop: true,
        language: {
            searchPlaceholder: "Search records"
        },
        // Hide pagination dropdown
        // Set pagination limit
        pageLength: 25,
        aaSorting: [1,'asc'],
        columnDefs: [
            // Disable sorting of first and last column 

            // Make text center of first,last and second last column
            //{ className: '', targets: [0,-1,-2] },

            // used for set the data-title attribute 
            {
                'targets': 0,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('data-title', "" ); 
                },
            },
            {
                'targets': 1,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('data-title', "<?php echo trans('message.first_name'); ?>" ); 
                },
            },
            {
                'targets': 2,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('data-title', "<?php echo trans('message.last_name'); ?>" ); 
                }
            },
            {
                'targets': 3,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('data-title', "<?php echo trans('message.email'); ?>" ); 
                }
            },
            {
                'targets': 4,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('data-title', "<?php echo trans('message.birth_date'); ?>" ); 
                }
            },
            {
                'targets': 5,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('data-title', "<?php echo trans('message.country'); ?>" ); 
                }
            },
            {
                'targets': 6,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('data-title', "<?php echo trans('message.type'); ?>" ); 
                },
                'width': '100px',
            },
            {
                'targets': 7,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('data-title', "<?php echo trans('message.status'); ?>" ); 
                },
            },
            {
                'targets': 8,
                orderable: false,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('data-title', "<?php echo trans('message.action'); ?>" ); 
                },
            },
        ],
        // Initially sort by 3rd column ascending
        // To Display processing text
        processing: true,
        serverSide: true,
        ajax:{
            beforeSend: function() {
                    // setting a timeout
            },
            url :"<?php echo route('getData');?>", // json datasource
            type: "post",  // method  , by default get
            data: function(d) {

                return $.extend({}, d, {
                    _token: $('input[name=_token]').val(),
                    startDate: startDate,
                    endDate: endDate,
                    usertype: usertype,
                    aistatus: status,
                });
            },
            complete: function() {
                $('#datepicker').daterangepicker({
                    //minDate: '01/01/2014',
                    //maxDate: '12/31/2016',
                    autoUpdateInput: false,
                    // maxDate: moment(),
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last Week': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
                        'This Week': [moment().startOf('week'), moment().endOf('week')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Year To Date': [moment().startOf('year'), moment()],
                        'All Time': [moment().subtract(10, 'year').startOf('year'), moment()],

                    },
                    opens: 'left',
                    applyClass: 'btn-small bg-slate',
                    cancelClass: 'btn-small btn-default',
                    locale: {
                        scancelLabel: 'Clear'
                    }
                });
                $('#datepicker').on('apply.daterangepicker', function(ev, picker) {
                    startDate = picker.startDate.format('YYYY-MM-DD');
                    endDate = picker.endDate.format('YYYY-MM-DD');
                    var myvaldate = picker.startDate.format('MMM D, YYYY') + '-' + picker.endDate.format('MMM D, YYYY');
                    $('#datepicker').val(myvaldate);
                    $('#datepicker').attr({'title' : myvaldate});
                    oTable.draw();
                    
                });
                $('#datepicker').on('cancel.daterangepicker', function(ev, picker) {
                    $('#datepicker').data('daterangepicker').setStartDate();
                    $('#datepicker').data('daterangepicker').setEndDate();
                    startDate = '';
                    endDate = '';
                    $('#datepicker').val('');
                    $('#date_field_filter').val('');
                    $('#datepicker').removeAttr('title');
                    oTable.draw();
                });
                
                $('.select2').select2();
                $('.select2UserType').select2({
                    placeholder: "User Type",
                    allowClear: true
                });
            },                  
            error: function(jqXHR, textStatus, errorThrown) {
            }
        },
        // set the suto width false
        autoWidth: false,
        
        //set header-footer div before content so we can make responsive better 
        //dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        initComplete: function () {
            var api = this.api();
            // For each column
            var columnCount = api.columns().eq(0).length;
            api.columns().eq(0).each(function (colIdx) {
                    // Set the header cell to contain the input element
                    if(colIdx == 0) {
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();
                        $(cell).html('<div class="text-center clearfix"><div class="icheck-primary d-inline"><input type="checkbox" id="main_checkbox" name="main_checkbox" value="1"><label for="main_checkbox"></label></div></div>');
                    } else if(colIdx == 4) {
                        
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();
                        $(cell).html('<input type="text" class="w-100 form-control" id="datepicker" readonly="true" placeholder="' + title + '" />');
                    } else if(colIdx == 6) {
                        
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();
                        dropdownvalue  = "<select class='usertype w-100 form-control select2 select2UserType' name='type[]' multiple='multiple' ><option value='1'>UserType1</option><option value='2'>UserType2</option><option value='3'>UserType3</option></select>"
                        $(cell).html(dropdownvalue);
                    }  else if(colIdx == 7) {
                        
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();
                        dropdownvalue  = "<div class='custom-select-class'><select class='status w-100 form-control select2 ' name='status'><option value='0'>Status</option><option value='active'>Active</option><option value='inactive'>Inactive</option></select></div>"
                        $(cell).html(dropdownvalue);
                        $(cell).css({width:'100px'});
                    } else if((columnCount-1) == colIdx) {
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        $(cell).html('');
                    } else {
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();
                        $(cell).html('<input type="text" class="w-100 form-control" placeholder="' + title + '" />');
    
                        // On every keypress in this input
                        $(
                            'input',
                            $('.filters th').eq($(api.column(colIdx).header()).index())
                        )
                        .off('keyup change')
                        .on('keyup change', function (e) {
                            e.stopPropagation();

                            // Get the search value
                            $(this).attr('title', $(this).val());
                            var regexr = '({search})'; //$(this).parents('th').find('select').val();

                            var cursorPosition = this.selectionStart;
                            // Search the column for that value
                            api
                                .column(colIdx)
                                .search(
                                    this.value != ''
                                        ? this.value
                                        : '',
                                    this.value != '',
                                    this.value == ''
                                )
                                .draw();

                            // $(this)
                            //     .focus()[0]
                            //     .setSelectionRange(cursorPosition, cursorPosition);
                        });
                    }
                        
                });
        },

        oLanguage: {
            sEmptyTable: "<?php echo trans('message.no_record_found'); ?>",
            sZeroRecords: "<?php echo trans('message.no_record_found'); ?>",
            sSearchPlaceholder: "",
        },
        "drawCallback": function( settings ) {
            $(document).find("#main_checkbox").prop('checked',false);
            manageAuthorCheckbox('main',false);
        },
        //scroll to top when redraw the data table          
        fnDrawCallback: function( oSettings ) {
            //add toltip to serach filed
        }  
    });
    $(document).on("change", '.usertype', function(e) { 
        usertype = $(this).val();
        oTable.draw();
        //Do stuff
    });
    $(document).on('change','.status',function() {
        status = $(this).val();
        oTable.draw();
    })
    function snake_case_string(str) {
        return str && str.match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g)
            .map(s => s.toLowerCase())
            .join('_');
    }
    $( "tr" ).not('.filters').find('th').each(function( index ) {
        if(index > 0){
            var mytext = $( this ).text();
            var myval = snake_case_string(mytext);
            var optionList = '<option data-column="'+index+'" value="'+index+'">'+mytext+'</option>';
            
            // $(document).find('#chk_container').append(appendHtml);
            $(document).find('#chk_container_select').append(optionList);
        }
    });
    
    var hideOption = [];
    $('#chk_container_select').multiselect({
        search:true,
        texts: {
            placeholder: '<?php echo trans("message.hide_show_column"); ?>'
        },
        onOptionClick: function( element, option ) {
            // too many selected, deselect this option
            var thisVals = $(element).val();
            var diffShow = $(hideOption).not(thisVals).get();
            var diffHide = $(thisVals).not(hideOption).get();

            hideOption = thisVals;
            if(diffShow[0] >= 0) {
                var column = oTable.column( diffShow[0] );
                column.visible( true );
            }
            if(diffHide[0] >= 0) {
                var column = oTable.column( diffHide[0] );
                column.visible( false );
            }
        }
    });
    $(document).on('change','#main_checkbox',function(e) {
        manageAuthorCheckbox('main',this.checked)
    });
    $(document).on('change','.author_item',function(e) {
        manageAuthorCheckbox()
    });
    $(".custom-action").click(function(event){
        event.preventDefault();
        var actionType = $(this).attr('data-action');
        var newArr  = getAllCheckedValue();
        if(newArr.length > 0) {
            $.ajax({
				url: "<?php echo route('custom-action'); ?>",
				type: "POST",
				data: {_token : $('input[name=_token]').val(),authorIds:newArr,action:actionType},
				dataType: "json",
				success: function(data){
				},complete: function (data) {
                    var finalJSon = data.responseJSON;
    	            toastr[finalJSon.status](finalJSon.msg)
                    oTable.draw();
				}
			});
        }
    });

    //File import code
    $('#import_author_anchor').click(function(){ $('#import_author_file').trigger('click'); });
    $(document).on('change','#import_author_file',function(e) {

        var formData = new FormData();

		formData.append('_token', $('input[name=_token]').val());
        formData.append('import_author_file', this.files[0]);
        
	    $.ajax({
	        url: "<?php echo route('import-author-csv'); ?>",
	        type: "post",
	        dataType: 'json',
	        processData: false, // important
	        contentType: false, // important
	        data: formData,
	        success: function(returnData) {
	            if(returnData.status == "success") {
	            	alertify.success(returnData.msg);
					$("#import_author_file").val('');
	            } else {
	            	alertify.error(returnData.msg);
	            	$("#import_author_file").val('');
	            }
	        },
            error: function (xhr, ajaxOptions, thrownError) {
	            alertify.error("<?php echo trans('message.something_went_wrong');?>");
	            $("#import_author_file").val('');
	        }
	    });
    })
});

function getAllCheckedValue() {
    var authorIdList = $(".author_item:checked").map(function(){
        return $(this).val();
    }).get(); // <----
    console.log(authorIdList);
    return authorIdList;
}
function manageAuthorCheckbox(from = 'item',isMainCheck=true) {
    var isDisable = false;
    if(from == 'main'){
        $(document).find('.author_item').prop('checked',isMainCheck);
         isDisable = isMainCheck;
    } else {
        var mainCheck = false;
        if ($(document).find('.author_item:checked').length == $(document).find('.author_item').length) {
            mainCheck = true;
        } 
        $(document).find('#main_checkbox').prop('checked',mainCheck);
        if ($(document).find('.author_item:checked').length > 0) {
            isDisable = true;
        }
    }
    if(!isDisable) {
        $(document).find('.custom-action').css({'pointer-events':'none','opacity':'0.5'})
        $(document).find('.custom-action').closest('div').css({'cursor':'not-allowed'});
    } else {
        $(document).find('.custom-action').css({'pointer-events':'','opacity':''})
        $(document).find('.custom-action').closest('div').css({'cursor':''});
    }
}