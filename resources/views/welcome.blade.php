<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Data Table Demo</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{asset('global_assets/DataTables/datatables.min.css')}}"/>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="{{asset('global_assets/DataTables/datatables.min.js')}}"></script>

        <script src="{{asset('global_assets/ui/moment/moment.min.js')}}"></script>
            <!-- <script src="{{asset('global_assets/pickers/daterangepicker.js')}}"></script> -->
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!-- Styles -->
        <style>
           
        </style>

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div id="chk_container"></div>

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Birthdate</th>
                        <th>Country</th>
                        <th>Type</th>
                        <th>Status</th>
                    </tr>
                </thead>        
                <tbody>
                </tbody>
            </table>
        </div>
        <script>
            $(document).ready(function() {
               
                var startDate = endDate = usertype = status = '';
                $('#example thead tr')
                    .clone(true)
                    .addClass('filters')
                    .appendTo('#example thead');
                oTable = $('#example').DataTable( {
                    orderCellsTop: true,
                    fixedHeader: true,
                    // Allow responsive
                    responsive: {
                        details: {
                            type: 'column',
                            target: 0
                        }
                    },
                    // Hide pagination dropdown
                    lengthChange: false,
                    // Set pagination limit
                    pageLength: 25,
                    
                    columnDefs: [
                        // Disable sorting of first and last column 
                        { orderable: false, targets: [0,-1] },
                        // Make text center of first,last and second last column
                        //{ className: '', targets: [0,-1,-2] },

                        // used for set the data-title attribute 
                        {
                            'targets': 0,
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td).attr('data-title', "First Name" ); 
                            }
                        },
                        {
                            'targets': 1,
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td).attr('data-title', "Last Name" ); 
                            }
                        },
                        {
                            'targets': 2,
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td).attr('data-title', "Email" ); 
                            }
                        },
                        {
                            'targets': 3,
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td).attr('data-title', "Birthdate" ); 
                            }
                        },
                        {
                            'targets': 4,
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td).attr('data-title', "Country" ); 
                            }
                        },
                        {
                            'targets': 5,
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td).attr('data-title', "Type" ); 
                            }
                        },
                        {
                            'targets': 6,
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td).attr('data-title', "Status" ); 
                            }
                        },
                    ],
                    // Initially sort by 3rd column ascending
                    aaSorting: [0,'asc'],
                    // To Display processing text
                    processing: true,
                    serverSide: true,
                    ajax:{
                        beforeSend: function() {
                                // setting a timeout
                                $(".datatable-html").css('opacity', '0.1');
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
                            $("#overlay").remove();
                            $(".datatable-html").css('opacity', '');
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
                                $('#datepicker').val(picker.startDate.format('MMM D, YYYY') + '-' + picker.endDate.format('MMM D, YYYY'));
                                oTable.draw();
                                
                            });
                            $('#datepicker').on('cancel.daterangepicker', function(ev, picker) {
                                $('#datepicker').data('daterangepicker').setStartDate();
                                $('#datepicker').data('daterangepicker').setEndDate();
                                startDate = '';
                                endDate = '';
                                $('#datepicker').val('');
                                $('#date_field_filter').val('');
                                oTable.draw();
                            });
                            $(document).on('change','.usertype',function() {
                                usertype = $(this).val();
                                oTable.draw();
                            })
                            $(document).on('change','.status',function() {
                                status = $(this).val();
                                oTable.draw();
                            })
                        },                  
                        error: function(jqXHR, textStatus, errorThrown) {
                        }
                    },
                    // set the suto width false
                    autoWidth: false,
                    
                    //set header-footer div before content so we can make responsive better 
                    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                    initComplete: function () {
                        var api = this.api();
                        console.log(api);
                        // For each column
                        api
                            .columns()
                            .eq(0)
                            .each(function (colIdx) {
                                // Set the header cell to contain the input element
                                if(colIdx == 3) {
                                    
                                    var cell = $('.filters th').eq(
                                        $(api.column(colIdx).header()).index()
                                    );
                                    console.log(colIdx);
                                    var title = $(cell).text();
                                    $(cell).html('<input type="text" id="datepicker" readonly="true" placeholder="' + title + '" />');
                                } else if(colIdx == 5) {
                                    
                                    var cell = $('.filters th').eq(
                                        $(api.column(colIdx).header()).index()
                                    );
                                    var title = $(cell).text();
                                    dropdownvalue  = "<select class='usertype' name='type[]' multiple='multiple'><option value='1'>UserType1</option><option value='2'>UserType2</option><option value='3'>UserType3</option></select>"
                                    $(cell).html(dropdownvalue);
                                }  else if(colIdx == 6) {
                                    
                                    var cell = $('.filters th').eq(
                                        $(api.column(colIdx).header()).index()
                                    );
                                    var title = $(cell).text();
                                    dropdownvalue  = "<select class='status' name='status'><option value='0'>Status</option><option value='active'>Active</option><option value='inactive'>InActive</option><option value='deleted'>Deleted</option></select>"
                                    $(cell).html(dropdownvalue);
                                } else {
                                    var cell = $('.filters th').eq(
                                        $(api.column(colIdx).header()).index()
                                    );
                                    console.log(colIdx);
                                    var title = $(cell).text();
                                    $(cell).html('<input type="text" placeholder="' + title + '" />');
                
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
            
                                        $(this)
                                            .focus()[0]
                                            .setSelectionRange(cursorPosition, cursorPosition);
                                    });
                                }
                                    
                            });
                    },

                    oLanguage: {
                        sEmptyTable: "No Record found",
                        sZeroRecords: "No Record found",
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
                function snake_case_string(str) {
                    return str && str.match(
        /[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g)
                        .map(s => s.toLowerCase())
                        .join('_');
                }
                $( "tr" ).not('.filters').find('th').each(function( index ) {
                    var mytext = $( this ).text();
                    var myval = snake_case_string(mytext);
                    var appendHtml = '<input type="checkbox" checked="true" class="hide_show_fields toggle-vis" data-column="'+index+'" id="'+myval+'" name="'+myval+'" value="1"><label for="'+myval+'" class="" >'+mytext+'</label>';
                    $(document).find('#chk_container').append(appendHtml);
                });
                $('.toggle-vis').on( 'change', function (e) {
                    e.preventDefault();
                    
                    // Get the column API object
                    var column = oTable.column( $(this).attr('data-column') );
                    if($(this).prop('checked')==true) {
                        column.visible( true );
                    } else {
                        column.visible( false );
                    }
            
                    // Toggle the visibility
                } );
            } );
        </script>
    </body>
</html>
