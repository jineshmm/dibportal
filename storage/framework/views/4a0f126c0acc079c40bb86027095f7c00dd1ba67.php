<?php $__env->startSection('headtitle'); ?>
<h3 class="text-blue bold">Production report </h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Filters</h4>

  
            <?php echo e(Form::open(array('route' => 'financeproductionfilter', 'name' => 'salesrequestfilter','id'=>'salesrequestfilter','class'=>'') )); ?>

            <div class="form-group mt-5 row">
                <label for="example-text-input" class="col-2 col-form-label">Inception date period</label>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="ins_startDate" name='ins_startDate'>
                </div>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="ins_endDate" name='ins_endDate'>
                </div>
            </div>
            <div class="form-group row ui-widget">
                <label for="customer_name" class="col-2 col-form-label">Customer</label>
                <div class="col-10">
                    <input  class='form-control' name='search' id="customer_name">
                    <input type='hidden' name='customerId' id='customerId' value=0 />
                    
                </div>

            </div>

               <div class="form-group mt-5 row">
                <label for="example-text-input" class="col-2 col-form-label">End date period</label>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="end_startDate" name='end_startDate'>
                </div>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="end_startDate" name='end_startDate'>
                </div>
            </div>

            
  <!--             <div class="form-group row">
                <label for="example-email-input" class="col-2 col-form-label">Production type</label>
                <div class="col-10">
                
                    <?php echo e(Form::select('production_type', [''=>'---Select---']+ $statusArray,  '' ,array('id' =>'production_type','class'=>'custom-select col-12','error-message' =>"Gender field is mandatory" ))); ?>  
                </div>
            </div> -->

            
            
            <button type="submit" class="btn btn-success mr-2">Generate report</button>
            <button type="button" class="btn btn-dark" id='filterCancel' redirectUrl='<?php echo e(route('financeproductionreport')); ?>'>Cancel</button>

       <?php echo e(Form::close()); ?>

    </div>
</div>


<?php if(isset($productionDetails)): ?>


<div class=" card">
    <div class="card-body">
        
 <a href="<?php echo e(route('productionreportexport')); ?>"><button type="button" class="btn btn-info d-none d-lg-block m-l-15 pull-right"><i class="mdi mdi-file-export"></i> Export</button></a>

        <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item dpip-pipeline-report"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-table"></i></span> <span class="hidden-xs-down">Policies</span></a> </li>
                                    <li class="nav-item dpip-issuance-report" > <a class="nav-link" data-toggle="tab" href="#profile" role="tab" ><span class="hidden-sm-up"><i class="mdi mdi-table"></i></span> <span class="hidden-xs-down">Endorsement</span></a> </li>
                                    
                                </ul>




    
                                <!-- Tab panes -->
 <div class="tab-content tabcontent-border" style='width:100%;'>
        <div class="tab-pane active" id="home" role="tabpanel">                    
                                        
            <div class="auto-scroll" style='width:100%;padding-top:10px'>
                 <table class="table table-bordered table-striped dpib_policy_list color-table info-table"  style="width:100%">
                    <thead>
                        <tr>
                            <th style="" class="nowrap">Policy no</th>                             
                            <th  class="">Validity</th>
                            <th  class="">Customer</th>
                            <th style="" class="nowrap">Inception date</th>
                            <th style="" class="nowrap">End date</th>
                            <th  class="">Amount</th>
                            <th>Vat</th>
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
        </div>



        <div class="tab-pane" id="profile" role="tabpanel">                    
                                        
            <div class="auto-scroll" style='width:100%;padding-top:10px'>
           <table class="table table-bordered table-striped dpib_endorsement_list color-table info-table"  style="width:100%">
                    <thead>
                        <tr>
                            <th style="" class="nowrap">Policy no</th> 
                            <th style="" class="nowrap">Type</th>
                            <th style="" class="nowrap">Endorsement number</th>
                            <th  class="">Validity</th>
                            <th  class="">Customer</th>
                            <th style="" class="nowrap">Inception date</th>
                            <th style="" class="nowrap">End date</th>
                            <th  class="">Amount</th>
                            <th>Vat</th>
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

<?php endif; ?>

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
    var columnDefs = [];
    var columnDefs1 = [];
    
    var complaintTable;
    var endorsementTable = '';
    var roleArray = <?php echo json_encode(Auth::user()->roles, 15, 512) ?>;
   $(function(){


$('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
$($.fn.dataTable.tables(true)).DataTable()
.columns.adjust()
.responsive.recalc();
});

       
    <?php if(isset($productionDetails)): ?>
    
   
                    columnDefs.push({"name": 'policynumber',  "targets": 0, data: function (row, type, val, meta) {
                            var subject = row['policy_number'];
                            row.sortData = row['policy_number'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    
                    columnDefs.push({"name": 'validity',  "targets": 1, data: function (row, type, val, meta) {
              
                        row.sortData = moment(row['inceptiondate']).format('DD-MM-YYYY') +'-'+moment(row['expirydate']).format('DD-MM-YYYY');
                        row.displayData =  moment(row['inceptiondate']).format('DD-MM-YYYY') +'-'+moment(row['expirydate']).format('DD-MM-YYYY');
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    columnDefs.push({"name": 'customer',  "targets":2, data: function (row, type, val, meta) {
              
                        row.sortData = row['customerName'];
                        row.displayData = row['customerName'];                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    columnDefs.push({"name": "issuedate",  "targets": 3, "orderable": true,data: function (row, type, val, meta) {
                            var subject =  moment(row['inceptiondate']).format('DD-MM-YYYY');
                            row.sortData = moment(row['inceptiondate']).format('DD-MM-YYYY');
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});

                    columnDefs.push({"name": "endedate",  "targets": 4, "orderable": true,data: function (row, type, val, meta) {
                            var subject =  moment(row['expirydate']).format('DD-MM-YYYY');
                            row.sortData = moment(row['expirydate']).format('DD-MM-YYYY');
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                    columnDefs.push({"name": 'amount',  "targets": 5, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['premiumAmount'].toFixed(2);
                            row.sortData =row['premiumAmount'].toFixed(2);
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    columnDefs.push({"name": "vat",  "targets": 6, "orderable": false,data: function (row, type, val, meta) {
                            var subject =  row['policyvatAmount'].toFixed(2);
                            row.sortData =  row['policyvatAmount'].toFixed(2);
                            row.displayData = subject;                       
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
        
      complaintTable =   $('.dpib_policy_list').DataTable( {
                data: <?php echo $productionDetails; ?>,
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

//ENDORSEMENT REPORT
      columnDefs1.push({"name": 'policynumber',  "targets": 0, data: function (row, type, val, meta) {
                            var subject = row['policy_number'];
                            row.sortData = row['policy_number'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                        
                     columnDefs1.push({"name": 'policytype',  "targets": 1, data: function (row, type, val, meta) {
                                                            
                            var subject = (row['product_name'] !='') ? row['product_name']: '';
                            row.sortData = subject;
                            row.displayData = subject;                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});    
                    
                    columnDefs1.push({"name": 'endorsementnumber',  "targets": 2, data: function (row, type, val, meta) {
                                                            
                            var subject = (row['endorsement_number'] !='') ? row['endorsement_number']: '';
                            row.sortData = subject;
                            row.displayData = subject;                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                        
                        
                    
                    columnDefs1.push({"name": 'validity',  "targets": 3, data: function (row, type, val, meta) {
              
                        row.sortData =  moment(row['endorsementStart']).format('DD-MM-YYYY') +'-'+moment(row['expirydate']).format('DD-MM-YYYY') ;
                        row.displayData = moment(row['endorsementStart']).format('DD-MM-YYYY') +'-'+moment(row['expirydate']).format('DD-MM-YYYY') ;
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    columnDefs1.push({"name": 'customer',  "targets":4, data: function (row, type, val, meta) {
              
                        row.sortData = row['customerName'];
                        row.displayData = row['customerName'];                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    columnDefs1.push({"name": "issuedate",  "targets": 5, "orderable": true,data: function (row, type, val, meta) {
                            var subject =   moment(row['endorsementStart']).format('DD-MM-YYYY');
                            row.sortData = moment(row['endorsementStart']).format('DD-MM-YYYY');
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    columnDefs1.push({"name": "enddate",  "targets": 6, "orderable": true,data: function (row, type, val, meta) {
                            var subject =   moment(row['expirydate']).format('DD-MM-YYYY');
                            row.sortData = moment(row['expirydate']).format('DD-MM-YYYY');
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                    columnDefs1.push({"name": 'amount',  "targets": 7, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['premiumAmount'].toFixed(2);
                            row.sortData =row['premiumAmount'].toFixed(2);
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    columnDefs1.push({"name": "vat",  "targets": 8, "orderable": false,data: function (row, type, val, meta) {
                        var subject =  row['endorsementvatAmount'].toFixed(2);
                            row.sortData =  row['endorsementvatAmount'].toFixed(2);                            
                            row.displayData = subject;                       
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
        
      endorsementTable =   $('.dpib_endorsement_list').DataTable( {
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
                order: [[4, "desc"]],
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
                dom: "Brtip"
     
    } ); 




    
          
    
    
    
    <?php endif; ?>
    
      $("#customer_name").autocomplete({
  disabled: false,
   position: { my : "right top", at: "right bottom" },
      source: function( request, response ) {
        $.ajax( {
          url: '<?php echo route("seachcustomer",["customer"]); ?>',
          headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          dataType: "json",
          data: {
            search: request.term
          },
          success: function( data ) {
 
                    
                    
        response($.map(data, function (item)
                    {
                        return{
                            label: item.name,
                            value: item.id,
                            id:item.name
                           
                        }
                    }))

          }
        } );
      },
      minLength: 2,
      select: function( event, ui ) {
          $('#customer_name').val(ui.item.label); // display the selected text
          $('#customerId').val(ui.item.value);
   
   return false;
      }
    } );
    
   
    
    
    
       
   });
   




</script>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.elite',['notificationCount'=>0 ] , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Xampp_new\htdocs\inslyportal\resources\views/Reports/financeproduction.blade.php ENDPATH**/ ?>