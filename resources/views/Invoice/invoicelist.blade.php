@extends('layouts.elite',['notificationCount'=>$notificationCount  ] )
@section('content')






<div class="panel panel-default open card">
    <div class="panel-heading card-body" >

           <h1 class="panel-title" ><small>Invoices</small></h1>
    </div><div class="panel-body"> 
            <div class="auto-scroll table-responsive" style='width:100%;'>
                
                <table class="table table-bordered table-striped dpib_invoice_list color-table info-table">
                        <thead>
                            <tr>
                            <th style="width: 10%" class="nowrap">Invoice No</th>
                            <th style="width: 5%" class="nowrap">Customer</th>                      
                            <th  class="nowrap">Policy</th>
                            <th  class="nowrap">Date</th>
                            <th  class="nowrap">Invoice sum</th>
                            <th  class="nowrap">Status</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                </table>
            </div>
        </div> 

</div>
  

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
    var policyTable = '';


      var roleArray = @json(Auth::user()->roles);

   $(function(){
    
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
                    
                  
                    
        
       
      policyTable =   $('.dpib_invoice_list').DataTable( {
                data: {!! $invoicedetails !!},
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
                dom: "Bfrtip"
     
    } ); 
    

    
    
    
       
   });
 


</script>
@endsection