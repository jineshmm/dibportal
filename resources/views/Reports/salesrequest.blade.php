@extends('layouts.elite_fullwidth',['notificationCount'=>0  ] )


@section('content')


<div class="card">
    <div class="card-body">
        <h4 class="card-title">Filters</h4>

  
            {{ Form::open(array('route' => 'salesrequestfilter', 'name' => 'salesrequestfilter','id'=>'salesrequestfilter','class'=>'') ) }}
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
                    <select class="custom-select col-12" id="request_status" name='request_status'>
                        <option selected="" value=''>Select status...</option>
                        @foreach($statusArray as $key=>$status)
                        <option value="{{$status}}">{{$key}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-success mr-2">Generate report</button>
            <button type="button" class="btn btn-dark" id='filterCancel' redirectUrl='{{ route('salesrequest')}}'>Cancel</button>

       {{ Form::close() }}
    </div>
</div>

<div class="card">
    <div class="card-body">
        <a href="{{ route('salesrequestexport')}}"><button type="button" class="btn btn-info d-none d-lg-block m-l-15 pull-right"><i class="mdi mdi-file-export"></i> Export</button></a>
        <div class="auto-scroll" style="width:100%;padding-top:10px">
        <table class="table table-bordered table-striped dpib_quote_request_list color-table info-table" >
                    <thead>
                        <tr>
                            <th style="width: 15%" class="nowrap">Request id</th>
                            <th style="width: 15%" class="nowrap">Customer name</th>                           
                            <th  class="nowrap" style="width: 10%">Type</th>
                            <th  class="nowrap" style="width: 15%">Description</th>
                            <th  class="nowrap" style="width: 10%">Status</th>
                            <th  class="nowrap" style="width: 10%">Created at</th>
                            <th  class="nowrap" style="width: 15%">Last modified at</th>
                           
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
    
@endsection


@section('pagescript')

<script>
    var columnDefs = [];
    var quoterequestTable = '';
    var roleArray = @json(Auth::user()->roles);  
$(function(){
    
    columnDefs.push({"name": 'requetid',  "targets": 0, data: function (row, type, val, meta) {
                
                            var urlString = '{!! route("crmrequestOverview",["##RID"]) !!}';
                            var link = urlString.replace("##RID", row['mainId']);
                            var linkFlag = true; 
                            
                             if(($.inArray( "ROLE_TECHNICAL_MANAGER", roleArray ) > -1) || ($.inArray( "ROLE_TECHNICAL", roleArray ) > -1) ) {
                               switch(row['status']) {
                                case 2:case 3:case 4: case 5: case 6:
                                    linkFlag =true;
                                break;
                            default:
                                linkFlag =false;
                                   
                               }
                                 
                                 
                             }   else if(($.inArray( "ROLE_SALES_MANAGER", roleArray ) > -1) || ($.inArray( "ROLE_SALES", roleArray ) > -1) ) {
                               switch(row['status']) {
                                   
                                case 0:case 1:case 4:case 7: case 8: case 9:case 10:
                                    linkFlag =true;
                                break;
                            default:
                                linkFlag =false;
                                   
                               }  
                             }
                    
                            var subject = (linkFlag) ?  "<a class='dp_quote_request_overview' openUrl='"+link+"' href='"+link+"'>"+row['crm_request_id']+"</a>" : row['crm_request_id'];
                            row.sortData = row['crm_request_id'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": "customername",  "targets": 1, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'type',  "targets": 2, "orderable": false, data: function (row, type, val, meta) {
                            var subject =(row['type']==0) ? 'Task' :'Request';
                            row.sortData = row['type'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        columnDefs.push({"name": 'Description',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject = (row['type']==0) ? row['subject']:row['description'];
                            row.sortData = (row['type']==0) ? row['subject']:row['description'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
        columnDefs.push({"name": 'status',  "targets": 4, data: function (row, type, val, meta) {
                            var newclass = getStatusColor(row['status']);
                             var subject = "<span class='capital-first font-bold "+newclass+"'>"+row['statusString']+"</span>";
                            if((($.inArray( "ROLE_SALES_MANAGER", roleArray ) > -1) || ($.inArray( "ROLE_SALES", roleArray ) > -1)) &&  ($.inArray( row['status'], [2,3,5,6] ) > -1) ) {
                                
                              subject ="<span class='capital-first font-bold'>Pending with technical department</span>";   
                            }
                            
                            row.sortData = row['status'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                
                    
        columnDefs.push({"name": 'createdat',  "targets": 5, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['created_date'];
                            row.sortData = row['created_date'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'updatedat',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['updated_date'];
                            row.sortData = row['updated_date'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                   
                    
        
       
      quoterequestTable =   $('.dpib_quote_request_list').DataTable( {
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
  
   function getStatusColor(status) {
    var newclass='';
        switch(status){
        case 0:
            newclass='text-primary';
            break;
        case 1:
            newclass='text-primary';
            
            break;
        case 2:
            newclass='text-warning';
          
            break;
        case 3:
              newclass='text-success';
            
            break;
        case 4:
            newclass='text-success';
            
            break;
        case 5:
            newclass='text-info';
            break;
        case 6:
            newclass='text-dark';
            break;
        case 7:
            newclass='text-cyan';
            break;
        case 8:
            newclass='text-warning';
            break;
        case 9:
            newclass='text-purple';
            break;
        case 10:
            newclass='text-danger';
            break;
            
        
    }
    
    return newclass;
   
   }
   
     
    
    



</script>
@endsection