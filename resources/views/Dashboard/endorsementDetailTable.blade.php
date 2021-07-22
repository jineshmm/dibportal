@extends('layouts.elite',['notificationCount'=>$notificationCount ] )


@section('content')

<div class="panel panel-default open card">
    <div class="panel-heading card-body">       
        <h1 class="card-title">Endorsements<small></small></h1> 
    </div>
    <div class="panel-body"> 
            <div class="auto-scroll table-responsive">
                <table class="table table-bordered table-striped dpib_approved_endorsement_list color-table info-table">
                    <thead>
                        <tr>
                            <th  style="width:20%" class="nowrap">Policy</th>  
                             <th  class="nowrap">Endorsement no.</th>
                            <th>Type</th>
                            <th>Inception date</th>
                            <th>End date</th>
                            <th class="nowrap">Issue date</th> 
                            <th>Due date</th>
                            <th class="nowrap" >Amount</th>
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
        </div> 

</div>



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
 
   var columnDefs1 = [];
  
   
    var approvedendorsementlistTable ='';
    var roleArray = @json(Auth::user()->roles);
   $(function(){
    

      columnDefs1.push({"name": 'policynumber',  "targets": 0, data: function (row, type, val, meta) {
                            var urlString = '{!! route("policyoverview",["##RID"]) !!}';
                            var link = urlString.replace("##RID", row['policy_id']);
                            var subject = (row['policy_number'] !==null) ? row['policy_number']: "---not issued---";
                            var subject =   (row['policy_number'] !==null) ? "<a href='"+link+"'>"+ row['policy_number']+"</a>": "<a href='"+link+"'>"+"---not issued---"+"</a>";
                            row.sortData = row['policy_number'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
         columnDefs1.push({"name": "endorse_number",  "targets": 1, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['endorsement_number'];
                            row.sortData = row['endorsement_number'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});           
                    
                    
        columnDefs1.push({"name": "endorse_type",  "targets": 2, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['typeString'];
                            row.sortData = row['typeString'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
        columnDefs1.push({"name": 'startDate',  "targets": 3, "orderable": true, data: function (row, type, val, meta) {
                                    var subject =  row['formatted_startDate'];
                                    row.sortData = row['start_date'];
                                    row.displayData = subject;                           
                                    
                                    return row;
                                }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    

                    columnDefs1.push({"name": 'endDate',  "targets": 4, "orderable": true, data: function (row, type, val, meta) {
                            var subject =(row['expiryDate'] !=null)? moment(row['expiryDate']).format('DD.MM.YYYY'):moment(row['expiryDate']).format('DD.MM.YYYY');  
                            row.sortData = (row['expiryDate'] !=null)? moment(row['expiryDate']).format('DD.MM.YYYY'):moment(row['expiryDate']).format('DD.MM.YYYY');
                                                     
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    


                    
        columnDefs1.push({"name": 'issueDate',  "targets": 5, "orderable": true, data: function (row, type, val, meta) {
                            var subject =row['formatted_issueDate'] ;
                            row.sortData = row['issue_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
       columnDefs1.push({"name": 'due_date',  "targets": 6, "orderable": true, data: function (row, type, val, meta) {
                            var subject =(row['due_date'] !=null)? moment(row['due_date']).format('DD.MM.YYYY'):'-';
                            row.sortData =(row['due_date'] !=null)? moment(row['due_date']).format('DD.MM.YYYY'):'-';
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        
        columnDefs1.push({"name": 'sum',  "targets": 7, data: function (row, type, val, meta) {
              
                        row.sortData = row['amount'].toFixed(2);
                        row.displayData = row['amount'].toFixed(2) ;                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    

    approvedendorsementlistTable =   $('.dpib_approved_endorsement_list').DataTable( {
                data: {!! $endorsementDetails !!},
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
    
    

            
          
   
       
   });



</script>
@endsection