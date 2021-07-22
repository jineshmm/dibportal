@extends('layouts.elite_fullwidth',['notificationCount'=>0  ] )


@section('content')


<div class="card">
    <div class="card-body">
        <h4 class="card-title">Filters</h4>

  
            {{ Form::open(array('route' => 'requestfilter', 'name' => 'salesrequestfilter','id'=>'salesrequestfilter','class'=>'') ) }}
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
                <label for="example-email-input" class="col-2 col-form-label">Type</label>
                <div class="col-10">
                
                    {{ Form::select('endorsement_type', [''=>'---Select---']+ $typeArray,  '' ,array('id' =>'endorsement_type','class'=>'custom-select col-12','error-message' =>"Gender field is mandatory" ))}}  
                </div>
            </div>
            
            <div class="form-group row">
                <label for="example-email-input" class="col-2 col-form-label">Status</label>
                <div class="col-10">
                
                    {{ Form::select('endorsement_status', [''=>'---Select---']+ $statusArray,  '' ,array('id' =>'endorsement_status','class'=>'custom-select col-12','error-message' =>"Gender field is mandatory" ))}}  
                </div>
            </div>
            
            
            
            <button type="submit" class="btn btn-success mr-2">Generate report</button>
            <button type="button" class="btn btn-dark" id='filterCancel' redirectUrl='{{ route('operationrequestreport')}}'>Cancel</button>

       {{ Form::close() }}
    </div>
</div>

@isset($requestData)
<div class="card">
    <div class="card-body">
        <a href="{{ route('requestexport')}}"><button type="button" class="btn btn-info d-none d-lg-block m-l-15 pull-right"><i class="mdi mdi-file-export"></i> Export</button></a>
        <div class="auto-scroll" style="width:100%;padding-top:10px">
        <table class="table table-bordered table-striped dpib_endorsement_list color-table info-table" >
                    <thead>
                        <tr>
                            <th style="width: 15%" class="nowrap">No:</th>
                            <th style="width: 10%" class="nowrap">Customer</th>
                            <th  class="nowrap" style="width: 10%">Type</th>
                            <th  class="nowrap" style="width: 10%">Policy</th>
                            <th  class="nowrap" style="width: 15%">Status</th>
                            <th  class="nowrap" style="width: 10%">Created date</th>
                            <th  class="nowrap" style="width: 10%">Last updated date</th>                          
                            <th  class="nowrap" style="width: 10%">Created by</th>
                          
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
    var columnDefs = [];
    var endorsementlistTable = '';
    var roleArray = @json(Auth::user()->roles);  
$(function(){
    @isset($requestData)
   columnDefs.push({"name": 'enorsenumber',  "targets": 0, data: function (row, type, val, meta) {
                        
                            var subject = row['request_id'];
                            row.sortData = row['request_id'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'customer',  "targets": 1, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['customerName'] ;
                            row.sortData = row['customerName'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
        columnDefs.push({"name": "endorse_type",  "targets": 2, "orderable": false,data: function (row, type, val, meta) {
                            var subject = getRequestType(row['etype']);
                            row.sortData = subject;
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'policy',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['policy_number'] ;
                            row.sortData = row['policy_number'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        columnDefs.push({"name": 'status',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  row['statusString'];
                            row.sortData = row['statusString'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
        columnDefs.push({"name": 'createddate',  "targets": 5, data: function (row, type, val, meta) {
              
                        row.sortData =  moment(row['createdAt']).format("DD-MM-YYYY");
                        row.displayData = moment(row['createdAt']).format("DD-MM-YYYY");                        
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
                    
         columnDefs.push({"name": 'lastupdateddate',  "targets": 6, data: function (row, type, val, meta) {
              
                        row.sortData =  moment(row['updatedAt']).format("DD-MM-YYYY");
                        row.displayData =  moment(row['updatedAt']).format("DD-MM-YYYY");                        
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
          columnDefs.push({"name": 'createdby',  "targets":7, data: function (row, type, val, meta) {
              
                        row.sortData = row['userName'];
                        row.displayData = row['userName'];                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
          
                    
                  
                    
        
       
      endorsementlistTable =   $('.dpib_endorsement_list').DataTable( {
                data: {!! $requestData !!},
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
    
    
    
  } );  
  

   function getRequestType(typeId) {

        var requestTypeArray =['','Addition' , 'CCHI Activation'  , 'Claim approval/Settlement' ,'Deletion' ,'Downgrade' ,'Updated member list' ,'Plate No Amendment' ,'Card Replacment' ,'CCHI Upload Status List' ,'MC Certificate' ,'Name Amendment' ,
'Card Printer Request' ,'Invoices Request' ,'Upgrade', 'Request' ,'Inquiry' ,'announcement' ,'Request sign' ,'Others'];
            
                               return requestTypeArray[typeId];
   
   }
     
    
    



</script>
@endsection