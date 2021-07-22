<div class="card open">
    <div class="card-body"> 
    <div class="panel-heading">
    <ul class="panel-actions list-inline pull-right">
        <li class="dpib_policy_claim_add" customerId="{{$customerId}}" policyId="{{$policyId}}"><span class="panel-action-add"  data-toggle="tooltip" title="Add a claim" ><span class="fas fa-plus text-blue"></span></span></li>  
                    </ul>
        <h1 class="card-title col-3">Claims<small></small></h1> </div>
       
            <div class="table-responsive" style='width:100%;'>
   
                   {{ Form::open(array('route' => array('addclaim'), 'name' => 'form_claim_add','id'=>'form_claim_add','files'=>'true' )) }} 
                   <input type="hidden" name="customer_id" value="{{$customerId}}" />
                   <input type="hidden" name="policy_id" value="{{$policyId}}" />
                   {{ Form::close() }}

                <table class="display nowrap table table-hover table-striped table-bordered dataTable dpib_claim_list" width='100%'>
                    <thead>
                            <tr>
                            <th style="width: 5%" class="nowrap">Claim id</th>
                            <th style="width: 15%" class="nowrap">Policy number</th>    
                            <th style="width: 15%" class="nowrap">Customer</th>                           
                            <th  class="nowrap">Id code/Reg no</th>
                            <th  class="nowrap">Status</th>
                            <th  class="nowrap">Claimant</th>
                            <th  class="nowrap">Claim handler</th>
                            <th  class="nowrap">Loss date</th>
                            <th  class="nowrap">Submitted date</th>
                           
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
        </div> 

</div>

<script src="{{ asset('js/global/datatable/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/global/datatable/datetime.js') }}" type="text/javascript"></script>



 		

       
<script>
    var columnDefs = [];
    var claimTable = '';
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
                           
                            //var subject =(row['incident_date'] !=null)? $.format.date( row['incident_date'], "yyyy.MM.dd HH:mm"):'';
                            row.sortData = row['incident_date'];
                            var subject =  moment(row['incident_date']).format('DD.MM.YYYY HH:mm');
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                        
         columnDefs.push({"name": 'Submitted date',  "targets": 8, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['submitted_insurer_date'];
                            row.sortData = row['submitted_insurer_date'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});   
        
             
      claimTable =   $('.dpib_claim_list').DataTable({
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
    
    $(document).off('click', '.dpib_policy_claim_add');
          $(document).on('click', '.dpib_policy_claim_add', function(){
                    $('#form_claim_add').submit();
           });
    
       
   });
      
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
   