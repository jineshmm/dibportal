@extends('layouts.elite_fullwidth',['notificationCount'=>0  ] )


@section('content')


<div class="card">
    <div class="card-body">
        <h4 class="card-title">Filters</h4>

  
            {{ Form::open(array('route' => 'leadfilter', 'name' => 'leadfilter','id'=>'leadfilter','class'=>'') ) }}
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
                <label for="example-email-input" class="col-2 col-form-label">Lead Type</label>
                <div class="col-10">
                    <select class="custom-select col-12" id="request_type" name='request_type'>
                        <option  value=''>Select type...</option>                      
                        <option value="1">Company</option>
                        <option value="0">Individual</option>                        
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="example-email-input" class="col-2 col-form-label">Lead group</label>
                <div class="col-10">
                    <select id="customergroup"  class="custom-select col-12" name="customergroup">
                        <option value="">--- not set ---</option>
                        <option value="corporate">Corporate</option>
                        <option value="retail">Retail</option>
                        <option value="sme">SME</option>
                    </select>
                   
                </div>
            </div>
            
            
            <button type="submit" class="btn btn-success mr-2">Generate report</button>
            <button type="button" class="btn btn-dark" id='filterCancel' redirectUrl='{{ route('saleslead')}}'>Cancel</button>

       {{ Form::close() }}
    </div>
</div>

@isset($customerDetails)
<div class="card">
    <div class="card-body">
        <a href="{{ route('leadexport')}}"><button type="button" class="btn btn-info d-none d-lg-block m-l-15 pull-right"><i class="mdi mdi-file-export"></i> Export</button></a>
        <div class="auto-scroll" style="width:100%;padding-top:10px">
        <table class="table table-bordered table-striped dpib_quote_request_list color-table info-table" >
                    <thead>
                        <tr>
                            <th style="width: 15%" class="nowrap">Name</th>
                            <th style="width: 10%" class="nowrap">Lead type</th>
                            <th  class="nowrap" style="width: 10%">Lead group</th>
                            <th  class="nowrap" style="width: 10%">Account manager</th>
                            <th  class="nowrap" style="width: 15%">Email</th>
                            <th  class="nowrap" style="width: 10%">Mobile</th>
                            <th  class="nowrap" style="width: 10%">Created at</th>
                          
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
    
@endsection
@section('pagescript')
<script>
    var columnDefs = [];
    var leadTable = '';
    var roleArray = @json(Auth::user()->roles);  
$(function(){
    
     columnDefs.push({"name": 'leadname',  "targets": 0, data: function (row, type, val, meta) {
                
                            
                             var linkString = "<a href='{!! route('customeroverview','##Id##') !!}'>"+row['customerName']+"</a>";
                            var link = linkString.replace("##Id##", row['customId']);           
                            row.sortData = row['customerName'];
                            row.displayData = link; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": "leadtype",  "targets": 1, "orderable": true,data: function (row, type, val, meta) {
                            var subject = (row['type']==0) ? 'Individual' :'Company';
                            row.sortData = row['type'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'leadgroup',  "targets": 2, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['customer_group'];
                            row.sortData = row['customer_group'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        columnDefs.push({"name": 'accountmanager',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['userName'];
                            row.sortData = row['userName'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        columnDefs.push({"name": 'email',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                           
                            row.displayData =  row['customerEmail'];                    
                             row.sortData = row['customerEmail'];
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
        columnDefs.push({"name": 'phone',  "targets": 5, data: function (row, type, val, meta) {
                            
                            
                            row.sortData = row['mobile'];
                            row.displayData =  row['mobile'];
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                
                    
        columnDefs.push({"name": 'createdat',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['createdAt'];
                            row.sortData = row['createdAt'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
       
                    
                   
        @isset($customerDetails)            
        
       
      leadTable =   $('.dpib_quote_request_list').DataTable( {
                data: {!! $customerDetails !!},
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
                dom: "Brtip"
     
    } );
    
    
    @endisset
   
  $("#customer_name").autocomplete({
  disabled: false,
   position: { my : "right top", at: "right bottom" },
      source: function( request, response ) {
        $.ajax( {
          url: '{!! route("seachcustomer",["leads"]) !!}',
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
      location.reload();
    })
    
    
    
  } );  
  
 
   
     
    
    



</script>
@endsection