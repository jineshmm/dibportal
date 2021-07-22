@extends('layouts.elite',['notificationCount'=>0 ] )
@section('headtitle')
<h3 class="text-blue bold">Production report </h3>
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Filters</h4>

  
            {{ Form::open(array('route' => 'productionfilter', 'name' => 'salesrequestfilter','id'=>'salesrequestfilter','class'=>'') ) }}
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
            <button type="button" class="btn btn-dark" id='filterCancel' redirectUrl='{{ route('productionreport')}}'>Cancel</button>

       {{ Form::close() }}
    </div>
</div>


@isset($productionDetails)
<div class=" card">
    <div class="card-body">
        
 <a href="{{ route('productionexport')}}"><button type="button" class="btn btn-info d-none d-lg-block m-l-15 pull-right"><i class="mdi mdi-file-export"></i> Export</button></a>
        <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item dpip-pipeline-report"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-table"></i></span> <span class="hidden-xs-down">Production segmant</span></a> </li>
                             
                                     <li class="nav-item dpip-endorsement-report"> <a class="nav-link" data-toggle="tab" href="#endorsement" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-table"></i></span> <span class="hidden-xs-down">Endorsement segmant</span></a> </li>
                                    <li class="nav-item dpip-renewal-report"> <a class="nav-link" data-toggle="tab" href="#profile" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-table"></i></span> <span class="hidden-xs-down">Client segmant</span></a> </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content tabcontent-border" style='width:100%;'>
                                    <div class="tab-pane active" id="home" role="tabpanel">
                                                  <div class="auto-scroll" style='width:100%;padding-top:10px'>
                <table class="table table-bordered table-striped dpib_policy_list color-table info-table"  style="width:100%">
                    <thead>
                        <tr>
                            <th style="" class="nowrap">Policy segment</th> 
                            <th style="" class="nowrap">Client segment</th> 
                            <th  class="">LOB</th>
                            <th  class="">Product</th>
                            <th style="" class="nowrap">Channel</th>
                            <th  class="">Agent</th>
                            <th>Type</th>
                            <th style="" class="nowrap">Year of inception</th>
                            <th style="" class="nowrap">Month of inception</th>
                            <th  class="">Client</th>     
                            <th  class="">Insurer</th>  
                            <th style="" class="nowrap">Policy</th>
                            <th  class="">Date of issuance</th>
                            <th  class="">Date of Expiry</th>                              
                            <th  class="">Premium</th>                           
                          
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
                                    </div>
                                    
                                    
                                                <div class="tab-pane  p-20" id="endorsement" role="tabpanel">
                                        
<div class="auto-scroll" style='width:100%;padding-top:10px'>
                <table class="table table-bordered table-striped dpib_endorsement_list color-table info-table"  style="width:100%">
                    <thead>
                        <tr>
                            <th style="" class="nowrap">Client</th>  
                            <th style="" class="nowrap">Insurer</th>
                            <th>Policy</th>
                            <th style="" class="nowrap">Endorsement number</th>
                            <th style="" class="nowrap">Endorsement type</th>
                            <th style="" class="nowrap">Issue date</th>
                            <th style="" class="nowrap">Inception date</th>
                            <th style="" class="nowrap">End date</th>
                            <th style="" class="nowrap">Amount</th>
                            <th style="" class="nowrap">Vat amount</th>
                            <th style="" class="nowrap">Endorsement count</th>
                            
                            
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
   </div>                        
                                    
                                    
                                    
                                    <div class="tab-pane  p-20" id="profile" role="tabpanel">
                                        
<div class="auto-scroll" style='width:100%;padding-top:10px'>
                <table class="table table-bordered table-striped dpib_renewal_list color-table info-table"  style="width:100%">
                    <thead>
                        <tr>
                            <th style="" class="nowrap">Client</th>  
                            <th style="" class="nowrap">Insurer</th>
                            <th>Sum of premium</th>
                            <th style="" class="nowrap">Segmant</th>
                                                        
                          
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
     var endocolumnDefs = [];
    var complaintTable = '';
    var renewalTable = '';
    var endorsementTable = '';
    var roleArray = @json(Auth::user()->roles);
   $(function(){
       
    @isset($productionDetails)
    
   
                    columnDefs.push({"name": 'policysegment',  "targets": 0, data: function (row, type, val, meta) {
                            var subject = (row['premiumAmount'] > 0) ? clientSegment(row['premiumAmount']):'Small';
                            row.sortData = subject;
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    columnDefs.push({"name": 'segment',  "targets": 1, data: function (row, type, val, meta) {
                                                            
                            var subject = (row['premiumAmount'] > 0) ? clientSegment(row['premiumAmount']):'Small';
                            row.sortData = subject;
                            row.displayData = subject;                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                    columnDefs.push({"name": 'lob',  "targets": 2, data: function (row, type, val, meta) {
              
                        row.sortData = row['lineofbusinesstitle'];
                        row.displayData = row['lineofbusinesstitle'];
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    columnDefs.push({"name": 'product',  "targets":3, data: function (row, type, val, meta) {
              
                        row.sortData = row['product_name'];
                        row.displayData = row['product_name'];                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    columnDefs.push({"name": "channel",  "targets": 4, "orderable": false,data: function (row, type, val, meta) {
                            var subject =  row['channel'];
                            row.sortData = row['channel'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                    columnDefs.push({"name": 'agent',  "targets": 5, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['agent'] ;
                            row.sortData = row['agent'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    columnDefs.push({"name": "type",  "targets": 6, "orderable": false,data: function (row, type, val, meta) {
                            var subject = (row['renewal_status'] > 0) ? 'Renewal' :'New';
                            row.sortData = row['renewal_status'];
                            row.displayData = subject;                       
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                    columnDefs.push({"name": 'yearofinception',  "targets": 7, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  moment(row['inceptiondate']).format('YYYY');
                            row.sortData =  moment(row['inceptiondate']).format('YYYY');
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                    columnDefs.push({"name": 'monthofinception',  "targets": 8, "orderable": false, data: function (row, type, val, meta) {
                            var subject = moment(row['inceptiondate']).format('MM');
                            row.sortData = moment(row['inceptiondate']).format('MM');
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
          
                    columnDefs.push({"name": 'client',  "targets": 9, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    columnDefs.push({"name": 'insurer',  "targets": 10, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  row['insurer_name'];
                            row.sortData = row['insurer_name'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    columnDefs.push({"name": 'policy',  "targets":11, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['policy_number'] ;
                            row.sortData = row['policy_number'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                    columnDefs.push({"name": 'dateofissuance',  "targets": 12, "orderable": false, data: function (row, type, val, meta) {
                                             
                     var subject = moment(row['issue_date']).format('DD-MM-YYYY');
                            row.sortData = moment(row['issue_date']).format('DD-MM-YYYY');
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    columnDefs.push({"name": 'dateofexpiry',  "targets": 13, "orderable": false, data: function (row, type, val, meta) {
                                             
                           var subject = moment(row['expirydate']).format('DD-MM-YYYY');
                            row.sortData =moment(row['expirydate']).format('DD-MM-YYYY');
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                    columnDefs.push({"name": 'premium',  "targets": 14, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['premiumAmount'] ;
                            row.sortData = row['premiumAmount'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
     
                    
      
                    
         
                    
      complaintTable =   $('.dpib_policy_list').DataTable( {
                data: {!! $productionDetails !!},
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
    
  
    
     rencolumnDefs.push({"name": 'client',  "targets": 0, data: function (row, type, val, meta) {
                
                                             
                            var subject = row['customerName'] ;
                            row.sortData = subject;
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
           rencolumnDefs.push({"name": 'insurer',  "targets": 1, data: function (row, type, val, meta) {
                
                                             
                            var subject = row['insurer_name'];
                            row.sortData = subject;
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                   
        
                    
       rencolumnDefs.push({"name": "sumofpremium",  "targets": 2, "orderable": false,data: function (row, type, val, meta) {
                            var subject =  row['premiumAmount'];
                            row.sortData = row['premiumAmount'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        rencolumnDefs.push({"name": 'segmant',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject = (row['premiumAmount'] > 0) ? clientSegment(row['premiumAmount']):'Small';
                            row.sortData = subject;
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
       
                        
                        
                        
                        
             renewalTable =   $('.dpib_renewal_list').DataTable( {
                data: {!! $clientsegmantDetails !!},
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
    

    
    endocolumnDefs.push({"name": 'client',  "targets": 0, data: function (row, type, val, meta) {
                            var subject = row['customerName'] ;
                            row.sortData = subject;
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    endocolumnDefs.push({"name": 'insurer',  "targets": 1, data: function (row, type, val, meta) {
                                                            
                            var subject = row['insurer_name'];
                            row.sortData = subject;
                            row.displayData = subject;                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                    endocolumnDefs.push({"name": 'policy',  "targets": 2, data: function (row, type, val, meta) {
              
                        row.sortData = row['policy_number'];
                        row.displayData = row['policy_number'];
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    endocolumnDefs.push({"name": 'endorsementnumber',  "targets":3, data: function (row, type, val, meta) {
              
                        row.sortData = row['endorsement_number'];
                        row.displayData = row['endorsement_number'];                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    endocolumnDefs.push({"name": "endorsementtype",  "targets": 4, "orderable": false,data: function (row, type, val, meta) {
                            var subject =  row['typeString'];
                            row.sortData = row['typeString'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                    endocolumnDefs.push({"name": 'issuedate',  "targets": 5, "orderable": false, data: function (row, type, val, meta) {
                            var subject =moment(row['issueDate']).format('DD.MM.YYYY');
                            row.sortData = moment(row['issueDate']).format('DD.MM.YYYY');
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    endocolumnDefs.push({"name": "inceptiondate",  "targets": 6, "orderable": false,data: function (row, type, val, meta) {
                            var subject = moment(row['endorsementStartDate']).format('DD.MM.YYYY'); 
                            row.sortData = moment(row['endorsementStartDate']).format('DD.MM.YYYY'); 
                            row.displayData = subject;                       
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                    endocolumnDefs.push({"name": 'enddate',  "targets": 7, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  moment(row['end_date']).format('DD.MM.YYYY');
                            row.sortData =  moment(row['end_date']).format('DD.MM.YYYY');
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                    endocolumnDefs.push({"name": 'amount',  "targets":8, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['amount'] ;
                            row.sortData = row['amount'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                        
                        endocolumnDefs.push({"name": 'vatamount',  "targets": 9, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  row['vatAmount'];
                            row.sortData = row['vatAmount'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    endocolumnDefs.push({"name": 'endorsementcount',  "targets": 10, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  row['endorsement_count'];
                            row.sortData = row['endorsement_count'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                    
                    
                    
                    
    
    endorsementTable =   $('.dpib_endorsement_list').DataTable( {
                data: {!! $endorsementDetails !!},
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
                columnDefs:endocolumnDefs,
                language: {
                                paginate: {
                                    "first": '<i class="fa fa-angle-double-left"></i>',
                                    "last": '<i class="fa fa-angle-double-right"></i>',
                                    "next": '<i class="fa fa-angle-right"></i>',
                                    "previous": '<i class="fa fa-angle-left"></i>'
                                }
                            },
                dom: "Brtip"
     
    });
    
    
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
    
    $(document).on('click','.dpip-endorsement-report',function(){
        endorsementTable.columns.adjust().draw()
    }) 
    $(document).on('click','.dpip-renewal-report',function(){
        renewalTable.columns.adjust().draw()
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