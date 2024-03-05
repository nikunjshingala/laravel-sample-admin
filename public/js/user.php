<?php
    header('Content-type: application/javascript');
    /* no store cache in browser */
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache"); 
?>
$(document).ready(function() {
    
    oTable = $('#example').DataTable( {
        orderCellsTop: true,
        language: {
            searchPlaceholder: "Search records"
        },
        // Allow responsive
        responsive: {
            details: {
                type: 'column',
                target: 0
            }
        },
        // Hide pagination dropdown
        // Set pagination limit
        pageLength: 25,
        
        columnDefs: [
            // Disable sorting of first and last column 

            // Make text center of first,last and second last column
            //{ className: '', targets: [0,-1,-2] },

            // used for set the data-title attribute 
            {
                'targets': 0,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('data-title', "<?php echo trans('message.name'); ?>" ); 
                },
            },
            {
                'targets': 1,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('data-title', "<?php echo trans('message.email'); ?>" ); 
                }
            },
            {
                'targets': 2,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('data-title', "<?php echo trans('message.gender'); ?>" ); 
                }
            },
            {
                'targets': 3,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('data-title', "<?php echo trans('message.type'); ?>" ); 
                },
                'width': '100px',
            },
            {
                'targets': 4,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('data-title', "<?php echo trans('message.status'); ?>" ); 
                },
            },
            {
                'targets': 5,
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
            url :"<?php echo route('getUserData');?>", // json datasource
            type: "post",  // method  , by default get
            data: function(d) {

                return $.extend({}, d, {
                    _token: $('input[name=_token]').val(),
                });
            },
            complete: function() {
            },                  
            error: function(jqXHR, textStatus, errorThrown) {
            }
        },
        // set the suto width false
        autoWidth: false,
        
        //set header-footer div before content so we can make responsive better 
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',

        oLanguage: {
            sEmptyTable: "<?php echo trans('message.no_record_found'); ?>",
            sZeroRecords: "<?php echo trans('message.no_record_found'); ?>",
            sSearchPlaceholder: "",
        },
        "drawCallback": function( settings ) {
            // Hide cancel delete container
            $('.cancel_delete').trigger('click');

        },
        //scroll to top when redraw the data table          
        fnDrawCallback: function( oSettings ) {
            //add toltip to serach filed
            $(window).scrollTop(0);
        }  
    });

    $(document).on('click','.reset-password',function(e) {
        var userId = $(this).data('id');
        $('.common-loader').show();
        $.ajax({
            url: '<?php echo route('userrestPasswordFromUser'); ?>',
            type: 'post',
            dataType:'json',
            data: {
                _token: $('input[name=_token]').val(),
                'userId':userId
            },
            complete: function(result) {
                result = result.responseJSON;
                if(result && result.status){
                    toastr[result.status](result.msg)
                } else {
                    toastr['error'](result.msg)
                }
                $('.common-loader').hide();

            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr['error']("<?php echo trans('message.something_went_wrong'); ?>")
            }
        });
    });
} );