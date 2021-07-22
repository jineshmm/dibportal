<div class="card open">
    <div class="card-body"> 
    <div class="panel-heading">
        <ul class="panel-actions list-inline pull-right"></ul> 
        <h1 class="card-title">Payments<small></small></h1> </div> 
            <div class="table-responsive" style='width:100%;'>
                <table class="display nowrap table table-hover table-striped table-bordered dataTable dpib_payment_table" width='100%'>
                    <thead>
                        <tr>
                            <th style="width: 15%" class="nowrap">Payment type</th>
                            <th  class="nowrap">Date</th>                           
                            <th  class="nowrap">Sum</th>
                            <th  class="nowrap">Payer name</th>
                            <th  class="nowrap">Reference no</th>
                            <th  class="nowrap">Document</th>
                            <th  class="nowrap"></th>
                            
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
    var paymentTable = '';
   $(function(){
    
        columnDefs.push({"name": 'paymenttype',  "targets": 0, data: function (row, type, val, meta) {
                            var subject = row['paymentmethodString'];
                            row.sortData = row['payment_transfer_type'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": "date",  "targets": 1, "orderable": false,data: function (row, type, val, meta) {
                            var subject = moment(row['payment_date']).format('DD.MM.YYYY');
                            row.sortData = row['payment_date'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'sum',  "targets": 2, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['payment_sum'].toFixed(2);
                            row.sortData = row['payment_sum'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});            
                    
        columnDefs.push({"name": 'payername',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['payer_name'];
                            row.sortData = row['payer_name'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    columnDefs.push({"name": 'reference',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['reference_number'];
                            row.sortData = row['reference_number'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    columnDefs.push({"name": 'fileupload',  "targets": 5, "orderable": false, data: function (row, type, val, meta) {
                            
                            var link = "{!! route('getfiledownload',['##CID','payment',0,'##FILE',0 ]) !!}";
                            
                            var linkString = link.replace("##CID", row['customer_id']).replace("##FILE", row['upload_file']); 
                            
                            var subject = (row['upload_file'] != null )  ? '<a target="_blank" href="'+linkString+'">'+row['upload_file']+'</a>':'';
                            
                            row.sortData = row['upload_file'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    columnDefs.push({"name": 'actions',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var link ="{!! route('deleteinvoicepayment',['##INVID##','##PID']) !!}";
                            var linkString = link.replace("##INVID##", row['invoice_id']).replace("##PID", row['id']);      
            var subject = '<a class="dpib_delete_payment" data-url="'+linkString+'"><span class="icon-delete" data-toggle="tooltip" title="" data-original-title="Delete payments"></span></a>';
                            
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
        
             
      paymentTable =   $('.dpib_payment_table').DataTable({
                data: {!! $paymentData !!},
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
    
//    REMOVE PAYMENT
   
     $(document).off('click', '.dpib_delete_payment');
    $(document).on('click', '.dpib_delete_payment', function() {
      var ajaxUrl = $(this).attr('data-url');
      $("#db_payment_delete_popup").remove();
      $('body').append('<div id="db_payment_delete_popup" title="Delete payment" style="display:none" >Do you really want to delete?</div>');
      var dialogElement = $("#db_payment_delete_popup");
      dialogElement.dialog({
        width: 400,
        resizable: false,
        bgiframe: true,
        modal: true,
        buttons: {
          "Delete": {
            buttonClass: "primary",
            buttonAction: function() {
              dialogElement.dialog('close');
              $.ajax({
                url: ajaxUrl,
                type: "GET"

              }).done(function(data) {

                location.reload(true);

              });

            }
          },
          "cancel": {
            buttonClass: "primary",
            buttonAction: function() {
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
   