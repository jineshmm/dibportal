@extends('layouts.elite',['notificationCount'=>$notificationCount ] )


@section('content')

<div class="panel panel-default open card">
    <div class="panel-heading card-body">
        

        <h1 class="panel-title">Incomplete  requests<small></small></h1> </div><div class="panel-body"> 
            <div class="auto-scroll table-responsive" style='width:100%;'>
                <table class="table table-bordered table-striped dpib_policy_list color-table info-table" >
                    <thead>
                        <tr>
                            <th style="width: 10%" class="nowrap">Request Id</th>                                                      
                            <th style="width: 10%">Type</th>
                            <th style="width: 20%" class="nowrap">Policy</th>
                            <th style="width: 20%" class="nowrap">Customer</th>
                            <th style="width: 20%" class="nowrap">Description</th> 
                            <th  class="nowrap" style="width: 10%">Status</th>
                            <th  class="nowrap" style="width: 10%">Created by</th>                            
                            <th  class="nowrap" style="width: 10%">Updated date</th>
                            
                            
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
<script src="{{ asset('js/dibcustom/dib-quote-request.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/dibcustom/dib-policy-add.js') }}" type="text/javascript"></script>
@endsection
 		
@section('pagescript')


 		

       
<script>
    var columnDefs = [];
    var quoterequestTable = '';
    var roleArray = @json(Auth::user()->roles);
   $(function(){
    columnDefs.push({"name": 'requestid',  "targets": 0, data: function (row, type, val, meta) {
                
                            var urlString = '{!! route("overviewendorsementcrmrequest",["##RID","##PID"]) !!}';
                            var link = urlString.replace("##PID", row['id']).replace("##RID", row['policy_id']);
                            var linkFlag = true; 
                        
                            var subject = "<a href='"+link+"'>"+ row['request_id']+"</a>";
                            row.sortData = row['request_id'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": "request_type",  "targets": 1, "orderable": true,data: function (row, type, val, meta) {
                            var subject = getRequestType(row['type'])
                            row.sortData = subject;
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
       columnDefs.push({"name": "policies",  "targets": 2, "orderable": true,data: function (row, type, val, meta) {
                           

                            var urlString = '{!! route("policyoverview",["##PID"]) !!}';
                            var link = urlString.replace("##PID", row['policy_id']);
                             row.sortData = row['policy_number'];
                            row.displayData = "<a href='"+link+"'>"+ row['policy_number']+"</a>";
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});


     
        columnDefs.push({"name": "request_customer",  "targets": 3, "orderable": true,data: function (row, type, val, meta) {
                  var urlString = '{!! route("customeroverview",["##CID"]) !!}';
                            var link = urlString.replace("##CID", row['customerId']);
                             row.sortData = row['customerName'];
                            row.displayData = "<a href='"+link+"'>"+ row['customerName']+"</a>";
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});

                    
        columnDefs.push({"name": 'description',  "targets": 4, "orderable": true, data: function (row, type, val, meta) {
                            var subject =row['description'] ;
                            row.sortData = row['description'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
      columnDefs.push({"name": 'status',  "targets": 5, data: function (row, type, val, meta) {
              
                        row.sortData = row['status'];
                        row.displayData = row['statusString'] ;                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": 'createdby',  "targets": 6, "orderable": true, data: function (row, type, val, meta) {
                            var subject =  row['userName'];
                            row.sortData = row['userName'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
                   
                    
        columnDefs.push({"name": 'updatedDate',"type":"date" , "targets": 7, "orderable": true, data: function (row, type, val, meta) {
                            var subject = moment(row['updated_at']).format('DD.MM.YYYY HH:mm');
                            row.sortData =row['updated_at'];
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        
                    
                  
                    
        
       
      quoterequestTable =   $('.dpib_policy_list').DataTable( {
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
                order: [[7, "desc"]],
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
                dom: "Blftip"
     
    } ); 

   });
    
        
    

       
  
   function getRequestType(typeId) {

        var requestTypeArray =['','Addition', 'CCHI',  'Deletion', 'Downgrade',  'Corrections',  'Certificate','Najam upload', 'Invoices Request', 'Upgrade',   'Others','Approvals','Request quatations','Active list'];
        requestTypeArray[17]='Announcement';
            
                               return requestTypeArray[typeId];
   
   }


</script>
     

@endsection