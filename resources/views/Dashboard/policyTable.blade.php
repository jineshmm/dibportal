@extends('layouts.elite',['notificationCount'=>$notificationCount ] )


@section('content')

<div class="panel panel-default open card">
    <div class="panel-heading card-body">

        <h1 class="card-title">Post Policies<small></small></h1> 
    </div>
    <div class="panel-body"> 
            <div class="auto-scroll" style='width:100%;'>
                <table class="table table-bordered table-striped dpib_policy_list color-table info-table" >
                    <thead>
                        <tr>
                            <th style="width: 5%" class="nowrap">Policy No</th>
                            <th style="width: 5%" class="nowrap">Insurer</th>                      
                            
                            <th  class="nowrap" >Inception date</th>
                            <th  class="nowrap" >End date</th>
                            <th  class="nowrap" >Issue date</th>
                            <th  class="nowrap" >Customer</th>
                            <th  class="nowrap" >Object</th>
                            <th  class="nowrap" >Status</th>  
                            <th  style="width:15%" class="nowrap" >Action</th>
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
        </div> 
    
</div>





<div class="panel panel-default open card">
    <div class="panel-heading card-body">

        <h1 class="card-title">Approved Policies<small></small></h1>
    </div>
    <div class="panel-body"> 
            <div class="auto-scroll" style='width:100%;'>
                <table class="table table-bordered table-striped dpib_approved_policy_list color-table info-table" >
                    <thead>
                        <tr>
                            <th style="width: 5%" class="nowrap">Policy No</th>
                            <th style="width: 5%" class="nowrap">Insurer</th>                      
                           
                            <th  class="nowrap" >Inception date</th>
                            <th  class="nowrap" >End date</th>
                            <th  class="nowrap" >Issue date</th>
                            <th  class="nowrap" >Customer</th>
                            <th  class="nowrap" >Object</th>
                            <th  class="nowrap" >Status</th>                            
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
        </div> 
    
</div>

 <script id='policy_issued_template' type='text/template'>
    
 {{ Form::open(array('route' => array('changepolicystatus','##POLID##'), 'name' => 'form_policy_status_change','id'=>'form_policy_status_change','files'=>'true' )) }}

    <div class="dialogform"><table class="insly_dialogform">
                <tbody>                    
                    <tr id="field_request_type" class="field">
                   
                        <td>
                            Do you really want to activate policy?
                    
                                <input type='hidden' name='flag' value=2>
                                    
                            
                        </td>
                    </tr>
                    
                    
                    

                </tbody></table></div>
    {{ Form::close() }}   
    
    
</script>

<script id='policy_rejected_template' type='text/template'>
    
 {{ Form::open(array('route' => array('rejectpolicy'), 'name' => 'form_policy_reject','id'=>'form_policy_reject','files'=>'true' )) }}

    <div class="dialogform"><table class="insly_dialogform">
                <tbody>                    
                    <tr id="field_reject_reason" class="field">
                  
                        <td>
                            
                           Reject reason
                               
                                    <input type="hidden" id="reject_policyId" name="reject_policy_id" value="<%- policyId %>"  >
                 
                        </td>
                        <td>
                            <div class="element"><textarea id="reject_reason" name="reject_reason" wrap="soft" rows="4" autocomplete="off" maxlength="255" class="editor col-md-12 form-control" required error-message="Reject reason is mandatory"></textarea>
<span id="error-message" style="display:none">Reject reason is mandatory</span></div>

                            <td>
                    </tr>
                    
                    
                    

                </tbody></table></div>
    {{ Form::close() }}   
    
    
</script>
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
<script src="{{ asset('js/global/datatable/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/global/datatable/datetime.js') }}" type="text/javascript"></script>
    
@endsection

 @section('pagescript') 
       
<script>
    var columnDefs = []
        columnDefs1 = [];
    var policyTable = ''
    approvedpolicyTable = '';
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



                    
//        columnDefs.push({"name": 'validity',  "targets": 2, "orderable": false, data: function (row, type, val, meta) {
//                            var subject =row['startDate']+ " - " +row['endDate'] ;
//                            row.sortData = row['start_date'];
//                            row.displayData = subject;                           
//                            
//                            return row;
//                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
        columnDefs.push({"name": 'inception_date',  "targets": 2, "orderable": true, data: function (row, type, val, meta) {
                            var subject =row['startDate'] ;
                            row.sortData = row['start_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
        columnDefs.push({"name": 'end_date',  "targets": 3, "orderable": true, data: function (row, type, val, meta) {
                            var subject =row['endDate'] ;
                            row.sortData = row['endDate'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
        columnDefs.push({"name": 'issue_date',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var subject = moment(row['issue_date']).format('DD.MM.YYYY');
                            row.sortData = moment(row['issue_date']).format('DD.MM.YYYY');
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                    
                    
                    
                    
        columnDefs.push({"name": 'customer',  "targets": 5, "orderable": true, data: function (row, type, val, meta) {

                            var urlString = '{!! route("customeroverview",["##CID"]) !!}';
                            var link = urlString.replace("##CID", row['customer_id']);
                            var subject =  row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = "<a href='"+link+"'>"+ row['customerName']+"</a>";                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
        columnDefs.push({"name": 'object',  "targets": 6, data: function (row, type, val, meta) {
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
                    
        columnDefs.push({"name": 'status',  "targets": 7, "orderable": true, data: function (row, type, val, meta) {
                            var subject = row['statusString'];
                            row.sortData =row['policy_status'];;
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
         columnDefs.push({"name": 'action',  "targets": 8, "orderable": false, data: function (row, type, val, meta) {
                            row.displayData = '<a class="dib-cursor-style dpib_policy_edit"  policy_id =' + row['mainId'] + ' data-toggle="tooltip" title="" data-original-title="Activate policy" data-placement="top" data-container=".panel-body"><span class="mdi mdi-thumb-up-outline" ></span></a><a class="dib-cursor-style dpib_policy_reject"  policy_id =' + row['mainId'] + ' data-toggle="tooltip" title="" data-original-title="Reject policy" style="margin-left:10px" data-container="dpib_policy_list"><span class="mdi mdi-thumb-down" ></span></a>' ;
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
                dom: "Bfrtip"
     
    } ); 
    
    //approvedPolicies
    
     columnDefs1.push({"name": 'policyno',  "targets": 0, data: function (row, type, val, meta) {
                
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
        columnDefs1.push({"name": "insurer",  "targets": 1, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['insurer_name'];
                            row.sortData = row['insurer_name'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
//        columnDefs1.push({"name": 'validity',  "targets": 2, "orderable": false, data: function (row, type, val, meta) {
//                            var subject =row['startDate']+ " - " +row['endDate'] ;
//                            row.sortData = row['start_date'];
//                            row.displayData = subject;                           
//                            
//                            return row;
//                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
                        
         columnDefs1.push({"name": 'inception_date',  "targets": 2, "orderable": true, data: function (row, type, val, meta) {
                            var subject =row['startDate'] ;
                            row.sortData = row['start_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                        
        columnDefs1.push({"name": 'end_date',  "targets": 3, "orderable": true, data: function (row, type, val, meta) {
                            var subject =row['endDate'] ;
                            row.sortData = row['endDate'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
        columnDefs1.push({"name": 'issue_date',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var subject = moment(row['issue_date']).format('DD.MM.YYYY');
                            row.sortData = moment(row['issue_date']).format('DD.MM.YYYY');
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});               
                        
                        
        columnDefs1.push({"name": 'customer',  "targets": 5, "orderable": true, data: function (row, type, val, meta) {
                            var subject =  row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
        columnDefs1.push({"name": 'object',  "targets": 6, data: function (row, type, val, meta) {
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
                    
        columnDefs1.push({"name": 'status',  "targets": 7, "orderable": true, data: function (row, type, val, meta) {
                            var subject = row['statusString'];
                            row.sortData =row['policy_status'];;
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                  
                    
        
       
      approvedpolicyTable =   $('.dpib_approved_policy_list').DataTable( {
                data: {!! $approvedPolicies !!},
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
                columnDefs:columnDefs1,
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
    
    
            
            
             $(document).off('click', '.dpib_policy_edit');
        $(document).on('click', '.dpib_policy_edit', function () {
            var policyId = $(this).attr('policy_id');
         
                var template = _.template($("#policy_issued_template").html());
                var result = template();
                $("#db_policy_change_status_popup").remove();
                var replaceHtml = result.replace('##POLID##',policyId);
                $('body').append('<div id="db_policy_change_status_popup" title="Issue/activate policy" style="display:none" >' + replaceHtml + '</div>');
                var dialogElement = $("#db_policy_change_status_popup");
                dialogElement.dialog({
                    width: 900,                    
                    modal: true,
                    buttons: {
                        "Activate": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:'Activate',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                              
                                    $("#policy_number").removeClass('error');
                                    $("form#form_policy_status_change").submit();
                                    $("#policy_number").removeClass('error');
                                
                            }
                        },
                        "cancel": {
                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                             text:'Cancel',
                            click: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
                    }
                });
                DIB.centerDialog();
           
       
        });


        $(document).off('click','.dpib_policy_reject');
    $(document).on('click','.dpib_policy_reject',function(){   
    
     var template = _.template($("#policy_rejected_template").html());
     
                var result = template({'policyId':$(this).attr('policy_id')});
                $("#db_policy_issued_popup").remove();
                $('body').append('<div id="db_policy_issued_popup" title="Reject policy" style="display:none" >' + result + '</div>');
                var dialogElement = $("#db_policy_issued_popup");
                dialogElement.dialog({
                    width: 900,
                   
                    modal: true,
                    buttons: {
                        "Reject": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:"Reject",
                            click: function () {
                               $("#field_reject_reason").find('.element').removeClass('has-danger');
                               $("#error-message").hide();

                                if($.trim($("#reject_reason").val()) =='') {
                                    $("#field_reject_reason").find('.element').addClass('has-danger');
                                    $("#error-message").show();
                                
                                    return false;
                                }
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));                                
                                    
                                    $("form#form_policy_reject").submit();
                                  
                                
                            }
                        },
                        "cancel": {
                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                                    text:"cancel",
                            click: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
                    }
                });
                DIB.centerDialog();
                
       
            });
    
    

    
    
    
       
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