@extends('layouts.elite',['notificationCount'=>$notificationCount ] )

@section('content')
  <div class="row mt-1 mb-4 ml-1">

<div id="filter-insly_customer" class="panel-filter open extend-filter col-md-12">
 				 {{ Form::open(array('route' => array('crmrequestfilter'), 'name' => 'form_management_customer_filter','id'=>'form_management_customer_filter') ) }}
                                    @csrf
                                    <table class="table-filter filtersperrow4">
                                        <tbody>
                                            <tr>
                                                <td id="filter_customer_name" class=" filterlevel1"><div><label for="filter_customer_name">Request Id:</label><div><input type="text" autocomplete="off" id="filter_request_number" name="filter_request_number" value="{{isset($formData['filterrequestnumber']) ? $formData['filterrequestnumber']:'' }}" class=""></div></div></td>
                                                
                                                <td id="filter_customergroup_oid" class="extend-filter filterlevel2"><div><label for="filter_request_status">Status:</label><div>
                                                            {{ Form::select('filter_request_status',[''=>'---- not set ----'] +   $statusArray, isset($formData['filterstatus']) ? $formData['filterstatus']:'' ,array('id' =>'filter_request_status',"error-message"=>'Account manager field is mandatory'))}} 
                                                            </div></div></td>
                                            </tr>

                                            <tr>
                                                <td colspan="4" class="panel-buttons"><button type="submit" id="submit_save" name="submit_save" class="submit_policy btn waves-effect waves-light btn-rounded btn-primary">Apply filters</button> <button type="button" id="mg_clear_filter" name="clear_filter" class="submit_policy btn waves-effect waves-light btn-rounded btn-primary">Clear filters</button>  </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                {{ Form::close() }}
 			</div>


  </div>



<div class="panel panel-default open card row">
    <div class="panel-heading card-body">

        <ul class="panel-actions list-inline pull-right dib_head">

            <li>
                <a class="dpib_client_request_add">
                   <span class="icon-add fas fa-plus large-size" data-toggle="tooltip" title="add request"></span>
                </a>
            </li>

                            </ul>
                            <h1 class="panel-title" ><small>Requests</small></h1>
    </div>
                            <div class="panel-body"> 
            <div class="auto-scroll table-responsive" style='width:100%;'>
                
        
                <table class="table table-bordered table-striped dpib_quote_request_list color-table info-table">
                    <thead>
                        <tr>
                            <th style="width: 15%" class="nowrap">Request id</th>
                            <th style="width: 15%" class="nowrap">Customer name</th>
                            <th  class="nowrap" style="width: 10%">Type</th>
                            <th  class="nowrap" style="width: 15%">Description</th>
                            <th  class="nowrap" style="width: 15%">Policy</th>
                            <th  class="nowrap" style="width: 15%">Assign to</th>
                            
                            <th  class="nowrap" style="width: 10%">Status</th>
                            <th  class="nowrap" style="width: 10%">Created at</th>
                            <th  class="nowrap" style="width: 15%">Last modified at</th> 
                             @if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) )  
                            <th  class="nowrap" style="width: 15%">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>           
                   </tbody>
                </table>
            </div>
        </div> 

</div>
                 
                            <script id='client_request_add_template' type='text/template'>
        
        {{ Form::open(array('route' => array('savecrmrequest', 0), 'name' => 'form_quote_request_add','id'=>'form_quote_request_add','files'=>'true' )) }}
				     <div class="col-lg-12 dialogform">
                                <div class="card">
                          
                                    <div class="card-body card-block">
                                        <div class="form-group">
                                            <label for="company" class="form-control-label">Customer</label>
                                           
                                             {{ Form::select('customer_select',[''=>'---- select customer ----'] + $customerDetails, '',array('id' =>'customer_select','style'=>'width: 100%;','class'=>"autocomplete required form-control","required" =>"required" ,"error-message"=>'Customer selection is mandatory')) }}
                                          
                                            <input type="hidden" name="crm_type" value='1' />
                                        </div>
                                                                                
                                        <div class="form-group card-block">
                                            <label for="vat" class=" form-control-label">Line of business</label>
                                            {{ Form::select('request_lineof_business', ['' =>'----Not Select----']+$lineofbusiness, '',array('id' =>'request_lineof_business','required'=>'required','style'=>'width: 100%;','class'=>'request_lineof_business required','error-message' =>"Line of business is mandatory" ))}}
                                        </div>      
                                     
                                          <div class="form-group card-block" style="display:none">
                                            <label for="vat" class=" form-control-label">Assign to</label>
                                            {{ Form::select('user_select', [''=>'---- select user ----'] +$userDetails, Auth::user()->id,array('id' =>'user_select','style'=>'width: 100%; ','class'=>"autocomplete required form-control","required" =>"required" ,"error-message"=>'User field is mandatory'))}}
                                        </div>
                                        <div class="form-group card-block">
                                            <label for="vat" class=" form-control-label">Description</label>
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
        
<script src="{{ asset('elitedesign/assets/node_modules/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('elitedesign/assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script> 
<script src="{{ asset('js/global/datatable/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/global/datatable/datetime.js') }}" type="text/javascript"></script>

@endsection

 @section('pagescript') 
<script>
    var columnDefs = [];
    var quoterequestTable = '';


      var roleArray = @json(Auth::user()->roles);     
      var keyobj = _.keys(@json($monthlyPremium));     
     var dataobj = _.values(@json($monthlyPremium));
   $(function(){
    
        columnDefs.push({"name": 'requetid',  "targets": 0, data: function (row, type, val, meta) {
                
                            var urlString = '{!! route("crmrequestOverview",["##RID"]) !!}';
                            var link = urlString.replace("##RID", row['mainId']);
                            var linkFlag = true; 
                            
                             if(($.inArray( "ROLE_TECHNICAL_MANAGER", roleArray ) > -1) || ($.inArray( "ROLE_TECHNICAL", roleArray ) > -1) ) {
                               switch(row['status']) {
                                case 2:case 3:case 4: case 5: case 6:case 11:case 7:
                                    linkFlag =true;
                                break;
                            default:
                                linkFlag =false;
                                   
                               }
                                 
                                 
                             }   else if(($.inArray( "ROLE_SALES_MANAGER", roleArray ) > -1) || ($.inArray( "ROLE_SALES", roleArray ) > -1) ) {
                               switch(row['status']) {
                                   
                                case 0:case 1:case 4:case 7: case 8: case 9:case 10:case 11:case 12:
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
                    
        columnDefs.push({"name": 'type',  "targets": 2, "orderable": true, data: function (row, type, val, meta) {
                            var subject = 'Request';
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
                    
        columnDefs.push({"name": 'policy',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var urlString = '{!! route("policyoverview",["##PID"]) !!}';
                            var link = urlString.replace("##PID", row['policyId']);
                             var subject =   (row['policy_number'] !==null) ? "<a href='"+link+"'>"+ row['policy_number']+"</a>": '-';
                            row.sortData = row['policy_id'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});             
                    
     columnDefs.push({"name": 'Assign',  "targets": 5, "orderable": true, data: function (row, type, val, meta) {
                            var subject = row['assigned'];
                            row.sortData = row['assigned'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});

                    
        columnDefs.push({"name": 'status',  "targets": 6, data: function (row, type, val, meta) {
                            var newclass = getStatusColor(row['status']);
                             var subject = "<span class='capital-first font-bold "+newclass+"'>"+row['statusString']+"</span>";
                             row.sortData = row['statusString'];
                            if((($.inArray( "ROLE_SALES_MANAGER", roleArray ) > -1) || ($.inArray( "ROLE_SALES", roleArray ) > -1)) &&  ($.inArray( row['status'], [2,5,6] ) > -1) ) {
                               row.sortData = "Pending with technical department";
                              subject ="<span class='capital-first font-bold'>Pending with technical department</span>";   
                            }
                            
                            
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'createdat',  "targets": 7,"type":"date", "orderable": true, data: function (row, type, val, meta) {
                            var subject = (row['created_date'] !=null)?  moment(row['created_date']).format('DD.MM.YYYY HH:mm') :'';
                            row.sortData = (row['created_date'] !=null)? moment(row['created_date']).format('DD.MM.YYYY HH:mm') :'';
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'updatedat',  "targets": 8,"type":"date", "orderable": true, data: function (row, type, val, meta) {
                            var subject = (row['updated_date'] !=null)? $.format.date( row['updated_date'], "dd.MM.yyyy HH:mm"):'';
                            row.sortData = (row['updated_date'] !=null)? $.format.date( row['updated_date'], "dd.MM.yyyy HH:mm"):'';
                            
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                        
           @if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) )          
            columnDefs.push({"name": 'actions',  "targets": 9, "orderable": false, data: function (row, type, val, meta) {
                                                                
                            var linkString = '<a class="dpib_crm_request_delete dib-cursor-style" return-url="{!! route("salescrmlist") !!}" delete_url="{!! route("removecrmrequest",["##CID","##CRMID"]) !!}"><span class="fas fa-archive dib-delete-icon" data-toggle="tooltip" title="" data-original-title="Delete CRM request"></span></a>';
                            var link = linkString.replace("##CID", row['customerId']).replace("##CRMID",row['mainId'] );
                             row.displayData = link
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});       
           @endif        
        
       
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
                order: [[7, "asc"]],
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
    

    
    
    $(document).off('click','.dpib_client_request_add');
    $(document).on('click','.dpib_client_request_add',function(){
        var template = _.template($("#client_request_add_template").html());      
    
        var data = {};
         var dialogElement =$("#db_client_request_popup");
        var result = template(data);
        

            $("#db_task_notification_popup").remove();
                $('body').append('<div id="db_client_request_popup" title="Add quote request" class="col-lg-12" >' + result + '</div>');
              
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
    
    
     //DELETE REQUEST
      $(document).off('click','.dpib_crm_request_delete');
        $(document).on('click','.dpib_crm_request_delete',function() {
         
        var deleteUrl = $(this).attr('delete_url');
        var returnUrl = $(this).attr('return-url');
        
        $("#db_crm_endorsement_request_deletepopup").remove();
                $('body').append('<div id="db_crm_endorsement_request_deletepopup" title="Remove crm request" style="display:none" > Do you really want to remove CRM request ?</div>');
                var dialogElement = $("#db_crm_endorsement_request_deletepopup");
                dialogElement.dialog({
                    width: 900,
                    resizable: false,
                    bgiframe: true,
                    modal: true,
                    buttons: {
                        "Delete": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:'Delete',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                 $.ajax({
                                 url: deleteUrl,
                               type: "GET"

                               }).done(function (data) {
                                   if(data.status) {
                                     location.replace(returnUrl);
                                   }
                                       
                                  })
                               
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
    
    //CLEAR FILTER
 $(document).on('click','#mg_clear_filter',function(){
   window.location.href = "{!! route('salescrmlist')  !!}";
    
})

   });
   function getStatusColor(status) {
    var newclass='';
        switch(status){
        case 0:
            newclass='text-primary';
            break;
        case 1:
            newclass='text-primary';
            
            break;
        case 2:
            newclass='text-warning';
          
            break;
        case 3:
              newclass='text-success';
            
            break;
        case 4:
            newclass='text-success';
            
            break;
        case 5:
            newclass='text-info';
            break;
        case 6:
            newclass='text-dark';
            break;
        case 7:
            newclass='text-cyan';
            break;
        case 8:
            newclass='text-warning';
            break;
        case 9:
            newclass='text-purple';
            break;
        case 10:
            newclass='text-danger';
            break;            
        
    }
    
    return newclass;
   
   }


</script>


@endsection