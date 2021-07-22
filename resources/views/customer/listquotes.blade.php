
<div class="card">
    <div class='card-body'>

<!--        <ul class="panel-actions list-inline pull-right">
          <li class="dpib_customer_crm_document_add"><span class="panel-action-add"  data-toggle="tooltip" title="Add a document"><span class="icon-add"></span></span></li>  
        </ul> -->
        <h1 class="card-title">Quotes<small></small></h1> 
        
        
            <div class="table-responsive" style='width:100%;'>
                <table class="display nowrap table table-hover table-striped table-bordered dataTable dpib_customer_doc" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 15%" class="nowrap">File</th>
                            <th style="width: 15%" class="nowrap">Company</th>                           
                            <th  class="nowrap" >Product</th>
                            <th  class="nowrap" >Comments</th>
                            <th  class="nowrap" >Uploaded By</th>
                            <th  class="nowrap" >Uploaded at</th>
                            <th  class="nowrap" style="width: 30%" >  </th>
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
    var customerQuoteTable = '';
   $(function(){
    
        columnDefs.push({"name": 'filename',  "targets": 0, data: function (row, type, val, meta) {
                            var subject = row['file_name'];
                            row.sortData = row['file_name'];
                            linkString = "<a href='{!! route('getfiledownload',['##CID','quote', '0', '##FILE',0]) !!}'>"+subject+"</a>";
                             var link = linkString.replace("##CID", row['customer_id']).replace("##FILE", subject);
                            row.displayData = link;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": "company",  "targets": 1, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['insurer_name'];
                            row.sortData = row['insurer_name'];
                            row.displayData = subject;
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});            
                    
        columnDefs.push({"name": "product",  "targets": 2, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['product_name'];
                            row.sortData = row['product_name'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});            
                              
        columnDefs.push({"name": "comment",  "targets": 3, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['additional_desc'];
                            row.sortData = row['additional_desc'];
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
                            var subject = (row['created_at'] !=null)? moment(row['created_at']).format('DD.MM.YYYY'):'-'; 
                            row.sortData = (row['created_at'] !=null)? moment(row['created_at']).format('DD.MM.YYYY'):'-';
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
         columnDefs.push({"name": 'actions',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var subject = '-';

                            var displayString ='';
                            
                            displayString = '<a href="{!! route("viewfile",["##CID","quote","0","##FILE"]) !!} " target="_blank"><span class="fas fa-eye text-blue" data-toggle="tooltip" title="" data-original-title="View brokingslip"></span></a>';
                            displayString+= '&nbsp;&nbsp;<a class="dpib_brokingslip_sendmail" openUrl="{!! route("sendquotespopup",["##CID","0","##MAINID","quote"] ) !!}" data-title="Send quote" data-toggle="tooltip"><span class="fas fa-envelope text-blue" data-toggle="tooltip" title="" data-original-title="Send email" ></span></a>';
                            displayString+= '&nbsp;&nbsp;<a class="dpib_send_issuance_document" openUrl="{!! route("issuancedocsendform",["##CID","##CRMID","##MAINID"] ) !!}" data-title="Send issuance document"><span class=fas fa-paper-plane text-blue" data-toggle="tooltip" title="" data-original-title="Send issuance document" ';
                            if(row['send_file_flag']==1) {
                              displayString+=' style=color:red'+'></span></a>';  
                            } else {
                               displayString+=' ></span></a>'; 
                            }
    var completeString =  displayString.replace(/##CID/g, row['customer_id']).replace(/##FILE/g, row['file_name']).replace(/##MAINID/g,row['mainId']).replace(/##CRMID/g,row['crm_id']);
                            row.displayData = completeString;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});           
                    
        
       
       
       
       
      customerQuoteTable =   $('.dpib_customer_doc').DataTable( {
                data: {!! $quoteData !!},
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
                dom: "Bfrtip"
     
    } ); 
    

    
    
       
   });


</script>