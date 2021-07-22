@extends('layouts.iframe')
@section('content')
<div class="panel panel-default open">
    <div class="panel-heading">
     <ul class="panel-actions list-inline pull-right">   
         <li class="dpib_endorsement_request_add" data-url='{!! route("addnewendorsementrequest") !!}'><span class="panel-action-add"  data-toggle="tooltip" title="Add endrosement request"><span class="icon-add"></span></span></li> 
     </ul> 
        

        <h1 class="panel-title">Endorsement requests<small></small></h1> </div><div class="panel-body"> 
            <div class="auto-scroll" style='width:100%;'>
                <table class="table table-bordered table-hovered table-striped dpib_policy_list" >
                    <thead>
                        <tr>
                            <th style="width: 10%" class="nowrap">Request Id</th>                                                      
                            <th>Type</th>
                            <th style="width: 20%" class="nowrap">Policy</th>
                            <th style="width: 20%" class="nowrap">Description</th> 
                            <th  class="nowrap" >Status</th>
                            <th  class="nowrap" >Created by</th>                            
                            <th  class="nowrap" >Updated date</th>
                            <th  class="nowrap" >Action</th>
                            
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
        </div> 

</div>
<link type="text/css" href="{{ asset('css/font-awesome.min.css') }}" type="text/css" rel="stylesheet" />
<link type="text/css" href="{{ asset('css/jquery.dataTables.min.css') }}" type="text/css" rel="stylesheet" />
<link type="text/css" href="{{ asset('css/dataTables.fixedColumns.min.css') }}" type="text/css" rel="stylesheet" />
<link type="text/css" href="{{ asset('css/dataTables.bootstrap.css') }}" type="text/css" rel="stylesheet" />
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css' >

        
<script src="{{ asset('js/global/datatable/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/global/datatable/DT_bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/global/datatable/fixedcolumn/dataTables.fixedColumns.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/dibcustom/dib-quote-request.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/dibcustom/dib-policy-add.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/global/datatable/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/global/datatable/datetime.js') }}" type="text/javascript"></script>
 		

       
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
                            row.sortData = row['id'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": "request_type",  "targets": 1, "orderable": false,data: function (row, type, val, meta) {
                            var subject = getRequestType(row['type'])
                            row.sortData = row['type'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
       columnDefs.push({"name": "policies",  "targets": 2, "orderable": false,data: function (row, type, val, meta) {
                            var subject =  row['policy_number'];
                            row.sortData = row['policy_number'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'description',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
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
        columnDefs.push({"name": 'createdby',  "targets": 5, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  row['userName'];
                            row.sortData = row['userName'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
                   
                    
        columnDefs.push({"name": 'updatedDate',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var subject = (row['updated_at'] !=null)? moment(row['updated_at']).format('DD.MM.YYYY hh:mm'):'-';
                            row.sortData =(row['updated_at'] !=null)? moment(row['updated_at']).format('DD.MM.YYYY hh:mm'):'-';
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
         columnDefs.push({"name": 'actions',  "targets": 7, "orderable": false, data: function (row, type, val, meta) {
                                             
                           linkString = '<a class="dpib_endorsement_crm_delete" delete_url="{!! route("deleteendorsementcrmrequest",["##RID"]) !!}"><span class="icon-delete" data-toggle="tooltip" title="" data-original-title="Delete endorsement request"></span></a>';                           
                             var link = linkString.replace("##RID", row['id']);
                             row.displayData = link
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
                order: [[6, "desc"]],
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
                $('body').append('<div id="db_quote_request_editpopup" title="Create request" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#db_quote_request_editpopup");
                dialogElement.dialog({
                    width: 900,
                    resizable: false,
                    bgiframe: true,
                    modal: true,
                    buttons: {
                        "Save": {
                            buttonClass: "primary",
                            buttonAction: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                $("form#form_endorsement_request_create").submit();
                            }
                        },
                        "cancel": {
                            buttonClass: "primary",
                            buttonAction: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
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
                            buttonClass: "primary",
                            buttonAction: function () {
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
                            buttonClass: "primary",
                            buttonAction: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
                    }
                }); 
                    DIB.centerDialog(); 
    });

    
    
    
       
   });
   function getRequestType(typeId) {

        var requestTypeArray =['','Addition', 'CCHI',  'Deletion', 'Downgrade',  'Card Replacment',  'Name Amendment','Card Printer Request', 'Invoices Request', 'Upgrade',   'Others'];
            
                               return requestTypeArray[typeId];
   
   }


</script>
@endsection