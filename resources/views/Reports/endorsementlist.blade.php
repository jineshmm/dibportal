@extends('layouts.elite',['notificationCount'=>0 ] )

@section('headtitle')
  Endorsement report
@endsection
@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Filters</h4>
 
            {{ Form::open(array('route' => 'endorsementfilter', 'name' => 'salesrequestfilter','id'=>'salesrequestfilter','class'=>'') ) }}
            <div class="form-group mt-5 row">
                <label for="example-text-input" class="col-2 col-form-label">Inception date period</label>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="startDate" name='startDate'>
                </div>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="endDate" name='endDate'>
                </div>
            </div>
            
            <div class="form-group mt-5 row">
                <label for="example-text-input" class="col-2 col-form-label">End date period</label>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="end_startDate" name='end_startDate'>
                </div>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="end_endDate" name='end_endDate'>
                </div>
            </div>
            
            
            <div class="form-group mt-5 row">
                <label for="example-text-input" class="col-2 col-form-label">Issue date period</label>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="issue_startDate" name='issu_startDate'>
                </div>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="issue_endDate" name='issue_endDate'>
                </div>
            </div>
            
            <div class="form-group mt-5 row">
                <label for="example-text-input" class="col-2 col-form-label">Due date period</label>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="due_startDate" name='due_startDate'>
                </div>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="due_endDate" name='due_endDate'>
                </div>
            </div>
            
            
            <div class="form-group row ui-widget">
                <label for="customer_name" class="col-2 col-form-label">Customer</label>
                <div class="col-10">
                    <input  class='form-control' name='search' id="customer_name">
                    <input type='hidden' name='customerId' id='customerId' value=0 />
                    
                </div>

            </div>
            <div class="form-group row">
                <label for="example-email-input" class="col-2 col-form-label">Type</label>
                <div class="col-10">
                {{ Form::select('endorsement_type',  [''=>'---Select---']+$typeArray,  '' ,array('id' =>'endorsement_type','class'=>'custom-select col-12','error-message' =>"Gender field is mandatory" ))}}  
                     
                </div>
            </div>
            <div class="form-group row">
                <label for="example-email-input" class="col-2 col-form-label">Status</label>
                <div class="col-10">
                {{ Form::select('endorsement_status',  [''=>'---Select---']+$statusArray,  '' ,array('id' =>'endorsement_status','class'=>'custom-select col-12','error-message' =>"Gender field is mandatory" ))}}  
                     
                </div>
            </div>
           
            
            <button type="submit" class="btn btn-success mr-2">Generate report</button>
            <button type="button" class="btn btn-dark" id='filterCancel' redirectUrl='{{ route('endorsementreport')}}'>Cancel</button>

       {{ Form::close() }}
    </div>
</div>

@isset($endorsementDetails)   
<div class="card">

    <div class="card-body"> 
        <a href="{{ route('endorsementexport')}}"><button type="button" class="btn btn-info d-none d-lg-block m-l-15 pull-right"><i class="mdi mdi-file-export"></i>Export</button></a>
            <div class="auto-scroll table-responsive">
                <table class="table table-bordered table-striped dpib_approved_endorsement_list color-table info-table">
                    <thead>
                        <tr>
                            <th  style="width:100%" class="nowrap">Policy</th>  
                             <th  class="nowrap">Endorsement no.</th>
                            <th>Type</th>
                            <th class="nowrap">Issue date</th> 
                            <th>Inception date</th>
                            <th>End date</th>
                            <th>Due date</th>
                            <th class="nowrap" >Amount</th>
                            <th class="nowrap" >Status</th>
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
        </div> 

</div>
 @endisset


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
    
@isset($endorsementDetails) 
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
                    
        columnDefs1.push({"name": 'issueDate',  "targets": 3, "orderable": true, data: function (row, type, val, meta) {
                            var subject =row['formatted_issueDate'] ;
                            row.sortData = row['issue_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    
        columnDefs1.push({"name": 'startDate',  "targets": 4, "orderable": true, data: function (row, type, val, meta) {
                            var subject =  row['formatted_startDate'];
                            row.sortData = row['start_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
                    
         columnDefs1.push({"name": 'endDate',  "targets": 5, "orderable": true, data: function (row, type, val, meta) {
                            var subject = (row['expiry_date'] !=null) ? moment(row['expiry_date']).format('DD.MM.YYYY'):'-';
                            row.sortData = (row['expiry_date'] !=null) ? moment(row['expiry_date']).format('DD.MM.YYYY'):'-';
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
         columnDefs1.push({"name": 'dueDate',  "targets": 6, "orderable": true, data: function (row, type, val, meta) {
                            var subject =(row['due_date'] !=null) ? moment(row['due_date']).format('DD.MM.YYYY'):'-';
                            row.sortData = (row['due_date'] !=null) ? moment(row['due_date']).format('DD.MM.YYYY'):'-';;
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});            
                    
        columnDefs1.push({"name": 'sum',  "targets": 7, data: function (row, type, val, meta) {
              
                        row.sortData = row['amount'].toFixed(2);
                        row.displayData = row['amount'].toFixed(2) ;                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
       columnDefs1.push({"name": 'status',  "targets": 8, data: function (row, type, val, meta) {
              
                        row.sortData = row['statusString'];
                        row.displayData = row['statusString'];                         
                           
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
                dom: "Brtip"
     
    } ); 
    
     @endisset

        $("#customer_name").autocomplete({
  disabled: false,
   position: { my : "right top", at: "right bottom" },
      source: function( request, response ) {
        $.ajax( {
          url: '{!! route("seachcustomer",["customer"]) !!}',
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
    
    $(document).on('click','#filterCancel',function(){
      window.location.href = $(this).attr('redirectUrl');
    })     
          
   
       
   });



</script>
@endsection