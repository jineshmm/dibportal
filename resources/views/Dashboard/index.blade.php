@extends('layouts.elite',['notificationCount'=>$notificationCount ] )
@section('createnewbutton')
@if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES', Auth::user()->roles))
<button type="button" class="btn btn-info d-none d-lg-block m-l-15 dpib_client_request_add" ><i
                                    class="fa fa-plus-circle"></i> Add request</button>
@endif                                    
@endsection

@section('headtitle')
Dashboard
@endsection




@section('content')
                   
                @if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) 

                            @include('Dashboard.technicaldashboard');
                  
                   
                     @elseif (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES', Auth::user()->roles)) 

                            @include('Dashboard.salesdashboard');
                            
                    @elseif (in_array('ROLE_OPERATION_MANAGER', Auth::user()->roles) || in_array('ROLE_OPERATION', Auth::user()->roles)) 

                            @include('Dashboard.operationdashboard');         
                            
                  @elseif (in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles)) 

                            @include('Dashboard.financedashboard');    
                      @endif                          
                        
                       @if( (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES', Auth::user()->roles)) && $taskFlag || in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles) )
            <div class="row">
   <div class="col-lg-12">          
                       
                       <div class="card">
                          <div class="card-body">
                            <h4 class="card-title">Reminders</h4>  
                               <div class="table-data__tool">
                                    <div class="table-data__tool-left">
                                    </div>
                                    <div class="table-data__tool-right">
                                        <span class="dpib_policy_claim_add"> 
                                            <button type="button" class="btn btn-info d-none d-lg-block m-l-15" id="dpib_add_task_notification" data-toggle="modal" data-target="#db_task_notification_popup"><i
                                    class="fa fa-plus-circle"></i> Add reminders</button>
                                            
                                            </span>
             
                                    </div>
                                </div>
                             <div class="table-responsive m-t-40">
                            <table class="table table-bordered table-striped dpib_task_notification_table color-table info-table">
                                        <thead>
                                            <tr>
                                                
                                                <th>No</th>
                                                <th>Date</th>
                                                <th>Description</th>
                                                <th>Priority</th>
                                                <th>Created by</th> 
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             @if(count($taskDetails) > 0)  
                                            @foreach($taskDetails as $key => $tasknotification)
                                            <tr class="tr-shadow">
                                                                                        
                                                <td><span class="block-email">{{$key+1}}</span></td>
                                                <td class="desc"> {{date('d.m.Y',strtotime($tasknotification->reminder_date))}} </td>
                                                <td class="desc">{{$tasknotification->description}}</td>
                                                @php
                                                $classValue='denied';
                                                @endphp
                                                @if($tasknotification->priority ==2)
                                                @php
                                                $classValue='process';
                                                @endphp
                                                                                               
                                                @endif
                                                <td class='{{$classValue}}'>{{ $tasknotification->priorityString}}</td>
                                                
                                                
                                                <td>{{ $tasknotification->createdUser }}</td>
                                                <td>
                                                    
                                                            <i class="ti-pencil task_reminder_edit" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" aria-describedby="tooltip404710" reminder_id='{{ $tasknotification->id }}' saved-data="{{json_encode($tasknotification)}}"></i>
                                                       &nbsp;&nbsp;
                                                  
                                                            <i class="ti-trash task_reminder_delete" data-placement="top" title="" data-original-title="Delete" remove_id='{{ $tasknotification->id }}'></i>
                                                       </td>
                                                
                                            </tr>
<!--                                            <tr class="spacer"></tr>-->
                                            @endforeach
                                                         
                                            
                                            @endif
                                   
                                        </tbody>
                                    </table>
                            
                             </div>
                            
                            
                          </div>
                       </div>
   </div>
            </div>                
                       
                        @endif       
           
    

 



<script id='task_notification_template' type='text/template'>
        
        {{ Form::open(array('route' => array('addtaskreminder'), 'name' => 'form_task_reminder_add','id'=>'form_task_reminder_add','files'=>'true' )) }}
				     <div class="col-lg-12">
                                <div class="card">
                                 <% if(reminderId >0)  {%>
                                     
                                     <input type="hidden"  name="reminder_Id" value="<%- reminderId %>"  id="testDatepicker"  style="float: left !important;">
                                    <% } %> 
                                    <div class="card-body card-block">
                                        <div class="form-group">
                                            <label for="company" class=" form-control-label">Date</label>
                                            
                                              <input type="date"  name="reminder_date" value="<% if(savedData.reminder_date){ %> <%- savedData.formattedate %>  <%} else {%> {{date('d.m.Y')}} <% } %>"  id="testDatepicker" class="datefield1 form-control" >
                                     
                                            
                                        </div>
                                        <div class="form-group">
                                            <label for="vat" class=" form-control-label">Priority</label>
                                           
                                                {{ Form::select('reminder_priority',  $priorityArray, null,array('id' =>'reminder_priority','required'=>'required','class'=>'form-control-lg form-control','error-message' =>"Gender field is mandatory" ))}}  
                                        </div>
                                        <div class="form-group">
                                            <label for="vat" class=" form-control-label">Description</label>
                                            <textarea  id="reminder_description" placeholder="Description" class="form-control" name="reminder_description"><% if(savedData.description){ %> <%- savedData.description %>  <%}%> </textarea>
                                                    <input type="hidden" name="reminder_type" value="{{$reminderType}}" />
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                            </div>
                            {{Form::close()}}
			
</script>
                            
                            <script id='client_request_add_template' type='text/template'>
        
        {{ Form::open(array('route' => array('savecrmrequest', 0), 'name' => 'form_quote_request_add','id'=>'form_quote_request_add','files'=>'true' )) }}
				     <div class="col-lg-12">
                                <div class="card">
                          
                                    <div class="card-body card-block">
                                        <div class="form-group">
                                            <label for="company" class="form-control-label">Customer</label>
                                           
                                             {{ Form::select('customer_select',[''=>'---- select customer ----'] + $customerDetails, '',array('id' =>'customer_select','style'=>'width: 100%;','class'=>"autocomplete required form-control","required" =>"required" ,"error-message"=>'Customer selection is mandatory')) }}
                                   
                                            <input type="hidden" name="crm_type" value='1' />
                                        </div>

                                        <div class="form-group">
                                            <label for="vat" class="form-control-label">Line of business</label>
                                            {{ Form::select('request_lineof_business', ['' =>'----Not Select----']+$lineofbusiness, '',array('id' =>'request_lineof_business','required'=>'required','style'=>'width: 100%;','class'=>'request_lineof_business required form-control','error-message' =>"Line of business is mandatory" ))}}
                                        </div>   
                                        
                                          <div class="form-group" style="display:none">
                                            <label for="vat" class="form-control-label">Assign to</label>
                                            {{ Form::select('user_select', [''=>'---- select user ----'] +$userDetails, Auth::user()->id,array('id' =>'user_select','style'=>'width: 100%; ','class'=>"autocomplete form-control","required" =>"required" ,"error-message"=>'User field is mandatory'))}}
                                        </div>
                                        <div class="form-group">
                                            <label for="vat" class="form-control-label">Description</label>
                                            <textarea class="note_add_entry form-control" name="request_description"  rows="9" wrap="soft" ></textarea>
                                                    
                                        </div>
                                        
                                        
                                        
                                        
                                    </div>
                                </div>
                            </div>
                            {{Form::close()}}
			
</script>
                     

@endsection

 @section('customcss')
  <link rel="stylesheet" type="text/css" href=" {{ asset('elitedesign/assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css') }} ">
    <link rel="stylesheet" type="text/css" href="{{ asset('elitedesign/assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css') }}"> 
@endsection
          

  @section('customscript')
      <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('elitedesign/dist/js/perfect-scrollbar.jquery.min.js') }}"></script>

    <!--stickey kit -->
    <script src="{{ asset('elitedesign/assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
     <script src="{{ asset('elitedesign/assets/node_modules/sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('elitedesign/assets/node_modules/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('elitedesign/assets/node_modules/morrisjs/morris.min.js') }}"></script>

    <!-- This is data table -->
    <script src="{{ asset('elitedesign/assets/node_modules/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('elitedesign/assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('elitedesign/assets/node_modules/skycons/skycons.js') }}"></script>
        <script src="{{ asset('js/dibcustom/dib_dashboard.js') }}"></script>
   
  @endsection






@section('pagescript')
<script>
    var columnDefs = [];
    var quoterequestTable = '';


      var roleArray =  @json(Auth::user()->roles);
      var graphData =@json($dashboardDetails['graphData']); 
       @if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES', Auth::user()->roles)) 
         var graphOption = {'graphdata':graphData,'lineColors': ['#fb9678', '#01c0c8'],'labels': ['Leads','customers'],'ykeys': ['a','b']};
       @elseif (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles))
         var graphOption = {'graphdata':graphData,'lineColors': ['#fb9678', '#01c0c8'],'labels': ['Production','Endorsement'],'ykeys': ['a','b']};
       @elseif (in_array('ROLE_OPERATION_MANAGER', Auth::user()->roles) || in_array('ROLE_OPERATION', Auth::user()->roles))          
         var graphOption = {'graphdata':graphData,'lineColors': ['#55ce63', '#009efb', '#2f3d4a','#FBA78E'],'labels': ['Addition','Deletion','Downgrade','Upgrade'],'ykeys': [1,4,5,11],'lineWidth':3,'pointSize': 3,'fillOpacity': 0};
       @elseif (in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles))          
         var graphOption = {'graphdata':graphData,'lineColors': ['#55ce63', '#009efb'],'labels': ['Production','Vat'],'ykeys': ['production','vat'],'lineWidth':3,'pointSize': 3,'fillOpacity': 0};
    @endif

      var dashboardObj = new Dashboard(graphOption);
     
     var leadsTable ='';
     var columnDefs1 = [];
      var selectedColumns = [{"name":"customerName","rowName":"customerName","title":"Name","linkFlag":true},
         {"name":"customer_idcode","rowName":"id_code","title":"Id code"},
         {"name":"customer_customercode","rowName":"customer_code","title":"Code"},
         {"name":"customer_type","rowName":"type","manipulationFlag":true,"manipulation":{0:"Individual",1:"Company"},"title":"Customer type"},
         {"name":"customer_email","rowName":"email","title":"E-mail address"},
         {"name":"customer_phone","rowName":"phone","title":"Phone"},
        
         {"name":"saleschannel_name","rowName":"userName","title":"Account manager"},
         {"name":"customergroup_name","rowName":"customer_group","title":"Customer group"}];
 
   $(function(){
    
        dashboardObj.initialSetting();
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
                            var subject =(row['type']==0) ? 'Task' :'Request';
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
                            if((($.inArray( "ROLE_SALES_MANAGER", roleArray ) > -1) || ($.inArray( "ROLE_SALES", roleArray ) > -1)) &&  ($.inArray( row['status'], [2,3,5,6] ) > -1) ) {
                                
                              subject ="<span class='capital-first'>Pending with technical department</span>";   
                            } else if((($.inArray( "ROLE_SALES_MANAGER", roleArray ) > -1) || ($.inArray( "ROLE_SALES", roleArray ) > -1)) &&  ($.inArray( row['status'], [0,1] ) > -1) ) {
                               subject ="<span class='capital-first "+newclass+"'>New</span>";
                            }
                            
                            row.sortData = row['status'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                
                    
        columnDefs.push({"name": 'createdat',  "targets": 5, "orderable": false, data: function (row, type, val, meta) {
                            var subject = (row['created_date'] !=null)? $.format.date( row['created_date'], "dd.MM.yyyy HH:mm"):''; 
                            row.sortData = (row['created_date'] !=null)? $.format.date( row['created_date'], "dd.MM.yyyy HH:mm"):'';
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'updatedat',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var subject =(row['updated_date'] !=null)? $.format.date( row['updated_date'], "dd.MM.yyyy HH:mm"):'';
                            row.sortData =(row['updated_date'] !=null)? $.format.date( row['updated_date'], "dd.MM.yyyy HH:mm"):'';
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
    
    $('.dpib_notification_table').DataTable( { dom: "Blftip",order: [[1, "desc"]],pageLength:10,displayLength:10,pagingType: 'simple_numbers',pagination: true,serverSide: false,processing: false, "columnDefs": [ 
        { targets: 0, searchable: false,orderable:false }]})
        
     //task notification table
      $('.dpib_task_notification_table').DataTable( { dom: "Blftip",order: [[1, "desc"]],pageLength:10,displayLength:10,pagingType: 'simple_numbers',pagination: true,serverSide: false,processing: false, "columnDefs": [ 
        { targets: 0, searchable: false,orderable:false },{targets: 5, searchable: false,orderable:false}]})
        
        
        
    
    
    //CHART
    
    
    
    $(document).on('change','.dpib_notification_action',function(){
 
        if($('.notofocation_change_id:checked').length > 0 && $('.dpib_notification_action').val() !='' ) {
                $.ajax({
                    url: '{!! route("changenotificationflag") !!}',
                    type: "POST",
                    data:{'formValues':$('.notofocation_change_id:checked').serializeArray()},
                         headers: {
                                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                             }

            }).done(function (data) {
                location.reload(true);

            });
        } else {
     
        }
    
    });

 /***    Task Notification    ***/   

    
    $(document).off('click','#dpib_add_task_notification');
    $(document).on('click','#dpib_add_task_notification',function(){
        var template = _.template($("#task_notification_template").html());
        var data = {'docType':0,'reminderId':0,'savedData':{}};
         var dialogElement =$("#db_task_notification_popup");
        var result = template(data);
        

            $("#db_task_notification_popup").remove();
                $('body').append('<div id="db_task_notification_popup" title="Add reminder" class="col-lg-12" >' + result + '</div>');
              
                $("#db_task_notification_popup").dialog({
                                                            minWidth: 600,                                                            
                                                            modal:true,
                                                            buttons: {
                                                                    "Update": {
                                                                                class: "btn waves-effect waves-light btn-rounded btn-success",
                                                                                text: "Save",                            
                                                                                click: function () {                               
                                                                                   $("form#form_task_reminder_add").submit();                             
                                                                                }
                                                                               },
                                                                        "cancel": {
                                                                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                                                                                    text: "Cancel",
                                                                           click:function(){  $(this).dialog("close"); }

                                                                        }
                                                            },
                                                            open:function() {
                                                            $('.modal-backdrop').remove();
                                                            //FORM.setDatePicker('.datefield');
                                                           
                                                            }
});


    });
    
  
    $(document).off('click','.task_reminder_edit');
    $(document).on('click','.task_reminder_edit',function(){
        var template = _.template($("#task_notification_template").html());
        var currentData = $.parseJSON($(this).attr('saved-data'));
    
        var data = {'docType':0,'reminderId':$(this).attr('reminder_id'),'savedData':currentData };
         var dialogElement =$("#db_task_notification_popup");
        var result = template(data);
        

            $("#db_task_notification_popup").remove();
                $('body').append('<div id="db_task_notification_popup" title="Add task notification" class="col-lg-12" >' + result + '</div>');
              
                $("#db_task_notification_popup").dialog({
                                                            minWidth: 600,                                                            
                                                            modal:true,
                                                            buttons: {
                                                                    "Update": {
                                                                                class: "btn waves-effect waves-light btn-rounded btn-success",
                                                                                text: "Update",                            
                                                                                click: function () {                               
                                                                                   $("form#form_task_reminder_add").submit();                             
                                                                                }
                                                                               },
                                                                        "cancel": {
                                                                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                                                                                    text: "Cancel",
                                                                           click:function(){  $(this).dialog("close"); }

                                                                        }
                                                            },
                                                            open:function() {
                                                            $('.modal-backdrop').remove();
                                                            FORM.setDatePicker('.datefield');
                                                            $("#reminder_priority").val(currentData.priority);
                                                            
                                                            }
});


    });  
    
    
    
    
     $(document).off('click','.task_reminder_delete');
    $(document).on('click','.task_reminder_delete',function(){
         $("#db_task_notification_deletepopup").remove();
                $('body').append('<div id="db_task_notification_deletepopup" title="Delete notification" class="col-lg-12" > <form id="form_delete_notification"  action={!! route("deletenotification") !!}>Do you want to delete reminder? <input type="hidden" name="notificationId" value="'+$(this).attr('remove_id')+'"/></form></div>');
              
                $("#db_task_notification_deletepopup").dialog({
                                                            minWidth: 600,                                                            
                                                            modal:true,
                                                            buttons: {
                                                                    "Update": {
                                                                                class: "btn waves-effect waves-light btn-rounded btn-success",
                                                                                text: "Delete",                            
                                                                                click: function () {                               
                                                                                   $("form#form_delete_notification").submit();                             
                                                                                }
                                                                               },
                                                                        "cancel": {
                                                                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                                                                                    text: "Cancel",
                                                                           click:function(){  $(this).dialog("close"); }

                                                                        }
                                                            },
                                                            open:function() {
                                                            $('.modal-backdrop').remove();
                                                            
                                                            }
});
        
        
    });
    
    
    
    $(document).off('click','.dpib_client_request_add');
    $(document).on('click','.dpib_client_request_add',function(){
        var template = _.template($("#client_request_add_template").html());      
    
        var data = {};
         var dialogElement =$("#db_client_request_popup");
        var result = template(data);

            $("#db_client_request_popup").remove();
                $('body').append('<div id="db_client_request_popup" title="Add request" class="col-lg-12" >' + result + '</div>');
              
                $("#db_client_request_popup").dialog({
                                                            minWidth: 600,                                                            
                                                            modal:true,
                                                            buttons: {
                                                                    "Create": {
                                                                                class: "btn waves-effect waves-light btn-rounded btn-success",
                                                                                text: "Create",                            
                                                                                click: function () {                               
                                                                                 var isValid = true;
                                                                                        var errorMessage = "";
                                                                                               var i=0;
                                                                                               $("form#form_quote_request_add .required:visible").each(function(){                
                                                                                                if($(this).val()=='') {
                                                                                                   isValid = false; 
                                                                                                   $(this).parent('.form-group').addClass('error');
                                                                                                   if( i==0 ) {
                                                                                                    errorMessage+="<b>The following errors occurred while validating data:"+"</b><br/>";
                                                                                                    i++;
                                                                                                   }
                                                                                                   errorMessage+="<b>"+ $(this).attr('error-message')+"</b><br/>"

                                                                                                } else {
                                                                                                   $(this).removeClass('error'); 
                                                                                                }
                                                                                               });


                                                                                           if(isValid) {
                                                                                               $("form#form_quote_request_add").submit();
                                                                                           } else {
                                                                                             DIB.alert(errorMessage,'Error!!!!');    
                                                                                           }  
        
        
       
                                                                                }
                                                                               },
                                                                        "cancel": {
                                                                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                                                                                    text: "Cancel",
                                                                           click:function(){  $(this).dialog("close"); }

                                                                        }
                                                            },
                                                            open:function() {
                                                            $('.modal-backdrop').remove();                                                            
                                                             $("#customer_select").select2({dropdownParent: $("#db_client_request_popup")});
                                                            }
});


    });
    
    //Leads
    
    $.each(selectedColumns,function(columnNum,value){
     columnDefs1.push({"name": value.name,  "title":value.title,"targets": columnNum, data: function (row, type, val, meta) {
                            var subject = row[value.rowName];
                            row.sortData = row[value.rowName];
                            
                            if (value['manipulationFlag'] && value.manipulationFlag) {
                              row.displayData = (value['manipulation'][row['type']] ==1) ? value['manipulation'][row['type']]:value['manipulation'][row['type']];  
                            } else if(value['linkFlag']) {                                
                                var linkString = "<a href='{!! route('customeroverview','##Id##') !!}'>"+row[value.rowName]+"</a>";
                                var link = linkString.replace("##Id##", row['customId']);                                
                                row.displayData = link;
                            } else {
                               row.displayData = subject; 
                            }
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
       
   });
    
    leadsTable =   $('.dpib_leads_table').DataTable({
                ajax : {
                            url: "{{ route('getcustomerdata',[$dashboardDetails['customerType']]) }}",
                            type: "GET",
                            cache: false
                        },
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
                columnDefs:columnDefs1,
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
    
    
    @if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) 

    
    
    

@endif

//Claims


   
    
    

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
   
    function getRequestType(typeId) {

        var requestTypeArray =['','Addition' , 'CCHI Activation'  , 'Claim approval/Settlement' ,'Deletion' ,'Downgrade' ,'Updated member list' ,'Plate No Amendment' ,'Card Replacment' ,'CCHI Upload Status List' ,'MC Certificate' ,'Name Amendment' ,
'Card Printer Request' ,'Invoices Request' ,'Upgrade', 'Request' ,'Inquiry' ,'announcement' ,'Request sign' ,'Others'];
            
                               return requestTypeArray[typeId];
   
   }
   
      
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

 

  <style>
         .ui-widget-header,.ui-state-default, ui-button {
            background:#b9cd6d;
            border: 1px solid #b9cd6d;
            color: #FFFFFF;
            font-weight: bold;
         }
         
      </style>


@endsection
