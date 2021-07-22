@extends('layouts.elite_fullwidth',['sidemenustatus' => array(),'countDetails'=>array(),'notificationCount'=>array() ] )
@section('overviewmenu')
 <ul id="page-content-menu" class="list-inline pull-right" style="display:none">
  
                    <li data-url="{{route('updatecrmrequestnotificationflag', ['quote',$crmDetails->mainId,$crmDetails->quotes_notification_flag])}}" class="dpib_request_flag_change">

<button type="button" class="btn waves-effect waves-light btn-rounded btn-success"> @if($crmDetails->quotes_notification_flag==1) Disable quotes notification  @else Enable quotes notification  @endif</button>
                      

                    </li>
                    
 </ul>

@endsection
@section('content')


<!--                  content here  -->
<!--TAB AREA-->
<div class="row">
   
           <div class="col-md-12 card">
        
        
        <ul class="nav nav-tabs customtab card-body" role="tablist">
                                <li id="tab_overview" class="nav-item" onclick="TAB.select('overview', null, 1)"> <a class="nav-link {{ empty($overviewTab) || $overviewTab == 'overview' ? 'active' : '' }}" data-toggle="tab" href="#content_overview" role="tab" aria-selected="true"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Overview</span></a> </li>
                                <li id="tab_document" class="nav-item" onclick="TAB.select('document', '{{route('customercrmdocuments',[$crmDetails->customerId, $crmDetails->mainId])}}', 0)"> <a class="nav-link {{ empty($overviewTab) || $overviewTab == 'document' ? 'active' : '' }}" data-toggle="tab" href="#content_document" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Documents ({{$docCount}})</span></a> </li>
                               @if( in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)   )
                                <li id="tab_brokingslip" class="nav-item" onclick="TAB.select('brokingslip', '{{route('brokingsliplist',[$crmDetails->customerId, $crmDetails->mainId])}}', 0)"> <a class="nav-link {{ empty($overviewTab) || $overviewTab == 'brokingslip' ? 'active' : '' }}" data-toggle="tab" href="#content_brokingslip" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Broking slip ({{$brokingslipCount}})</span></a> </li>
                                
                                @endif
                                
                                <li id="tab_log" class="nav-item" onclick="TAB.select('log', '{{route('crmrequestlogdata', $crmDetails->mainId)}}', 0)"> <a class="nav-link {{ empty($overviewTab) || $overviewTab == 'log' ? 'active' : '' }}" data-toggle="tab" href="#content_log" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Log</span></a> </li>
                                
        </ul>
        

    </div>
   
    
    
</div>
<!--TAB CONTENT AREA-->
<div class="row card">
    <div class="col-md-12">
        <div id="content_overview" class="tabcontent col-md-12 card-body" >
            <div class="row">

                
                <div id="main-content" class="col-md-5" >
                    <div id="panel-customer_overview" class="panel panel-default open"><div class="panel-heading">
                            <ul class="panel-actions list-inline pull-right">
                                @if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES_MANAGER', Auth::user()->roles)  || Auth::user()->id == $crmDetails->user_id || Auth::user()->id == $crmDetails->assigned_to)
                                <li class="dpib_request_edit dib-cursor-style" editUrl='{!! route("getquoterequesteditform",[$crmDetails->customerId,$crmDetails->mainId]) !!} '><span class="fas fa-edit text-blue" data-toggle="tooltip" title="" data-original-title="Edit CRM"></span></li>
                                @endif
                                @if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) )
                                <li class="dpib_sales_crm_assign dib-cursor-style" ><span class="fas fa-handshake text-blue" data-toggle="tooltip" title="" data-original-title="Assign request"></span></li>
                                @endif
                                @if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles))
                                <li class="dpib_crm_request_delete dib-cursor-style" delete_url='{!! route("removecrmrequest",[$crmDetails->customerId, $crmDetails->mainId]) !!}' return-url='{!! route("salescrmlist") !!}' ><span class="fas fa-archive text-blue" data-toggle="tooltip" title="" data-original-title="Delete CRM"></span></li>
                                @endif

                            </ul><h3 class="panel-title">Request info</h3></div>
                        <div id="customer_overview" class="panel-collapse panel-body">
                            <table class="info-table" width='100%'>
                                <tbody>
                                    
                                    <tr><td>CRM No:</td><td><b>{{$crmDetails->crm_request_id}}</b></td></tr>
                                    <tr><td>Customer:</td><td><b class='bg-cyan'>{{ ucfirst(trans($crmDetails->customerName))    }}</b></td></tr>
                                    <tr><td>CRM Type:</td><td>
                                           
                                        @if ($crmDetails->type == 0)
                                          {{'Task'}}
                                        @elseif($crmDetails->type == 1)
                                          {{'Request'}}
                                        @else
                                         <span class='capital-first font-bold text-danger'>Renewal</span> 
                                        @endif
                                        </td></tr>
                                     @if ($crmDetails->type == 2)
                                     <tr><td>Policy:</td><td><a href='{!! route("policyoverview",[$crmDetails->policyId]) !!}'>{{$crmDetails->policy_number}}</a></td></tr>
                                    @endif
                                    <tr><td>Line of business:</td><td>{{$crmDetails->lineofbusinesstitle}}</td></tr>  
                                     


                                    <tr><td>Created By:</td><td>{{$crmDetails->userName}}</td></tr>
                                    <tr><td>Created date:</td><td>{{date("d.m.Y h:i",strtotime($crmDetails->created_date))}}</td></tr>
                                    
                                    @if( in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES', Auth::user()->roles)  )
                                        <tr><td>Assigned to:</td><td>{{$crmDetails->assignedName}}</td></tr>
                                    @endif
                                    <tr><td>Priority:</td><td>{{$crmDetails->priorityString}}</td></tr>
                                      @if($crmDetails ->type ==1)
                                                  
                                        <tr><td>Description:</td><td>{{$crmDetails->description}}</td></tr>  
                                      @else               
                                        <tr><td>Subject:</td><td>{{$crmDetails->subject}}</td></tr>
                                        <tr><td>Attendees:</td><td>{{$crmDetails->attendees}}</td></tr> 
                                        <tr><td>Location:</td><td>{{$crmDetails->location}}</td></tr> 
                                        <tr><td>Reminder:</td><td>{{ ($crmDetails->reminder == 1) ? 'Yes' : 'No' }}  </td></tr>
                                         @if($crmDetails->repeat_flag ==1)
                                            <tr><td>Repeat date:</td><td>{{date("d.m.Y h:i",strtotime($crmDetails->repeat_date)) }}</td></tr>
                                         @endif  
                                     @endif 
                                     <tr ><td>Does policy schedule is needed?</td><td><div class="custom-control custom-switch">
  <input type="checkbox" changeUrl='{!! route("changepolicyscheduleflag",[$crmDetails->customerId,$crmDetails->mainId]) !!}' class="custom-control-input" id="customSwitch1" @if($crmDetails->policy_schedule_flag ==1)   checked @endif >
  <label class="custom-control-label" for="customSwitch1"></label>
</div></td></tr>

@if($crmDetails->policy_schedule_flag ==0)  
<tr><td>Reason:</td><td>{{$crmDetails->policy_schedule_reason}}</td></tr>

@endif

                                  
                                                         <tr class="subtitle"><th class="subtitle" colspan="2" style='padding-top:0;padding-bottom:0'>         <div class="panel-heading" style='padding-left:0;padding-bottom:0'>
                                                @if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES_MANAGER', Auth::user()->roles)  ||  Auth::user()->id == $crmDetails->assigned_to)
                                                  @if(! in_array('ROLE_SALES', Auth::user()->roles))
                                                
                                                <ul class="panel-actions list-inline pull-right">

                                                    <li class="dpib_request_status_edit" editUrl='{!! route("getStatusEditform",[$crmDetails->customerId,$crmDetails->mainId,$crmDetails->type,$crmDetails ->status]) !!} '><span class="fas fa-edit text-blue" data-toggle="tooltip" title="" data-original-title="Edit status"></span></li>

                                                </ul>
                                                  @endif
                                                @endif
                                                <h3 class="panel-title">Current info</h3>
                                            </div></th></tr>
                                        <tr><td>Status:</td><td><a>{{$crmDetails->statusString}}</a></td></tr>
                                        <tr><td>Last modified date:</td><td class="phoneNumber">{{ date("d.m.Y h:i",strtotime($crmDetails->updated_date))}}</td></tr>

                                        @if($crmDetails ->type ==1 && $crmDetails ->status==5)
                                            <tr><td>Revise reason</td><td class="phoneNumber">{{$crmDetails->revise_reason}}</td></tr>
                                        @endif 
                                        @if($crmDetails ->type ==1 && $crmDetails ->status==8)
                                            <tr><td>Reject reason:</td><td>{{$crmDetails->reject_reason}}</td></tr>
                                        @endif 
                                        
                                        @if($crmDetails ->comments !='')
                                            <tr><td>Comments:</td><td>{{$crmDetails ->comments}}</td></tr>
                                        @endif
                                   
                                </tbody>
                            </table>
                        </div>
                      </div>


                </div>
                <aside class="col-md-6">
                    <div id="panel-entries-block" class="panel panel-default open">
                      
                         <div class="panel-heading">
                            @if( in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)   )
                            <ul class="panel-actions list-inline pull-right">
                                <li class="dpib_crm_quote_add dib-cursor-style"><span class="panel-action-add"  data-toggle="tooltip" title="Upload a quote"><span class="fas fa-plus text-blue"></span></span></li>
                                
                            </ul>
                            @endif
                            <h3 class="panel-title">Quote list</h3>
                        </div>
                        <div id="entries-block" class="panel-collapse panel-body">
                            
                             @if( $quotes ->count() >0) 
                              <table class="table table-hovered table-bordered table-striped" id="brokenslip_entries-list" style="table-layout: fixed;">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Filename</th>
                                        <th style="width: 20%;">Company</th>
                                        <th style="width: 8%;">Uploaded date</th>
                                        <th style="width: 10%;">Action</th>
                                    </tr>
                                </thead>
                                <thead>
                                       @foreach ($quotes as $quote)
                                      <tr class="datarow">
                                          <td class="nohover"><a href='{!! route("getfiledownload",[$crmDetails->customerId,"quote",$crmDetails->mainId,$quote->file_name,0]) !!} '>{{ $quote->file_name }}</a></td>
                                          <td class="nohover">{{ $quote->insurer_name }}</td>
                                          <td class="nohover">{{ date("d.m.Y h:i",strtotime($quote->created_at))  }}</td>
                                          <td class="nohover"> 
                                              <a href='{!! route("viewfile",[$crmDetails->customerId,"quote",$crmDetails->mainId,$quote->file_name]) !!} ' target='_blank'><span class="fas fa-eye text-blue mr-right" data-toggle="tooltip" title="" data-original-title="View quotes"></span></a>
                                              <a class="dpib_brokingslip_sendmail" openUrl='{!! route("sendquotespopup",[$crmDetails->customerId,$crmDetails->mainId,$quote->mainId,"quote"]) !!}' data-title="Send quote"><span class="fas fa-envelope text-blue mr-right" data-toggle="tooltip" title="" data-original-title="Send email" ></span></a>
                                              <a class="dpib_brokingslip_sendmail" openUrl='{!! route("addpolicy",[$crmDetails->customerId,$crmDetails->mainId,$quote->mainId]) !!}' data-title="Add policy" href='{!! route("addpolicy",[$crmDetails->customerId,$crmDetails->mainId,$quote->mainId]) !!}'><span class="fas fa-plus text-blue" data-toggle="tooltip" title="" data-original-title="Add policy" ></span></a>
                                              @if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles))  
                                              @if($quote->display_flag==0)
                                              <a class="dpib_quote_salesview" openUrl='{!! route("displayquote",[$crmDetails->customerId,$crmDetails->mainId,$quote->mainId]) !!}' data-title="Add policy" href='#' action-type="1" ><span class="mdi mdi-account-off text-red" data-toggle="tooltip" title="" data-original-title="Enable sales display" ></span></a>
                                              @else
                                              <a class="dpib_quote_salesview" openUrl='{!! route("displayquote",[$crmDetails->customerId,$crmDetails->mainId,$quote->mainId]) !!}' data-title="Add policy" href='#' action-type="0" ><span class="mdi mdi-account text-blue" data-toggle="tooltip" title="" data-original-title="disable sales display" ></span></a>
                                              @endif
                                              @endif
                                          </td>
                                      </tr>
                                  
                                 @endforeach
                                   
                                </thead>
                            </table>
                             
                             
                             @endif
                            

                        </div>
                    </div>
                    
                    <div id="panel-entries-block" class="panel panel-default open">
                        <div class="panel-heading">
                            @if( in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)   )
                            <ul class="panel-actions list-inline pull-right">
                                <li ><a href="{{route("createcomparisondoc",[$crmDetails->customerId,$crmDetails->mainId])}}" class="btn waves-effect waves-light btn-rounded btn-success">Create comparison list</a></li> 
                                <li class="dpib_customer_crm_comparisondoc_add dib-cursor-style"><span class="panel-action-add"  data-toggle="tooltip" title="Add a document"><span class="fas fa-plus text-blue"></span></span></li>
                                
                            </ul>
                            @endif
                            <h3 class="panel-title">Comparison documents</h3>
                        </div>
                        <div id="entries-block" class="panel-collapse panel-body">
                            
                             @if( $comparisonfiles ->count() >0) 
                              <table class="table table-hovered table-bordered table-striped" id="brokenslip_entries-list" style="table-layout: fixed;">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Filename</th>                                       
                                        <th style="width: 8%;">Uploaded date</th>
                                        <th style="width: 10%;">Action</th>
                                    </tr>
                                </thead>
                                <thead>
                                       @foreach ($comparisonfiles as $comparisonfile)
                                      <tr class="datarow">
                                          <td class="nohover"><a href='{!! route("getfiledownload",[$crmDetails->customerId,"comparison",$crmDetails->mainId,$comparisonfile->filename,0]) !!} '>{{ $comparisonfile->filename }}</a></td>
                                          <td class="nohover">{{ date("d.m.Y h:i",strtotime($comparisonfile->uploaded_at))  }}</td>
                                          <td class="nohover"> 
                                              <a href='{!! route("viewfile",[$crmDetails->customerId,"comparison",$crmDetails->mainId,$comparisonfile->filename]) !!} ' target='_blank'><span class="fas fa-eye text-blue mr-right" data-toggle="tooltip" title="" data-original-title="View comparison doc"></span></a>
                                              <a class="dpib_brokingslip_sendmail" openUrl='{!! route("sendquotespopup",[$crmDetails->customerId,$crmDetails->mainId,$comparisonfile->mainId,"comparison"]) !!}' data-title="Send comparison document"><span class="fas fa-envelope text-blue" data-toggle="tooltip" title="" data-original-title="Send email" ></span></a>
                                              
                                          </td>
                                      </tr>
                                  
                                 @endforeach
                                   
                                </thead>
                            </table>
                             
                             
                             @endif
                            

                        </div>
                    </div>

                </aside>

                <div class="col-md-12" style="margin-left:20px">
                  <div id="panel-customer_comments" class="panel panel-default open"><div class="panel-heading">
                            <ul class="panel-actions list-inline pull-right">

                                <li class="dpib_comment_add" ><span class="fas fa-plus text-blue" data-toggle="tooltip" title="" data-original-title="Add Comments"></span></li>


                            </ul><h3 class="panel-title">Comments</h3></div>
                        <div id="customer_overview" class="panel-collapse panel-body">
                            <table class="info-table" width='100%'>
                                <tbody>
                                  

                                  @foreach ($commentDetails as $commentDetail)
                                    <tr>
                                      <td>
                                        <span class='text-warning'> {{$commentDetail->createdBy}} </span><span style="margin-left:10px" class="text-warning">: {{date('m.d.Y H:i',strtotime($commentDetail-> created_at))}}</span><br />
                                       <span style="margin-left:10px"><i>Message:&nbsp;&nbsp;   {{$commentDetail->comments}}</i></span></td></tr>
                                          
                                   @endforeach
                                                         
                                   
                                </tbody>
                            </table>
                        </div>
                      </div>
                  
                </div>


            </div>
        </div>
        <div id="content_document" class="tabcontent col-12"  style="display:none" rel="{{route('customercrmdocuments',[$crmDetails->customerId, $crmDetails->mainId])}}" >
           
            Document display area

        </div>
        
         <div id="content_brokingslip" class="tabcontent col-12"  style="display:none" rel="{{route('brokingsliplist',[$crmDetails->customerId, $crmDetails->mainId])}}" >
           
            Broking slip display area

        </div>
        
        

        <div id="content_log" class="tabcontent col-12"  style="display:none;" rel="{{route('crmrequestlogdata', $crmDetails->mainId)}}">
            log display area 

        </div>

    </div>

</div>
<script id='comment_add_template' type='text/template'>
        
        {{ Form::open(array('route' => array('savesalescomments',$crmDetails->customerId,$crmDetails->mainId), 'name' => 'form_claimant_add','id'=>'form_claimant_add','files'=>'true' )) }}
    @csrf   
   
<div class="dialogform">
    <table class="insly_dialogform">
    <tbody><tr style="display:none;">
                    <td></td>
                <td>
                                            
                                    </td>
    </tr>
    <tr style="display:none;">
                    <td></td>
                <td>
                                                <input type="hidden" id="crm_request_id" name="crm_request_id" value="{{$crmDetails->mainId}}">
                                    </td>
    </tr>
    <tr id="field_claim_claimant_name" class="field ">
                    <td class="">
    <div class="label ">
                <span class="text-danger icon-asterix"></span>
        <span class="title">Comment</span>
    </div>
</td>
                <td>
            <div class="element">
              <textarea name="crm_comment" value="" autocomplete="off" wrap="soft" rows="4" class="form-control editor required"  required='required' error-message="Comment field is mandatory"></textarea>
                                                                
                                                                                                                            </div>
        </td>
    </tr>

</tbody></table></div>
  {{ Form::close() }}
</script>


<script id='policyscheduleflag_add_template' type='text/template'>
        
        
   
<div class="dialogform">
    <table class="insly_dialogform">
    <tbody><tr style="display:none;">
                    <td></td>
                <td>
                                            
                                    </td>
    </tr>
    <tr style="display:none;" id="reason_field" class="alert alert-danger alert-block">
                    <td></td>
                <td>Reason field is mandatory</td>
    </tr>
    <tr id="field_claim_claimant_name" class="field ">
                    <td class="">
    <div class="label ">
                <span class="text-danger icon-asterix"></span>
        <span class="title">Reason</span>
    </div>
</td>
                <td>
            <div class="element">
              <textarea id="policyscheduleflag_reason" name="policyscheduleflag_reason" value="" autocomplete="off" wrap="soft" rows="4" class="form-control editor required"  required='required' error-message="Comment field is mandatory" reasonUrl='{!! route("changepolicyscheduleflag",[$crmDetails->customerId,$crmDetails->mainId]) !!}'></textarea>
                                                                
                                                                                                                            </div>
        </td>
    </tr>

</tbody></table></div>
  
</script>


<script id='quote_upload_template' type='text/template'>
        
        {{ Form::open(array('route' => array('createquote',$crmDetails->customerId, $crmDetails->mainId), 'name' => 'form_quote_generation','id'=>'form_quote_generation','files'=>'true' )) }}
@csrf    
<div class="dialogform">
    <table class="insly_dialogform" id='brokenslip_creation_table'>
        <tbody>
            <tr id="field_documenttype_oid" class="field dp_main_tr">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Insurance company</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        {{ Form::select('insurance_company',  $insuranceCompany, null,array('name'=>'companyId','id' =>'insurance_company','required'=>'required','class'=>'required form-control','error-message' =>"Insurance company field is mandatory" ))}}  
                    </div>
                </td>
            </tr>
                 <tr id="field_quote_comment" class="field ">
                        <td class="">
                            <div class="label ">
                                <span class="text-danger "></span>
                                <span class="title">Comment</span>
                            </div>
                        </td>
                        <td>
                            <div class="element">
                                <input type="text" id="quote_comment" name="quote_comment" value="" autocomplete="off" maxlength="255">
                            </div>
                        </td>
                    </tr>
        
            <tr id="field_document_upload" class="field dp_main_tr">
                <td class="">
                    <div class="label ">
                        <span class="text-danger"></span>
                        <span class="title">File</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="file" class="required" id="quote_file" name="quote_file[]" multiple="multiple" value="" onchange="FORM.validateFile($(this), 128, 'Maximum file upload size 128 MB exceeded!')" autocomplete="off" error-message="File field is mandatory" required>
                    </div>
                </td>
            </tr>
            
            
            
            


        </tbody></table></div>
{{ Form::close() }}
</script>
<script id='sales_request_assign_template' type='text/template'>
        
        {{ Form::open(array('route' => array('assignsalescrmrequest',$crmDetails->mainId), 'name' => 'form_sales_request_assign','id'=>'form_sales_request_assign','files'=>'true' )) }}
				     <div class="col-lg-12">
                                <div class="card">
                                
                                    <div class="card-body card-block">
                                        <div class="form-group">
                                            <label for="vat" class=" form-control-label">Team members</label>
                                           
                                                {{ Form::select('technical_team',  $assignUsers, null,array('id' =>'technical_team','required'=>'required','class'=>'form-control-lg form-control','error-message' =>"Gender field is mandatory" ))}}  
                                        </div>
                                                                               
                                        
                                    </div>
                                </div>
                            </div>
                            {{Form::close()}}
			
</script>



@endsection
@section('customcss')
<link rel="stylesheet" type="text/css" href=" {{ asset('elitedesign/assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css') }} ">
<link rel="stylesheet" type="text/css" href="{{ asset('elitedesign/assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css') }}"> 


@endsection

@section('pagescript')
<script src="{{ asset('elitedesign/assets/node_modules/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('elitedesign/assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>

<script src="{{ asset('js/dibcustom/dib-customer-overview.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/dibcustom/dib-quote-request.js') }}" type="text/javascript"></script>

<script>
      $(function(){
          

      var options = {};
      var quoteRequest = new DibQuoterequest(options);
      quoteRequest.initialSetting()


      $(document).on('change','#customSwitch1',function() {
        var flag=0;

         if($(this).prop('checked')) {
            $.ajax({
                    url: $(this).attr('changeUrl'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "post",
                    data:{'scheduleflag':1}

            }).done(function (data) {

                location.reload();
            });
         } else {
             $("#dp_policyscheduleflag_form").remove();                                   


                                    $('body').append('<div id="dp_policyscheduleflag_form" title="Reason"  >' +$("#policyscheduleflag_add_template").html() + '</div>');
                                    var dialogElement = $("#dp_policyscheduleflag_form");
                                        
                                     dialogElement.dialog({
                                            width: 500,
                                           
                                            modal: true,
                                            buttons: {
         
                                                    "Edit": {
                                                                
                                                                class: "btn waves-effect waves-light btn-rounded btn-success dp_document_data_edit",
                                                                text: 'Save',
                                                                click: function () {
                                                                    $.ajax({
                                                                            url: $("#policyscheduleflag_reason").attr('reasonUrl'),
                                                                            headers: {
                                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                            },
                                                                            data :{'scheduleflag':0,'document_comment':$("#policyscheduleflag_reason").val()
                                                                          },
                                                                          beforeSend: function( xhr ) {
    $("#reason_field").hide();
    if($.trim($("#policyscheduleflag_reason").val())=='') {
         $("#reason_field").show();
         return false;
    }
  }

           
                                                                            }).done(function(data) {
                                                                                if(data.status) {
                                                                                   dialogElement.dialog('close');
                                                                                   dialogElement.remove();
                                                                                   
                                                                                   DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
                                                                                   DIB.closeProgressDialog();
                                                                                   location.reload(); 
                                                                                }
                                                                            })
                                                                    
                                                                }
                                                            },
                                                    "cancel":   {
                                                                    class: "btn waves-effect waves-light btn-rounded btn-danger",
                                                                    text: 'Cancel',
                                                                    click: function () {
                                                                        dialogElement.dialog('close');
                                                                        dialogElement.remove();
                                                                        location.reload();
                                                                    }
                                                                }
            
                                                      },

                                            close: function (event, ui) {

                                            }
                                    });
         }
         
        



      })






      //Edit document
    
    $(document).off('click','.dpib_document_edit');
    $(document).on('click','.dpib_document_edit',function(){
        
            var documentId = $(this).attr('docId');
            var customerId = $(this).attr('customerId');

            var linkString = '{!! route("customerdocedit",["##customerId##","##docId##"]) !!}';
            var link = linkString.replace("##customerId##", customerId).replace("##docId##", documentId);
            $.ajax({
                url: link,
                type: "GET"           
           
            }).done( function (data) {
                                if(data.status) { 
                                    
                                    $("#dp_edit_selected_doc").remove();  
                                    $('body').append('<div id="dp_edit_selected_doc" title="Edit document"  >' + data.content + '</div>');
                                    var dialogElement = $("#dp_edit_selected_doc");
                                        
                                     dialogElement.dialog({
                                            width: 500,
                                           
                                            modal: true,
                                            buttons: {
         
                                                    "Edit": {
                                                                
                                                                class: "btn waves-effect waves-light btn-rounded btn-success dp_document_data_edit",
                                                                text: 'Edit',
                                                                click: function () {
                                                                    $.ajax({
                                                                            url: $('form#form_document_edit').attr('action'),
                                                                            headers: {
                                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                            },
                                                                            data :{'documenttype_oid':$('#documenttype_oid').val(),'document_comment':$('#document_comment').val()}
           
                                                                            })


                                                                    .done(function(data) {
                                                                                if(data.status) {
                                                                                   dialogElement.dialog('close');
                                                                                   dialogElement.remove();
                                                                                   //loading document datatable once more
                                                                                   $('#tab_document').find('a').trigger('click');
                                                                                   DIB.progressDialog(LOCALE.get('DIB.COMMON.Progress.Loading'));
                                                                                   DIB.closeProgressDialog(); 
                                                                                }
                                                                            })
                                                                    
                                                                }
                                                            },
                                                    "cancel":   {
                                                                    class: "btn waves-effect waves-light btn-rounded btn-danger",
                                                                    text: 'Cancel',
                                                                    click: function () {
                                                                        dialogElement.dialog('close');
                                                                        dialogElement.remove();
                                                                    }
                                                                }
            
                                                      },

                                            close: function (event, ui) {

                                            }
                                    });  
                                }
             });
       
    });
    
              //Document upload initialization
    $(document).on('click','.dpib_customer_crm_comparisondoc_add',function(){
     $.ajax({
            url: "{!! route('comparisonuploadform',[$crmDetails->customerId,$crmDetails->mainId]) !!}",
            type: "GET"

        }).done(function (data) {
            DIB.alert(data.html, 'Add comparison document',false, false, false,'customdocadd');            

            $("button.primary").off('click');
            $("button.primary").on('click', function (event) {
        event.preventDefault();

                if($('#document_file')[0].files.length <=0) {
                   DIB.displayErrors("File is mandatory", LOCALE.get('insly.common.whoops'));  
                } else if($('#document_file')[0].files.length >0) {
                  var checkFlag = FORM.validateFile($('#document_file'), 128, 'Maximum file upload size 128 MB exceeded!');
                  
                  if(checkFlag) {
                   $("#form_document_add").submit();   
                  }
                   
                } else {
                    
                }
           
                  
                
            });


        });
    
    });
    
    
                  //Change display flag of quotes
                  $(document).off('click','.dpib_quote_salesview');
    $(document).on('click','.dpib_quote_salesview',function(){
     $.ajax({
            url: $(this).attr("openUrl"),
            data: { displayflag: $(this).attr("action-type") },
            type: "GET"

        }).done(function (data) {
    if(data.success) {
            location.reload();   
       }


        });
    
    });

 $(document).off('click','.dpib_comment_add');
    $(document).on('click','.dpib_comment_add',function(){


        $("#db_comment_add_popup").remove();



        var template = _.template($("#comment_add_template").html());
        var result = template();



        $('body').append('<div id="db_comment_add_popup" title="Add comment" style="display:none" >' + result + '</div>');
        var dialogElement = $("#db_comment_add_popup");
        dialogElement.dialog({
          width: 900,
          
          modal: true,
          buttons: {
            "Save": {
              class: "btn waves-effect waves-light btn-rounded btn-success",
               text:'Save',
     
              click: function() {
                 DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));

                                var isValid = true;
         var errorMessage = "";
                var i=0;
                $(".required:visible").each(function(){                
                 if($(this).val()=='' || $(this).val() == null) {
                    isValid = false; 
                    $(this).addClass('form-control-danger');
                    $(this).parent('.element').addClass('has-danger')
                    if( i==0 ) {
                     errorMessage+="<b>The following errors occurred while validating data:"+"</b><br/>";
                     i++;
                    }
                    errorMessage+="<b>"+ $(this).attr('error-message')+"</b><br/>"
                  
                 } else {
                    $(this).removeClass('error'); 
                    $(this).removeClass('form-control-danger');
                    $(this).parent('.element').removeClass('has-danger')
                 }
                });
                

            if(isValid) {

                $("form#form_claimant_add").submit();
            } else {
              DIB.alert(errorMessage,'Error!!!!');    
            }

               

              }
            },
            "cancel": {
              class: "btn waves-effect waves-light btn-rounded btn-danger",
              text:'Cancel',
              click: function() {
                dialogElement.dialog('close');
                dialogElement.remove();
              }
            }
          },
          open: function() {

            
          }

        });



        DIB.centerDialog();
    
    });
    
   //UPLOAD QUOTE FUNCTIONALITY
   $(document).off('click','.dpib_crm_quote_add');
       $(document).on('click','.dpib_crm_quote_add',function(){
 $("#db_quote_add_popup").remove();



        var template = _.template($("#quote_upload_template").html());
        var result = template();



        $('body').append('<div id="db_quote_add_popup" title="Upload quote" style="display:none" >' + result + '</div>');
        var dialogElement = $("#db_quote_add_popup");
        dialogElement.dialog({
          width: 900,
          
          modal: true,
          buttons: {
            "Save": {
              class: "btn waves-effect waves-light btn-rounded btn-success",
               text:'Quote upload',
     
              click: function() {
                 DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));

                                var isValid = true;
         var errorMessage = "";
                var i=0;
                $(".required:visible").each(function(){                
                 if($(this).val()=='' || $(this).val() == null) {
                    isValid = false; 
                    $(this).addClass('form-control-danger');
                    $(this).parent('.element').addClass('has-danger')
                    if( i==0 ) {
                     errorMessage+="<b>The following errors occurred while validating data:"+"</b><br/>";
                     i++;
                    }
                    errorMessage+="<b>"+ $(this).attr('error-message')+"</b><br/>"
                  
                 } else {
                    $(this).removeClass('error'); 
                    $(this).removeClass('form-control-danger');
                    $(this).parent('.element').removeClass('has-danger')
                 }
                });
                

            if(isValid) {

                $("form#form_quote_generation").submit();
            } else {
              DIB.alert(errorMessage,'Error!!!!');    
            }

               

              }
            },
            "cancel": {
              class: "btn waves-effect waves-light btn-rounded btn-danger",
              text:'Cancel',
              click: function() {
                dialogElement.dialog('close');
                dialogElement.remove();
              }
            }
          },
          open: function() {

            
          }

        });



        DIB.centerDialog();
    
    });
   
//ASSIGN TO OPERATION TEAM
    
    $(document).on('click','.dpib_sales_crm_assign',function() {
         
        var assignUrl = $(this).attr('assign_url');
        
        $("#db_sales_endorsement_request_assignpopup").remove();
        var template = _.template($("#sales_request_assign_template").html());
        var result = template();
                $('body').append('<div id="db_sales_endorsement_request_assignpopup" title="Assign request" style="display:none" >' + result + '</div>');
                var dialogElement = $("#db_sales_endorsement_request_assignpopup");
                dialogElement.dialog({
                    width: 900,
                    resizable: false,
                    bgiframe: true,
                    modal: true,
                    buttons: {
                        "Delete": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:'Assign',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                $("form#form_sales_request_assign").submit();
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
                    open:function(){
                      $("form#form_endorsement_request_assign").attr('action',assignUrl);  
                    }
                }); 
                    DIB.centerDialog(); 
    });
    
    
     //DELETE REQUEST
      $(document).off('click','.dpib_crm_request_delete');
        $(document).on('click','.dpib_crm_request_delete',function() {
         
        var deleteUrl = $(this).attr('delete_url');
        var returnUrl = $(this).attr('return-url');
        
        $("#db_crm_endorsement_request_deletepopup").remove();
                $('body').append('<div id="db_crm_endorsement_request_deletepopup" title="Remove crm request" style="display:none" > Do you really want to remove CRM request ?</div>');
                var dialogElement = $("#db_crm_endorsement_request_deletepopup");
                dialogElement.dialog({
                    width: 900,
                    resizable: false,
                    bgiframe: true,
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
                                     location.replace(returnUrl);
                                   }
                                       
                                  })
                               
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
    

    
    

          
      })          

</script>
<script>
    var crmOverview ='';
   $(function() {
         
     @if (Session::get('requestoverviewtabselected'))
         //var tabValue = "{!! Session::get('requestoverviewtabselected') !!}";
        // $('#tab_'+tabValue).find('a').trigger('click');
     @endif 

   }); 
   
</script>

@endsection