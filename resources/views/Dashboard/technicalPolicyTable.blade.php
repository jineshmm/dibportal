@extends('layouts.elite',['notificationCount'=>$notificationCount ] )


@section('content')

<div class="panel panel-default open card">
    <div class="panel-heading card-body" >

            <ul class="panel-actions list-inline pull-right dib_head" >

<!--                                <li ><a class="dpib_quote_request_add large-size"  href='{!! route("createpolicy") !!}'><span class="icon-add fas fa-plus large-size" data-toggle="tooltip" title="" data-original-title="Add policy" data-toggle="modal" data-target="#dpib_quote_request_add"></span></a></li>-->


            </ul>
        <h1 class="panel-title" ><small>Policy</small></h1>
    </div>
                            <div class="panel-body"> 
            <div class="auto-scroll table-responsive" style='width:100%;'>
                
        
                <table class="table table-bordered table-striped dpib_policy_list color-table info-table" >
                    <thead>
                        <tr>
                            <th style="width: 5%" class="nowrap">Policy No</th>
                            <th style="width: 5%" class="nowrap">Insurer</th>                      
                            <th  class="nowrap" >Validity</th>
                            <th  class="nowrap">Inception date</th>
                            <th  class="nowrap">Expiry date</th>
                            <th  class="nowrap">Issue date</th>
                            <th  class="nowrap">Customer</th>
                            <th  class="nowrap">Object</th>
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
<script src="{{ asset('js/dibcustom/dib-quote-request.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/dibcustom/dib-policy-add.js') }}" type="text/javascript"></script>
@endsection
 		
@section('pagescript')


 		

       
<script>
    var columnDefs = [];
    var policyTable = '';


      var roleArray = @json(Auth::user()->roles);

   $(function(){
    
        columnDefs.push({"name": 'policyno',  "targets": 0, data: function (row, type, val, meta) {
                
                            var urlString = '{!! route("policyoverview",["##RID"]) !!}';
                            var link = urlString.replace("##RID", row['mainId']);
                             @if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) 
                             var subject =   (row['policy_number'] !==null) ? "<a href='"+link+"'>"+ row['policy_number']+"</a>": "<a href='"+link+"'>"+"---not issued---"+"</a>";
                         @else
                              var subject =   (row['policy_number'] !==null) ?  row['policy_number']: "---not issued---";
                        @endif
                            var subject =   (row['policy_number'] !==null) ? "<a href='"+link+"'>"+ row['policy_number']+"</a>": "<a href='"+link+"'>"+"---not issued---"+"</a>";
                            row.sortData = row['policy_number'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": "insurer",  "targets": 1, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['insurer_name'];
                            row.sortData = row['insurer_name'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'validity',  "targets": 2, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['startDate']+ " - " +row['endDate'] ;
                            row.sortData = row['start_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  


        columnDefs.push({"name": 'inceptiondate',  "targets": 3, "orderable": true, data: function (row, type, val, meta) {
                            var subject = (row['startDate'] !=null)? row['startDate']:'-';
                            row.sortData = (row['startDate'] !=null)? row['startDate']:'-';
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 

          columnDefs.push({"name": 'enddate',  "targets": 4, "orderable": true, data: function (row, type, val, meta) {
                            var subject =(row['endDate'] !=null)? row['endDate']:'-';
                            row.sortData = (row['endDate'] !=null)? row['endDate']:'-';
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
         columnDefs.push({"name": 'issue_date',  "targets": 5, "orderable": true, data: function (row, type, val, meta) {
                            var subject =(row['issue_date'] !=null)? moment(row['issue_date']).format('DD.MM.YYYY'):'-';
                            row.sortData = (row['issue_date'] !=null)? moment(row['issue_date']).format('DD.MM.YYYY'):'-';
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 

        columnDefs.push({"name": 'customer',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
        columnDefs.push({"name": 'object',  "targets": 7, data: function (row, type, val, meta) {
                       var subject = row['product_name'];
                           
                            
                            var objectJson = JSON.parse(row['objectdetails']);
                            var objectString =(row['product_name']!=null) ? row['product_name']+'<br>':'-';
                            if(_.size(objectJson) >0) {
                                $.each(objectJson,function(key,value){
                                  objectString+= createObjectColumnValue(value,value.object_type);                                
                                   
                               })
                            }
                           // newString = (objectString !='') ? objectString.slice(0, -1) :'' ;
                            row.sortData = (objectString !='') ? objectString.slice(0, -1) :'' ;
                            row.displayData = (objectString !='') ? objectString.slice(0, -1) :'' ;                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                
                    
        columnDefs.push({"name": 'status',  "targets": 8, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['statusString'];
                            row.sortData =row['statusString'];;
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                  
                    
        
       
      policyTable =   $('.dpib_policy_list').DataTable( {
                data: {!! $allpolicies !!},
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
                order: [[2, "desc"]],
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
   function createObjectColumnValue(objectJson,objectType) {
        var objectString =''; 
        var personArray =['address','gender','last_name','dob'];
        var vehicleArray =['make','model','year','license_plate'];
        var propertyArray = ['property_type','year_built','area','construction_material'];

        $.each(objectJson,function(key,value){
              
                                 if(objectType =='person' && $.inArray( key, personArray )> -1) {
                                   objectString +=(value !==null) ? value+"," : '';
                                 } else if(objectType =='vehicle' && $.inArray( key, vehicleArray )> -1){
                                    objectString +=(value !==null) ? value+",": ''; 
                                 } else if(objectType =='property' && $.inArray( key, propertyArray )> -1){
                                    objectString +=(value !==null) ? value+",": '';
                                 }
                                 
                                   
                               });
                               
                               return objectString;
   
   }


</script>
@endsection

