@extends('layouts.elite',['notificationCount'=>0 ] )
@section('headtitle')
  Invoice report
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Filters</h4>

  
            {{ Form::open(array('route' => 'invoicefilter', 'name' => 'salesrequestfilter','id'=>'salesrequestfilter','class'=>'') ) }}
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
                <label for="example-email-input" class="col-2 col-form-label">Status</label>
                <div class="col-10">
                
                    {{ Form::select('invoice_status', [''=>'---Select---']+ $statusArray,  '' ,array('id' =>'invoice_status','class'=>'custom-select col-12','error-message' =>"Gender field is mandatory" ))}}  
                </div>
            </div>
            
            
            <button type="submit" class="btn btn-success mr-2">Generate report</button>
            <button type="button" class="btn btn-dark" id='filterCancel' redirectUrl='{{ route('invoicereport')}}'>Cancel</button>

       {{ Form::close() }}
    </div>
</div>


@isset($invoiceDetails)
<div class=" card">
    <div class="card-body">
 <a href="{{ route('invoiceexport')}}"><button type="button" class="btn btn-info d-none d-lg-block m-l-15 pull-right"><i class="mdi mdi-file-export"></i> Export</button></a>
   
            <div class="auto-scroll" style='width:100%;padding-top:10px'>
                <table class="table table-bordered table-striped dpib_policy_list color-table info-table" >
               
                     <thead>
                            <tr>
                            <th style="width: 10%" class="nowrap">Invoice No</th>
                            <th style="width: 5%" class="nowrap">Customer</th>              
                            <th  class="nowrap">Policy</th>
                            <th  class="nowrap">Date</th>
                            <th  class="nowrap">Invoice sum</th>
                            <th>Policy status</th>
                            <th  class="nowrap">Status</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
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
    var complaintTable = '';
    var roleArray = @json(Auth::user()->roles);
   $(function(){
    @isset($invoiceDetails)
         columnDefs.push({"name": 'invoiceno',  "targets": 0, data: function (row, type, val, meta) {
                
                            var link ="{!! route('invoiceoverview','##Id##') !!}";
                            var linkString = link.replace("##Id##", row['invoiceId']); 
                            var subject =    "<a href='"+linkString+"'>"+ row['invoiceId']+"</a>";
                            row.sortData = row['invoiceId'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": "customer",  "targets": 1, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['name'];
                            row.sortData = row['name'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'policy',  "targets": 2, "orderable": true, data: function (row, type, val, meta) {
                            var subject =row['policy_number'] ;
                            row.sortData = row['policy_number'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        columnDefs.push({"name": 'date',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  moment(row['generated_date']).format('DD.MM.YYYY');
                            row.sortData = row['generated_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});   
         columnDefs.push({"name": 'invoice_sum',  "targets": 4, "orderable": true, data: function (row, type, val, meta) {
                            var subject =  row['invoice_sum'].toFixed(2);
                            row.sortData = row['invoice_sum'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
               
                    
        columnDefs.push({"name": 'status',  "targets": 5, "orderable": false, data: function (row, type, val, meta) {
                            var subject =(row['paid_status']==0) ? '<span class="badge badge-warning  badge-pill">'+row['invoiceStatusString']+'</span>' : '<span class="badge badge-success badge-pill">'+row['invoiceStatusString']+'</span>';
                            row.sortData =row['paid_status'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                  
          
                  
                    
        
       
      complaintTable =   $('.dpib_policy_list').DataTable( {
                data: {!! $invoiceDetails !!},
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
                order: [[0, "desc"]],
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
    
    $(document).on('click','#filterCancel',function(){
      window.location.href = $(this).attr('redirectUrl');
    })
    
    
       
   });



</script>
@endsection