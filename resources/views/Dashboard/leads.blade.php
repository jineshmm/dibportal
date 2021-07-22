@extends('layouts.elite',['notificationCount'=>$notificationCount  ] )
@section('content')
<div class="panel panel-default open card">
    <div class="panel-heading card-body" >

          <ul class="panel-actions list-inline pull-right dib_head" >

                                <li ><a href="{{ route('customeradd') }}" ><span class="icon-add fas fa-plus large-size" data-toggle="tooltip" title="Add leads" ></span></a></li>


                            </ul> <h1 class="panel-title" ><small>Leads</small></h1></div><div class="panel-body"> 
            <div class="auto-scroll table-responsive" style='width:100%;'>
                
        
                <table class="table table-bordered table-striped dpib_leads_table color-table info-table" >
                    <thead>
                        <tr>
                            <th style="width: 15%" class="nowrap">customerName</th>
                            <th  style="width: 15%" class="nowrap">customer_idcode</th>                           
                            <th  style="width: 15%" class="nowrap">customer_customercode</th>
                            <th  style="width: 15%" class="nowrap">customer_type</th>
                            <th  style="width: 15%" class="nowrap">customer_email</th>
                            <th  style="width: 15%" class="nowrap">customer_phone</th>
                            <th  style="width: 15%" class="nowrap">saleschannel_name</th>
                            <th  style="width: 15%" class="nowrap">customergroup_name</th>
                            
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
    var leadsTable = '';

      var roleArray = @json(Auth::user()->roles);
      
          var selectedColumns = [{"name":"customerName","rowName":"customerName","title":"Name","linkFlag":true},
         {"name":"customer_idcode","rowName":"id_code","title":"Id code"},
         {"name":"customer_customercode","rowName":"customer_code","title":"Code"},
         {"name":"customer_type","rowName":"type","manipulationFlag":true,"manipulation":{0:"Individual",1:"Company"},"title":"Customer type"},
         {"name":"customer_email","rowName":"email","title":"E-mail address"},
         {"name":"customer_phone","rowName":"phone","title":"Phone"},
        
         {"name":"saleschannel_name","rowName":"userName","title":"Account manager"},
         {"name":"customergroup_name","rowName":"customer_group","title":"Customer group"}];

   $(function(){
          $.each(selectedColumns,function(columnNum,value){
     columnDefs1.push({"name": value.name,  "title":value.title,"targets": columnNum, data: function (row, type, val, meta) {
                            var subject = row[value.rowName];
                            row.sortData = row[value.rowName];
                            
                            if (value['manipulationFlag'] && value.manipulationFlag) {
                              row.displayData = (value['manipulation'][row['type']] ==1) ? value['manipulation'][row['type']]:value['manipulation'][row['type']];  
                            } else if(value['linkFlag']) {                                
                                var linkString = "<a href='{!! route('customeroverview','##Id##') !!}'>"+row[value.rowName]+"</a>";
                                var link = linkString.replace("##Id##", row['customId']);                                
                                row.displayData = link;
                            } else {
                               row.displayData = subject; 
                            }
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
       
   });
        
             
      leadsTable =   $('.dpib_leads_table').DataTable({
                ajax : {
                            url: "{{ route('getcustomerdata',['leads']) }}",
                            type: "GET",
                            cache: false
                        },
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
