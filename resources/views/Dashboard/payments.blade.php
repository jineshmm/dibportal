@extends('layouts.elite',['notificationCount'=>$notificationCount  ] )
@section('content')

<div class="panel panel-default open card">
    <div class="panel-heading card-body">


         <ul class="panel-actions list-inline pull-right dib_head" >

                                <li ><a class='dpib_invoice_payment' target="_blank">
                                        <span class="icon-add fas fa-plus large-size"  data-toggle="tooltip" title="" data-original-title="Add payments"></span></a></li>


                            </ul> 
        <h1 class="panel-title" ><small>Payments</small></h1></div>
        <div class="panel-body"> 
            <div class="auto-scroll table-responsive" style='width:100%;'>
                
        
                <table class="table table-bordered table-striped dpib_payment_table color-table info-table" id="dpib_payment_table">
                    <thead>
                          <tr>
                            <th style="width: 15%" class="nowrap">Payment type</th>
                            <th  class="nowrap" style="width: 15%">Date</th>                           
                            <th  class="nowrap" style="width: 15%">Sum</th>
                            <th  class="nowrap" style="width: 15%">Payer name</th>
                            <th  class="nowrap" style="width: 5%">Reference no</th>
                            <th  class="nowrap" style="width: 20%">Document</th>
                            <th  class="nowrap" style="width: 10%">Action</th>
                            
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
        </div> 

</div>


<script  id='invoice_payment_template' type='text/template'>
    
    
    {{ Form::open(array('route' => array('saveinvoicepayment',0), 'name' => 'form_invoice_payment','id'=>'form_invoice_payment','files'=>'true' )) }}
 
   
    <div class="col-lg-12 dialogform">
                                <div class="card">
                          
                                    <div class="card-body card-block">
                                    
                                       <div class="form-group">
                                            <label for="customer" class=" form-control-label">Customer</label>
                                            
                                             {{ Form::select('customerId', [''=>'------- select customer--------']+ $customers, '',array('name'=>'customerId','id' =>'customerId','required'=>'required','class'=>'required form-control','error-message' =>"Insurance company field is mandatory"))}}        
                                                                                      
                                        </div>
                            
                                       <div class="form-group">
                                            <label for="invoice" class=" form-control-label">Invoice</label>
                                           
                                             {{ Form::select('invoiceId', [''=>'------- select invoice--------'], '',array('name'=>'invoiceId','id' =>'companyInvoices','required'=>'required','class'=>'required form-control','error-message' =>"Invoice is mandatory"))}}        
                                                                                 
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="paymenttype" class=" form-control-label">Payment type</label>
                                            
                                             {{ Form::select('paymentmode',  $paymentMode, '',array('name'=>'paymentmode','id' =>'paymentmode','required'=>'required','class'=>'required form-control','error-message' =>"Insurance company field is mandatory"))}}        
                                                                           
                                           
                                        </div>

                                        
                                        
                                          <div class="form-group">
                                            <label for="paymentdate" class=" form-control-label">Payment date</label>
                                           
                                            <input type="date" id="payment_date" name="payment_date" value="{{date('Y-m-d')}}" maxlength="10" autocomplete="off" class="form-control" style="margin-right: 0px !important" onchange="FORM.checkPastDate('#policy_date_start');"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_date_start_comment"></div></div>
                                          
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="paymentsum" class=" form-control-label">Payment sum</label>
                                            
                                            <input type="text" id="payment_sum" name="payment_sum" value="" maxlength="10" autocomplete="off"  style="margin-right: 0px !important" class="form-control">
                                             
                                        </div>
                                        
                                         <div class="form-group">
                                            <label for="payername" class=" form-control-label">Payer name</label>
                                         
                                             <input type="text" id="payer_name" name="payer_name" value="" maxlength="10" autocomplete="off"  style="margin-right: 0px !important" class="form-control">
                                        
                                        <div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_payer_name">
                                              </div>
                                        </div>
                                        
                                         <div class="form-group">
                                            <label for="referenceno" class=" form-control-label">Reference no:</label>
                                        
                                              <input type="text" id="payment_refernce_number" name="payment_reference_number" value="" maxlength="10" autocomplete="off"  style="margin-right: 0px !important" class="form-control">
                                        
                                        <div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_payment_ref"></div>
                                             </div>
                                        </div>
                                        
                                         <div class="form-group">
                                            <label for="document" class=" form-control-label">Document</label>
                                             
                                             <input type="file" id="payment_document" name="payment_document" value="" autocomplete="off" maxlength="255"  required error-message="Contact person name is mandatory" class="form-control">
                                        
                                        <div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_payment_ref"></div>
                                              </div>
                                        </div>

                                        
                                        
                                        
                                        
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
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
    var paymentTable = '';


      var roleArray = @json(Auth::user()->roles);

   $(function(){
    
        columnDefs.push({"name": 'paymenttype',  "targets": 0, data: function (row, type, val, meta) {
                            var subject = row['paymentmethodString'];
                            row.sortData = row['payment_transfer_type'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": "date",  "targets": 1, "orderable": true,data: function (row, type, val, meta) {
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
                    
        columnDefs.push({"name": 'payername',  "targets": 3, "orderable": true, data: function (row, type, val, meta) {
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
              var subject = '<a target="_blank" class="dpib_delete_payment" data-url="'+linkString+'"><span class="fas fa-archive" data-toggle="tooltip" title="" data-original-title="Delete payments"></span></a>';
                            
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
        
             
      paymentTable =   $('#dpib_payment_table').DataTable({
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
                dom: "Blftip"
     
    } ); 
    

   
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
            class: "btn waves-effect waves-light btn-rounded btn-success",
            text:"Delete",
            click: function() {
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
            class: "btn waves-effect waves-light btn-rounded btn-danger",
            text:"cancel",
            click: function() {
              dialogElement.dialog('close');
              dialogElement.remove();
            }
          }
        }
        
      });

      DIB.centerDialog();
    });
    

    
    $(document).off('click','.dpib_invoice_payment');
    $(document).on('click','.dpib_invoice_payment',function(){
        var template = _.template($("#invoice_payment_template").html());
     
          var result = template();
          
         
            $("#db_invoice_payment_popup").remove();
                $('body').append('<div id="db_invoice_payment_popup" title="Add payment" class="col-lg-12" >' + result + '</div>');
              
                var dialogElement = $("#db_invoice_payment_popup");
                
        $("#db_invoice_payment_popup").dialog({
                                                            minWidth: 600,                                                            
                                                            modal:true,                                                            
                                                            buttons: {
                                                                    "Add": {
                                                                      class: "btn waves-effect waves-light btn-rounded btn-primary",                                                                 
                                                                      text: "Create",                            
                                                                       click: function () {                               
                                                                         
                                                                            if($('#companyInvoices').val()=='') {  
                                                                                    var errorMessage = "<b>" + $('#companyInvoices').attr('error-message') + "</b>" 
                                                                                   alertBox(errorMessage);
                                                                                   
                                                                                  } else {
                                                                                      $("form#form_invoice_payment").submit();
                                                                                     
                                                                                   }
                                                                                    
                                                                                }
                                                                     },
                                                                    "cancel": {
                                                                      class: "btn waves-effect waves-light btn-rounded btn-danger",
                                                                      text:"Cancel",
                                                                           click:function(){ 
                                                                               $(this).dialog("close");
                                                                           } 
                                                                     
                                                                    }

                                                            },
                                                            open:function() {
                                                             var defaultSett ={ 'buttonImage': "{{ asset('Images/icon-calendarpicker.png') }}"};                                                

                                                            FORM.setDatePicker('.datefield',defaultSett);
                                                          
                                                            }
});
                 
     DIB.centerDialog();  
    });
    

     $(document).off('change','#customerId');
                        $(document).on('change','#customerId',function(){                            
                           $.ajax({
                                 url: "{!! route('getcustomerinvoice') !!}",
                                  headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                 type: "post",
                                 data:{'customer_id':$(this).val(),'selectedoption':''}

                               }).done(function (data) {
                                  
                                     $("#companyInvoices").empty().html(data.optionstring);
                                  
                                
                                       
                                  }); 
              
                        });
    
    
   });
   
      function alertBox(result) {
   $("#db_error_notification_popup").remove();
                $('body').append('<div id="db_error_notification_popup" title="Error!!!!" class="col-lg-12" >' + result + '</div>');
              
                $("#db_error_notification_popup").dialog({
                                                            minWidth: 600,                                                            
                                                            modal:true,
                                                            buttons: {
                                                                    
                                                                        "cancel": {
                                                                            class: "btn waves-effect waves-light btn-rounded btn-primary",
                                                                             text: "Ok",
                                                                           click:function(){ 
                                                                               $(this).dialog("close");
                                                                           
                                                                           }

                                                                        }
                                                            },
                                                            open:function() {
                                                            $('.modal-backdrop').remove();                                                            
                                                            
                                                            }
});
   
   
   }
   
   

</script>
@endsection