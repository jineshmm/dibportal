@extends('layouts.iframe')
@section('content')
<div class="panel panel-default open">
    <div class="panel-heading">

        

        <h1 class="panel-title">Endorsements<small></small></h1> </div><div class="panel-body"> 
            <div class="auto-scroll" style='width:100%;'>
                <table class="table table-bordered table-hovered table-striped dpib_endorsement_list" >
                    <thead>
                        <tr>
                            <th style="width: 10%" class="nowrap">Policy</th>                                                      
                            <th>Type</th>
                            <th style="width: 20%" class="nowrap">Issue date</th> 
                            <th  class="nowrap" >Start date</th>
                            <th  class="nowrap" >Amount</th>                            
                           <th  class="nowrap" ></th>
                            
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
        </div> 

</div>

<div class="panel panel-default open">
    <div class="panel-heading">

        

        <h1 class="panel-title">Approved Endorsements<small></small></h1> </div><div class="panel-body"> 
            <div class="auto-scroll" style='width:100%;'>
                <table class="table table-bordered table-hovered table-striped dpib_approved_endorsement_list">
                    <thead>
                        <tr>
                            <th style="width: 10%" class="nowrap">Policy</th>                                                      
                            <th>Type</th>
                            <th style="width: 20%" class="nowrap">Issue date</th> 
                            <th  class="nowrap" >Start date</th>
                            <th  class="nowrap" >Amount</th>
                            
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
            </div>
        </div> 

</div>

<script id='endorsement_issued_template' type='text/template'>
    
 {{ Form::open(array('route' => array('issueendorsement'), 'name' => 'form_endorsement_issue','id'=>'form_endorsement_issue','files'=>'true' )) }}

    <div class="dialogform"><table class="insly_dialogform">
                <tbody>                    
                    <tr id="field_request_type" class="field">
                        <td class="">
                            <div class="label ">
                                <span class="text-danger "></span>
                                <span class="title">Endorsement number</span>
                            </div>
                        </td>
                        <td>
                            
                            <div class="element">
                                <input type='hidden' name='endorsement_id' value="<%- endorsementId %>">
                                    <input type="hidden" id="policyId" name="endorsement_policy_id" value="<%- policyId %>"  >
                             <input type="text" id="endorsement_number" name="endorsement_number" value="" autocomplete="off" maxlength="255">
                            </div>
                        </td>
                    </tr>
                    
                    
                    

                </tbody></table></div>
    {{ Form::close() }}   
    
    
</script>
<link type="text/css" href="{{ asset('css/font-awesome.min.css') }}" type="text/css" rel="stylesheet" />
<link type="text/css" href="{{ asset('css/jquery.dataTables.min.css') }}" type="text/css" rel="stylesheet" />
<link type="text/css" href="{{ asset('css/dataTables.fixedColumns.min.css') }}" type="text/css" rel="stylesheet" />
<link type="text/css" href="{{ asset('css/dataTables.bootstrap.css') }}" type="text/css" rel="stylesheet" />
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css' >

        
<script src="{{ asset('js/global/datatable/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/global/datatable/DT_bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/global/datatable/fixedcolumn/dataTables.fixedColumns.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/dibcustom/dib-quote-request.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/dibcustom/dib-policy-add.js') }}" type="text/javascript"></script>

 		

       
<script>
    var columnDefs =[] , 
    columnDefs1 = [];
  
    var endorsementlistTable = '';
    var approvedendorsementlistTable ='';
    var roleArray = @json(Auth::user()->roles);
   $(function(){
    
        columnDefs.push({"name": 'policynumber',  "targets": 0, data: function (row, type, val, meta) {
                            var urlString = '{!! route("policyoverview",["##RID"]) !!}';
                            var link = urlString.replace("##RID", row['policy_id']);
                            var subject = (row['policy_number'] !==null) ? row['policy_number']: "---not issued---";
                            var subject =   (row['policy_number'] !==null) ? "<a href='"+link+"'>"+ row['policy_number']+"</a>": "<a href='"+link+"'>"+"---not issued---"+"</a>";
                            row.sortData = row['policy_id'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": "endorse_type",  "targets": 1, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['typeString'];
                            row.sortData = row['typeString'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'issueDate',  "targets": 2, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['formatted_issueDate'] ;
                            row.sortData = row['issue_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        columnDefs.push({"name": 'startDate',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  row['formatted_startDate'];
                            row.sortData = row['start_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
        columnDefs.push({"name": 'sum',  "targets": 4, data: function (row, type, val, meta) {
              
                        row.sortData = row['amount'].toFixed(2);
                        row.displayData = row['amount'].toFixed(2) ;                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
      columnDefs.push({"name": 'updatedDate',  "targets": 5, "orderable": false, data: function (row, type, val, meta) {
                            row.displayData = '<a class="dpib_endorsement_edit" endorsement_id=' + row['id'] + ' policy_id =' + row['policy_id'] + ' ><span class="icon-edit" data-toggle="tooltip" title="" data-original-title="Activate endorsement"></span></a>' ;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                        
       
      endorsementlistTable =   $('.dpib_endorsement_list').DataTable( {
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
                columnDefs:columnDefs,
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
    
<!--    Approved endorsement details-->

      columnDefs1.push({"name": 'policynumber',  "targets": 0, data: function (row, type, val, meta) {
                            var urlString = '{!! route("policyoverview",["##RID"]) !!}';
                            var link = urlString.replace("##RID", row['policy_id']);
                            var subject = (row['policy_number'] !==null) ? row['policy_number']: "---not issued---";
                            var subject =   (row['policy_number'] !==null) ? "<a href='"+link+"'>"+ row['policy_number']+"</a>": "<a href='"+link+"'>"+"---not issued---"+"</a>";
                            row.sortData = row['policy_id'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs1.push({"name": "endorse_type",  "targets": 1, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['typeString'];
                            row.sortData = row['typeString'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs1.push({"name": 'issueDate',  "targets": 2, "orderable": true, data: function (row, type, val, meta) {
                            var subject =row['formatted_issueDate'] ;
                            row.sortData = row['issue_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        columnDefs1.push({"name": 'startDate',  "targets": 3, "orderable": true, data: function (row, type, val, meta) {
                            var subject =  row['formatted_startDate'];
                            row.sortData = row['start_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
        columnDefs1.push({"name": 'sum',  "targets": 4, data: function (row, type, val, meta) {
              
                        row.sortData = row['amount'].toFixed(2);
                        row.displayData = row['amount'].toFixed(2) ;                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    

    approvedendorsementlistTable =   $('.dpib_approved_endorsement_list').DataTable( {
                data: {!! $approvedEndorsement !!},
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
    
    
    
    
    
    
    
    
     $(document).off('click','.dpib_endorsement_edit');
    $(document).on('click','.dpib_endorsement_edit',function(){   
    
     var template = _.template($("#endorsement_issued_template").html());
     
                var result = template({'endorsementId':$(this).attr('endorsement_id'),'policyId':$(this).attr('policy_id')});
                $("#db_endorsement_issued_popup").remove();
                $('body').append('<div id="db_endorsement_issued_popup" title="Issue policy endorsement" style="display:none" >' + result + '</div>');
                var dialogElement = $("#db_endorsement_issued_popup");
                dialogElement.dialog({
                    width: 900,
                    resizable: false,
                    bgiframe: true,
                    modal: true,
                    buttons: {
                        "Issue": {
                            buttonClass: "primary",
                            buttonAction: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                if ($.trim($("#endorsement_number").val()) != '') {
                                    $("#endorsement_number").removeClass('error');
                                    $("form#form_endorsement_issue").submit();
                                    $("#endorsement_number").removeClass('error');
                                }
                                else {
                                    DIB.alert('<b> Please enter endorsement number</b>', 'Error!!!!');
                                    $("#endorsement_number").addClass('error');
                                }
                            }
                        },
                        "cancel": {
                            buttonClass: "primary",
                            buttonAction: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
                    }
                });
                DIB.centerDialog();
                
       
            });
            
          
   
       
   });



</script>
@endsection