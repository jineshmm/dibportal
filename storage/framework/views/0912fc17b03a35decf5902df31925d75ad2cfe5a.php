<?php $__env->startSection('content'); ?>
<div class="panel panel-default open card">
    <div class="panel-heading card-body">

        

        <h1 class="card-title">Endorsements<small></small></h1> 
    </div>
    <div class="panel-body"> 
            <div class="auto-scroll" style='width:100%;'>
                <table class="table table-bordered table-striped dpib_endorsement_list color-table info-table" >
                    <thead>
                        <tr>
                            <th style="width:10%" class="nowrap">Policy</th>
                            
                            <th style="width: 40%" class="nowrap">Customer</th>
                            <th>Insurance</th>
                            <th>Type</th>
                            <th style="width: 20%" class="nowrap">Issue date</th> 
                            <th  class="nowrap" >Inception date</th>
                            <th  class="nowrap" >End date</th>
                            <th  class="nowrap" >Due date</th>
                            <th  class="nowrap" >Amount</th>                            
                           <th  class="nowrap" >Action</th>
                            
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
        <h1 class="card-title">Approved Endorsements<small></small></h1> 
    </div>
    <div class="panel-body"> 
            <div class="auto-scroll table-responsive">
                <table class="table table-bordered table-striped dpib_approved_endorsement_list color-table info-table">
                    <thead>
                        <tr>
                            <th   class="nowrap">Endorsement no</th>
                            <th   class="nowrap">Policy</th>
                            <th  style="width:40%" class="nowrap">Customer</th>
                            <th>Type</th>
                            <th class="nowrap">Issue date</th> 
                            <th  class="nowrap" >Inception date</th>
                            <th  class="nowrap" >End date</th>
                            <th  class="nowrap" >Due date</th>
                            <th class="nowrap" >Amount</th>
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
        </div> 

</div>

<script id='endorsement_issued_template' type='text/template'>
    
 <?php echo e(Form::open(array('route' => array('issueendorsement'), 'name' => 'form_endorsement_issue','id'=>'form_endorsement_issue','files'=>'true' ))); ?>


    <div class="dialogform"><table class="insly_dialogform">
                <tbody>                    
                    <tr id="field_request_type" class="field">
                  
                        <td>
                            
                            Do you really want to issue endorsement?.
                                <input type='hidden' name='endorsement_id' value="<%- endorsementId %>">
                                    <input type="hidden" id="policyId" name="endorsement_policy_id" value="<%- policyId %>"  >
                 
                        </td>
                    </tr>
                    
                    
                    

                </tbody></table></div>
    <?php echo e(Form::close()); ?>   
    
    
</script>

<script id='endorsement_rejected_template' type='text/template'>
    
 <?php echo e(Form::open(array('route' => array('rejectendorsement'), 'name' => 'form_endorsement_reject','id'=>'form_endorsement_reject','files'=>'true' ))); ?>


    <div class="dialogform"><table class="insly_dialogform">
                <tbody>                    
                    <tr id="field_reject_reason" class="field">
                  
                        <td>
                            
                           Reject reason
                                <input type='hidden' name='reject_endorsement_id' value="<%- endorsementId %>">
                                    <input type="hidden" id="reject_policyId" name="reject_endorsement_policy_id" value="<%- policyId %>"  >
                 
                        </td>
                        <td>
                            <div class="element"><textarea id="reject_reason" name="reject_reason" wrap="soft" rows="4" autocomplete="off" maxlength="255" class="editor col-md-12 form-control" required error-message="Reject reason is mandatory"></textarea>
<span id="error-message" style="display:none">Reject reason is mandatory</span></div>

                            <td>
                    </tr>
                    
                    
                    

                </tbody></table></div>
    <?php echo e(Form::close()); ?>   
    
    
</script>




<?php $__env->stopSection(); ?>

<?php $__env->startSection('customcss'); ?>
<link rel="stylesheet" type="text/css" href=" <?php echo e(asset('elitedesign/assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css')); ?> ">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('elitedesign/assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css')); ?>"> 


<?php $__env->stopSection(); ?>

<?php $__env->startSection('customscript'); ?> 
        
<script src="<?php echo e(asset('elitedesign/assets/node_modules/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('elitedesign/assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/global/datatable/moment.min.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/global/datatable/datetime.js')); ?>" type="text/javascript"></script>

    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('pagescript'); ?> 
     
<script>
    var columnDefs =[] , 
    columnDefs1 = [];
  
    var endorsementlistTable = '';
    var approvedendorsementlistTable ='';
    var roleArray = <?php echo json_encode(Auth::user()->roles, 15, 512) ?>;
   $(function(){
    
        columnDefs.push({"name": 'policynumber',  "targets": 0, data: function (row, type, val, meta) {
                            var urlString = '<?php echo route("overviewendorsement",["##RID"]); ?>';
                            var link = urlString.replace("##RID", row['id']);
                            var subject = (row['policy_number'] !==null) ? row['policy_number']: "---not issued---";
                            var subject =   (row['policy_number'] !==null) ? "<a href='"+link+"'>"+ row['policy_number']+"</a>": "<a href='"+link+"'>"+"---not issued---"+"</a>";
                            row.sortData = row['policy_id'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 

columnDefs.push({"name": "customer",  "targets": 1, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});


columnDefs.push({"name": "insurance",  "targets": 2, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['insurer_name'];
                            row.sortData = row['insurer_name'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});




        columnDefs.push({"name": "endorse_type",  "targets": 3, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['typeString'];
                            row.sortData = row['typeString'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'issueDate',  "targets": 4, "orderable": true, data: function (row, type, val, meta) {
                            var subject =row['formatted_issueDate'] ;
                            row.sortData = row['issue_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        columnDefs.push({"name": 'startDate',  "targets": 5, "orderable": true, data: function (row, type, val, meta) {
                            var subject =  row['formatted_startDate'];
                            row.sortData = row['start_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": 'expiry_date',  "targets": 6, "orderable": true, data: function (row, type, val, meta) {
                            var subject =  moment(row['expiry_date']).format('DD.MM.YYYY');
                            row.sortData = moment(row['expiry_date']).format('DD.MM.YYYY');
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
          columnDefs.push({"name": 'due_date',  "targets": 7, "orderable": true, data: function (row, type, val, meta) {
                            var subject = (row['due_date'] !=null) ? moment(row['due_date']).format('DD.MM.YYYY'):'-';
                            row.sortData = (row['due_date'] !=null) ? moment(row['due_date']).format('DD.MM.YYYY'):'-';
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});           
                    
                    
                    
        columnDefs.push({"name": 'sum',  "targets": 8, data: function (row, type, val, meta) {
              
                        row.sortData = (row['amount']+row['vat_amount']).toFixed(2);
                        row.displayData = (row['amount']+row['vat_amount']).toFixed(2);                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
      columnDefs.push({"name": 'updatedDate',  "targets": 9, "orderable": false, data: function (row, type, val, meta) {
                            row.displayData = '<a class="dib-cursor-style dpib_endorsement_edit" endorsement_id=' + row['id'] + ' policy_id =' + row['policy_id'] + ' ><span class="mdi mdi-thumb-up-outline" data-toggle="tooltip" title="" data-original-title="Activate endorsement"></span></a><a class="dib-cursor-style dpib_endorsement_reject" endorsement_id=' + row['id'] + ' policy_id =' + row['policy_id'] + ' style="margin-left:10px"><span class="mdi mdi-thumb-down" data-toggle="tooltip" title="" data-original-title="Reject endorsement"></span></a>' ;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                        
       
      endorsementlistTable =   $('.dpib_endorsement_list').DataTable( {
                data: <?php echo $endorsementDetails; ?>,
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
    
columnDefs1.push({"name": 'endorsementno',  "targets": 0, data: function (row, type, val, meta) {
                            var urlString = '<?php echo route("overviewendorsement",["##RID"]); ?>';
                            var link = urlString.replace("##RID", row['id']);
                            var subject = (row['endorsement_number'] !==null) ? row['endorsement_number']: "---not issued---";
                            var subject =   (row['endorsement_number'] !==null) ? "<a href='"+link+"'>"+ row['endorsement_number']+"</a>": "<a href='"+link+"'>"+"---not issued---"+"</a>";
                            row.sortData = row['endorsement_number'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 

      columnDefs1.push({"name": 'policynumber',  "targets": 1, data: function (row, type, val, meta) {
                            var urlString = '<?php echo route("policyoverview",["##RID"]); ?>';
                            var link = urlString.replace("##RID", row['policy_id']);
                            var subject = (row['policy_number'] !==null) ? row['policy_number']: "---not issued---";
                            var subject =   (row['policy_number'] !==null) ? "<a href='"+link+"'>"+ row['policy_number']+"</a>": "<a href='"+link+"'>"+"---not issued---"+"</a>";
                            row.sortData = row['policy_number'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 

      columnDefs1.push({"name": "customer",  "targets": 2, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
        columnDefs1.push({"name": "endorse_type",  "targets": 3, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['typeString'];
                            row.sortData = row['typeString'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs1.push({"name": 'issueDate',  "targets": 4, "orderable": true, data: function (row, type, val, meta) {
                            var subject =row['formatted_issueDate'] ;
                            row.sortData = row['issue_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        columnDefs1.push({"name": 'startDate',  "targets": 5, "orderable": true, data: function (row, type, val, meta) {
                            var subject =  row['formatted_startDate'];
                            row.sortData = row['start_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                        
        columnDefs1.push({"name": 'expiry_date',  "targets": 6, "orderable": true, data: function (row, type, val, meta) {
                            var subject =  moment(row['expiry_date']).format('DD.MM.YYYY');
                            row.sortData = moment(row['expiry_date']).format('DD.MM.YYYY');
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
          columnDefs1.push({"name": 'due_date',  "targets": 7, "orderable": true, data: function (row, type, val, meta) {
                            var subject =  (row['due_date'] !=null) ? moment(row['due_date']).format('DD.MM.YYYY'): '-';
                            row.sortData = (row['due_date'] !=null) ? moment(row['due_date']).format('DD.MM.YYYY'): '-';
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                   
                    
        columnDefs1.push({"name": 'sum',  "targets": 8, data: function (row, type, val, meta) {
              
                        row.sortData = (row['amount']+row['vat_amount']).toFixed(2);
                        row.displayData = (row['amount']+row['vat_amount']).toFixed(2);                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    

    approvedendorsementlistTable =   $('.dpib_approved_endorsement_list').DataTable( {
                data: <?php echo $approvedEndorsement; ?>,
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
                dom: "Blftip"
     
    } ); 
    
    
    
    
    
    
    
    
     $(document).off('click','.dpib_endorsement_edit');
    $(document).on('click','.dpib_endorsement_edit',function(){   
    
     var template = _.template($("#endorsement_issued_template").html());
     
                var result = template({'endorsementId':$(this).attr('endorsement_id'),'policyId':$(this).attr('policy_id')});
                $("#db_endorsement_issued_popup").remove();
                $('body').append('<div id="db_endorsement_issued_popup" title="Issue policy endorsement" style="display:none" >' + result + '</div>');
                var dialogElement = $("#db_endorsement_issued_popup");
                dialogElement.dialog({
                    width: 900,
                   
                    modal: true,
                    buttons: {
                        "Issue": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:"Issue",
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));

                                    $("#endorsement_number").removeClass('error');
                                    $("form#form_endorsement_issue").submit();
                                    $("#endorsement_number").removeClass('error');
                                
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

     $(document).off('click','.dpib_endorsement_reject');
    $(document).on('click','.dpib_endorsement_reject',function(){   
    
     var template = _.template($("#endorsement_rejected_template").html());
     
                var result = template({'endorsementId':$(this).attr('endorsement_id'),'policyId':$(this).attr('policy_id')});
                $("#db_endorsement_issued_popup").remove();
                $('body').append('<div id="db_endorsement_issued_popup" title="Reject policy endorsement" style="display:none" >' + result + '</div>');
                var dialogElement = $("#db_endorsement_issued_popup");
                dialogElement.dialog({
                    width: 900,
                   
                    modal: true,
                    buttons: {
                        "Issue": {
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
                                    
                                    $("form#form_endorsement_reject").submit();
                                  
                                
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



</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.elite',['notificationCount'=>$notificationCount ] , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Xampp_new\htdocs\inslyportal\resources\views/Dashboard/financeendorsementTable.blade.php ENDPATH**/ ?>