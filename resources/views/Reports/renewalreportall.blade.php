@extends('layouts.elite',['notificationCount'=>0 ] )
@section('headtitle')
<h3 class="text-blue bold">Renewal report </h3>
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Filters</h4>

  
            {{ Form::open(array('route' => 'renewalfilter', 'name' => 'salesrequestfilter','id'=>'salesrequestfilter','class'=>'') ) }}
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

            
            
            <button type="submit" class="btn btn-success mr-2">Generate report</button>
            <button type="button" class="btn btn-dark" id='filterCancel' redirectUrl='{{ route('corporatepipelinereport')}}'>Cancel</button>

       {{ Form::close() }}
    </div>
</div>


@isset($renewalDetails)
<div class=" card">
    <div class="card-body">
        
 <a href="{{ route('renewalexport')}}"><button type="button" class="btn btn-info d-none d-lg-block m-l-15 pull-right"><i class="mdi mdi-file-export"></i> Export</button></a>
    
                                <!-- Tab panes -->
                     
                                        
<div class="auto-scroll" style='width:100%;padding-top:10px'>
                <table class="table table-bordered table-striped dpib_renewal_list color-table info-table"  style="width:100%">
                    <thead>
                        <tr>
                            <th style="" class="nowrap">Client segment</th>                                                      
                            <th style="" class="nowrap">Channel</th>
                            <th style="" class="nowrap">Current Policy</th> 
                            <th  class="">Premium</th>
                            <th  class="">Agent</th>
                            <th  class="">LOB</th>
                            <th  class="">Client</th>          
                            <th  class="">Product</th>
                            <th  class="">Expiry date</th>                              
                            <th  class="">Renewal date</th>
                            <th  class="">Remainig days</th>                          
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
    var rencolumnDefs = [];
    var complaintTable = '';
    var renewalTable = '';
    var underIssuanceTable = '';
    var roleArray = @json(Auth::user()->roles);
   $(function(){
       
    @isset($renewalDetails)
        
    //Renewal column
    
  
    
     rencolumnDefs.push({"name": 'segment',  "targets": 0, data: function (row, type, val, meta) {
                
                                             
                            var subject = (row['premiumAmount'] > 0) ? clientSegment(row['premiumAmount']):'Small';
                            row.sortData = subject;
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                        
        
                    
       rencolumnDefs.push({"name": "channel",  "targets": 1, "orderable": false,data: function (row, type, val, meta) {
                            var subject =  row['channel'];
                            row.sortData = row['channel'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        rencolumnDefs.push({"name": 'policy',  "targets": 2, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['policy_number'] ;
                            row.sortData = row['policy_number'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
        rencolumnDefs.push({"name": 'premium',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['total_premium'] ;
                            row.sortData = row['total_premium'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
                    
                    
        rencolumnDefs.push({"name": 'agent',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['agent'] ;
                            row.sortData = row['agent'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
      rencolumnDefs.push({"name": 'lob',  "targets": 5, data: function (row, type, val, meta) {
              
                        row.sortData = row['lineofbusinesstitle'];
                        row.displayData = row['lineofbusinesstitle'];                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
                    
        rencolumnDefs.push({"name": 'client',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});    
                    
                    
          rencolumnDefs.push({"name": 'product',  "targets": 7, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  row['product_name'];
                            row.sortData = row['product_name'];
                            row.displayData = subject;
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});               
                    
                   
                    
        rencolumnDefs.push({"name": 'expirydate',  "targets": 8, "orderable": false, data: function (row, type, val, meta) {
                            var subject = moment(row['end_date']).format('DD-MM-YYYY');
                            row.sortData =row['end_date'];
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
         rencolumnDefs.push({"name": 'renewaldate',  "targets": 9, "orderable": false, data: function (row, type, val, meta) {
                                             
                     var subject = moment(row['expirydate']).format('DD-MM-YYYY');
                            row.sortData =row['expirydate'];
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                        
         rencolumnDefs.push({"name": 'remainingdays',  "targets": 10, "orderable": false, data: function (row, type, val, meta) {
                                             
                           var subject = row['datediff'];
                            row.sortData =row['datediff'];
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                        
                        
                        
                        
                 renewalTable =   $('.dpib_renewal_list').DataTable( {
                data: {!! $renewalDetails !!},
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
                order: [[1, "desc"]],
                pageLength: 10,
                displayLength: 10,
                autoFill: false,
                search: false,
                columnDefs:rencolumnDefs,
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
   
   function clientSegment(amount) {
         var segment ='';
        if( amount <= 500000) {
          segment = "Small";  
        } else if(amount <= 3000000) {
           segment = "Medium"; 
        } else if(amount <= 10000000) {
          segment = "Large";  
        } else if(amount > 10000000) {
           segment = "Key Account"; 
        }
        return segment;
   
   }



</script>
@endsection