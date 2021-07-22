
<div class="card open">
     <div class="card-body"> 
    <div class="panel-heading">
     <ul class="panel-actions list-inline pull-right">   
         <li class="dpib_endorsement_request_add" data-url='{!! route("addendorsementrequest",[$policyId]) !!}'><span class="panel-action-add"  data-toggle="tooltip" title="Add endrosement request"><span class="fas fa-plus text-blue"></span></span></li> 
     </ul> 
        

        <h1 class="card-title col-3">Endorsement request<small></small></h1> </div>
            <div class="table-responsive" style='width:100%;'>
                <table class="display nowrap table table-hover table-striped table-bordered dataTable dpib_policy_list" width='100%'>
                    <thead>
                        <tr>
                            <th style="width: 10%" class="nowrap">Request Id</th>                                                      
                            <th>Type</th>
                            <th style="width: 20%" class="nowrap">Description</th> 
                            <th  class="nowrap" >Status</th>
                            <th  class="nowrap" >Created by</th>                            
                            <th  class="nowrap" >Updated date</th>
                            
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
                    
        columnDefs.push({"name": 'description',  "targets": 2, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['description'] ;
                            row.sortData = row['description'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        columnDefs.push({"name": 'createdby',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  row['userName'];
                            row.sortData = row['userName'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
        columnDefs.push({"name": 'status',  "targets": 3, data: function (row, type, val, meta) {
              
                        row.sortData = row['status'];
                        row.displayData = row['statusString'] ;                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                
                    
        columnDefs.push({"name": 'updatedDate',  "targets": 5, "orderable": false, data: function (row, type, val, meta) {
                            var subject = (row['updated_at'] !=null)? moment(row['updated_at']).format('DD.MM.YYYY hh:mm'):'-'; 
                            row.sortData =(row['updated_at'] !=null)? moment(row['updated_at']).format('DD.MM.YYYY hh:mm'):'-'; 
                            row.displayData = subject;
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
                order: [[5, "desc"]],
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
                    
                    modal: true,
                    buttons: {
                        "Save": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:"Save",
                            click: function () {
                                DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));

                               $("form#form_endorsement_request_create").submit();
                               

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
                    }
                });               

            
DIB.centerDialog();
            });
    

    
    
    })
    

    
    
    
       
   });
   function getRequestType(typeId) {

     var requestTypeArray =['','Addition', 'CCHI',  'Deletion', 'Downgrade',  'Corrections',  'Certificate','Najam upload', 'Invoices Request', 'Upgrade',   'Others','Approvals','Request quatations','Active list'];
      requestTypeArray[17]='Announcement';
       
            
                               return requestTypeArray[typeId];
   
   }


</script>
