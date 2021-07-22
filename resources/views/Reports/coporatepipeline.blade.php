@extends('layouts.elite',['notificationCount'=>0 ] )
@section('headtitle')
<h3 class="text-blue bold">Coporate pipeline report </h3>
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Filters</h4>

  
            {{ Form::open(array('route' => 'pipelinefilter', 'name' => 'salesrequestfilter','id'=>'salesrequestfilter','class'=>'') ) }}
            <div class="form-group mt-5 row">
                <label for="example-text-input" class="col-2 col-form-label">Date period</label>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="startDate" name='startDate'>
                </div>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="endDate" name='endDate'>
                </div>
            </div>
            <div class="form-group row ui-widget">
                <label for="customer_name" class="col-2 col-form-label">Customer</label>
                <div class="col-10">
                    <input  class='form-control' name='search' id="customer_name">
                    <input type='hidden' name='customerId' id='customerId' value=0 />
                    
                </div>

            </div>

            
            
            <button type="submit" class="btn btn-success mr-2">Generate report</button>
            <button type="button" class="btn btn-dark" id='filterCancel' redirectUrl='{{ route('corporatepipelinereport')}}'>Cancel</button>

       {{ Form::close() }}
    </div>
</div>


@isset($pendingwithtechDetails)
<div class=" card">
    <div class="card-body">
        
 <a href="{{ route('pipelineexport')}}"><button type="button" class="btn btn-info d-none d-lg-block m-l-15 pull-right"><i class="mdi mdi-file-export"></i> Export</button></a>
        <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item dpip-pipeline-report"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-table"></i></span> <span class="hidden-xs-down">Pending with tech</span></a> </li>
                                    <li class="nav-item dpip-issuance-report" > <a class="nav-link" data-toggle="tab" href="#profile" role="tab" ><span class="hidden-sm-up"><i class="mdi mdi-table"></i></span> <span class="hidden-xs-down">Under issuance</span></a> </li>
                                    <li class="nav-item dpip-renewal-report"> <a class="nav-link" data-toggle="tab" href="#messages" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-table"></i></span> <span class="hidden-xs-down">Renewal</span></a> </li>
                                   <li class="nav-item dpip-lost-report"> <a class="nav-link" data-toggle="tab" href="#div_lost_issuance" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-table"></i></span> <span class="hidden-xs-down">Lost</span></a> </li>
        </ul>
                                <!-- Tab panes -->
                                <div class="tab-content tabcontent-border" style='width:100%;'>
                                    <div class="tab-pane active" id="home" role="tabpanel">
                                                  <div class="auto-scroll" style='width:100%;padding-top:10px'>
                <table class="table table-bordered table-striped dpib_policy_list color-table info-table"  style="width:100%">
                    <thead>
                        <tr>
                            <th style="" class="nowrap">Client segment</th>                                                      
                            <th>Type</th>
                            <th style="" class="nowrap">Channel</th>
                            <th style="" class="nowrap">Policy</th> 
                            <th  class="">Agent</th>
                            <th  class="">LOB</th>
                            <th  class="">Client</th>          
                            <th  class="">Date of submission</th>
                            <th  class="">Date of approach</th>                              
                            <th  class="">Date of last action</th>                            
                          
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
                                    </div>
                                    <div class="tab-pane  p-20" id="profile" role="tabpanel">
                                        
<div class="auto-scroll" style='width:100%;padding-top:10px'>
                <table class="table table-bordered table-striped dpib_underissuance_list color-table info-table"  style="width:100%">
                    <thead>
                        <tr>
                            <th style="" class="nowrap">Client segment</th>                                                      
                            <th>Type</th>
                            <th style="" class="nowrap">Channel</th>
                            <th style="" class="nowrap">Policy</th> 
                            <th  class="">Agent</th>
                            <th  class="">LOB</th>
                            <th  class="">Client</th>          
                            <th  class="">Date of submission</th>
                            <th  class="">Date of approach</th>                              
                            <th  class="">Date of last action</th>
                          
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
   </div>
                                    <div class="tab-pane p-20" id="messages" role="tabpanel">
                                        
<div class="auto-scroll" style='width:100%;padding-top:10px'>
                <table class="table table-bordered table-striped dpib_renewal_list color-table info-table"  style="width:100%">
                    <thead>
                        <tr>
                            <th style="" class="nowrap">Client segment</th>                                                      
                            <th style="" class="nowrap">Channel</th>
                            <th style="" class="nowrap">Current Policy</th> 
                            <th  class="">Premium</th>
                            <th  class="">Agent</th>
                            <th  class="">LOB</th>
                            <th  class="">Client</th>          
                            <th  class="">Product</th>
                            <th  class="">Expiry date</th>                              
                            <th  class="">Renewal date</th>
                            <th  class="">Remainig days</th>                          
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
                                        
                                        
                                    </div>
                                    

                                    
 <div class="tab-pane  p-20" id="div_lost_issuance" role="tabpanel">
                                        
<div class="auto-scroll" style='width:100%;padding-top:10px'>
                <table class="table table-bordered table-striped dpib_lostissuance_list color-table info-table"  style="width:100%">
                    <thead>
                        <tr>
                            <th style="" class="nowrap">Client segment</th>                                                      
                            <th>Type</th>
                            <th style="" class="nowrap">Channel</th>                         
                            <th  class="">Agent</th>
                            <th  class="">LOB</th>
                            <th  class="">Client</th>          
                            <th  class="">Date of submission</th>
                            <th  class="">Date of approach</th>                              
                            <th  class="">Date of last action</th>
                          
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
   </div>
                               
   

      

</div>
</div>
</div>
@endisset

@endsection

 @section('customcss')
<link rel="stylesheet" type="text/css" href=" {{ asset('elitedesign/assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css') }} ">
<link rel="stylesheet" type="text/css" href="{{ asset('elitedesign/assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css') }}"> 


@endsection

@section('customscript')   
<script src="{{ asset('elitedesign/assets/node_modules/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('elitedesign/assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/global/datatable/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/global/datatable/datetime.js') }}" type="text/javascript"></script>

@endsection

 @section('pagescript')   
   
<script>
    var columnDefs = [];
    var rencolumnDefs = [];
    var lostcolumnDefs =[];
    var complaintTable = '';
    var renewalTable = '';
    var underIssuanceTable = '';
    var lostIssuanceTable = '';
    var roleArray = @json(Auth::user()->roles);
   $(function(){
       
    @isset($pendingwithtechDetails)
        columnDefs.push({"name": 'segment',  "targets": 0, data: function (row, type, val, meta) {
                
                                             
                            var subject = (row['premiumAmount'] > 0) ? clientSegment(row['premiumAmount']):'Small';
                            row.sortData = subject;
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": "type",  "targets": 1, "orderable": false,data: function (row, type, val, meta) {
                            var subject = (row['renewal_status'] > 0) ? 'Renewal' :'New';
                            row.sortData = row['renewal_status'];
                            row.displayData = subject;                       
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
       columnDefs.push({"name": "channel",  "targets": 2, "orderable": false,data: function (row, type, val, meta) {
                            var subject =  row['channel'];
                            row.sortData = row['channel'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'policy',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['policy_number'] ;
                            row.sortData = row['policy_number'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        columnDefs.push({"name": 'agent',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['agent'] ;
                            row.sortData = row['agent'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
      columnDefs.push({"name": 'lob',  "targets": 5, data: function (row, type, val, meta) {
              
                        row.sortData = row['lineofbusinesstitle'];
                        row.displayData = row['lineofbusinesstitle'];                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": 'client',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
                   
                    
        columnDefs.push({"name": 'dateofsubmission',  "targets": 7, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['technical_reporting_date'];
                            row.sortData =row['technical_reporting_date'];
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
         columnDefs.push({"name": 'dateofapproach',  "targets": 8, "orderable": false, data: function (row, type, val, meta) {
                                             
                     var subject = row['broking_slip_send_date'];
                            row.sortData =row['broking_slip_send_date'];
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
         columnDefs.push({"name": 'dateoflastupdate',  "targets": 9, "orderable": false, data: function (row, type, val, meta) {
                                             
                           var subject = row['lastUpdated'];
                            row.sortData =row['lastUpdated'];
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
      complaintTable =   $('.dpib_policy_list').DataTable( {
                data: {!! $pendingwithtechDetails !!},
                autoWidth: true,
                stateSave: false,
                stateDuration: 60 * 60 * 24,
                responsive: true,
                deferRender: true,
                lengthChange: true,
                pagination: true,
                rowLength: true,
                scrollX: true,
                pagingType: 'full_numbers',
                processing: true,
                serverSide: false,
                destroy: true,
                order: [[9, "desc"]],
                pageLength: 10,
                displayLength: 10,
                autoFill: false,
                search: false,
                columnDefs:columnDefs,
                language: {
                                paginate: {
                                    "first": '<i class="fa fa-angle-double-left"></i>',
                                    "last": '<i class="fa fa-angle-double-right"></i>',
                                    "next": '<i class="fa fa-angle-right"></i>',
                                    "previous": '<i class="fa fa-angle-left"></i>'
                                }
                            },
                dom: "Brtip"
     
    } ); 
    
          underIssuanceTable =   $('.dpib_underissuance_list').DataTable( {
                data: {!! $underissuanceDetails !!},
                autoWidth: true,
                stateSave: false,
                stateDuration: 60 * 60 * 24,
                responsive: true,
                deferRender: true,
                lengthChange: true,
                pagination: true,
                rowLength: true,
                scrollX: true,
                pagingType: 'full_numbers',
                processing: true,
                serverSide: false,
                destroy: true,
                order: [[9, "desc"]],
                pageLength: 10,
                displayLength: 10,
                autoFill: false,
                search: false,
                columnDefs:columnDefs,
                language: {
                                paginate: {
                                    "first": '<i class="fa fa-angle-double-left"></i>',
                                    "last": '<i class="fa fa-angle-double-right"></i>',
                                    "next": '<i class="fa fa-angle-right"></i>',
                                    "previous": '<i class="fa fa-angle-left"></i>'
                                }
                            },
                dom: "Brtip"
     
    } ); 
    //Renewal column
    
  
    
     rencolumnDefs.push({"name": 'segment',  "targets": 0, data: function (row, type, val, meta) {
                
                                             
                            var subject = (row['premiumAmount'] > 0) ? clientSegment(row['premiumAmount']):'Small';
                            row.sortData = subject;
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                        
        
                    
       rencolumnDefs.push({"name": "channel",  "targets": 1, "orderable": false,data: function (row, type, val, meta) {
                            var subject =  row['channel'];
                            row.sortData = row['channel'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        rencolumnDefs.push({"name": 'policy',  "targets": 2, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['policy_number'] ;
                            row.sortData = row['policy_number'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
        rencolumnDefs.push({"name": 'premium',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['total_premium'] ;
                            row.sortData = row['total_premium'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    
        rencolumnDefs.push({"name": 'agent',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['agent'] ;
                            row.sortData = row['agent'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
      rencolumnDefs.push({"name": 'lob',  "targets": 5, data: function (row, type, val, meta) {
              
                        row.sortData = row['lineofbusinesstitle'];
                        row.displayData = row['lineofbusinesstitle'];                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
        rencolumnDefs.push({"name": 'client',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});    
                    
                    
          rencolumnDefs.push({"name": 'product',  "targets": 7, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  row['product_name'];
                            row.sortData = row['product_name'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});               
                    
                   
                    
        rencolumnDefs.push({"name": 'expirydate',  "targets": 8, "orderable": false, data: function (row, type, val, meta) {
                            var subject = moment(row['end_date']).format('DD-MM-YYYY');
                            row.sortData =row['end_date'];
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
         rencolumnDefs.push({"name": 'renewaldate',  "targets": 9, "orderable": false, data: function (row, type, val, meta) {
                                             
                     var subject = moment(row['expirydate']).format('DD-MM-YYYY');
                            row.sortData =row['expirydate'];
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                        
         rencolumnDefs.push({"name": 'remainingdays',  "targets": 10, "orderable": false, data: function (row, type, val, meta) {
                                             
                           var subject = row['datediff'];
                            row.sortData =row['datediff'];
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                        
                        
                        
                        
                 renewalTable =   $('.dpib_renewal_list').DataTable( {
                data: {!! $renewalDetails !!},
                autoWidth: true,
                stateSave: false,
                stateDuration: 60 * 60 * 24,
                responsive: true,
                deferRender: true,
                lengthChange: true,
                pagination: true,
                rowLength: true,
                scrollX: true,
                pagingType: 'full_numbers',
                processing: true,
                serverSide: false,
                destroy: true,
                order: [[1, "desc"]],
                pageLength: 10,
                displayLength: 10,
                autoFill: false,
                search: false,
                columnDefs:rencolumnDefs,
                language: {
                                paginate: {
                                    "first": '<i class="fa fa-angle-double-left"></i>',
                                    "last": '<i class="fa fa-angle-double-right"></i>',
                                    "next": '<i class="fa fa-angle-right"></i>',
                                    "previous": '<i class="fa fa-angle-left"></i>'
                                }
                            },
                dom: "Brtip"
     
    } ); 
    
    //Lost request details
    
        lostcolumnDefs.push({"name": 'segment',  "targets": 0, data: function (row, type, val, meta) {
                
                                             
                            var subject = (row['premiumAmount'] > 0) ? clientSegment(row['premiumAmount']):'Small';
                            row.sortData = subject;
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        lostcolumnDefs.push({"name": "type",  "targets": 1, "orderable": false,data: function (row, type, val, meta) {
                            var subject = (row['renewal_status'] > 0) ? 'Renewal' :'New';
                            row.sortData = row['renewal_status'];
                            row.displayData = subject;                       
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
       lostcolumnDefs.push({"name": "channel",  "targets": 2, "orderable": false,data: function (row, type, val, meta) {
                            var subject =  'direct';
                            row.sortData = 'direct';
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    

        lostcolumnDefs.push({"name": 'agent',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['agent'] ;
                            row.sortData = row['agent'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        lostcolumnDefs.push({"name": 'lob',  "targets": 4, data: function (row, type, val, meta) {
              
                        row.sortData = row['lineofbusinesstitle'];
                        row.displayData = row['lineofbusinesstitle'];                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
        lostcolumnDefs.push({"name": 'client',  "targets": 5, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                   
                    
        lostcolumnDefs.push({"name": 'dateofsubmission',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['technical_reporting_date'];
                            row.sortData =row['technical_reporting_date'];
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
         lostcolumnDefs.push({"name": 'dateofapproach',  "targets": 7, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['broking_slip_send_date'];
                            row.sortData =row['broking_slip_send_date'];
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
         lostcolumnDefs.push({"name": 'dateoflastupdate',  "targets": 8, "orderable": false, data: function (row, type, val, meta) {
                                             
                           var subject = row['lastUpdated'];
                            row.sortData =row['lastUpdated'];
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
      lostIssuanceTable =   $('.dpib_lostissuance_list').DataTable( {
                data: {!! $lostrequestDetails !!},
                autoWidth: true,
                stateSave: false,
                stateDuration: 60 * 60 * 24,
                responsive: true,
                deferRender: true,
                lengthChange: true,
                pagination: true,
                rowLength: true,
                scrollX: true,
                pagingType: 'full_numbers',
                processing: true,
                serverSide: false,
                destroy: true,
                order: [[8, "desc"]],
                pageLength: 10,
                displayLength: 10,
                autoFill: false,
                search: false,
                columnDefs:lostcolumnDefs,
                language: {
                                paginate: {
                                    "first": '<i class="fa fa-angle-double-left"></i>',
                                    "last": '<i class="fa fa-angle-double-right"></i>',
                                    "next": '<i class="fa fa-angle-right"></i>',
                                    "previous": '<i class="fa fa-angle-left"></i>'
                                }
                            },
                dom: "Brtip"
     
    } ); 
    
    
    
    
    
    
    @endisset
    
      $("#customer_name").autocomplete({
  disabled: false,
   position: { my : "right top", at: "right bottom" },
      source: function( request, response ) {
        $.ajax( {
          url: '{!! route("seachcustomer",["customer"]) !!}',
          headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          dataType: "json",
          data: {
            search: request.term
          },
          success: function( data ) {
 
                    
                    
        response($.map(data, function (item)
                    {
                        return{
                            label: item.name,
                            value: item.id,
                            id:item.name
                           
                        }
                    }))

          }
        } );
      },
      minLength: 2,
      select: function( event, ui ) {
          $('#customer_name').val(ui.item.label); // display the selected text
          $('#customerId').val(ui.item.value);
   
   return false;
      }
    } );
    
    $(document).on('click','#filterCancel',function(){
      window.location.href = $(this).attr('redirectUrl');
    })
    
    $(document).on('click','.dpip-issuance-report',function(){
        underIssuanceTable.columns.adjust().draw();
    }) 
    $(document).on('click','.dpip-renewal-report',function(){
        renewalTable.columns.adjust().draw();
    }) 
      $(document).on('click','.dpip-lost-report',function(){
        lostIssuanceTable.columns.adjust().draw();
    })
    
    
    
       
   });
   
   function clientSegment(amount) {
         var segment ='';
        if( amount <= 500000) {
          segment = "Small";  
        } else if(amount <= 3000000) {
           segment = "Medium"; 
        } else if(amount <= 10000000) {
          segment = "Large";  
        } else if(amount > 10000000) {
           segment = "Key Account"; 
        }
        return segment;
   
   }



</script>
@endsection