@extends('layouts.elite',['notificationCount'=>$notificationCount ] )


@section('content')
<div class="panel panel-default open card">
    <div class="panel-heading card-body">
     <ul class="panel-actions list-inline pull-right dib_head"> 
         
           <li ><a class="dpib_complaint_add" id="dpib_complaint_add" data-url='{!! route("addcomplaint") !!}'><span class="icon-add fas fa-plus large-size" data-toggle="tooltip" title="Add complaint" ></span></a></li>
       
     </ul> 
        

        <h1 class="panel-title">Complaints<small></small></h1> </div><div class="panel-body"> 
            <div class="auto-scroll" style='width:100%;'>
                <table class="table table-bordered table-striped dpib_policy_list color-table info-table" >
                    <thead>
                        <tr>
                            <th style="" class="nowrap">Complaint no:</th>                                                      
                            <th>Type</th>
                            <th style="" class="nowrap">Client name</th>
                            <th style="" class="nowrap">Policy</th> 
                            <th  class="" >Requested date</th>
                            <th  class="" >Remarks</th>
                            <th  class="" >Validity</th>                            
                            <th  class="" >Status</th>
                            <th  class="" >Created date</th>
                            <th  class="" >Updated date</th>                            
                            <th  class="" >Action</th>
                            
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
<script src="{{ asset('js/dibcustom/dib-quote-request.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/dibcustom/dib-policy-add.js') }}" type="text/javascript"></script>
@endsection

 @section('pagescript')   
   
<script>
    var columnDefs = [];
    var complaintTable = '';
    var roleArray = @json(Auth::user()->roles);
   $(function(){
    
        columnDefs.push({"name": 'complaintnumber',  "targets": 0, data: function (row, type, val, meta) {
                
                            var urlString = '{!! route("complaintoverview",["##CID"]) !!}';
                            var link = urlString.replace("##CID", row['id']);
                            var linkFlag = true; 
                        
                            var subject = "<a href='"+link+"'>"+ row['id']+"</a>";
                            row.sortData = row['id'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": "complaint_type",  "targets": 1, "orderable": false,data: function (row, type, val, meta) {
                            var subject = row['complaintType'];
                            row.sortData = row['complaintType'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
       columnDefs.push({"name": "clientname",  "targets": 2, "orderable": false,data: function (row, type, val, meta) {
                            var subject =  row['clientName'];
                            row.sortData = row['clientName'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        columnDefs.push({"name": 'policy',  "targets": 3, "orderable": false, data: function (row, type, val, meta) {
                            var subject =row['policy_number'] ;
                            row.sortData = row['policy_number'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        columnDefs.push({"name": 'requested_date',  "targets": 4, "orderable": false, data: function (row, type, val, meta) {
                            var subject = (row['requested_date'] !=null)? moment(row['requested_date']).format('DD.MM.YYYY'):'-';
                            row.sortData = row['requested_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
      columnDefs.push({"name": 'remarks',  "targets": 5, data: function (row, type, val, meta) {
              
                        row.sortData = row['remarks'];
                        row.displayData = row['remarks'];                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        columnDefs.push({"name": 'validity',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                            var subject =  row['complaintValidity'];
                            row.sortData = row['complaintValidity'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
                   
                    
        columnDefs.push({"name": 'status',  "targets": 7, "orderable": false, data: function (row, type, val, meta) {
                            var subject = row['statusString'];
                            row.sortData =row['statusString'];
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
         columnDefs.push({"name": 'created_date',  "targets": 8, "orderable": false, data: function (row, type, val, meta) {
                                             
                     var subject = (row['created_at'] !=null)? moment(row['created_at']).format('DD.MM.YYYY'):'-';
                            row.sortData =(row['created_at'] !=null)? moment(row['created_at']).format('DD.MM.YYYY'):'-';
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
         columnDefs.push({"name": 'updated_date',  "targets": 9, "orderable": false, data: function (row, type, val, meta) {
                                             
                           var subject = (row['updated_at'] !=null)? moment(row['updated_at']).format('DD.MM.YYYY'):'-';
                            row.sortData = (row['updated_at'] !=null)? moment(row['updated_at']).format('DD.MM.YYYY'):'-';
                            row.displayData = subject;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                   
          columnDefs.push({"name": 'action',  "targets": 10, "orderable": false, data: function (row, type, val, meta) {
                                             
                          linkString = '<a class="dpib_complaint_edit" edit_url="{!! route("editcomplaint",["##RID"]) !!}"><span class="fas fa-edit" data-toggle="tooltip" title="Edit complaint" data-original-title="Edit complaint"></span></a>'+'<a class="dpib_complaint_delete" delete_url="{!! route("deletecomplaint",["##RID"]) !!}"><span class="fas fa-archive" data-toggle="tooltip" title="Delete complaint" data-original-title="Delete complaint"></span></a>';                           
                             var link = linkString.replace("##RID", row['id']).replace("##RID", row['id']);
                             row.displayData = link
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                  
                    
        
       
      complaintTable =   $('.dpib_policy_list').DataTable( {
                data: {!! $complaintDetails !!},
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
                order: [[9, "desc"]],
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
    
    $(document).on('click','#dpib_complaint_add',function(){    
   console.log('click')
            $.ajax({
                    url: $(this).attr('data-url'),
                    type: "GET"

            }).done(function (data) {
                $("#db_complaint_add_popup").remove();
                $('body').append('<div id="db_complaint_add_popup" title="Add Complaint" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#db_complaint_add_popup");
                dialogElement.dialog({
                    width: 900,
                    
                    modal: true,
                    buttons: {
                        "Save": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:'Save',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                $("form#form_complaint_create").submit();
                            }
                        },
                        "cancel": {
                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                            text:'Cancel',
                            click: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
                    },
                    open:function( event, ui ) {
                        FORM.setDatePicker('.datefield');
                         $("#complaint_client").select2({dropdownParent: $("#db_complaint_add_popup")});    
                        $(document).off('change','#complaint_client');
                        $(document).on('change','#complaint_client',function(){                            
                           $.ajax({
                                 url: "{!! route('clientpolicies') !!}",
                                  headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                 type: "post",
                                 data:{'customer_id':$(this).val(),'selectedoption':''}

                               }).done(function (data) {
                                   if(data.status) {
                                     $("#complaint_policy").empty().html(data.optionstring);
                                   }
                                       
                                  }); 
              
                        });
                    }
                }); 
                    DIB.centerDialog();
            });
    
    });
    
    
    
    $(document).on('click','.dpib_complaint_delete',function() {
         
        var deleteUrl = $(this).attr('delete_url');
        $("#db_complaint_deletepopup").remove();
                $('body').append('<div id="db_complaint_deletepopup" title="Remove complaint" style="display:none" > Do you really want to remove complaint ?</div>');
                var dialogElement = $("#db_complaint_deletepopup");
                dialogElement.dialog({
                    width: 900,
                    
                    modal: true,
                    buttons: {
                        "Delete": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:'Delete',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                 $.ajax({
                                 url: deleteUrl,
                               type: "GET"

                               }).done(function (data) {
                                   if(data.status) {
                                     location.reload(true);
                                   }
                                       
                                  })
                               
                            }
                        },
                        "cancel": {
                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                            text:'cancel',
                            click: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
                    }
                }); 
                    DIB.centerDialog(); 
    });
    
    
    $(document).on('click','.dpib_complaint_edit',function(){
   
            $.ajax({
                    url: $(this).attr('edit_url'),
                    type: "GET"

            }).done(function (data) {
                $("#db_complaint_edit_popup").remove();
                $('body').append('<div id="db_complaint_edit_popup" title="Edit Complaint" style="display:none" >' + data.content + '</div>');
                var dialogElement = $("#db_complaint_edit_popup");
                dialogElement.dialog({
                    width: 900,
                    
                    modal: true,
                    buttons: {
                        "Update": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:'Update',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                $("form#form_complaint_create").submit();
                            }
                        },
                        "cancel": {
                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                            text:'Cancel',
                            click: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
                    },
                    open:function( event, ui ) {
                 
                        FORM.setDatePicker('.datefield');
                        $(document).off('change','#complaint_client');
                        $(document).on('change','#complaint_client',function(){                            
                           $.ajax({
                                 url: "{!! route('clientpolicies') !!}",
                                  headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                  },
                                 type: "post",
                                 data:{'customer_id':$(this).val(),'selectedoption':$("#complaint_policy").attr('selected_id')}

                               }).done(function (data) {
                                   if(data.status) {
                                     $("#complaint_policy").empty().html(data.optionstring);
                                   }
                                       
                                  }) 
              
                        });
                        
                       $("#complaint_client").trigger('change'); 
                  
                    }
                }); 
                    DIB.centerDialog();
            });
    
    });

    
    
    
       
   });



</script>
@endsection