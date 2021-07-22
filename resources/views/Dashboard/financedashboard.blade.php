

<div class="row">
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Customers</h5>
                <div class="d-flex no-block align-items-center m-t-20 m-b-20">
                    <div id="sparklinedash"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                    <div class="ml-auto">
                        <h2 class="text-success"><i class="ti-arrow-up"></i> <span class="counter">{{$dashboardDetails['customercount']}}</span></h2>
                    </div>
                </div>
            </div>
            <div id="sparkline8" class="sparkchart"></div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Policy</h5>
                <div class="d-flex no-block align-items-center m-t-20 m-b-20">
                    <div id="sparklinedash2"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                    <div class="ml-auto">
                        <h2 class="text-purple"><i class="ti-arrow-up"></i> <span class="counter">{{$dashboardDetails['policycount']}}</span></h2>
                    </div>
                </div>
            </div>
            <div id="sparkline8" class="sparkchart"></div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Production amount</h5>
                <div class="d-flex no-block align-items-center m-t-20 m-b-20">
                    <div id="sparklinedash3"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                    <div class="ml-auto">
                        <h2 class="text-info"><i class="ti-arrow-up"></i> <span class="counter">{{ number_format($dashboardDetails['productionsum'], 0, '.', ',')   }} </span></h2>
                    </div>
                </div>
            </div>
            <div id="sparkline8" class="sparkchart"></div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">VAT amount</h5>
                <div class="d-flex no-block align-items-center m-t-20 m-b-20">
                    <div id="sparklinedash4"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                    <div class="ml-auto">
                        <h2 class="text-danger"><i class="ti-arrow-up"></i> <span class="counter">{{ number_format($dashboardDetails['vatsum'], 0, '.', ',')   }}</span></h2>
                    </div>
                </div>
            </div>
            <div id="sparkline8" class="sparkchart"></div>
        </div>
    </div>
    <!-- Column -->
</div>


<div class="row">
        <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Production line Chart</h4>
                <ul class="list-inline text-right">
                    <li>
                        <h5><i class="fa fa-circle m-r-5 text-success"></i>Production</h5>
                    </li>
                    <li>
                        <h5><i class="fa fa-circle m-r-5 text-info"></i>VAT</h5>
                    </li>
                    
                </ul>
                <div id="leadsgraph"></div>
            </div>
        </div>
    </div>
</div>



<div class="row">

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Posted Policies</h4>

                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table table-bordered table-striped dpib_policy_list color-table info-table">
                        <thead>
                            <tr>
                            <th style="width: 5%" class="nowrap">Policy No</th>
                            <th style="width: 5%" class="nowrap">Insurer</th>                      
                            <th  class="nowrap" >Validity</th>
                            <th  class="nowrap" >Customer</th>
                            <th  class="nowrap" >Object</th>
                            <th  class="nowrap" >Status</th> 
                            <th  class="nowrap" >Action</th> 
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
        <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Posted Endorsements</h4>

                <div class="table-responsive m-t-40">
                    <table  class="table table-bordered table-striped dpib_endorsement_list color-table info-table">
                        <thead>
                              <tr>
                            <th style="width: 10%" class="nowrap">Policy</th>                                                      
                            <th>Type</th>
                            <th style="width: 20%" class="nowrap">Issue date</th> 
                            <th  class="nowrap" >Start date</th>
                            <th  class="nowrap" >Amount</th>
                            <th  class="nowrap" >Action</th> 
                        </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
    
    
   
</div>

<div class="row">

 <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Customers</h4>

                <div class="table-responsive m-t-40">
                    <table class="table table-bordered table-striped dpib_leads_table color-table info-table" >
                        <thead>
                            <tr>
                                <th style="width: 15%" class="nowrap">customerName</th>
                                <th  class="nowrap">customer_idcode</th>                           
                                <th  class="nowrap">customer_customercode</th>
                                <th  class="nowrap">customer_type</th>
                                <th  class="nowrap">customer_email</th>
                                <th  class="nowrap">customer_phone</th>
                                <th  class="nowrap">saleschannel_name</th>
                                <th  class="nowrap">customergroup_name</th>

                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<script id='endorsement_issued_template' type='text/template'>
    
 {{ Form::open(array('route' => array('issueendorsement'), 'name' => 'form_endorsement_issue','id'=>'form_endorsement_issue','files'=>'true' )) }}

    <div class="dialogform"><table class="insly_dialogform">
                <tbody>                    
                    <tr id="field_request_type" class="field">
                  
                        <td>
                            
                            Do you really want to issue endorsement?.
                                <input type='hidden' name='endorsement_id' value="<%- endorsementId %>">
                                    <input type="hidden" id="policyId" name="endorsement_policy_id" value="<%- policyId %>"  >
                 
                        </td>
                    </tr>
                    
                    
                    

                </tbody></table></div>
    {{ Form::close() }}   
    
    
</script>

<script id='endorsement_rejected_template' type='text/template'>
    
 {{ Form::open(array('route' => array('rejectendorsement'), 'name' => 'form_endorsement_reject','id'=>'form_endorsement_reject','files'=>'true' )) }}

    <div class="dialogform"><table class="insly_dialogform">
                <tbody>                    
                    <tr id="field_reject_reason" class="field">
                  
                        <td>
                            
                           Reject reason
                                <input type='hidden' name='reject_endorsement_id' value="<%- endorsementId %>">
                                    <input type="hidden" id="reject_policyId" name="reject_endorsement_policy_id" value="<%- policyId %>"  >
                 
                        </td>
                        <td>
                            <div class="element"><textarea id="reject_reason" name="reject_reason" wrap="soft" rows="4" autocomplete="off" maxlength="255" class="editor col-md-12 form-control" required error-message="Reject reason is mandatory"></textarea>
<span id="error-message" style="display:none">Reject reason is mandatory</span></div>

                            <td>
                    </tr>
                    
                    
                    

                </tbody></table></div>
    {{ Form::close() }}   
    
    
</script>

<script id='policy_issued_template' type='text/template'>
    
 {{ Form::open(array('route' => array('changepolicystatus','##POLID##'), 'name' => 'form_policy_status_change','id'=>'form_policy_status_change','files'=>'true' )) }}

    <div class="dialogform"><table class="insly_dialogform">
                <tbody>                    
                    <tr id="field_request_type" class="field">
                   
                        <td>
                            Do you really want to activate policy?
                    
                                <input type='hidden' name='flag' value=2>
                                    
                            
                        </td>
                    </tr>
                    
                    
                    

                </tbody></table></div>
    {{ Form::close() }}   
    
    
</script>
<script id='policy_rejected_template' type='text/template'>
    
 {{ Form::open(array('route' => array('rejectpolicy'), 'name' => 'form_policy_reject','id'=>'form_policy_reject','files'=>'true' )) }}

    <div class="dialogform"><table class="insly_dialogform">
                <tbody>                    
                    <tr id="field_reject_reason" class="field">
                  
                        <td>
                            
                           Reject reason
                               
                                    <input type="hidden" id="reject_policyId" name="reject_policy_id" value="<%- policyId %>"  >
                 
                        </td>
                        <td>
                            <div class="element"><textarea id="reject_reason" name="reject_reason" wrap="soft" rows="4" autocomplete="off" maxlength="255" class="editor col-md-12 form-control" required error-message="Reject reason is mandatory"></textarea>
<span id="error-message" style="display:none">Reject reason is mandatory</span></div>

                            <td>
                    </tr>
                    
                    
                    

                </tbody></table></div>
    {{ Form::close() }}   
    
    
</script>
@section('innerpagescript')

<script>
    $(function(){
        
        $(document).off('click', '.dpib_policy_edit');
        $(document).on('click', '.dpib_policy_edit', function () {
            var policyId = $(this).attr('policy_id');
         
                var template = _.template($("#policy_issued_template").html());
                var result = template();
                $("#db_policy_change_status_popup").remove();
                var replaceHtml = result.replace('##POLID##',policyId);
                $('body').append('<div id="db_policy_change_status_popup" title="Issue/activate policy" style="display:none" >' + replaceHtml + '</div>');
                var dialogElement = $("#db_policy_change_status_popup");
                dialogElement.dialog({
                    width: 900,                    
                    modal: true,
                    buttons: {
                        "Activate": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:'Activate',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                              
                                    $("#policy_number").removeClass('error');
                                    $("form#form_policy_status_change").submit();
                                    $("#policy_number").removeClass('error');
                                
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
                    }
                });
                DIB.centerDialog();
           
       
        });
        
        
        
        $(document).off('click','.dpib_endorsement_edit');
    $(document).on('click','.dpib_endorsement_edit',function(){   
    
     var template = _.template($("#endorsement_issued_template").html());
     
                var result = template({'endorsementId':$(this).attr('endorsement_id'),'policyId':$(this).attr('policy_id')});
                $("#db_endorsement_issued_popup").remove();
                $('body').append('<div id="db_endorsement_issued_popup" title="Issue policy endorsement" style="display:none" >' + result + '</div>');
                var dialogElement = $("#db_endorsement_issued_popup");
                dialogElement.dialog({
                    width: 900,
                   
                    modal: true,
                    buttons: {
                        "Issue": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:"Issue",
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                
                                    $("#endorsement_number").removeClass('error');
                                    $("form#form_endorsement_issue").submit();
                                    $("#endorsement_number").removeClass('error');
                                
                            }
                        },
                        "cancel": {
                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                                    text:"cancel",
                            click: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
                    }
                });
                DIB.centerDialog();
                
       
            });
            
            
            var encolumnDefs =[] ; 

  
    var endorsementlistTable = '';

   
        encolumnDefs.push({"name": 'policynumber',  "targets": 0, data: function (row, type, val, meta) {
                            var urlString = '{!! route("policyoverview",["##RID"]) !!}';
                            var link = urlString.replace("##RID", row['policy_id']);
                            var subject = (row['policy_number'] !==null) ? row['policy_number']: "---not issued---";
                            var subject =   (row['policy_number'] !==null) ? "<a href='"+link+"'>"+ row['policy_number']+"</a>": "<a href='"+link+"'>"+"---not issued---"+"</a>";
                            row.sortData = row['policy_id'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        encolumnDefs.push({"name": "endorse_type",  "targets": 1, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['typeString'];
                            row.sortData = row['typeString'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        encolumnDefs.push({"name": 'issueDate',  "targets": 2, "orderable": true, data: function (row, type, val, meta) {
                            var subject =row['formatted_issueDate'] ;
                            row.sortData = row['issue_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        encolumnDefs.push({"name": 'startDate',  "targets": 3, "orderable": true, data: function (row, type, val, meta) {
                            var subject =  row['formatted_startDate'];
                            row.sortData = row['start_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
        encolumnDefs.push({"name": 'sum',  "targets": 4, data: function (row, type, val, meta) {
              
                        row.sortData = row['amount'].toFixed(2);
                        row.displayData = row['amount'].toFixed(2) ;                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                        
                         encolumnDefs.push({"name": 'updatedDate',  "targets": 5, "orderable": false, data: function (row, type, val, meta) {
                            //row.displayData = '<a class="dpib_endorsement_edit" endorsement_id=' + row['id'] + ' policy_id =' + row['policy_id'] + ' ><span class="fas fa-edit" data-toggle="tooltip" title="" data-original-title="Activate endorsement"></span></a>' ;
                            row.displayData = '<a class="dib-cursor-style dpib_endorsement_edit" endorsement_id=' + row['id'] + ' policy_id =' + row['policy_id'] + ' ><span class="mdi mdi-thumb-up-outline" data-toggle="tooltip" title="" data-original-title="Activate endorsement"></span></a><a class="dib-cursor-style dpib_endorsement_reject" endorsement_id=' + row['id'] + ' policy_id =' + row['policy_id'] + ' ><span class="mdi mdi-thumb-down" data-toggle="tooltip" title="" data-original-title="Reject endorsement"></span></a>' ;
    
    return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                        
                           

                        
       
      endorsementlistTable =   $('.dpib_endorsement_list').DataTable( {
                data: {!! $dashboardDetails['postendorsementdetails'] !!},
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
                columnDefs:encolumnDefs,
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
    
    var ppcolumnDefs = [];
     
    var policyTable = '';
 

    
        ppcolumnDefs.push({"name": 'policyno',  "targets": 0, data: function (row, type, val, meta) {
                
                            var urlString = '{!! route("policyoverview",["##RID"]) !!}';
                            var link = urlString.replace("##RID", row['mainId']);
                             @if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) 
                             var subject =   (row['policy_number'] !==null) ? "<a href='"+link+"'>"+ row['policy_number']+"</a>": "<a href='"+link+"'>"+"---not issued---"+"</a>";
                         @else
                              var subject =   (row['policy_number'] !==null) ?  row['policy_number']: "---not issued---";
                        @endif
                            var subject =   (row['policy_number'] !==null) ? "<a href='"+link+"'>"+ row['policy_number']+"</a>": "<a href='"+link+"'>"+"---not issued---"+"</a>";
                            row.sortData = row['mainId'];
                            row.displayData = subject; 
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}}); 
        ppcolumnDefs.push({"name": "insurer",  "targets": 1, "orderable": true,data: function (row, type, val, meta) {
                            var subject = row['insurer_name'];
                            row.sortData = row['insurer_name'];
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
                    
        ppcolumnDefs.push({"name": 'validity',  "targets": 2, "orderable": true, data: function (row, type, val, meta) {
                            var subject =row['startDate']+ " - " +row['endDate'] ;
                            row.sortData = row['start_date'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});  
        ppcolumnDefs.push({"name": 'customer',  "targets": 3, "orderable": true, data: function (row, type, val, meta) {
                            var subject =  row['customerName'];
                            row.sortData = row['customerName'];
                            row.displayData = subject;                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});              
                    
        ppcolumnDefs.push({"name": 'object',  "targets": 4, data: function (row, type, val, meta) {
                       var subject = row['product_name'];
                           
                            
                            var objectJson = JSON.parse(row['objectdetails']);
                            var objectString =(row['product_name']!=null) ? row['product_name']+'<br>':'-';
                            if(_.size(objectJson) >0) {
                                $.each(objectJson,function(key,value){
                                  objectString+= createObjectColumnValue(value,value.object_type);                                
                                   
                               })
                            }
                           // newString = (objectString !='') ? objectString.slice(0, -1) :'' ;
                            row.sortData = (objectString !='') ? objectString.slice(0, -1) :'' ;
                            row.displayData = (objectString !='') ? objectString.slice(0, -1) :'' ;                         
                           
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                
                    
        ppcolumnDefs.push({"name": 'status',  "targets": 5, "orderable": true, data: function (row, type, val, meta) {
                            var subject = '<span class="badge badge-success badge-pill">'+row['statusString']+'</span>';
                            row.sortData =row['policy_status'];;
                            row.displayData = subject;
                           
                            
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});
       ppcolumnDefs.push({"name": 'action',  "targets": 6, "orderable": false, data: function (row, type, val, meta) {
                           
                            row.displayData = '<a class="dib-cursor-style dpib_policy_edit"  policy_id =' + row['mainId'] + ' data-toggle="tooltip" title="" data-original-title="Activate policy" data-placement="top" data-container=".panel-body"><span class="mdi mdi-thumb-up-outline" ></span></a><a class="dib-cursor-style dpib_policy_reject"  policy_id =' + row['mainId'] + ' data-toggle="tooltip" title="" data-original-title="Reject policy" style="margin-left:10px" data-container="dpib_policy_list"><span class="mdi mdi-thumb-down" ></span></a>' ;
                            return row;
                        }, render: {"_": 'sortData', "display": 'displayData', 'filter': 'sortData'}});                  
                    
                  
                    
        
       
      policyTable =   $('.dpib_policy_list').DataTable( {
                data: {!! $dashboardDetails['postpolicy'] !!},
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
                columnDefs:ppcolumnDefs,
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
    
    
     $(document).off('click','.dpib_endorsement_reject');
    $(document).on('click','.dpib_endorsement_reject',function(){   
    
     var template = _.template($("#endorsement_rejected_template").html());
     
                var result = template({'endorsementId':$(this).attr('endorsement_id'),'policyId':$(this).attr('policy_id')});
                $("#db_endorsement_issued_popup").remove();
                $('body').append('<div id="db_endorsement_issued_popup" title="Reject policy endorsement" style="display:none" >' + result + '</div>');
                var dialogElement = $("#db_endorsement_issued_popup");
                dialogElement.dialog({
                    width: 900,
                   
                    modal: true,
                    buttons: {
                        "Issue": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:"Reject",
                            click: function () {
                               $("#field_reject_reason").find('.element').removeClass('has-danger');
                               $("#error-message").hide();

                                if($.trim($("#reject_reason").val()) =='') {
                                    $("#field_reject_reason").find('.element').addClass('has-danger');
                                    $("#error-message").show();
                                
                                    return false;
                                }
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));                                
                                    
                                    $("form#form_endorsement_reject").submit();
                                  
                                
                            }
                        },
                        "cancel": {
                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                                    text:"cancel",
                            click: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
                    }
                });
                DIB.centerDialog();
                
       
            });
            
            
             $(document).off('click','.dpib_policy_reject');
    $(document).on('click','.dpib_policy_reject',function(){   
    
     var template = _.template($("#policy_rejected_template").html());
     
                var result = template({'policyId':$(this).attr('policy_id')});
                $("#db_policy_issued_popup").remove();
                $('body').append('<div id="db_policy_issued_popup" title="Reject policy" style="display:none" >' + result + '</div>');
                var dialogElement = $("#db_policy_issued_popup");
                dialogElement.dialog({
                    width: 900,
                   
                    modal: true,
                    buttons: {
                        "Reject": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:"Reject",
                            click: function () {
                               $("#field_reject_reason").find('.element').removeClass('has-danger');
                               $("#error-message").hide();

                                if($.trim($("#reject_reason").val()) =='') {
                                    $("#field_reject_reason").find('.element').addClass('has-danger');
                                    $("#error-message").show();
                                
                                    return false;
                                }
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));                                
                                    
                                    $("form#form_policy_reject").submit();
                                  
                                
                            }
                        },
                        "cancel": {
                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                                    text:"cancel",
                            click: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
                    }
                });
                DIB.centerDialog();
                
       
            });
            
      
        
        
    })
</script>
@endsection

