@extends('layouts.elite',['notificationCount'=>0  ] )

@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Filters</h4>
  
            {{ Form::open(array('route' => 'claimfilter', 'name' => 'salesrequestfilter','id'=>'salesrequestfilter','class'=>'') ) }}
            <div class="form-group mt-5 row">
                <label for="example-text-input" class="col-2 col-form-label">Date period</label>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="startDate" name='startDate'>
                </div>
                <div class="col-5">
                    <input class="form-control" type="date" value="" id="endDate" name='endDate'>
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
                <label for="example-email-input" class="col-2 col-form-label">Status</label>
                <div class="col-10">
                
                    {{ Form::select('claim_status', [''=>'---Select---']+ $statusArray,  '' ,array('id' =>'claim_status','class'=>'custom-select col-12','error-message' =>"Gender field is mandatory" ))}}  
                </div>
            </div>
            
            
            
            <button type="submit" class="btn btn-success mr-2">Generate report</button>
            <button type="button" class="btn btn-dark" id='filterCancel' redirectUrl='{{ route('policycompliant')}}'>Cancel</button>

       {{ Form::close() }}
    </div>
</div>





     @isset($claimDetails)                
        <div class="card">
    <div class="card-body">
        <a href="{{ route('claimexport')}}"><button type="button" class="btn btn-info d-none d-lg-block m-l-15 pull-right"><i class="mdi mdi-file-export"></i> Export</button></a>
        <div class="auto-scroll" style="width:100%;padding-top:10px">
                                    <table class="table table-bordered table-striped dpib_claim_list color-table info-table">
                                              <thead>
                        <tr>
                            <th style="width: 15%" class="nowrap">Claim id</th>
                            <th style="width: 15%" class="nowrap">Policy number</th>    
                            <th style="width: 15%" class="nowrap">Customer</th>                           
                            <th  class="nowrap">Id code/Reg no</th>
                            <th  class="nowrap">Status</th>
                            <th  class="nowrap">Claimant</th>
                            <th  class="nowrap">Claim handler</th>
                            <th  class="nowrap">Loss date</th>
                            <th  class="nowrap">Submitted date</th>
                           
                        </tr>
                    </thead>
                                        
                                    </table>
                                </div>
                                <!-- END DATA TABLE-->
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
    var columnDefs = [];
    var claimTable = '';
    var roleArray = @json(Auth::user()->roles);     
   
   $(function(){
 @isset($claimDetails)  
        columnDefs.push({"name": 'claimid',  "targets": 0, data: function (row, type, val, meta) {
                
                             var urlString = '{!! route("overviewclaim",["##CID"]) !!}';
                            var link = urlString.replace("##CID", row['id']);    
                    
                           var subject =  "<a class='dp_claim_request_overview' openUrl='"+link+"' href='"+link+"'>"+row['id']+"</a>" ;
                            row.sortData = row['id'];
                            row.displayData = subject;
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
             columnDefs.push({"name": "policynumber",  "targets": 1, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['policy_number'];
                            row.sortData = row['policy_number'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});       
                
        columnDefs.push({"name": "customername",  "targets": 2, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'id/regno',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  (row['id_code'] !=null)? row['id_code']:'';
                            row.sortData = row['id_code'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        columnDefs.push({"name": 'Status',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var subject  =  row['statusString'];
                            row.sortData = row['statusString'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
        columnDefs.push({"name": 'Claimant',  "targets": 5, data: function (row, type, val, meta) {
                           console.log(row['claimant'])
                            var objectString =(row['claimant'] !='')? generateClaimantString(row['claimant']):'';  
                            row.sortData = (objectString !='') ? objectString.slice(0, -1) :'' ;
                            row.displayData = (objectString !='') ? objectString.slice(0, -1) :'' ;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                
                    
        columnDefs.push({"name": 'Claimhandler',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['claimHandler'];
                            row.sortData = row['claimHandler'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'Loss date',  "targets": 7, "orderable": false, data: function (row, type, val, meta) {
                           
                            var subject =(row['incident_date'] !=null)? $.format.date( row['incident_date'], "dd.MM.yyyy HH:mm"):'';
                            row.sortData = row['incident_date'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                        
         columnDefs.push({"name": 'Submitted date',  "targets": 8, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['submitted_insurer_date'];
                            row.sortData = row['submitted_insurer_date'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                 
                    
                   
                    

       
      claimTable =   $('.dpib_claim_list').DataTable( {
                data: {!! $claimDetails !!},
                autoWidth: true,
                stateSave: false,
                stateDuration: 60 * 60 * 24,
                responsive: true,
                deferRender: true,
                lengthChange: true,
                pagination: true,
                rowLength: true,
                scrollX: true,
                pagingType: 'simple_numbers',
                processing: false,
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
    
  } );  
    
    
   
   function generateClaimantString(claimantString) {
                            
                            var objectJson = JSON.parse(claimantString);
                            var objectString =(_.size(objectJson) >0) ? '':'-';
                            if(_.size(objectJson) >0) {
                                $.each(objectJson,function(key,jsonvalue) {
                                  $.each(jsonvalue,function(objkey,value){
                                         objectString +=(value !==null) ? objkey+':'+value+"," : '';
                                   
                                 });                             
                                   
                               })
                            }
                            return objectString
   }
  


</script>
@endsection

