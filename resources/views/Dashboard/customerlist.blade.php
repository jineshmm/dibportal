@extends('layouts.elite',['notificationCount'=>$notificationCount ] )



@section('content')
<div class="dpib_custom_filter">
       <div class="panel-heading">
                        <ul class="panel-actions list-inline pull-right">
                            <li><span class="panel-action-download" onclick="" data-toggle="tooltip" title="" data-original-title="Export to Excel"><span class="fas fa-download"></span></span></li>
                            <li><span class="panel-action-settings customersetting"  data-toggle="tooltip" title="" data-original-title="Settings" ajax-url="{{ route('getcustomerColumnsettingdata') }}" redirect-url="{{ route('listcustomerdata') }}" data-localstorageName="customerColumnSetting_{{ Auth::user()->id }}"><span class="icon-settings"></span></span></li>
                            <li><span class="panel-action-filter" onclick="FILTER.toggle('insly_customer')" data-toggle="tooltip" title="" data-original-title="Open/close filters"><span class="icon-filter open"></span><span class="fas fa-filter"></span></span></li>
                            <li><a href="{{ route('customeradd') }}"><span class="panel-action-add"  data-toggle="tooltip" title="" data-original-title="Add a customer"><span class="fas fa-plus"></span></span></a></li>
                        </ul>
                        <h1 class="panel-title">{{ $title ?? "Customer" }}<small></small></h1>
                    </div>

</div>

<div id="filter-insly_customer" class="panel-filter open extend-filter card  dpib-custom-filter">
 				<form method="post"  id="dpib_customer_filter" class="card-body">
                                    <table class="table-filter filtersperrow4">
                                        <tbody>
                                            <tr>
                                                <td id="filter_customer_name" class=" filterlevel1"><div><label for="filter_customer_name">Name:</label><div><input type="text" autocomplete="off" id="filter_customer_name" name="filter_customer_name" value="" class=""></div></div></td>
                                                <td id="filter_customer_idcode" class=" filterlevel1"><div><label for="filter_customer_idcode">ID code / reg no:</label><div><input type="text" autocomplete="off" id="filter_customer_idcode" name="filter_customer_idcode" value="" class=""></div></div></td>
                                                <td id="filter_customer_contactperson" class=" filterlevel1"><div><label for="filter_customer_contactperson">Contact person:</label><div><input type="text" autocomplete="off" id="filter_customer_contactperson" name="filter_customer_contactperson" value="" class=""></div></div></td>
                                            </tr>
                                            <tr>
                                                <td id="filter_customer_type" class="extend-filter filterlevel2"><div><label for="filter_customer_type">Customer type:</label><div><select id="filter_customer_type" name="filter_customer_type"><option value="">--- all ---</option><option value="1">company</option><option value="0">individual</option></select></div></div></td>
                                                <td id="filter_customergroup_oid" class="extend-filter filterlevel2"><div><label for="filter_customergroup_oid">Customer group:</label><div>
                                                            {{ Form::select('filter_customergroup_oid',[''=>'---- not set ----'] +   $usergroup, null,array('id' =>'filter_customergroup_oid',"error-message"=>'Account manager field is mandatory'))}} 
                                                            </div></div></td>
                                                <td id="filter_broker_person_oid" class="extend-filter filterlevel2"><div><label for="filter_broker_person_oid">Account manager:</label><div>
                                                            {{ Form::select('filter_broker_person_oid', [''=>'---- not set ----'] +   $users , null,array('id' =>'filter_broker_person_oid',"error-message"=>'Account manager field is mandatory'))}} 
                                                            </div></div></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="show-extended-filters"><a onclick="FILTER.toggleExtended('insly_customer')">Hide extended filters</a></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="panel-buttons"><button type="button" class="btn btn-primary" style="float: right" id="filter_clear" name="filter_clear" value="filter_clear">Clear filters</button>&nbsp;&nbsp;<button type="button" class="btn btn-primary" style="float: right" id="customer_filter_submit" name="filter_submit" value="filter_submit">Apply filters</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
 			</div>




<div class="auto-scroll" style="width:100%;padding-top:10px">
    <table class="table table-bordered table-striped color-table info-table" id="dib_customer_list_table">
        <thead>
            <tr id="custom_columns">

            </tr>
        </thead><tbody>
           
        </tbody>
    </table>
</div>

@endsection

@section('customcss')
<link rel="stylesheet" type="text/css" href=" {{ asset('elitedesign/assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css') }} ">
<link rel="stylesheet" type="text/css" href="{{ asset('elitedesign/assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css') }}"> 


@endsection

@section('customscript') 
        
<script src="{{ asset('elitedesign/assets/node_modules/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('elitedesign/assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>

<script src="{{ asset('js/dibcustom/customerscript.js') }}" type="text/javascript"></script>

    
@endsection

@section('pagescript') 




<script>
    var i = 0;
    var columnDefs = [];
    var columnDefs1 = [];
    var table_opt;
    var customerListTable;
    var selectedColumns =[];
if (localStorage.getItem("customerColumnSetting_"+{{ Auth::user()->id }}) === null) {
 
     selectedColumns = [{"name":"customerName","rowName":"customerName","title":"Name","linkFlag":true},
         {"name":"customer_idcode","rowName":"id_code","title":"Id code"},
         {"name":"customer_customercode","rowName":"customer_code","title":"Code"},
         {"name":"customer_type","rowName":"type","manipulationFlag":true,"manipulation":{0:"Individual",1:"Company"},"title":"Customer type"},
         {"name":"customer_email","rowName":"email","title":"E-mail address"},
         {"name":"customer_phone","rowName":"phone","title":"Phone"},
         {"name":"customer_url","rowName":"website","title":"Website"},
         {"name":"saleschannel_name","rowName":"userName","title":"Account manager"},
         {"name":"customergroup_name","rowName":"customer_group","title":"Customer group"}];
     localStorage.setItem("customerColumnSetting_"+{{ Auth::user()->id }},JSON.stringify(selectedColumns));
} else {
   selectedColumns = $.parseJSON(localStorage.getItem("customerColumnSetting_"+{{ Auth::user()->id }}));
}
console.log(selectedColumns);
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

   
    
    $(function () {
        
                        tab1_opt = getOptVlaues();
                        tab1_opt.ajax = {
                            url: "{{ route('getcustomerdata',['customers']) }}",
                            type: "GET",
                            cache: false
                        }
                        tab1_opt.columnDefs = columnDefs1;
                        //DATATABLE INITIALIZATION
                        customerListTable = $('#dib_customer_list_table').DataTable(tab1_opt);
                        
                        
                        $(document).on('click',"#customer_filter_submit",function(){
                           
                                                    tab1_opt = getOptVlaues();
                                                    tab1_opt.ajax = {
                                                        url: "{{route('dashboardcustomerfilter')}}",
                                                        type: "GET",
                                                        cache: false,
                                                        data: {
                                                                "formData": $('#dpib_customer_filter').serializeArray()
                                                                
                                                            }
                                                    }
                        tab1_opt.columnDefs = columnDefs1;
                        customerListTable = $('#dib_customer_list_table').DataTable(tab1_opt);

                            
                        });
                        
                        $(document).on('click',"#filter_clear",function(){                           
                              window.location.replace( "{!! route('dashboardcustomers') !!}"); 
                            
                        });
                        
                        
       
      
    });
    
     function getOptVlaues() {
                        var opt = {

                            language: {

                                paginate: {
                                    "first": '<i class="fa fa-angle-double-left"></i>',
                                    "last": '<i class="fa fa-angle-double-right"></i>',
                                    "next": '<i class="fa fa-angle-right"></i>',
                                    "previous": '<i class="fa fa-angle-left"></i>'
                                }
                            },
                            lengthMenu: [10, 20, 50, 100, 200],
                            paging: true,
                            scrollCollapse: true,
                            dom: "Blftip",
                            autoWidth: false,
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
                            search: {
                                "search": ""
                            },

                            drawCallback: function (settings) {
                                //requestTable.columns.adjust().draw()
                            },
                            initComplete: function (settings, json) {
                               
                            },

                        };

                        return opt;
                    }
   
</script>
@endsection

