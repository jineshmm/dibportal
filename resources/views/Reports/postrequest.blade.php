@extends('layouts.elite',['notificationCount'=>0 ] )
@section('headtitle')
<h3 class="text-blue bold">Posted request report </h3>
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Filters</h4>

  
            {{ Form::open(array('route' => 'financepostrequestfilter', 'name' => 'salesrequestfilter','id'=>'salesrequestfilter','class'=>'') ) }}
            <div class="form-group mt-5 row">
                <label for="example-text-input" class="col-2 col-form-label">Inception date period</label>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="ins_startDate" name='ins_startDate'>
                </div>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="ins_endDate" name='ins_endDate'>
                </div>
            </div>
            <div class="form-group row ui-widget">
                <label for="customer_name" class="col-2 col-form-label">Customer</label>
                <div class="col-10">
                    <input  class='form-control' name='search' id="customer_name">
                    <input type='hidden' name='customerId' id='customerId' value=0 />
                    
                </div>

            </div>

               <div class="form-group mt-5 row">
                <label for="example-text-input" class="col-2 col-form-label">End date period</label>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="end_startDate" name='end_startDate'>
                </div>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="end_startDate" name='end_startDate'>
                </div>
            </div>
            
         <div class="form-group mt-5 row">
                <label for="example-text-input" class="col-2 col-form-label">Post status</label>
                <div class="col-10">
                   {{ Form::select('post_status', [''=>'---Select---']+ $poststatusArray,  '' ,array('id' =>'post_status','class'=>'custom-select col-12','error-message' =>"Gender field is mandatory" ))}} 
                </div>
                
            </div>

            
  <!--             <div class="form-group row">
                <label for="example-email-input" class="col-2 col-form-label">Production type</label>
                <div class="col-10">
                
                    {{ Form::select('production_type', [''=>'---Select---']+ $statusArray,  '' ,array('id' =>'production_type','class'=>'custom-select col-12','error-message' =>"Gender field is mandatory" ))}}  
                </div>
            </div> -->

            
            
            <button type="submit" class="btn btn-success mr-2">Generate report</button>
            <button type="button" class="btn btn-dark" id='filterCancel' redirectUrl='{{ route('financeproductionreport')}}'>Cancel</button>

       {{ Form::close() }}
    </div>
</div>


@isset($productionDetails)


<div class=" card">
    <div class="card-body">
        
 <a href="{{ route('postrequestreportexport')}}"><button type="button" class="btn btn-info d-none d-lg-block m-l-15 pull-right"><i class="mdi mdi-file-export"></i> Export</button></a>

        <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item dpip-pipeline-report"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-table"></i></span> <span class="hidden-xs-down">Policies</span></a> </li>
                                    <li class="nav-item dpip-issuance-report" > <a class="nav-link" data-toggle="tab" href="#profile" role="tab" ><span class="hidden-sm-up"><i class="mdi mdi-table"></i></span> <span class="hidden-xs-down">Endorsement</span></a> </li>
                                    
                                </ul>




    
                                <!-- Tab panes -->
 <div class="tab-content tabcontent-border" style='width:100%;'>
        <div class="tab-pane active" id="home" role="tabpanel">                    
                                        
            <div class="auto-scroll" style='width:100%;padding-top:10px'>
                 <table class="table table-bordered table-striped dpib_policy_list color-table info-table"  style="width:100%">
                    <thead>
                        <tr>
                            <th style="" class="nowrap">Policy no</th>                             
                            <th  class="">Validity</th>
                            <th  class="">Customer</th>
                            <th style="" class="nowrap">Inception date</th>
                            <th style="" class="nowrap">End date</th>
                            <th  class="">Amount</th>
                            <th>Vat</th>
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
        </div>



        <div class="tab-pane" id="profile" role="tabpanel">                    
                                        
            <div class="auto-scroll" style='width:100%;padding-top:10px'>
           <table class="table table-bordered table-striped dpib_endorsement_list color-table info-table"  style="width:100%">
                    <thead>
                        <tr>
                            <th style="" class="nowrap">Policy no</th> 
                            <th style="" class="nowrap">Endorsement number</th>
                            <th  class="">Validity</th>
                            <th  class="">Customer</th>
                            <th style="" class="nowrap">Inception date</th>
                            <th style="" class="nowrap">End date</th>
                            <th  class="">Amount</th>
                            <th>Vat</th>
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
    var columnDefs1 = [];
    
    var complaintTable;
    var endorsementTable = '';
    var roleArray = @json(Auth::user()->roles);
   $(function(){


$('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
$($.fn.dataTable.tables(true)).DataTable()
.columns.adjust()
.responsive.recalc();
});

       
    @isset($productionDetails)
    
   
                    columnDefs.push({"name": 'policynumber',  "targets": 0, data: function (row, type, val, meta) {
                            var subject = row['policy_number'];
                            row.sortData = row['policy_number'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    
                    columnDefs.push({"name": 'validity',  "targets": 1, data: function (row, type, val, meta) {
              
                        row.sortData = moment(row['inceptiondate']).format('DD-MM-YYYY') +'-'+moment(row['expirydate']).format('DD-MM-YYYY');
                        row.displayData =  moment(row['inceptiondate']).format('DD-MM-YYYY') +'-'+moment(row['expirydate']).format('DD-MM-YYYY');
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    columnDefs.push({"name": 'customer',  "targets":2, data: function (row, type, val, meta) {
              
                        row.sortData = row['customerName'];
                        row.displayData = row['customerName'];                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    columnDefs.push({"name": "issuedate",  "targets": 3, "orderable": true,data: function (row, type, val, meta) {
                            var subject =  moment(row['inceptiondate']).format('DD-MM-YYYY');
                            row.sortData = moment(row['inceptiondate']).format('DD-MM-YYYY');
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});

                    columnDefs.push({"name": "endedate",  "targets": 4, "orderable": true,data: function (row, type, val, meta) {
                            var subject =  moment(row['expirydate']).format('DD-MM-YYYY');
                            row.sortData = moment(row['expirydate']).format('DD-MM-YYYY');
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                    columnDefs.push({"name": 'amount',  "targets": 5, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['premiumAmount'].toFixed(2);
                            row.sortData =row['premiumAmount'].toFixed(2);
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    columnDefs.push({"name": "vat",  "targets": 6, "orderable": false,data: function (row, type, val, meta) {
                            var subject =  row['policyvatAmount'].toFixed(2);
                            row.sortData =  row['policyvatAmount'].toFixed(2);
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
                order: [[4, "desc"]],
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

//ENDORSEMENT REPORT
      columnDefs1.push({"name": 'policynumber',  "targets": 0, data: function (row, type, val, meta) {
                            var subject = row['policy_number'];
                            row.sortData = row['policy_number'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    columnDefs1.push({"name": 'endorsementnumber',  "targets": 1, data: function (row, type, val, meta) {
                                                            
                            var subject = (row['endorsement_number'] !='') ? row['endorsement_number']: '';
                            row.sortData = subject;
                            row.displayData = subject;                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                    columnDefs1.push({"name": 'validity',  "targets": 2, data: function (row, type, val, meta) {
              
                        row.sortData =  moment(row['endorsementStart']).format('DD-MM-YYYY') +'-'+moment(row['expirydate']).format('DD-MM-YYYY') ;
                        row.displayData = moment(row['endorsementStart']).format('DD-MM-YYYY') +'-'+moment(row['expirydate']).format('DD-MM-YYYY') ;
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    columnDefs1.push({"name": 'customer',  "targets":3, data: function (row, type, val, meta) {
              
                        row.sortData = row['customerName'];
                        row.displayData = row['customerName'];                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    columnDefs1.push({"name": "issuedate",  "targets": 4, "orderable": true,data: function (row, type, val, meta) {
                            var subject =   moment(row['endorsementStart']).format('DD-MM-YYYY');
                            row.sortData = moment(row['endorsementStart']).format('DD-MM-YYYY');
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    columnDefs1.push({"name": "enddate",  "targets": 5, "orderable": true,data: function (row, type, val, meta) {
                            var subject =   moment(row['expirydate']).format('DD-MM-YYYY');
                            row.sortData = moment(row['expirydate']).format('DD-MM-YYYY');
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                    columnDefs1.push({"name": 'amount',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['premiumAmount'].toFixed(2);
                            row.sortData =row['premiumAmount'].toFixed(2);
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    columnDefs1.push({"name": "vat",  "targets": 7, "orderable": false,data: function (row, type, val, meta) {
                        var subject =  row['endorsementvatAmount'].toFixed(2);
                            row.sortData =  row['endorsementvatAmount'].toFixed(2);                            
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
                order: [[4, "desc"]],
                pageLength: 10,
                displayLength: 10,
                autoFill: false,
                search: false,
                columnDefs:columnDefs1,
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
    
   
    
    
    
       
   });
   




</script>



@endsection