@extends('layouts.elite',['notificationCount'=>$notificationCount ] )


@section('content')

<div class="panel panel-default open card">
    <div class="panel-heading card-body">

        <h1 class="card-title">Requests<small></small></h1> 
    </div>
    <div class="panel-body"> 
            <div class="auto-scroll" style='width:100%;'>
                <table id="myTable" class="table table-bordered table-striped dpib_quote_request_list color-table info-table">
                                        <thead>
                                       <tr>
                            <th style="width: 15%" class="nowrap">Request id</th>
                            <th style="width: 15%" class="nowrap">Customer name</th>                           
                            <th  class="nowrap" style="width: 10%">Type</th>
                            <th  class="nowrap" style="width: 15%">Description</th>
                            <th  class="nowrap" style="width: 10%">Status</th>
                            <th  class="nowrap" style="width: 10%">Created at</th>
                            <th  class="nowrap" style="width: 15%">Last modified at</th>
                           
                        </tr>
                                        </thead>
                                        
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
<script src="{{ asset('js/dibcustom/dib-quote-request.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/dibcustom/dib-policy-add.js') }}" type="text/javascript"></script>
    
@endsection

 @section('pagescript') 
       
<script>
    var columnDefs = []
       
    var quoterequestTable = ''
   
    var roleArray = @json(Auth::user()->roles);

   $(function(){
    
         columnDefs.push({"name": 'requetid',  "targets": 0, data: function (row, type, val, meta) {
                
                            var urlString = '{!! route("crmrequestOverview",["##RID"]) !!}';
                            var link = urlString.replace("##RID", row['mainId']);
                            var linkFlag = true; 
                            
                             if(($.inArray( "ROLE_TECHNICAL_MANAGER", roleArray ) > -1) || ($.inArray( "ROLE_TECHNICAL", roleArray ) > -1) ) {
                               switch(row['status']) {
                                case 2:case 3:case 4: case 5: case 6:
                                    linkFlag =true;
                                break;
                            default:
                                linkFlag =false;
                                   
                               }
                                 
                                 
                             }   else if(($.inArray( "ROLE_SALES_MANAGER", roleArray ) > -1) || ($.inArray( "ROLE_SALES", roleArray ) > -1) ) {
                               switch(row['status']) {
                                   
                                case 0:case 1:case 4:case 7: case 8: case 9:case 10:
                                    linkFlag =true;
                                break;
                            default:
                                linkFlag =false;
                                   
                               }  
                             }
                    
                            var subject = (linkFlag) ?  "<a class='dp_quote_request_overview' openUrl='"+link+"' href='"+link+"'>"+row['crm_request_id']+"</a>" : row['crm_request_id'];
                            row.sortData = row['crm_request_id'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": "customername",  "targets": 1, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'type',  "targets": 2, "orderable": false, data: function (row, type, val, meta) {
                            var subject ='';
                            if(row['type']==0) {
                                subject = 'Task';
                            } else if(row['type']==1) {
                                subject = 'Request';
                            } else {
                                subject = "<span class='capital-first font-bold text-danger'>Renewal</span>";
                            }
                            row.sortData = row['type'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        columnDefs.push({"name": 'Description',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject = (row['type']==0) ? row['subject']:row['description'];
                            row.sortData = (row['type']==0) ? row['subject']:row['description'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
        columnDefs.push({"name": 'status',  "targets": 4, data: function (row, type, val, meta) {
                            var newclass = getStatusColor(row['status']);
                             var subject = "<span class='capital-first "+newclass+"'>"+row['statusString']+"</span>";
                             row.sortData = row['statusString']; 
                            if((($.inArray( "ROLE_SALES_MANAGER", roleArray ) > -1) || ($.inArray( "ROLE_SALES", roleArray ) > -1)) &&  ($.inArray( row['status'], [2,5,6] ) > -1) ) {
                               row.sortData = 'Pending with technical department'; 
                              subject ="<span class='capital-first'>Pending with technical department</span>";   
                            } else if((($.inArray( "ROLE_SALES_MANAGER", roleArray ) > -1) || ($.inArray( "ROLE_SALES", roleArray ) > -1)) &&  ($.inArray( row['status'], [0,1] ) > -1) ) {
                               subject ="<span class='capital-first "+newclass+"'>New</span>";
                               row.sortData = 'New'; 
                            }
                            
                            
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                
                    
        columnDefs.push({"name": 'createdat',  "targets": 5, "orderable": false, data: function (row, type, val, meta) {
                            var subject = (row['created_date'] !=null)? $.format.date( row['created_date'], "dd.MM.yyyy HH:mm"):'';
                            row.sortData =(row['created_date'] !=null)? $.format.date( row['created_date'], "dd.MM.yyyy HH:mm"):'';
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'updatedat',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var subject = (row['updated_date'] !=null)? $.format.date( row['updated_date'], "dd.MM.yyyy HH:mm"):'';
                            row.sortData = (row['updated_date'] !=null)? $.format.date( row['updated_date'], "dd.MM.yyyy HH:mm"):'';
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                   
                    
        
       
      quoterequestTable =   $('.dpib_quote_request_list').DataTable( {
                data: {!! $requestData !!},
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
                order: [[6, "asc"]],
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
    function getStatusColor(status) {
    var newclass='';
        switch(status){
        case 0:
            newclass='process';
            break;
        case 1:
            newclass='process';
            
            break;
        case 2:
            newclass='review';
          
            break;
        case 3:
              newclass='approved';
            
            break;
        case 4:
            newclass='quoteupload';
            
            break;
        case 5:
            newclass='revisequotation';
            break;
        case 6:
            newclass='requestpolicy';
            break;
        case 7:
            newclass='policyupload';
            break;
        case 8:
            newclass='reject';
            break;
        case 9:
            newclass='complete';
            break;
        case 10:
            newclass='denied';
            break;
            
        
    }
    
    return newclass;
   
   }


</script>
@endsection