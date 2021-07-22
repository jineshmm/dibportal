<div class="card open">
    
     <div class="card-body"> 
            <div class="panel-heading">
                       <ul class="panel-actions list-inline pull-right">
                     
                            <div class="btn-group">
                                <span class="icon-settings text-blue"></span>
                         
                                <ul id="add-menu" class="dropdown-menu" role="menu">
                                    <li id="dpib_debtnotice_call" data-url="{{route('debtnoticecall',[$invoiceId])}}" policyId="1"><a>Log a debt notice call</a></li>                                     
                                    <li id="dpib_send_debt" data-url="{{route('debtnoticecall',[$invoiceId])}}" policyId="1"><a>Send debt notice</a></li>
                                    
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <h3 class="panel-title">Debt management</h3>
                </div>
            <div class="table-responsive" style='width:100%;'>
                <table class="display nowrap table table-hover table-striped table-bordered dataTable dpib_debtmanagement" width='100%'>
                    <thead>
                        <tr>
                            <th  class="nowrap">Date</th>
                            <th  class="nowrap">Type</th>                           
                            <th  class="nowrap">Recipient</th>
                            <th  class="nowrap">Info</th>                           
                            <th  class="nowrap">Broker</th>                           
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
        </div> 

</div>
   <script id='invoice_debtmanagement_call' type='text/template'>
    
 {{ Form::open(array('route' => array('savenoticecalldetails',$invoiceId), 'name' => 'form_debt_management','id'=>'form_debt_management','files'=>'true' )) }}

    <div class="dialogform">
    <table class="insly_dialogform">
    <tbody>
    <tr>
                    <td>
                       <div class="label" style="width:100%"><span class="text-danger icon-asterix"></span><span class="title">PHONE NO / NAME</span></div> 
                        </td>
                <td>
                                                 <input type="text" id="debt_mgt_name" name="debt_mgt_name" value="" autocomplete="off" class="form-control">
                                                      <input type="hidden"  name="debt_mgt_type" value="phone" autocomplete="off" class="form-control">
                                    </td>
    </tr>
    <tr id="field_claim_claimant_name" class="field ">
                    <td class="">
    <div class="label ">
              
        <span class="title">COMMENTS, RECAP OF THE CALL</span>
    </div>
</td>
                <td>
            <div class="element">
          <textarea id="comment" name="comment" wrap="soft" rows="4" autocomplete="off" maxlength="255" class="editor required" required error-message="Please enter content in message box"></textarea>                                                     
     </div>
        </td>
    </tr>

</tbody></table>
</div>
    {{ Form::close() }}   
    
    
</script>  


<script id='invoice_debtmanagement_mail' type='text/template'>
    
 {{ Form::open(array('route' => array('savenoticecalldetails',$invoiceId), 'name' => 'form_debt_management_mail','id'=>'form_debt_management_mail','files'=>'true' )) }}

    <div class="dialogform">
    <table class="insly_dialogform">
    <tbody>
    <tr>
                    <td>
                       <div class="label" style="width:100%"><span class="text-danger icon-asterix"></span><span class="title">Email address</span></div> 
                        </td>
                <td>
                                                 <input type="email" id="debt_mgt_name" name="debt_mgt_name" value="{{$customer->email}}" autocomplete="off" class="form-control">
                                                     <input type="hidden"  name="customer_name" value="{{$customer->name}}" autocomplete="off" class="form-control">
                                                      <input type="hidden"  name="debt_mgt_type" value="mail" autocomplete="off" class="form-control">
                                    </td>
    </tr>
       <tr>
                    <td>
                       <div class="label" style="width:100%"><span class="text-danger icon-asterix"></span><span class="title">Subject</span></div> 
                        </td>
                <td>
                                                 <input type="text" id="debt_mgt_subject" name="debt_mgt_subject" value="Diamond Insurance Broker notice: unpaid invoice no {{$invoiceId}}" autocomplete="off" class="form-control">
                                    </td>
    </tr>
    
    <tr id="field_claim_claimant_name" class="field">
                    <td class="">
    <div class="label">
              
        <span class="title">Content</span>
    </div>
</td>
                <td>
            <div class="element">
          <textarea id="comment" name="comment" wrap="soft" rows="4" autocomplete="off" maxlength="255" class="editor required" required error-message="Please enter content in message box"><p>Dear customer,</p>

<p>according to our records, invoice no {{$invoiceId}} appears to not
have been paid in time. Please make the payment as soon as possible
or if you have any questions regarding this matter, contact us for 
further clarification.</p>

<p>For your convenience, we have also attached the invoice to this e-mail.</p></textarea>                                                     
     </div>
        </td>
    </tr>
    
       <tr id="field_claim_payment_reserve_change" class="field ">
            <td class="">
                <div class="label ">
                    <span class="text-danger "></span>
                    <span class="title">Attach invoice</span>
                </div>
            </td>
            <td>
                <div class="element custom-control custom-checkbox mr-sm-2">
                    <input type="hidden" name="invoice_attach_flag" value="">
                        <input class="custom-control-input"  type="checkbox" id="invoice_attach_flag" name="invoice_attach_flag" value="1"  >
                            <label class="custom-control-label" for="invoice_attach_flag">Yes</label>

                </div>
            </td>
        </tr>
        <tr>
                    <td>
                       <div class="label" style="width:100%"><span class="title">EXTEND DUE DATE</span></div> 
                        </td>
                <td>
                                                 <input type="text" id="debt_mgt_extend_due_date" name="debt_mgt_extend_due_date" value="" autocomplete="off" class="form-control" style="width:50%">days
                                    </td>
    </tr>
        
    
    

</tbody>
</table>
</div>
    {{ Form::close() }}   
    
    
</script>   
<script src="{{ asset('js/global/datatable/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/global/datatable/datetime.js') }}" type="text/javascript"></script>

       
<script>
    var columnDefs = [];
    var customerLogTable = '';
   $(function(){
    
        columnDefs.push({"name": 'managedate',  "targets": 0, data: function (row, type, val, meta) {
                            var subject = moment(row['created_date']).format('DD.MM.YYYY HH:mm'); 
                            row.sortData = row['created_date'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": "managementtype",  "targets": 1, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['management_type'];
                            row.sortData = row['management_type'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'recipient',  "targets": 2, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['recipient'];
                            row.sortData = row['recipient'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'info',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['info'];
                            row.sortData = row['info'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'broker',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['userName'];
                            row.sortData = row['userName'];
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
  
        
             
      customerLogTable =   $('.dpib_debtmanagement').DataTable({
                data: {!! $debtmanagementDetails !!},
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
                dom: "Brtip"
     
    } );  
    
    
    
    $(document).off('click','#dpib_debtnotice_call');
    $(document).on('click','#dpib_debtnotice_call',function(){
        var template = _.template($("#invoice_debtmanagement_call").html());
     
        var result = template();

            $("#db_invoice_debt_popup").remove();
                $('body').append('<div id="db_invoice_debt_popup" title="Log a debt notice call" style="display:none" >' + result + '</div>');
                var dialogElement = $("#db_invoice_debt_popup");

                dialogElement.dialog({
                    width: 900,                   
                    modal: true,
                    buttons: {
                        "Update": {
                           
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:"Log a debt notice call",
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));

                               $("form#form_debt_management").submit();
                               

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
                      
                });  
       
    });
    
    
    $(document).off('click','#dpib_send_debt');
    $(document).on('click','#dpib_send_debt',function(){
        var template = _.template($("#invoice_debtmanagement_mail").html());
     
        var result = template();

            $("#db_invoice_debtmail_popup").remove();
                $('body').append('<div id="db_invoice_debtmail_popup" title="Send debt invoice" style="display:none" >' + result + '</div>');
                var dialogElement = $("#db_invoice_debtmail_popup");

                dialogElement.dialog({
                    width: 900,                   
                    modal: true,
                    buttons: {
                        "Update": {
                           
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:"Send debt invoice",
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));

                               $("form#form_debt_management_mail").submit();
                               

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
                      
                });  
       
    });
    
    
    
    
    
    
       
   });


</script>
   