@extends('layouts.elite',['notificationCount'=>0 ] )
@section('headtitle')
<h3 class="text-blue bold">Installment report </h3>
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Filters</h4>

  
            {{ Form::open(array('route' => 'installmentfilter', 'name' => 'salesrequestfilter','id'=>'salesrequestfilter','class'=>'') ) }}
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
            
              <div class="form-group row">
                <label for="example-email-input" class="col-2 col-form-label">Paid type</label>
                <div class="col-10">
                
                    {{ Form::select('intallment_type', [''=>'---Select---']+ $statusArray,  '' ,array('id' =>'intallment_type','class'=>'custom-select col-12','error-message' =>"Paid type field is mandatory" ))}}  
                </div>
            </div>

            
            
            <button type="submit" class="btn btn-success mr-2">Generate report</button>
            <button type="button" class="btn btn-dark" id='filterCancel' redirectUrl='{{ route('installmentreport')}}'>Cancel</button>

       {{ Form::close() }}
    </div>
</div>


@isset($installmentDetails)
<div class=" card">
    <div class="card-body">
        
 <a href="{{ route('installmentexport')}}"><button type="button" class="btn btn-info d-none d-lg-block m-l-15 pull-right"><i class="mdi mdi-file-export"></i> Export</button></a>
  
                                <!-- Tab panes -->
                                <div class="tab-content tabcontent-border" style='width:100%;'>
                                    <div class="tab-pane active" id="home" role="tabpanel">
                                                  <div class="auto-scroll" style='width:100%;padding-top:10px'>
                <table class="table table-bordered table-striped dpib_policy_list color-table info-table"  style="width:100%">
                    <thead>
                        <tr>
                            <th style="" class="nowrap">Policy no</th> 
                            <th style="" class="nowrap">Customer</th>
                            <th  class="">End date</th>
                            <th  class="">Due date</th>
                            <th style="" class="nowrap">Amount</th>                            
                            <th  class="">Vat</th>
                            <th  class="">Vat amount</th>
                            <th  class="">Total amount</th>
                            <th>Status</th>
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
       
    @isset($installmentDetails)
    
   
                    columnDefs.push({"name": 'policynumber',  "targets": 0, data: function (row, type, val, meta) {
                            var subject = row['policy_number'];
                            row.sortData = row['policy_number'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    columnDefs.push({"name": 'customer',  "targets":1, data: function (row, type, val, meta) {
              
                        row.sortData = row['customerName'];
                        row.displayData = row['customerName'];                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 

                    columnDefs.push({"name": 'enddate',  "targets": 2, data: function (row, type, val, meta) {
                                                            
                           var subject =  (row['instEnddate'] !==null) ? moment(row['instEnddate']).format('DD-MM-YYYY'):moment(row['instEnddate']).format('DD-MM-YYYY');
                            row.sortData = (row['instEnddate'] !==null ) ? moment(row['instEnddate']).format('DD-MM-YYYY'):moment(row['instEnddate']).format('DD-MM-YYYY');
                            row.displayData = subject;                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                     columnDefs.push({"name": "duedate",  "targets": 3, "orderable": false,data: function (row, type, val, meta) {
                            var subject =  (row['instDuedate'] !==null) ? moment(row['instDuedate']).format('DD-MM-YYYY'):moment(row['instDuedate']).format('DD-MM-YYYY');
                            row.sortData = (row['instDuedate'] !==null ) ? moment(row['instDuedate']).format('DD-MM-YYYY'):moment(row['instDuedate']).format('DD-MM-YYYY');
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});

                     columnDefs.push({"name": 'amount',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['instAmount'].toFixed(2);
                            
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});

                     columnDefs.push({"name": "vatpercentage",  "targets": 5, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['instvatpercentage']+' %';                            
                            row.displayData = subject;                       
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});

                     columnDefs.push({"name": "vatamount",  "targets": 6, "orderable": false,data: function (row, type, val, meta) {
                            var subject =  row['instVatamount'].toFixed(2);
                            
                            row.displayData = subject;                       
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                    columnDefs.push({"name": 'totalamount',  "targets": 7,"orderable": false, data: function (row, type, val, meta) {
              
                         var subject =  (row['instAmount']+ row['instVatamount']).toFixed(2);
                            
                        row.displayData = subject; 
                        
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    
                     
                    
                    
                    
                    columnDefs.push({"name": "status",  "targets": 8, "orderable": false,data: function (row, type, val, meta) {
                            var subject = (row['instPaidstatus'] ==0) ? 'Unpaid': 'Paid';
                            row.sortData = row['instPaidstatus'];
                            row.displayData = subject;                       
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
        
      complaintTable =   $('.dpib_policy_list').DataTable( {
                data: {!! $installmentDetails !!},
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