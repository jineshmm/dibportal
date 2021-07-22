@extends('layouts.elite',['notificationCount'=>$notificationCount  ] )

@section('content')

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                         <h4 class="card-title">Claims</h4> 
                                <div class="table-data__tool">
                                    <div class="table-data__tool-left">
                                                               {{ Form::open(array('route' => array('addclaim'), 'name' => 'form_claim_add','id'=>'form_claim_add','files'=>'true' )) }} 
                   <input type="hidden" name="customer_id" value="0" />
                   <input type="hidden" name="policy_id" value="0" />
                   {{ Form::close() }}
                                    </div>
                                  
                                    <div class="table-data__tool-right" style="float:right">
                                        <a class="dpib_policy_claim_add">    <button class="btn btn-info d-none d-lg-block m-l-15" id="dpib_add_claim">   <i class="fa fa-plus-circle"></i>add claim</button></a>
                  
                                    </div>
                                </div>
                                <!-- DATA TABLE-->
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dpib_claim_list color-table info-table">
                                              <thead>
                        <tr>
                            <th style="width: 15%" class="nowrap">Claim id</th>
                            <th style="width: 15%" class="nowrap">Policy number</th>    
                            <th style="width: 15%" class="nowrap">Customer</th>                           
                            <th  class="nowrap">Id code/Reg no</th>
                            <th  class="nowrap">Status</th>
                            <th  class="nowrap">Claimant</th>
                            <th  class="nowrap">Claim handler</th>
                            <th  class="nowrap">Loss date</th>
                            <th  class="nowrap">Submitted date</th>
                            <th  class="nowrap">Insly claim number</th>
                           
                        </tr>
                    </thead>
                                        
                                    </table>
                                </div>
                                <!-- END DATA TABLE-->
                            </div>
                        </div>
                        </div>
                        </div>
                        
                        
               
                  

@section('customcss')
  <link rel="stylesheet" type="text/css" href=" {{ asset('elitedesign/assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css') }} ">
    <link rel="stylesheet" type="text/css" href="{{ asset('elitedesign/assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css') }}"> 
@endsection

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
    var claimTable = '';
    var roleArray = @json(Auth::user()->roles);     
   
   $(function(){

        columnDefs.push({"name": 'claimid',  "targets": 0, data: function (row, type, val, meta) {
                
                             var urlString = '{!! route("overviewclaim",["##CID"]) !!}';
                            var link = urlString.replace("##CID", row['id']);    
                    
                           var subject =  "<a class='dp_claim_request_overview' openUrl='"+link+"' href='"+link+"'>"+row['id']+"</a>" ;
                            row.sortData = row['id'];
                            row.displayData = subject;
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
             columnDefs.push({"name": "policynumber",  "targets": 1, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['policy_number'];
                            row.sortData = row['policy_number'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});       
                
        columnDefs.push({"name": "customername",  "targets": 2, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'id/regno',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  (row['id_code'] !=null)? row['id_code']:'';
                            row.sortData = row['id_code'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        columnDefs.push({"name": 'Status',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var subject  =  row['statusString'];
                            row.sortData = row['statusString'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
        columnDefs.push({"name": 'Claimant',  "targets": 5, data: function (row, type, val, meta) {
                           
                            var objectString = generateClaimantString(row['claimant']);  
                            row.sortData = (objectString !='') ? objectString.slice(0, -1) :'' ;
                            row.displayData = (objectString !='') ? objectString.slice(0, -1) :'' ;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                
                    
        columnDefs.push({"name": 'Claimhandler',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['claimHandler'];
                            row.sortData = row['claimHandler'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'Loss date',  "targets": 7, "orderable": false, data: function (row, type, val, meta) {
                           
                            var subject =(row['incident_date'] !=null)? $.format.date( row['incident_date'], "dd.MM.yyyy HH:mm"):'';
                            row.sortData = row['incident_date'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                        
         columnDefs.push({"name": 'Submitted date',  "targets": 8, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['submitted_insurer_date'];
                            row.sortData = row['submitted_insurer_date'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});

                    
         columnDefs.push({"name": 'Insly claim number',  "targets": 9, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['insly_claim_id'];
                            row.sortData = row['insly_claim_id'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});           
                    

       
      claimTable =   $('.dpib_claim_list').DataTable( {
                data: {!! $claimDetails !!},
                autoWidth: true,
                stateSave: false,
                stateDuration: 60 * 60 * 24,
                responsive: true,
                deferRender: true,
                lengthChange: true,
                pagination: true,
                rowLength: true,
                scrollX: true,
                pagingType: 'simple_numbers',
                processing: false,
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
                dom: "Blftip"
     
    } );
    
                $(document).off('click', '.dpib_policy_claim_add');
                $(document).on('click', '.dpib_policy_claim_add', function(){
                          $('#form_claim_add').submit();
                });
    
    
  } );  
    
    
   
   function generateClaimantString(claimantString) {
                            var objectJson = JSON.parse(claimantString);
                            var objectString =(_.size(objectJson) >0) ? '':'-';
                            if(_.size(objectJson) >0) {
                                $.each(objectJson,function(key,value) {
                                  $.each(value,function(objkey,value){
                                         objectString +=(value !==null) ? objkey+':'+value+"," : '';
                                   
                                 });                             
                                   
                               })
                            }
                            return objectString
   }
  


</script>
@endsection

