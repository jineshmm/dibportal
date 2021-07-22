<div class="card open">
    <div class="card-body"> 
    <div class="panel-heading">
        <ul class="panel-actions list-inline pull-right"></ul> 
        <h1 class="card-title">Log<small></small></h1> </div>
         <div class="table-responsive" style='width:100%;'>
                <table class="display nowrap table table-hover table-striped table-bordered dataTable dpib_customer_log" width='100%'>
                    <thead>
                        <tr>
                            <th style="width: 15%" class="nowrap">Date/time</th>
                            <th style="width: 25%" class="nowrap">Edited by</th>                           
                            <th  class="nowrap">Title</th>
                            <th  class="nowrap">Old value</th>
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
    var customerLogTable = '';
   $(function(){
    
        columnDefs.push({"name": 'createdby',  "targets": 0, data: function (row, type, val, meta) {
                            var subject = (row['edited_at'] !=null)? moment(row['edited_at']).format('DD.MM.YYYY hh:mm'):'-';
                            row.sortData = (row['edited_at'] !=null)? moment(row['edited_at']).format('DD.MM.YYYY hh:mm'):'-';
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": "editedBy",  "targets": 1, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['userName'];
                            row.sortData = row['userName'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'title',  "targets": 2, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['title'];
                            row.sortData = row['title'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});            
                    
        columnDefs.push({"name": 'oldValue',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['old_value'];
                            row.sortData = row['old_value'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
        
             
      customerLogTable =   $('.dpib_customer_log').DataTable( {
                data: {!! $logdata !!},
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
       
   });


</script>
   