
<div class="card open">
     <div class="card-body">  
    <div class="panel-heading">
     <ul class="panel-actions list-inline pull-right">   
          <li class="dpib_quote_request_add"><span class="panel-action-add"  data-toggle="tooltip" title="Add CRM"><span class="fas fa-plus text-blue large-size"></span></span></li> 
     </ul> 
    </div>    
     
        
        
<!--        <ul class="panel-actions list-inline pull-right">
       <li class="dpib_crm_request_add"><span class="panel-action-add"  data-toggle="tooltip" title="Add a quote request"><span class="icon-add"></span> Add task</span></li> 
       <li class="dpib_quote_request_add"><span class="panel-action-add"  data-toggle="tooltip" title="Add a quote request"><span class="icon-add"></span> Add request</span></li> 
        </ul> -->
        <h1 class="card-title">Request<small></small></h1> 
         
            <div class="table-responsive" style='width:100%;'>
                <table class="display nowrap table table-hover table-striped table-bordered dataTable dpib_quote_request_list" width='100%'>
                    <thead>
                        <tr>
                            <th style="width: 10%" class="nowrap">Request id</th>
                            <th style="width: 10%" class="nowrap">Customer name</th>                           
                            <th  class="nowrap" style="width: 10%">Type</th>
                            <th  class="nowrap" style="width: 15%">Description</th>
                            <th  class="nowrap" style="width: 10%">Assign to</th>
                            <th  class="nowrap" style="width: 5%">Status</th>
                            <th  class="nowrap" style="width: 5%">Created at</th>
                            <th  class="nowrap" style="width: 5%">Last modified at</th>
                            <th  class="nowrap" >  </th>
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
    var quoterequestTable = '';


      var roleArray = @json(Auth::user()->roles);

   $(function(){
    
        columnDefs.push({"name": 'requetid',  "targets": 0, data: function (row, type, val, meta) {
                
                            var urlString = '{!! route("crmrequestOverview",["##RID"]) !!}';
                            var link = urlString.replace("##RID", row['mainId']);
                            var linkFlag = true; 
                            
                             if(($.inArray( "ROLE_TECHNICAL_MANAGER", roleArray ) > -1) || ($.inArray( "ROLE_TECHNICAL", roleArray ) > -1) ) {
                               switch(row['status']) {
                                case 2:case 3:case 4: case 5: case 6:case 0:case 1:case 11:case 12:case 0:
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
        columnDefs.push({"name": "customername",  "targets": 1, "orderable": false,data: function (row, type, val, meta) {
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
                    columnDefs.push({"name": 'Assign',  "targets": 4, "orderable": true, data: function (row, type, val, meta) {
                            var subject = row['assigned'];
                            row.sortData = row['assigned'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
        columnDefs.push({"name": 'status',  "targets": 5, data: function (row, type, val, meta) {
                       var subject = "<span class='capital-first'>"+row['statusString']+"</span>";
                            if((($.inArray( "ROLE_SALES_MANAGER", roleArray ) > -1) || ($.inArray( "ROLE_SALES", roleArray ) > -1)) &&  ($.inArray( row['status'], [2,3,5,6] ) > -1) ) {
                                
                              subject ="<span class='capital-first'>Pending with technical department</span>";   
                            }
                            
                            row.sortData = row['status'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                
                    
        columnDefs.push({"name": 'createdat',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['created_date'];
                            row.sortData = row['created_date'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'updatedat',  "targets": 7, "orderable": false, data: function (row, type, val, meta) {
                            var subject = (row['updated_date'] !=null)? moment(row['updated_date']).format('DD.MM.YYYY hh:mm'):'-';
                            row.sortData = (row['updated_date'] !=null)? moment(row['updated_date']).format('DD.MM.YYYY hh:mm'):'-';
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
         columnDefs.push({"name": 'actions',  "targets": 8, "orderable": false, data: function (row, type, val, meta) {
                            var subject = '-'; 
                            var urlString = '{!! route("getquoterequesteditform",[$customerId,"##RID"]) !!}';                           
                            var link = urlString.replace("##RID", row['mainId']);
                            var urlString_1 = '{!! route("crmrequestOverview",["##RID"]) !!}';
                            var link_1 = urlString_1.replace("##RID", row['mainId']);
                            var titleEdit =(row['type']==0) ? 'Edit task info' :'Edit request info';
                            var titleView =(row['type']==0) ? 'View task' :'View request';                           
                            var linkFlag = true; 
                            
                             if(($.inArray( "ROLE_TECHNICAL_MANAGER", roleArray ) > -1) || ($.inArray( "ROLE_TECHNICAL", roleArray ) > -1) ) {
                                 
                               switch(row['status']) {
                                   
                                case 2:case 3:case 4: case 5: case 6:case 0: case 1:case 11:case 12:
                                    linkFlag =true;
                                break;
                            default:
                                linkFlag =false;
                                   
                               }
                                 
                                 
                             }   else if(($.inArray( "ROLE_SALES_MANAGER", roleArray ) > -1) || ($.inArray( "ROLE_SALES", roleArray ) > -1) ) {
                               switch(row['status']) {
                                   
                                case 0:case 1:case 4: case 7: case 8: case 9:case 10:case 11:case 12:
                                    linkFlag =true;
                                break;
                            default:
                                linkFlag =false;
                                   
                               }  
                             }
                            
                           var  displayString = (linkFlag) ? '<a class="dpib_request_edit" docId='+row['mainId']+' customerId ='+ row['customer_id']+' editUrl ="'+link+'" ><span class="fas fa-edit text-blue" data-toggle="tooltip" title="" data-original-title="'+titleEdit+'"></span></a>' : '<a class="dpib_request_edit_disabled" docId='+row['mainId']+' customerId ='+ row['customer_id']+'  ><span class="fas fa-edit" style="color:red" data-toggle="tooltip" title="" data-original-title="'+titleEdit+'"></span></a>';                           
                           
                             displayString+=(linkFlag) ?  '&nbsp;<a class="dp_quote_request_overview" openUrl="'+link_1+'" href="'+link_1+'"><span class="fas fa-eye text-blue" data-toggle="tooltip" title="" data-original-title="'+titleView+'"></span></a>' :'&nbsp;<a class="dp_quote_request_overview_disabled" ><span class="fas fa-eye-slash text-blue" style="color:red" data-toggle="tooltip" title="" data-original-title="'+titleView+'"></span></a>' ;
                           row.displayData = displayString;
    //row.displayData = subject;
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
    
    var options = {'requestFormUrl':'{!! route("getquoterequestform",$customerId) !!}'};
    var quoteRequest = new DibQuoterequest(options);
    quoteRequest.initialSetting()
    
    // Request type change events
    
    
    
    //Document upload initialization
        $(document).off('change','#crm_type');
    $(document).on('change','#crm_type',function(){
     $(".dib_task").hide();
     $(".dib_request").hide();
       if($(this).val()==0) {
         $(".dib_task").show();
       }  else {
         $(".dib_request").show(); 
       }
       
    });
    
    //Document status change show hide
   
   $(document).off('change','.request_status');
    $(document).on('change','.request_status',function(){
 
    $(".dpib_reject_reason").hide();
    $(".dpib_comment_reason").hide();
    $(".dpib_revise_reason").hide();
    
       if($(this).val() ==8) {
          $(".dpib_reject_reason").show();
       } else if ($(this).val() ==9 || $(this).val() ==10) {
          $(".dpib_comment_reason").show();
       } else if($(this).val() ==5) {
          $(".dpib_revise_reason").show(); 
       }
       
    });
    
     $(document).off('click','.dpib_document_delete');
    $(document).on('click','.dpib_document_delete',function(){
           $.ajax({
            url: "{!! route('customerdocdelete',$customerId) !!}",
            headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     },
            data :{'docId':$(this).attr('docId')}
          }).done( function (data) {
                                    if(data.success) {                                             
                                            location.reload(true);
                                     }
             });
       
    });
    
    
    //Edit document
    
    $(document).off('click','.dpib_document_edit');
    $(document).on('click','.dpib_document_edit',function(){
        
            var documentId = $(this).attr('docId');
            var customerId = $(this).attr('customerId');

            var linkString = '{!! route("customerdocedit",["##customerId##","##docId##"]) !!}';
            var link = linkString.replace("##customerId##", customerId).replace("##docId##", documentId);
            $.ajax({
                url: link,
                type: "GET"           
           
            }).done( function (data) {
                                if(data.status) { 
                                    
                                    $("#dp_edit_selected_doc").remove();  
                                    $('body').append('<div id="dp_edit_selected_doc" title="Edit document"  >' + data.content + '</div>');
                                    var dialogElement = $("#dp_edit_selected_doc");
                                        
                                     dialogElement.dialog({
                                            width: 500,                                            
                                            modal: true,
                                            buttons: {
         
                                                    "Edit": {
                                                               
                                                                class: "btn waves-effect waves-light btn-rounded btn-success dp_document_data_edit",
                                                                text:'Edit',
                                                                click: function () {
                                                                    $.ajax({
                                                                            url: $('form#form_document_edit').attr('action'),
                                                                            headers: {
                                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                            },
                                                                            data :{'documenttype_oid':$('#documenttype_oid').val(),'document_comment':$('#document_comment').val()}
           
                                                                            }).done(function(data) {
                                                                                if(data.status) {
                                                                                   dialogElement.dialog('close');
                                                                                   dialogElement.remove();
                                                                                   //loading document datatable once more
                                                                                   $('#tab_document').find('a').trigger('click');
                                                                                   DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
                                                                                   DIB.closeProgressDialog();
                                                                                }
                                                                            })
                                                                    
                                                                }
                                                            },
                                                    "cancel":   {
                                                                    
                                                                    class: "btn waves-effect waves-light btn-rounded btn-danger",
                                                                    text:'Cancel',
                                                                    click: function () {
                                                                        dialogElement.dialog('close');
                                                                        dialogElement.remove();
                                                                    }
                                                                }
            
                                                      },

                                            close: function (event, ui) {

                                            }
                                    });  
                                }
             });
       
    })
    
    
       
   });


</script>