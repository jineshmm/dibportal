

<div class="row">
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Customers</h5>
                <div class="d-flex no-block align-items-center m-t-20 m-b-20">
                    <div id="sparklinedash"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                    <div class="ml-auto">
                        <h2 class="text-success"><i class="ti-arrow-up"></i> <span class="counter"><?php echo e($dashboardDetails['customerCount']); ?></span></h2>
                    </div>
                </div>
            </div>
            <div id="sparkline8" class="sparkchart"></div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Quote</h5>
                <div class="d-flex no-block align-items-center m-t-20 m-b-20">
                    <div id="sparklinedash2"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                    <div class="ml-auto">
                        <h2 class="text-purple"><i class="ti-arrow-up"></i> <span class="counter"><?php echo e($dashboardDetails['quoteCount']); ?></span></h2>
                    </div>
                </div>
            </div>
            <div id="sparkline8" class="sparkchart"></div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Policies</h5>
                <div class="d-flex no-block align-items-center m-t-20 m-b-20">
                    <div id="sparklinedash3"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                    <div class="ml-auto">
                        <h2 class="text-info"><i class="ti-arrow-up"></i> <span class="counter"><?php echo e($dashboardDetails['policyCount']); ?></span></h2>
                    </div>
                </div>
            </div>
            <div id="sparkline8" class="sparkchart"></div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Production</h5>
                <div class="d-flex no-block align-items-center m-t-20 m-b-20">
                    <div id="sparklinedash4"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                    <div class="ml-auto">
                        <h2 class="text-danger"><i class="ti-arrow-up"></i> <span class="counter"><?php echo e(number_format($dashboardDetails['policySum'], 0, '.', ',')); ?></span></h2>
                    </div>
                </div>
            </div>
            <div id="sparkline8" class="sparkchart"></div>
        </div>
    </div>
    <!-- Column -->
</div>


<div class="row">
    <!-- Column -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="card-title m-b-40">TECHNICAL IN <?php echo e(date('Y')); ?></h5>
                        <p>Sales lead is a potential sales contact, individual or organization that expresses an interest in our insurance services who may may eventually become our customer.</p>
                        <p>Customer is a contact, individual or organization that have a policy issued through us.</p>
                    </div>
                    <div class="col-md-8 col-sm-6 col-xs-12">
                        <ul class="list-inline text-right">
                            <li>
                                <h5><i class="fa fa-circle m-r-5 text-primary"></i>Production</h5>
                            </li>
                            <li>
                                <h5><i class="fa fa-circle m-r-5 text-cyan"></i>Endorsement</h5>
                            </li>
                        </ul>

                        <div id="leadsgraph" style="height: 250px;">


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
</div>



<div class="row">

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Requests</h4>

                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table table-bordered table-striped dpib_quote_request_list color-table info-table">
                        <thead>
                            <tr>
                                <th style="width: 15%" class="nowrap">Request id</th>
                                <th style="width: 15%" class="nowrap">Customer name</th>                           
                                <th  class="nowrap" style="width: 10%">Type</th>
                                <th  class="nowrap" style="width: 15%">Description</th>
                                <th  class="nowrap" style="width: 10%">Status</th>
                                <th  class="nowrap" style="width: 10%">Created at</th>
                                <th  class="nowrap" style="width: 15%">Last modified at</th>

                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">

        <div class="card">
            <div class="card-body">
                <ul class="panel-actions list-inline pull-right dib_head" >

                                <li ><a class="dpib_quote_request_add large-size"  href='<?php echo e(route('customeradd')); ?>'><span class="icon-add fas fa-plus large-size" data-toggle="tooltip" title="" data-original-title="Add customer" data-toggle="modal" data-target="#dpib_quote_request_add"></span></a></li>


               </ul>
                <h4 class="card-title" style="width:50%">Customers</h4>

                <div class="table-responsive m-t-40">
                    <table class="table table-bordered table-striped dpib_leads_table color-table info-table" >
                        <thead>
                            <tr>
                                <th style="width: 15%" class="nowrap">customerName</th>
                                <th  class="nowrap">customer_idcode</th>                           
                                <th  class="nowrap">customer_customercode</th>
                                <th  class="nowrap">customer_type</th>
                                <th  class="nowrap">customer_email</th>
                                <th  class="nowrap">customer_phone</th>
                                <th  class="nowrap">saleschannel_name</th>
                                <th  class="nowrap">customergroup_name</th>

                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>



    </div>







</div>

<div class="row">

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                
                <h4 class="card-title">Quotes</h4>

                <div class="table-responsive m-t-40">
                    <table class="table table-bordered table-striped dpib_customer_doc color-table info-table" >
                        <thead>
                            <tr>
                                <th  class="nowrap" >Customer</th>
                                <th style="width: 15%" class="nowrap">File</th>
                                <th style="width: 15%" class="nowrap">Company</th>                           
                                <th  class="nowrap" >Product</th>

                                <th  class="nowrap" >Uploaded By</th>
                                <th  class="nowrap" >Uploaded at</th>

                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>  



    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                 <ul class="panel-actions list-inline pull-right dib_head" >

                                <li ><a class="dpib_quote_request_add large-size"  href='<?php echo route("createpolicy"); ?>'><span class="icon-add fas fa-plus large-size" data-toggle="tooltip" title="" data-original-title="Add policy" data-toggle="modal" data-target="#dpib_quote_request_add"></span></a></li>


               </ul>
                <h4 class="card-title" style="font-weight: 500;width:50%;">Policy</h4>

                <div class="table-responsive m-t-40">
                     <table class="table table-bordered table-striped dpib_policy_list color-table info-table" >
                    <thead>
                        <tr>
                            <th style="width: 5%" class="nowrap">Policy No</th>
                            <th style="width: 5%" class="nowrap">Insurer</th>                      
                            <th  class="nowrap" >Validity</th>
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
    </div>

<!-- Reminder details -->
<!--<div class="col-lg-12">

        <div class="card">
            <div class="card-body">

                <h4 class="card-title" style="width:50%">Reminders</h4>

                <div class="table-responsive m-t-40">
                    <table class="table table-bordered table-striped dpib_reminders_table color-table info-table" style="width:100%">
                        <thead>
                            <tr>
                                <th  class="nowrap" style='width:50%'>Details</th>
                                <th  class="nowrap" style='width:50%'>Actions</th>                           
                                

                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>



    </div>-->


</div>

<?php $__env->startSection('innerpagescript'); ?>
<script>
$(function(){
    
 var encolumnDefs = [];
    var policyTable = '';
  
 
    
        encolumnDefs.push({"name": 'policyno',  "targets": 0, data: function (row, type, val, meta) {
                
                            var urlString = '<?php echo route("policyoverview",["##RID"]); ?>';
                            var link = urlString.replace("##RID", row['mainId']);
                             <?php if(in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)): ?> 
                             var subject =   (row['policy_number'] !==null) ? "<a href='"+link+"'>"+ row['policy_number']+"</a>": "<a href='"+link+"'>"+"---not issued---"+"</a>";
                         <?php else: ?>
                              var subject =   (row['policy_number'] !==null) ?  row['policy_number']: "---not issued---";
                        <?php endif; ?>
                            var subject =   (row['policy_number'] !==null) ? "<a href='"+link+"'>"+ row['policy_number']+"</a>": "<a href='"+link+"'>"+"---not issued---"+"</a>";
                            row.sortData = row['mainId'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        encolumnDefs.push({"name": "insurer",  "targets": 1, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['insurer_name'];
                            row.sortData = row['insurer_name'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        encolumnDefs.push({"name": 'validity',  "targets": 2, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['startDate']+ " - " +row['endDate'] ;
                            row.sortData = row['start_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        encolumnDefs.push({"name": 'customer',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
        encolumnDefs.push({"name": 'object',  "targets": 4, data: function (row, type, val, meta) {
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
                    
        encolumnDefs.push({"name": 'status',  "targets": 5, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['statusString'];
                            row.sortData =row['policy_status'];;
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                  
                    
        
       
      policyTable =   $('.dpib_policy_list').DataTable( {
                data: <?php echo $dashboardDetails['allpolicies']; ?>,
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
                columnDefs:encolumnDefs,
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
    
    //Quotessss
    
    var qcolumnDefs = [];
    var customerQuoteTable = '';
     qcolumnDefs.push({"name": "customername",  "targets": 0, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
        qcolumnDefs.push({"name": 'filename',  "targets": 1, data: function (row, type, val, meta) {
                            var subject = row['file_name'];
                            row.sortData = row['file_name'];
                            linkString = "<a href='<?php echo route('getfiledownload',['##CID','quote', '0', '##FILE',0]); ?>'>"+subject+"</a>";
                             var link = linkString.replace("##CID", row['customer_id']).replace("##FILE", subject);
                            row.displayData = link;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        qcolumnDefs.push({"name": "company",  "targets": 2, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['insurer_name'];
                            row.sortData = row['insurer_name'];
                            row.displayData = subject;
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});            
                    
        qcolumnDefs.push({"name": "product",  "targets": 3, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['product_name'];
                            row.sortData = row['product_name'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});            
                              
       
                    
                
                    
        qcolumnDefs.push({"name": 'Uploaded by',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['uploadedBy'];
                            row.sortData = row['uploadedBy'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        qcolumnDefs.push({"name": 'Uploaded at',  "targets": 5, "orderable": false, data: function (row, type, val, meta) {
                            var subject = (row['created_at'] !=null)? moment(row['created_at']).format('DD.MM.YYYY'):'-';
                            row.sortData =(row['created_at'] !=null)? moment(row['created_at']).format('DD.MM.YYYY'):'-';
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
      
                    
        
       
       
       
       
      customerQuoteTable =   $('.dpib_customer_doc').DataTable( {
                data: <?php echo $dashboardDetails['quoteData']; ?>,
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
                order: [[4, "desc"]],
                pageLength: 10,
                displayLength: 10,
                autoFill: false,
                search: false,
                columnDefs:qcolumnDefs,
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

// Reminder details
 //Quotessss
    
    var rcolumnDefs = [];
    var reminderTable = '';
     rcolumnDefs.push({"name": "customername",  "targets": 0, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['details'];
                            row.sortData = row['details'];
                            row.displayData = subject;                          
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
        rcolumnDefs.push({"name": 'filename', "orderable": false, "targets": 1, data: function (row, type, val, meta) {
                       
                            linkString = '<a class="dpib_brokingslip_sendmail" openUrl="<?php echo route("mailform",["##REID"]); ?>" data-title="Send reminder"><span class="fas fa-envelope text-blue mr-right" data-toggle="tooltip" title="" data-original-title="Send email" ></span></a>';

                             var link = linkString.replace("##REID", row['id']);
                            row.displayData = link;
                          
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
      
                    
        
       
       
       
       
      reminderTable =   $('.dpib_reminders_table').DataTable( {
                data: <?php echo $reminderDetails; ?>,
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
                columnDefs:rcolumnDefs,
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

function sendReminderNotification() {
    let _that = this;
    $(document).off('click', '.dpib_brokingslip_sendmail');
    $(document).on('click', '.dpib_brokingslip_sendmail', function() {
      let title = $(this).attr('data-title');

      $.ajax({
        url: $(this).attr('openUrl'),
        type: "GET"

      }).done(function(data) {
        $("#db_brokenslip_send_popup").remove();
        $('body').append('<div id="db_brokenslip_send_popup" title="' + title + '" style="display:none" >' + data.content + '</div>');
        var dialogElement = $("#db_brokenslip_send_popup");
        dialogElement.dialog({
          width: 900,          
          modal: true,
          buttons: {
            "Send": {
              class: "btn waves-effect waves-light btn-rounded btn-success",
              text:'Send',
              click: function() {
                // dialogElement.dialog('close');
                //   dialogElement.remove();  

                var isValid = true;
                var errorMessage = "";
                var i = 0;
                $("#form_reminder_sendmail .required:visible").each(function() {
                  if ($(this).val() == '' || $(this).val() == null) {
                    isValid = false;
                    $(this).addClass('error');
                    if (i == 0) {
                      errorMessage += "<b>The following errors occurred while validating data:" + "</b><br/>";
                      i++;
                    }
                    errorMessage += "<b>" + $(this).attr('error-message') + "</b><br/>"

                  } else if ($(this).attr('type') == 'email' && (/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/.test($(this).val()) == false)) {
                    isValid = false;
                    $(this).addClass('error');
                    if (i == 0) {
                      errorMessage += "<b>The following errors occurred while validating data:" + "</b><br/>";
                      i++;
                    }
                    errorMessage += "<b>Please enter valid email address</b><br/>"
                  } else {
                    $(this).removeClass('error');
                  }
                });


                if (isValid) {
                  $("form#form_reminder_sendmail").submit();
                } else {
                  DIB.alert(errorMessage, 'Error!!!!');
                }
              }
            },

          }
        });


        DIB.centerDialog();
      });

      DIB.centerDialog();
    });

  }

$(function(){

sendReminderNotification();


})


       
    
})
</script>

<?php $__env->stopSection(); ?><?php /**PATH D:\Xampp_new\htdocs\inslyportal\resources\views/Dashboard/technicaldashboard.blade.php ENDPATH**/ ?>