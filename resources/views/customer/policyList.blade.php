<div class='card'>
<div class="card-body open">
  
    
<!--        <ul class="panel-actions list-inline pull-right">
          <li class="dpib_customer_crm_document_add"><span class="panel-action-add"  data-toggle="tooltip" title="Add a document"><span class="icon-add"></span></span></li>  
        </ul> -->
        <h1 class="card-title">Policies<small></small></h1> 
  
     
            <div class="table-responsive" style='width:100%;'>
               
                       <table class="display nowrap table table-hover table-striped table-bordered dataTable dpib_policy_list"  width="100%">
                    <thead>
                        <tr>
                            <th style="width: 5%" class="nowrap">Policy No</th>
                            <th style="width: 5%" class="nowrap">Insurer</th>
                            <th  class="nowrap" >Validity</th>
                            <th  class="nowrap" >Inception date</th>
                            <th  class="nowrap" >Expiry date</th>
                            <th  class="nowrap" >Issue date</th>
                           <th  class="nowrap" >Object</th>
                            <th  class="nowrap" >Gross premium</th>
                            <th  class="nowrap" >Status</th>
                            
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table> 
                    
           
            
            </div>
        
</div>
</div>

<script src="{{ asset('js/dibcustom/dib-quote-request.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/dibcustom/dib-policy-add.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/global/datatable/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/global/datatable/datetime.js') }}" type="text/javascript"></script>

 		

       
<script>
    var columnDefs = [];
    var policyTable = '';


      var roleArray = @json(Auth::user()->roles);

   $(function(){
    
        columnDefs.push({"name": 'policyno',  "targets": 0, data: function (row, type, val, meta) {
                
                            var urlString = '{!! route("policyoverview",["##RID"]) !!}';
                            var link = urlString.replace("##RID", row['mainId']);
                            var linkFlag = true; 
                        
                            var subject =   (row['policy_number'] !==null) ? "<a href='"+link+"'>"+ row['policy_number']+"</a>": "<a href='"+link+"'>"+"---not issued---"+"</a>";
                            row.sortData = row['mainId'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": "insurer",  "targets": 1, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['insurer_name'];
                            row.sortData = row['insurer_name'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'validity',  "targets": 2, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['startDate']+ " - " +row['endDate'] ;
                            row.sortData = row['start_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
                    
        columnDefs.push({"name": "inception_date",  "targets": 3, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['startDate'];
                            row.sortData = row['startDate'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
        columnDefs.push({"name": "expiry_date",  "targets": 4, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['endDate'];
                            row.sortData = row['endDate'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": "issue_date",  "targets": 5, "orderable": true, data: function (row, type, val, meta) {
                            var subject = moment(row['issue_date']).format('DD.MM.YYYY'); 
                            row.sortData = moment(row['issue_date']).format('DD.MM.YYYY');
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});            
                    
        columnDefs.push({"name": 'object',  "targets": 6, data: function (row, type, val, meta) {
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
       columnDefs.push({"name": 'grosspremium',  "targets": 7, "orderable": false, data: function (row, type, val, meta) {
                            var subject = $.fn.dataTable.render.number(',', '.', 2, 'SAR ').display(row['gross_amount']) ;
                            row.sortData = row['gross_amount'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
                    
                    
        columnDefs.push({"name": 'status',  "targets": 8, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['statusString'];
                            row.sortData =row['statusString'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
                  
                    
        
       
      policyTable =   $('.dpib_policy_list').DataTable( {
                data: {!! $allpolicies !!},
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
                dom: "Bfrtip"
     
    } ); 
    

    
    
    
       
   });
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


</script>
