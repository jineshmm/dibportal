

<div class="row">
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Endorsement</h5>
                <div class="d-flex no-block align-items-center m-t-20 m-b-20">
                    <div id="sparklinedash"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                    <div class="ml-auto">
                        <h2 class="text-success"><i class="ti-arrow-up"></i> <span class="counter">{{$dashboardDetails['endorsementcount']}}</span></h2>
                    </div>
                </div>
            </div>
            <div id="sparkline8" class="sparkchart"></div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Claim</h5>
                <div class="d-flex no-block align-items-center m-t-20 m-b-20">
                    <div id="sparklinedash2"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                    <div class="ml-auto">
                        <h2 class="text-purple"><i class="ti-arrow-up"></i> <span class="counter">{{$dashboardDetails['claimcount']}}</span></h2>
                    </div>
                </div>
            </div>
            <div id="sparkline8" class="sparkchart"></div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Complaint</h5>
                <div class="d-flex no-block align-items-center m-t-20 m-b-20">
                    <div id="sparklinedash3"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                    <div class="ml-auto">
                        <h2 class="text-info"><i class="ti-arrow-up"></i> <span class="counter">{{$dashboardDetails['complaintcount']}}</span></h2>
                    </div>
                </div>
            </div>
            <div id="sparkline8" class="sparkchart"></div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Endorsement sum</h5>
                <div class="d-flex no-block align-items-center m-t-20 m-b-20">
                    <div id="sparklinedash4"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                    <div class="ml-auto">
                        <h2 class="text-danger"><i class="ti-arrow-up"></i> <span class="counter">{{ number_format($dashboardDetails['endorsemetsum'], 0, '.', ',')   }}</span></h2>
                    </div>
                </div>
            </div>
            <div id="sparkline8" class="sparkchart"></div>
        </div>
    </div>
    <!-- Column -->
</div>
<div class="row">
        <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Endorsement line Chart</h4>
                <ul class="list-inline text-right">
                    <li>
                        <h5><i class="fa fa-circle m-r-5 text-success"></i>Addition</h5>
                    </li>
                    <li>
                        <h5><i class="fa fa-circle m-r-5 text-info"></i>Deletion</h5>
                    </li>
                    <li>
                        <h5><i class="fa fa-circle m-r-5 text-inverse"></i>Downgrade</h5>
                    </li>
                    <li>
                        <h5><i class="fa fa-circle m-r-5" style="color:#FBA78E"></i>Upgrade</h5>
                    </li>
                </ul>
                <div id="leadsgraph"></div>
            </div>
        </div>
    </div>
</div>


<div class="row">

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Claims</h4>

                <div class="table-responsive m-t-40">
                    <table  class="table table-bordered table-striped dpib_claim_list color-table info-table">
                        <thead>
                        <th style="width: 15%" class="nowrap">Claim id</th>
                        <th style="width: 15%" class="nowrap">Policy number</th>    
                        <th style="width: 15%" class="nowrap">Customer</th>                           
                        <th  class="nowrap">Id code/Reg no</th>
                        <th  class="nowrap">Status</th>
                        <th  class="nowrap">Claimant</th>
                        <th  class="nowrap">Claim handler</th>
                        <th  class="nowrap">Loss date</th>
                        <th  class="nowrap">Submitted date</th>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
 <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Complaints</h4>

                <div class="table-responsive m-t-40">
                    
                    <table class="table table-bordered table-striped dpib_policy_list color-table info-table" >
                    <thead>
                        <tr>
                            <th style="" class="nowrap">Complaint no:</th>                                                      
                            <th>Type</th>
                            <th style="" class="nowrap">Client name</th>
                            <th style="" class="nowrap">Policy</th> 
                            <th  class="" >Requested date</th>
                            <th  class="" >Remarks</th>
                            <th  class="" >Validity</th>                            
                            <th  class="" >Status</th>
                            <th  class="" >Created date</th>
                            <th  class="" >Updated date</th>
                            
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

<div class="row">
        <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Customers</h4>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped dpib_leads_table color-table info-table" >
                        <thead>
                            <tr>
                                <th style="width: 15%" class="nowrap">customerName</th>
                                <th  class="nowrap">customer_idcode</th>                           
                                <th  class="nowrap">customer_customercode</th>
                                <th  class="nowrap">customer_type</th>
                                <th  class="nowrap">customer_email</th>
                                <th  class="nowrap">customer_phone</th>
                                <th  class="nowrap">saleschannel_name</th>
                                <th  class="nowrap">customergroup_name</th>

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
@section('innerpagescript')
<script>

$(function(){
   var pcolumnDefs = [];
    var claimTable = '';
    
   
   

        pcolumnDefs.push({"name": 'claimid',  "targets": 0, data: function (row, type, val, meta) {
                
                             var urlString = '{!! route("overviewclaim",["##CID"]) !!}';
                            var link = urlString.replace("##CID", row['id']);    
                    
                           var subject =  "<a class='dp_claim_request_overview' openUrl='"+link+"' href='"+link+"'>"+row['id']+"</a>" ;
                            row.sortData = row['id'];
                            row.displayData = subject;
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
             pcolumnDefs.push({"name": "policynumber",  "targets": 1, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['policy_number'];
                            row.sortData = row['policy_number'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});       
                
        pcolumnDefs.push({"name": "customername",  "targets": 2, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        pcolumnDefs.push({"name": 'id/regno',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  (row['id_code'] !=null)? row['id_code']:'';
                            row.sortData = row['id_code'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        pcolumnDefs.push({"name": 'Status',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var subject  =  row['statusString'];
                            row.sortData = row['statusString'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
        pcolumnDefs.push({"name": 'Claimant',  "targets": 5, data: function (row, type, val, meta) {
                           
                            var objectString = generateClaimantString(row['claimant']);  
                            row.sortData = (objectString !='') ? objectString.slice(0, -1) :'' ;
                            row.displayData = (objectString !='') ? objectString.slice(0, -1) :'' ;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                
                    
        pcolumnDefs.push({"name": 'Claimhandler',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['claimHandler'];
                            row.sortData = row['claimHandler'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        pcolumnDefs.push({"name": 'Loss date',  "targets": 7, "orderable": false, data: function (row, type, val, meta) {
                           
                            var subject =(row['incident_date'] !=null)? $.format.date( row['incident_date'], "dd.MM.yyyy HH:mm"):'';
                            row.sortData = (row['incident_date'] !=null)? $.format.date( row['incident_date'], "dd.MM.yyyy HH:mm"):'';
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                        
         pcolumnDefs.push({"name": 'Submitted date',  "targets": 8, "orderable": false, data: function (row, type, val, meta) {
                            var subject = (row['submitted_insurer_date'] !=null)? $.format.date( row['submitted_insurer_date'], "dd.MM.yyyy HH:mm"):'';
                            row.sortData = (row['submitted_insurer_date'] !=null)? $.format.date( row['submitted_insurer_date'], "dd.MM.yyyy HH:mm"):'';
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                 
                    
                   
                    

       
      claimTable =   $('.dpib_claim_list').DataTable( {
                data: {!! $dashboardDetails['claimDetails']  !!},
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
                columnDefs:pcolumnDefs,
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
                
    //Complaints
    
    var comcolumnDefs = [];
    var complaintTable = '';
   
        comcolumnDefs.push({"name": 'complaintnumber',  "targets": 0, data: function (row, type, val, meta) {
                
                            var urlString = '{!! route("complaintoverview",["##CID"]) !!}';
                            var link = urlString.replace("##CID", row['id']);
                            var linkFlag = true; 
                        
                            var subject = "<a href='"+link+"'>"+ row['id']+"</a>";
                            row.sortData = row['id'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        comcolumnDefs.push({"name": "complaint_type",  "targets": 1, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['complaintType'];
                            row.sortData = row['complaintType'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
       comcolumnDefs.push({"name": "clientname",  "targets": 2, "orderable": false,data: function (row, type, val, meta) {
                            var subject =  row['clientName'];
                            row.sortData = row['clientName'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        comcolumnDefs.push({"name": 'policy',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['policy_number'] ;
                            row.sortData = row['policy_number'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        comcolumnDefs.push({"name": 'requested_date',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var subject = (row['requested_date'] !=null)? $.format.date( row['requested_date'], "dd.MM.yyyy HH:mm"):''; 
                            row.sortData =(row['requested_date'] !=null)? $.format.date( row['requested_date'], "dd.MM.yyyy HH:mm"):'';
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
     comcolumnDefs.push({"name": 'status',  "targets": 5, "orderable": false, data: function (row, type, val, meta) {
                            var subject = '';
                            
                            if( row['request_status']==1 ) {
                              subject = '<span class="badge badge-success badge-danger">'+row['statusString']+'</span>'; 
                            } else {
                              subject = '<span class="badge badge-success badge-pill">'+row['statusString']+'</span>';   
                            }
                          
                            row.sortData =row['statusString'];
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                   
                        
      comcolumnDefs.push({"name": 'remarks',  "targets": 6, data: function (row, type, val, meta) {
              
                        row.sortData = row['remarks'];
                        row.displayData = row['remarks'];                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        comcolumnDefs.push({"name": 'validity',  "targets": 7, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  row['complaintValidity'];
                            row.sortData = row['complaintValidity'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
                   
                    
        
                    
         comcolumnDefs.push({"name": 'created_date',  "targets": 8, "orderable": false, data: function (row, type, val, meta) {
                                             
                     var subject = (row['created_at'] !=null)? $.format.date( row['created_at'], "dd.MM.yyyy HH:mm"):''; 
                            row.sortData =(row['created_at'] !=null)? $.format.date( row['created_at'], "dd.MM.yyyy HH:mm"):'';
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
         comcolumnDefs.push({"name": 'updated_date',  "targets": 9, "orderable": false, data: function (row, type, val, meta) {
                                             
                           var subject = (row['updated_at'] !=null)? $.format.date( row['updated_at'], "dd.MM.yyyy HH:mm"):''; 
                            row.sortData =(row['updated_at'] !=null)? $.format.date( row['updated_at'], "dd.MM.yyyy HH:mm"):''; 
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                   
          
                  
                    
        
       
      complaintTable =   $('.dpib_policy_list').DataTable( {
                data: {!! $dashboardDetails['complaintDetails']  !!},
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
                columnDefs:comcolumnDefs,
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
    
})


</script>
@endsection









