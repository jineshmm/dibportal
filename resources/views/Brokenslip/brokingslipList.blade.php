
<div class="card open">
     <div class="card-body"> 
    <div class="panel-heading">
        <ul class="panel-actions list-inline pull-right">
          <li  id='dp_generate_broken_slip' openUrl='{!! route("brokenslipmainform",[$customerId,$crmId]) !!}' class="dib-cursor-style"><span class="panel-action-add"  data-toggle="tooltip" title="Add a broking slip" ><span class="fas fa-plus text-blue"></span></span></li>  
          <li  id='dpib_brokingslip_upload' openUrl='{!! route("brokenslipmainform",[$customerId,$crmId]) !!}' class="dib-cursor-style" style="padding-left:2px;margin-left:0px;"><span class="panel-action-add"  data-toggle="tooltip" title="Upload a broking slip" ><span class="fas fa-upload text-blue"></span></span></li>
        </ul> 
        <h1 class="card-title col-3">Broking slip<small></small></h1> </div>
             <div class="table-responsive" style='width:100%;'>
                <table class="display nowrap table table-hover table-striped table-bordered dataTable dpib_customer_brokingslip" width='100%'>
                    <thead>
                        <tr>
                            <th style="width: 25%" class="nowrap">File</th>
                            <th style="width: 10%" class="nowrap">Company</th>                           
                            <th  class="nowrap" >Product</th>
                            <th  class="nowrap" >Status</th>
                            <th  class="nowrap" >Uploaded By</th>
                            <th  class="nowrap" >Created at</th>
                            <th  class="nowrap" >Actions  </th>
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
        </div>
</div>

<script id='brokingslip_upload_template' type='text/template'>
        
        {{ Form::open(array('route' => array('uploadbrokingslip',$customerId, $crmId), 'name' => 'form_brokingslip_generation','id'=>'form_brokingslip_generation','files'=>'true' )) }}
@csrf    
<div class="dialogform">
    <table class="insly_dialogform" id='brokenslip_creation_table'>
        <tbody>
            <tr id="field_documenttype_oid" class="field dp_main_tr">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Insurance company</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        {{ Form::select('insurance_company',  $insuranceCompany, null,array('multiple'=>'multiple','name'=>'insurance_company[]','id' =>'insurance_company','required'=>'required','class'=>'required form-control','error-message' =>"Insurance company field is mandatory" ))}}  
                    </div>
                </td>
            </tr>
            <tr id="field_document_comment" class="field dp_main_tr">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Product</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        {{ Form::select('insurance_product', [''=>'---Select product---']+$insuranceProduct, null,array('id' =>'insurance_product','required'=>'required','class'=>'required form-control','error-message' =>"Product field is mandatory",'openUrl'=> route("brokenslipfields",[$customerId,$crmId]) ))}}  
                    </div>
                </td>
            </tr>
            <tr id="field_document_comment" class="field dp_main_tr">
                <td class="">
                    <div class="label ">
                        <span class="text-danger"></span>
                        <span class="title">File</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="file" class="required" id="broking_slip_file" name="broking_slip_file" value="" onchange="FORM.validateFile($(this), 128, 'Maximum file upload size 128 MB exceeded!')" autocomplete="off" error-message="File is mandatory" required>
                    </div>
                </td>
            </tr>
            
            
            
            


        </tbody></table></div>
{{ Form::close() }}
</script>
<script src="{{ asset('js/global/datatable/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/global/datatable/datetime.js') }}" type="text/javascript"></script>
<script>
    var columnDefs = [];
    var customerbrokinglistTable = '';
    var customerId ={{$customerId }}; 
    var crmId = {{$crmId }}       
   $(function(){
    
        columnDefs.push({"name": 'filename',  "targets": 0, data: function (row, type, val, meta) {
                            var subject = row['file_name'];
                            row.sortData = row['file_name'];

                            linkString = "<a href='{!! route('getfiledownload',['##CID','brokingslip','##CRMID','##FILE',0]) !!}'>"+subject+"</a>";                             
                             var link = linkString.replace("##CID", row['customerId']).replace("##FILE", subject).replace("##CRMID", row['crmId']);
                            row.displayData = link;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": "company",  "targets": 1, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['insurerName'];
                            row.sortData = row['insurerName'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'product',  "targets": 2, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['productName'];
                            row.sortData = row['productName'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});            
                    
         columnDefs.push({"name": 'status',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['statusString'];
                            row.sortData = row['status'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});               
        columnDefs.push({"name": 'Uploaded by',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['uploadedBy'];
                            row.sortData = row['uploadedBy'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'Uploaded at',  "targets": 5, "orderable": false, data: function (row, type, val, meta) {
                            var subject = (row['created_date'] !=null)? moment(row['created_date']).format('DD.MM.YYYY'):'-'; 
                            row.sortData = (row['created_date'] !=null)? moment(row['created_date']).format('DD.MM.YYYY'):'-';
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
         columnDefs.push({"name": 'actions',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var subject = '-';  
                            var displayString ='';
                            
                            displayString = '<a href="{!! route("viewfile",["##CID","brokingslip","##CRMID","##FILE"]) !!} " target="_blank"><span class="fas fa-eye text-blue mr-right" data-toggle="tooltip" title="" data-original-title="View brokingslip"></span></a>';
                            displayString+= '&nbsp;&nbsp;<a class="dpib_brokingslip_sendmail" openUrl="{!! route("sendbrokenslippopup",["##CID","##CRMID","##MAINID"] ) !!}" data-title="Send broking slip"><span class="fas fa-envelope text-blue mr-right" data-toggle="tooltip" title="" data-original-title="Send email" ';
                            if(row['sendmail_flag']==1) {
                              displayString+=' style=color:red'+'></span></a>';  
                            } else {
                               displayString+=' ></span></a>'; 
                            }
                            displayString+='&nbsp;&nbsp;<a class="dpib_delete_brokingslip" actionUrl="{!! route("deletebrokingslip",["##CID","##CRMID","##FILE","##MAINID"]) !!}"><span class="fas fa-times-circle text-blue mr-right" data-toggle="tooltip" title="" data-original-title="Delete brokingslip"></span></a>';
                            if(row['quotes_id'] ==null) {
                             displayString+='&nbsp;&nbsp;<a class="dpib_upload_quote" actionUrl="{!! route("quoteuploadform",["##CID","##CRMID","##MAINID"]) !!}"><span class="fas fa-upload text-blue" data-toggle="tooltip" title="" data-original-title="Upload quotes"></span></a>';   
                            }
                            
    
                            var completeString =  displayString.replace(/##CID/g, row['customerId']).replace(/##FILE/g, row['file_name']).replace(/##CRMID/g, row['crmId']).replace(/##MAINID/g,row['brokId']);
                            row.displayData = completeString;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});           
                    
        
       
       
       
       
      customerbrokinglistTable =   $('.dpib_customer_brokingslip').DataTable( {
                data: {!! $brokingslipData !!},
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
                dom: "Blftip",

     
    } ); 
    
    $(document).off('click','#dpib_brokingslip_upload');
    $(document).on('click','#dpib_brokingslip_upload',function(){


        $("#db_comment_add_popup").remove();



        var template = _.template($("#brokingslip_upload_template").html());
        var result = template();



        $('body').append('<div id="db_comment_add_popup" title="Add comment" style="display:none" >' + result + '</div>');
        var dialogElement = $("#db_comment_add_popup");
        dialogElement.dialog({
          width: 900,
          
          modal: true,
          buttons: {
            "Save": {
              class: "btn waves-effect waves-light btn-rounded btn-success",
               text:'Save',
     
              click: function() {
                 DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));

                                var isValid = true;
         var errorMessage = "";
                var i=0;
                $(".required:visible").each(function(){                
                 if($(this).val()=='' || $(this).val() == null) {
                    console.log($(this).attr("name"));
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
                $("form#form_brokingslip_generation").submit();
            } else {
               DIB.alert(errorMessage,'Error!!!!');
            }

               

              }
            },
            "cancel": {
              class: "btn waves-effect waves-light btn-rounded btn-danger",
              text:'Cancel',
              click: function() {
                dialogElement.dialog('close');
                dialogElement.remove();
              }
            }
          },
          open: function() {

            
          }

        });



        DIB.centerDialog();
    
    });
    
    
    
    
    
       
   });


</script>