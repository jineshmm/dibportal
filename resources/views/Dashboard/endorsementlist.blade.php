@extends('layouts.elite',['notificationCount'=>$notificationCount ] )


@section('content')

<div class="panel panel-default open card">
    <div class="panel-heading card-body">
     <ul class="panel-actions list-inline pull-right dib_head">   
         <li class="dpib_endorsement_request_add" data-url='{!! route("addnewendorsementrequest") !!}' >
             <span class="panel-action-add"   title="Add endrosement request"  data-toggle="tooltip"><span class="icon-add icon-add fas fa-plus large-size"></span></span></li> 
     </ul> 
        

        <h1 class="panel-title">Operation requests<small></small></h1> </div><div class="panel-body"> 
            <div class="auto-scroll table-responsive" style='width:100%;'>
                <table class="table table-bordered table-striped dpib_policy_list color-table info-table" >
                    <thead>
                        <tr>
                            <th style="width: 10%" class="nowrap">Request Id</th>                                                      
                            <th style="width: 10%">Type</th>
                            <th style="width: 20%" class="nowrap">Policy</th>
                            <th style="width: 20%" class="nowrap">Description</th> 
                            <th  class="nowrap" style="width: 10%">Status</th>
                            <th  class="nowrap" style="width: 10%">Created by</th>
                            <th  class="nowrap" style="width: 10%">Assign to</th>                            
                            <th  class="nowrap" style="width: 10%">Updated date</th>
                             @if (in_array('ROLE_OPERATION_MANAGER', Auth::user()->roles) )          
                            <th  class="nowrap" style="width: 10%">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
        </div> 

</div>
<script id='endorsement_request_assign_template' type='text/template'>
        
        {{ Form::open(array('route' => array('addtaskreminder'), 'name' => 'form_endorsement_request_assign','id'=>'form_endorsement_request_assign','files'=>'true' )) }}
				     <div class="col-lg-12">
                                <div class="card">
                                
                                    <div class="card-body card-block">
                                        <div class="form-group">
                                            <label for="vat" class=" form-control-label">Team members</label>
                                           
                                                {{ Form::select('operation_team',  $assignUsers, null,array('id' =>'operation_team','required'=>'required','class'=>'form-control-lg form-control','error-message' =>"Gender field is mandatory" ))}}  
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
                            var subject =  row['policy_number'];
                            row.sortData = row['policy_number'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'description',  "targets": 3, "orderable": true, data: function (row, type, val, meta) {
                            var subject =row['description'] ;
                            row.sortData = row['description'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
      columnDefs.push({"name": 'status',  "targets": 4, data: function (row, type, val, meta) {
              
                        row.sortData = row['status'];
                        row.displayData = row['statusString'] ;                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": 'createdby',  "targets": 5, "orderable": true, data: function (row, type, val, meta) {
                            var subject =  row['userName'];
                            row.sortData = row['userName'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
       columnDefs.push({"name": 'assignTo',  "targets": 6, "orderable": true, data: function (row, type, val, meta) {
                            var subject =  (row['assign_to'] !=null) ? row['AssignName']:'-';
                            row.sortData = row['AssignName'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                   
                    
        columnDefs.push({"name": 'updatedDate',"type":"date" , "targets": 7, "orderable": true, data: function (row, type, val, meta) {
                            var subject = moment(row['updated_at']).format('DD.MM.YYYY HH:mm');
                            row.sortData =row['updated_at'];
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
           @if (in_array('ROLE_OPERATION_MANAGER', Auth::user()->roles) )          
         columnDefs.push({"name": 'actions',  "targets": 8, "orderable": false, data: function (row, type, val, meta) {
                         var linkString = ''; 
                                            
                            linkString = '<a class="dpib_endorsement_crm_delete dib-cursor-style" delete_url="{!! route("deleteendorsementcrmrequest",["##RID"]) !!}"><span class="fas fa-archive dib-delete-icon" data-toggle="tooltip" title="" data-original-title="Delete endorsement request"></span></a>';
                            linkString =linkString+'<a class="dpib_endorsement_crm_assign dib-cursor-style" assign_url="{!! route("assignndorsementcrmrequest",["##DRID"]) !!}" style="margin-left:7px"><span class="fas fa-handshake dib-delete-icon" data-toggle="tooltip" title="" data-original-title="Assign endorsement request"></span></a>';                           
                                     
                var link = linkString.replace("##RID", row['id']).replace("##DRID", row['id']);
                             row.displayData = link
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                               
              @endif         
                  
                    
        
       
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
    
    $(document).on('click','.dpib_endorsement_request_add',function(){    
   
            $.ajax({
                    url: $(this).attr('data-url'),
                    type: "GET"

            }).done(function (data) {
                $("#db_quote_request_editpopup").remove();
                $('body').append('<div id="db_quote_request_editpopup" class="modal" title="Create endorsement request" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#db_quote_request_editpopup");
                dialogElement.dialog({
                    width: 900,
                   
                    modal: true,
                    draggable: false,
                    buttons: {
                        "Save": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:"Save",
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));

                                var isValid = true;
         var errorMessage = "";
                var i=0;
                $(".required:visible").each(function(){                
                 if($(this).val()=='' || $(this).val() == null) {
                    isValid = false; 
                    $(this).addClass('form-control-danger');
                    $(this).parent('.element').addClass('has-danger')
                    if( i==0 ) {
                     errorMessage+="<b>The following errors occurred while validating data:"+"</b><br/>";
                     i++;
                    }
                    errorMessage+="<b>"+ $(this).attr('error-message')+"</b><br/>"
                  
                 } else {
                    $(this).removeClass('error'); 
                    $(this).removeClass('form-control-danger');
                    $(this).parent('.element').removeClass('has-danger')
                 }
                });
                

            if(isValid) {

                $("form#form_endorsement_request_create").submit();
            } else {
              DIB.alert(errorMessage,'Error!!!!');    
            }

                               
                            }
                        },
                        "cancel": {
                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                             text:"Cancel",
                            click: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
                    },
                    open:function() {
                         $("#request_customer").select2({dropdownParent: $("#db_quote_request_editpopup")});
                    }
                }); 
                    DIB.centerDialog();
            });
    
    });
    
    
    
    $(document).on('click','.dpib_endorsement_crm_delete',function() {
         
        var deleteUrl = $(this).attr('delete_url');
        $("#db_crm_endorsement_request_deletepopup").remove();
                $('body').append('<div id="db_crm_endorsement_request_deletepopup" title="Remove endorsement request" style="display:none" > Do you really want to remove endorsement request ?</div>');
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
                                     location.reload(true);
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
    
    //ASSIGN TO OPERATION TEAM
    
    $(document).on('click','.dpib_endorsement_crm_assign',function() {
         
        var assignUrl = $(this).attr('assign_url');
        
        $("#db_crm_endorsement_request_assignpopup").remove();
        var template = _.template($("#endorsement_request_assign_template").html());
        var result = template();
                $('body').append('<div id="db_crm_endorsement_request_assignpopup" title="Assign endorsement request" style="display:none" >' + result + '</div>');
                var dialogElement = $("#db_crm_endorsement_request_assignpopup");
                dialogElement.dialog({
                    width: 900,
                    resizable: false,
                    bgiframe: true,
                    modal: true,
                    buttons: {
                        "Delete": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:'Assign',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                $("form#form_endorsement_request_assign").submit();
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
                    },
                    open:function(){
                      $("form#form_endorsement_request_assign").attr('action',assignUrl);  
                    }
                }); 
                    DIB.centerDialog(); 
    });

    
    
    
       
   });
   function getRequestType(typeId) {

        var requestTypeArray =['','Addition', 'CCHI',  'Deletion', 'Downgrade',  'Corrections',  'Certificate','Najam upload', 'Invoices Request', 'Upgrade',   'Others','Approvals','Request quatations','Active list'];
        requestTypeArray[17]='Announcement';
            
                               return requestTypeArray[typeId];
   
   }


</script>
     

@endsection