@extends('layouts.elite',['notificationCount'=>$notificationCount ] )


@section('content')

<div class="panel panel-default open card">
    <div class="panel-heading card-body">       
        <h1 class="card-title">Quotes<small></small></h1> 
    </div>
    <div class="panel-body"> 
            <div class="auto-scroll table-responsive">
                 <table class="table table-bordered table-striped dpib_customer_doc color-table info-table" >
                        <thead>
                            <tr>
                                <th  class="nowrap" >Customer</th>
                                <th style="width: 15%" class="nowrap">File</th>
                                <th style="width: 15%" class="nowrap">Company</th>                           
                                <th  class="nowrap" >Product</th>

                                <th  class="nowrap" >Uploaded By</th>
                                <th  class="nowrap" >Uploaded at</th>

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
 
  var qcolumnDefs = [];
    var customerQuoteTable = '';
     qcolumnDefs.push({"name": "customername",  "targets": 0, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
        qcolumnDefs.push({"name": 'filename',  "targets": 1, data: function (row, type, val, meta) {
                            var subject = row['file_name'];
                            row.sortData = row['file_name'];
                            linkString = "<a href='{!! route('getfiledownload',['##CID','quote', '0', '##FILE',0]) !!}'>"+subject+"</a>";
                             var link = linkString.replace("##CID", row['customer_id']).replace("##FILE", subject);
                            row.displayData = link;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        qcolumnDefs.push({"name": "company",  "targets": 2, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['insurer_name'];
                            row.sortData = row['insurer_name'];
                            row.displayData = subject;
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});            
                    
        qcolumnDefs.push({"name": "product",  "targets": 3, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['product_name'];
                            row.sortData = row['product_name'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});            
                              
       
                    
                
                    
        qcolumnDefs.push({"name": 'Uploaded by',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['uploadedBy'];
                            row.sortData = row['uploadedBy'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        qcolumnDefs.push({"name": 'Uploaded at',  "targets": 5, "orderable": true, data: function (row, type, val, meta) {
                            var subject = (row['created_at'] !=null)? moment(row['created_at']).format('DD.MM.YYYY hh:mm'):'-';
                            row.sortData = (row['created_at'] !=null)? moment(row['created_at']).format('DD.MM.YYYY hh:mm'):'-';
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
    
       
      customerQuoteTable =   $('.dpib_customer_doc').DataTable( {
                data: {!! $quoteData !!},
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
                order: [[5, "desc"]],
                pageLength: 10,
                displayLength: 10,
                autoFill: false,
                search: false,
                columnDefs:qcolumnDefs,
                language: {
                                paginate: {
                                    "first": '<i class="fa fa-angle-double-left"></i>',
                                    "last": '<i class="fa fa-angle-double-right"></i>',
                                    "next": '<i class="fa fa-angle-right"></i>',
                                    "previous": '<i class="fa fa-angle-left"></i>'
                                }
                            },
                dom: "Blftip"
     
    } ); 
   
</script>
@endsection