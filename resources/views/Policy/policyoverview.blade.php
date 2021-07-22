@extends('layouts.elite_fullwidth',['sidemenustatus' => array(),'countDetails'=>array(),'notificationCount'=>array() ] )
@section('headtitle')
  Policy @if(!empty($policyDetails->policy_number)):: {{$policyDetails->policy_number}} @else :: -- Not Issued---@endif
  
 
@endsection
@section('overviewmenu')
 <ul id="page-content-menu" class="list-inline pull-right">
                   
 
    
@if (($policyDetails->policy_status ==0 || $policyDetails->policy_status ==6) )  
<li  class="dpib_policy_flag_change btn waves-effect waves-light btn-rounded btn-outline-primary" changeflag="1" data-url="{{route('changepolicystatus',[$policyDetails->mainId])}}">Post policy</li>     
      @elseif($policyDetails->policy_status ==1)     
     <li  class="dpib_policy_flag_change btn waves-effect waves-light btn-rounded btn-outline-primary" changeflag="2" data-url="{{route('changepolicystatus',[$policyDetails->mainId])}}">Issue/Activate policy</li> 
     @elseif($policyDetails->policy_status ==1 || $policyDetails->policy_status ==2)     
     <li  class="dpib_policy_flag_change btn waves-effect waves-light btn-rounded btn-outline-primary" changeflag="3" data-url="{{route('changepolicystatus',[$policyDetails->mainId])}}">Lock policy</li>     
     @endif     
     <li class="dpib_policy_flag_change btn waves-effect waves-light btn-rounded btn-outline-primary" changeflag="4" data-url="{{route('changepolicystatus',[$policyDetails->mainId])}}">Delete policy</li>
 </ul>

@endsection
@section('content')
@section('warningmessage')

 @if($policyscheduleCount ==0 && $policyDetails->policy_status ==2)
  <div class="alert alert-warning alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong>Policy schedule is didn't set yet!!!!!!</strong>
        
  </div>
{{-- @elseif($policyscheduleCount ==0 && $policyDetails->policy_schedule_flag ==1) --}} 
<!--  <div class="alert alert-warning alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>In oder to post policy we must set policy schedule!!!!!</strong>
        
  </div>-->
  @endif
  @endsection
<!--                  content here  -->
<!--TAB AREA-->
<div class="row">

    
        <div class="col-md-12 card">
        
        
        <ul class="nav nav-tabs policytab card-body" role="tablist" id="policytab">
                                <li id="tab_overview" class="nav-item" onclick="TAB.select('overview', null, 1)"> <a class="nav-link {{ empty($overviewTab) || $overviewTab == 'overview' ? 'active' : '' }}" data-toggle="tab" href="#content_overview" role="tab" aria-selected="true"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Overview</span></a> </li>
                                
                                <li id="tab_coverage" class="nav-item" onclick="TAB.select('coverage', null, 0)"> <a class="nav-link {{ empty($overviewTab) || $overviewTab == 'coverage' ? 'active' : '' }}" data-toggle="tab" href="#content_coverage" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">coverage</span></a> </li>
                                
                                <li id="tab_endorsement" class="nav-item" onclick="TAB.select('endorsement', '{{route('listendorsement',[$policyDetails->mainId])}}', 0)"> <a class="nav-link {{ empty($overviewTab) || $overviewTab == 'endorsement' ? 'active' : '' }}" data-toggle="tab" href="#content_endorsement" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Endorsement ({{$endorsementCount}})</span></a> </li>
                                
                                <li id="tab_installment" class="nav-item" onclick="TAB.select('installment', null, 0)"> <a class="nav-link {{ empty($overviewTab) || $overviewTab == 'installment' ? 'active' : '' }}" data-toggle="tab" href="#content_installment" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Installment schedule</span></a> </li>
                                <li id="tab_document" class="nav-item" onclick="TAB.select('document', '{{route('policydocuments',[$policyDetails->customer_id, $policyDetails->mainId])}}', 0)"> <a class="nav-link {{ empty($overviewTab) || $overviewTab == 'document' ? 'active' : '' }}" data-toggle="tab" href="#content_document" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Documents ({{$documentCount}})</span></a> </li>
                                <li id="tab_claims" class="nav-item" onclick="TAB.select('claims', '{{route('getclaimdetails',[$policyDetails->mainId])}}', 0)"> <a class="nav-link {{ empty($overviewTab) || $overviewTab == 'claims' ? 'active' : '' }}" data-toggle="tab" href="#content_claims" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Claims ({{$claimCount}})</span></a> </li>
                                <li id="tab_crm" class="nav-item" onclick="TAB.select('crm', '{{route('endorsementrequest',[$policyDetails->mainId])}}', 0)"> <a class="nav-link {{ empty($overviewTab) || $overviewTab == 'crm' ? 'active' : '' }}" data-toggle="tab" href="#content_crm" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Requests ({{$policyrequestCount}})</span></a> </li>
                                <li id="tab_log" class="nav-item" onclick="TAB.select('log', '{{route('policylogdata',[$policyDetails->mainId])}}', 0)"> <a class="nav-link {{ empty($overviewTab) || $overviewTab == 'log' ? 'active' : '' }}" data-toggle="tab" href="#content_log" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Log</span></a> </li>
        </ul>
        

    </div>
    
    
    
    
    
</div>
<!--TAB CONTENT AREA-->
<div class="row card dpib-custom-form">
  
        <div id="content_overview" class="tabcontent col-md-12 card-body">
            
            
            
            
            <div class="row">
                
                  <div id="main-content" class="col-md-8">
                    <div id="panel-customer_overview" class="panel panel-default open">
                        <div class="panel-heading">
                                @if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || Auth::user()->id == $policyDetails->created_by || in_array('ROLE_TECHNICAL_LEAD', Auth::user()->roles))
                            <ul class="panel-actions list-inline pull-right">
                                @if($policyDetails->policy_status ==2)
                                 
                                  <li ><a href="{{ route('checklistform', [ $policyDetails->mainId])}}"><span class="far fa-list-alt text-blue" data-toggle="tooltip" title="" data-original-title="Policy checklist"></span></a></li>
                                 @endif
                                 <li ><a href="{{ route('createschedule', [$policyDetails->customer_id, $policyDetails->mainId])}}"><span class="far fa-calendar-plus text-blue" data-toggle="tooltip" title="" data-original-title="Edit policy schedule"></span></a></li>
                                <li ><a href="{{ route('editpolicy',$policyDetails->mainId)}}"><span class="fas fa-edit text-blue" data-toggle="tooltip" title="" data-original-title="Edit policy info"></span></a></li>


                            </ul>
                             @endif
                            <h3 class="panel-title">Policy info</h3></div>
                        <div id="customer_overview" class="panel-collapse panel-body">
                            <table class="info-table" width='100%'>
                                <tbody>
                                    <tr><td>Customer:</td><td><a href='{{route('customeroverview',$policyDetails->customer_id)}}'>{{ $policyDetails->customerName}}</a> </td></tr>
                                    <tr><td>Coverage:</td><td>{{$policyDetails->coverage}}</td></tr>
                                    <tr><td>Object:</td><td>{{ ($policyDetails->coverage_info==null) ? $policyDetails->product_name :  $policyDetails->coverage_info}}   </td></tr>

                                    <tr class="subtitle"><th colspan="2">Policy info</th></tr>
                                    <tr><td>Policy type:</td><td>
                                            @if($policyDetails->policy_type ==3)
                                            Motor
                                            @elseif($policyDetails->policy_type ==2)
                                            Medical                                           
                                            @else
                                            General
                                            @endif
                                        </td></tr>
                                    <tr><td>Insurer:</td><td class="phoneNumber">{{$policyDetails->insurer_name}}</td></tr>
                                    <tr><td>Policy number:</td><td class="phoneNumber">@if($policyDetails->policy_number !='')  <span class='text-success' style='font-weight: bold'>{{$policyDetails->policy_number}}<span> @else <span class='text-danger' style='font-weight: bold'>not issued</span>@endif</td></tr>
                                    <tr><td>Inception date:</td><td> {{date('d.m.Y', strtotime($policyDetails->start_date)) }}  </td></tr>
                                    <tr><td>End date:</td><td>{{date('d.m.Y', strtotime($policyDetails->end_date)) }} </td></tr>
                                    <tr><td>Issue date:</td><td>{{date('d.m.Y', strtotime($policyDetails->issue_date)) }} </td></tr>
                                    
                                    <tr><td>Status:</td><td>
                                            @if($policyDetails->policy_status ==0)
                                            <span class='text-danger' style='font-weight: bold'>Saved</span>
                                            @elseif($policyDetails->policy_status ==2)
                                            <span class='text-success' style='font-weight: bold'> Active/Issued</span>
                                            @elseif($policyDetails->policy_status ==4)
                                            <span class='text-success' style='font-weight: bold'> Renewed</span>
                                             @elseif($policyDetails->policy_status ==6)
                                            <span class='text-danger' style='font-weight: bold'> Rejected</span>
                                            @else
                                             Post
                                            @endif
                                        
                                        </td></tr>
                                        @if($policyDetails->policy_status ==6)
                                       <tr><td>Rejected reason:</td><td class="phoneNumber"><i><b>{{$policyDetails->reject_reason}}</b></i></td></tr>
                                      @endif  
                                    <tr class="subtitle"><th colspan="2">Sales</th></tr>
                                    <tr><td>Sales type:</td><td>{{  ($policyDetails->sales_type ==1) ? "new sales": "manual renewal" }}</td></tr>
                                    <tr class="subtitle"><th colspan="2">Renewal</th></tr>
                                    <tr><td>Renewable status:</td><td>{{$policyDetails->policystatusString}}</td></tr>
                                    @if( $policyDetails->renewal_status ==1)
                                    <tr><td>Previous policy number:</td><td><a href="{{route('policyoverview',[$policyDetails->previous_policy_id])}}">{{$policyDetails->previusPolicy}}</a></td></tr>
                                     @endif    
                                    

                                </tbody>
                            </table>
                        </div></div>
                    <div id="panel-customer_address" class="panel panel-default open">
                        <div class="panel-heading">
                                        <h3 class="panel-title">Persons and commission split</h3>
                        </div>
                        <div id="customer_address" class="panel-collapse panel-body">


                            @if(count($commissionDetails) >0)
                            <table class="table table-bordered table-striped table-hovered color-table">
                                <thead>
                                    <tr> <th>#</th><th>PERSON</th><th class="nowrap">ROLE </th><th class="nowrap">COMMISSION(%) </th><th class="nowrap">COMMISSION SAR </th></tr> 

                                </thead>
                                <tbody>


                                    @foreach($commissionDetails as $key =>$commission) 
                                    <tr>

                                        <td>{{$key+1}} </td>
                                        <td>{{ ($commission->distributor_type =='sales person') ? $commission->salesperson: 'Diamond insurance broker'  }} </td>
                                        <td>{{($commission->distributor_type =='sales person') ? $commission->distributor_type: 'rounding correction' }} </td>
                                        <td>{{($commission->commission_type ==0) ? $commission->percentage: 'Amount' }} </td>
                                        <td>{{  number_format($commission->totalAmount, 2, '.', ',')   }} </td>

                                    </tr>
                                    @endforeach


                                </tbody></table>
                            @endif

                        </div>
                    </div>

                    <div id="panel-customer_contact" class="panel panel-default open">
                        <div class="panel-heading">
                            
                            <h3 class="panel-title">Revenue</h3></div>
                        <div id="customer_contact" class="panel-collapse panel-body">
                            @if(count($commissionDetails) >0)
                            @php 
                            $amount=0;
                            $total =0;
                            @endphp                           
                            <table class="info-table">
                                <tbody><tr>
                                        <td class="normal" style="width: 31%; font-weight: bold; text-align: left;"></td>
                                        <td style="width: 23%; font-weight: bold; text-align: right; white-space: nowrap;">Commission</td>

                                        <td style="width: 23%; font-weight: bold; text-align: right; white-space: nowrap;">Total</td>
                                    </tr>
                                    @foreach($commissionDetails as $key =>$commission) 
                                    @if($commission->distributor_type =='sales person')
                                    @php 
                                    $amount= $commission->totalAmount  ;

                                    @endphp 
                                    <tr>
                                        <td class="normal" style="text-align: left">Internal persons:</td>

                                        <td style="text-align: right; white-space: nowrap">
                                            <span style="color: #919191">{{ number_format($commission->totalAmount, 2, '.', ',')   }} SAR</span>
                                        </td>
                                        <td style="font-weight: bold; text-align: right; white-space: nowrap">{{ number_format($commission->totalAmount, 2, '.', ',')  }} SAR</td>
                                    </tr>
                                    @elseif($commission->distributor_type =='diamond')   
                                    @php 
                                    $total= $commission->totalAmount;

                                    @endphp 
                                    <tr>
                                        <td class="normal" style="text-align: left">Diamond insurance broker:</td>

                                        <td style="text-align: right; white-space: nowrap">
                                            <span style="color: #919191">{{ number_format( $commission->totalAmount - $amount, 2, '.', ',')  }}   SAR</span>
                                        </td>
                                        <td style="font-weight: bold; text-align: right; white-space: nowrap">{{ number_format($commission->totalAmount - $amount, 2, '.', ',')  }} SAR</td>
                                    </tr>
                                    @endif
                                    @endforeach

                                    <tr>
                                        <td style="font-weight: bold; text-align: left">TOTAL:</td>
                                        <td style="font-weight: bold; text-align: right; white-space: nowrap">{{ number_format($total, 2, '.', ',') }} SAR</td>

                                        <td style="font-weight: bold; text-align: right; white-space: nowrap">{{ number_format($total, 2, '.', ',') }} SAR</td>
                                    </tr>
                                </tbody></table>



                            @endif
                        </div>
                    </div>


                </div> 
                
                
                
                
                
                <aside class="col-md-3">
                    @php 

                    $total =0;
                    $payment=0;
                    @endphp   
                    <div id="panel-customer_balance" class="panel panel-default open">
                        <div class="panel-heading"><h3 class="panel-title">Premium</h3></div>
                        <div id="customer_premium" class="panel-collapse panel-body">

                            <table class="info-table">

                                <tbody>
                                    <tr id="signedPremium">
                                        <td>Gross premium:</td>
                                        <td>
                                            <span>
                                                {{number_format($policyDetails->total_premium, 2, '.', ',') }}  SAR</span>
                                        </td>
                                    </tr>
                                    @if($endorsementCount > 0)
                                   <tr id="signedPremium">
                                        <td>Endorsements:</td>
                                        <td>
                                            <span>
                                                @if($endorsementamount !='')
                                                {{ number_format($endorsementamount, 2, '.', ',') }}  SAR
                                                @else
                                                 0.00 SAR
                                                @endif
                                                
                                                </span>
                                        </td>
                                    </tr>
                                    @endif

                                    <tr id="gross_written_premium">
                                        <td>Gross written premium:</td>
                                        <td>
                                            <span>
                                                {{ number_format(($policyDetails->total_premium + $endorsementamount), 2, '.', ',')  }} SAR</span>
                                        </td>
                                    </tr>
                                     <tr id="installments">
                                        <td>Installments:</td>
                                        <td>
                                            <span>
                                                {{($policyDetails->installment_number !=0) ? $policyDetails->installment_number:''  }}</span>
                                        </td>
                                    </tr>
                                   	

                                    <tr id="collection">
                                        <td>Collection:</td>
                                        <td>
                                            <span>
                                                {{ $policyDetails->collection_type }}  </span>
                                        </td>
                                    </tr>

                                    <tr id="netPremium">
                                        <td>Net premium:</td>
                                        <td>
                                            <span>
                                           
                                                {{ number_format(($policyDetails->total_premium +  $endorsementamount +$vatAmount->installmentVat)- $companyRevanue, 2, '.', ',')  }} SAR</span>
                                        </td>
                                    </tr>
                                    
                                      <tr  class="text-success">
                                        <td >Total premium:</td>
                                        <td>
                                            <span style="font-weight: bold">
                                           
                                                {{ number_format(($policyDetails->total_premium +  $endorsementamount +$vatAmount->installmentVat), 2, '.', ',')  }} SAR</span>
                                        </td>
                                    </tr>
                                </tbody></table>



                        </div>

                    </div>
                    <div id="panel-customer_profile" class="panel panel-default open">
                        <div class="panel-heading"><h3 class="panel-title">Commission</h3></div>
                        <div id="company_commission" class="panel-collapse panel-body">
                            <table class="info-table">

                                <tbody><tr>
                                        <td>Commission:</td>
                                        <td>

                                            {{$policyDetails->commision}}% 
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Commission:</td>
                                        <td style="width: 60%;  ">
                                     
                                            {{ number_format($companyRevanue, 2, '.', ',')   }}
                      
                                            SAR
                                        </td>
                                    </tr>


                                </tbody></table>
                        </div>
                    </div>
                    <div id="panel-customer_salesopportunities" class="panel panel-default open">
                        <div class="panel-heading"><h3 class="panel-title">Taxes</h3></div>
                        <div id="customer_tax" class="panel-collapse panel-body">
                            <table class="info-table">
                                <tbody><tr>
                                        <td>Total:</td>
                                        <td id="total_tax_value"> {{ number_format(($vatAmount->installmentVat), 2, '.', ',')   }} SAR</td>
                                    </tr>
                                </tbody></table>
                        </div>
                    </div>


                </aside>
             
            </div>
        </div>
    
        <div id="content_coverage" class="tabcontent col-12" rel="" style="display: none;">


            <div id="panel-policy_product_10020236" class="panel panel-default open">
                <div class="panel-heading">
                    @if(!empty($policyDetails->product_id))
                    <ul class="panel-actions list-inline pull-right">
                        <li>
                            
                            <a href="{{route('updatecoveragedata',[$policyDetails->mainId, $policyDetails->product_id])}}">
                                <span class="fas fa-edit text-blue" data-toggle="tooltip" title="" data-original-title="Edit product info"></span></a>
                        </li>
                        
                    </ul>
                    @endif
                    <h3 class="panel-title">{{ $policyDetails->product_name}}</h3></div>
                <div id="policy_product_10020236" class="panel-collapse panel-body">
                    <table class="insly-form">
                        <tbody>

                            @foreach($coverageDetails as $key =>$coverageDetail) 
                            <tr id="field_prop_field_{{$key}}" class="field">
                                <td class=""><div class="label full-height" style="height: 32px;"><span class="title">{{$coverageLabel[$key]}}</span></div></td>
                                <td><div class="element element-text">
                                        {{ $policyDetails->$coverageDetail}}

                                    </div></td>
                            </tr>


                            @endforeach




                            <tr class="field">
                                <td colspan="2" class="subtitle">Commission</td>
                            </tr>
                            <tr class="field">
                                <td><div class="label">Commission:</div></td>
                                <td><div class="element element-text">{{ $policyDetails->commision }}%</div></td>
                            </tr>
                            <tr class="field">
                                <td colspan="2" class="subtitle">Tax</td>
                            </tr>
                            <tr class="field">
                                <td><div class="label">Tax:</div></td>
                                <td><div class="element element-text">{{ $policyDetails->tax }}%</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @if(count($objectDetails) >0)
            <div>
                <div id="panel-policy_object" class="panel panel-default open">
                    <div class="panel-heading">
                        @if(!empty($policyDetails->product_id))  
                        <ul class="panel-actions list-inline pull-right">
                            @if( $policyDetails->product_id ==27 || $policyDetails->product_id ==38)
                            <li class="dp_policy_object_add" data-url="{{route('editobjectform',[$policyDetails->mainId,$policyDetails->product_id, 0])}}"><span class="fas fa-plus" data-toggle="tooltip" title="" data-original-title="Add another object" ></span>
                              @else  
                                <li class="dp_policy_object_edit" product-id="{{$policyDetails->product_id}}" object-id="{{$objectDetails[0]->objectId}}" data-url="{{route('editobjectform',[$policyDetails->mainId,$policyDetails->product_id, $objectDetails[0]->objectId])}}"><span class="fas fa-edit text-blue" data-toggle="tooltip" title="" data-original-title="Edit object"></span>                                
                             @endif   
                            </li>
                        </ul>
                        @endif
                          
                        <h3 class="panel-title">Objects</h3></div>
                    <div id="policy_object" class="panel-collapse panel-body">
                        
                       
                            

                                @foreach($objectDetails as $key =>$objectDetail) 
                                    @if(($objectDetail->object_type =='person' || $objectDetail->object_type =='vehicle_multiple') && $key==0)
                            <table class="table table-striped table-hovered table-bordered insly-form">
                                    <thead>
                                <tr class="headerrow">
                                    <th style="width: 80%">Object</th>
                                    <th style="width: 1%">Status</th>
                                    <th style="width: 1%; white-space: nowrap;">Last Change</th>
                                    <th style="width: 1%"></th>
                                </tr>
                            </thead>
                            
                                @elseif($key==0)
                                <table class="insly-form">
                                <tbody>
                                
                            @endif
                                @if($objectDetail->object_type =='person')  
                                <tr class="datarow table-striped-row-light objectrow">
                                    <td>

                                        {{$objectDetail->last_name}},{{$objectDetail->first_name}}

                                    </td>
                                    <td style="width: 1%;">active</td>
                                    <td style="width: 1%;">@if(!is_null($objectDetail->updated_date))  {{ date('d.m.Y h:i',strtotime($objectDetail->updated_date))}} @endif</td>
                                    <td class="iconactions"> 
                                        @if(!empty($policyDetails->product_id))  
                                        <a class="dp_policy_object_edit" data-url="{{route('editobjectform',[$policyDetails->mainId,$policyDetails->product_id, $objectDetail->objectId])}}">
                                            <span data-original-title="Edit object" class="fas fa-edit text-blue" data-toggle="tooltip" title=""></span></a>
                                            <a class="dp_policy_object_remove" data-url="{{route('editobjectform',[$policyDetails->mainId,$policyDetails->product_id, $objectDetail->objectId])}}">
                                                <span data-original-title="Remove object" class="fas fa-archive text-blue" data-toggle="tooltip" title=""></span></a>
                                                @endif
                                    </td>
                                </tr>
                                
                                @elseif($objectDetail->object_type =='vehicle_multiple')  
                                <tr class="datarow table-striped-row-light objectrow">
                                    <td>

                                        {{$objectDetail->make}},{{$objectDetail->model}},{{$objectDetail->year}},{{$objectDetail->license_plate}},{{$objectDetail->no_of_passengers}}

                                    </td>
                                    <td style="width: 1%;">active</td>
                                    <td style="width: 1%;">@if(!is_null($objectDetail->updated_date))   {{ date('d.m.Y h:i',strtotime($objectDetail->updated_date))}} @endif </td>
                                    <td class="iconactions">
                                        @if(!empty($policyDetails->product_id))  
                                        <a class="dp_policy_object_edit" data-url="{{route('editobjectform',[$policyDetails->mainId,$policyDetails->product_id, $objectDetail->objectId])}}">
                                            <span data-original-title="Edit object" class="fas fa-edit" data-toggle="tooltip" title=""></span></a>
                                            <a class="dp_policy_object_remove" data-url="{{route('removeobject')}}" objId="{{$objectDetail->objectId}}" product-id='{{$policyDetails->mainId}}'><span data-original-title="Remove object" class="fas fa-archive" data-toggle="tooltip" title=""></span></a>
                                    @endif
                                    </td>
                                </tr>
                                
                                

                                @elseif($objectDetail->object_type =='vehicle')
                                <tr id="field_prop_field_10002072" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">Make</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span>{{ $objectDetail->make }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10002073" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">Model</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span>{{ $objectDetail->model }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10002075" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">Year</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span>{{ $objectDetail->year }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10001061" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">License plate</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span>{{ $objectDetail->license_plate }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10002085" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">Body Type</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span>{{ $objectDetail->body_type }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10001062" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">VIN code</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span>{{ $objectDetail->vin_code }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10002076" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">Usage</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span>{{ $objectDetail->vehicle_usage }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10008080" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">CAR NUMBER OF PASSENGERS</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span>{{ $objectDetail->no_of_passengers }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10001063" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger "></span>
                                            <span class="title">Power (kw)</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span>{{ $objectDetail->power }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10001064" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">Gross weight (kg)</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span>{{ $objectDetail->gross_weight }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>



                                 @elseif($objectDetail->object_type =='property') 

                                <tr id="field_prop_field_10001067" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">Address</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">
                                                
                                                <span>{{ $objectDetail->address }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10001068" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">Property type</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">
                                              
                                                <span>{{ $objectDetail->property_type }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10001069" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger "></span>
                                            <span class="title">Year built</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">
                                                
                                                <span>{{ $objectDetail->year_built }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10001070" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger "></span>
                                            <span class="title">Area</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">
                                               
                                                <span>{{ $objectDetail->area }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10001071" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger "></span>
                                            <span class="title">Construction material</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">
                                                
                                                <span>{{ $objectDetail->construction_material }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                

                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

        </div>
        <div id="content_endorsement" class="tabcontent col-12" rel="{{route('listendorsement',[$policyDetails->mainId])}}" style="display:none">
            endorsement  display area

        </div>

        <div id="content_installment" class="tabcontent col-12" rel="" style="display: none;">
            <div id="panel-policy_installment_payment" class="panel panel-default open">
                
                <div class="panel-heading">
                  @if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || Auth::user()->id == $policyDetails->created_by)  
                    <ul class="panel-actions list-inline pull-right">
                        <li id="dpib_edit_premium_info" data-url="{{route('getpremiuminfodata',[$policyDetails->mainId])}}"><span class="fas fa-edit text-blue" data-toggle="tooltip" title="" data-original-title="Edit premium info"></span></li>
                 
                    </ul>
                    @endif
                    <h3 class="panel-title">Payment</h3>
                </div>
                <div id="policy_installment_payment" class="panel-collapse panel-body">
                    <table class="info-table">
                        <tbody>
                            <tr>
                                <td style="width: 12%; font-weight: bold">Gross Premium:</td>
                                <td style="width: 21%">
                                    <span class="text-danger" style="font-weight: bold">
                                        
                                        {{ number_format($policyDetails->gross_amount, 2, '.', ',') }} SAR
                                    </span>
                                </td>
                                <td style="width: 12%; font-weight: bold">Installments:</td>
                                <td style="width: 21%">
                                    {{$policyDetails->installment_number}}
                                </td>
                                <td style="width: 12%; font-weight: bold"></td>
                                <td style="width: 21%"></td>
                            </tr>
                            <tr>
                                <td style="width: 12%; font-weight: bold">Addition amount:</td>
                                <td style="width: 21%">{{ number_format($policyDetails->additional_amount, 2, '.', ',')  }} SAR</td>

                                <td style="width: 12%; font-weight: bold">Collection:</td>
                                <td style="width: 21%">
                                    {{ $policyDetails->collection_type }}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 12%; font-weight: bold">Commission:</td>
                                <td style="width: 21%">{{ $policyDetails->commision }}  %</td>

                                <td style="width: 12%; font-weight: bold"></td>
                                <td style="width: 21%">

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
             @if(count($installmentDetails) >0)
            <div id="panel-policy_installment_schedule" class="panel panel-default open">
                <div class="panel-heading">
                    <ul class="panel-actions list-inline pull-right">
                     
                            <div class="btn-group">
                                <span class="icon-settings text-blue"></span>
                         
                                <ul id="add-menu" class="dropdown-menu" role="menu">
                                   @if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles)  || in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || Auth::user()->id == $policyDetails->created_by)   
                                    <li id="dpib_regenerate_installment" data-url="{{route('regenerateinstallment',[$policyDetails->mainId])}}" policyId="{{$policyDetails->mainId}}"><a>Generate installment schedule</a></li>
                                      @endif
                                    @if (in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles))    
                                    <li id="dpib_generate_invoice" data-url="{{route('invoicegenerate',[$policyDetails->mainId])}}" policyId="{{$policyDetails->mainId}}"><a>Generate Invoice</a></li>
                                      @endif
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <h3 class="panel-title">Installment schedule</h3>
                </div>
                <div id="policy_installment_schedule" class="panel-collapse panel-body">
                    
                    <table class="table table-striped table-hovered table-bordered policy_installment_table">
                        <thead>
                            <tr>
                               @if($policyDetails->policy_status ==2 && (in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles)))
                                <th style="width: 1%;"></th>
                                 @endif
                                <th style="width: 1%;">Installment</th>
                                <th style="width: 5%;">Endorsement</th>
                                
                                <th style="width: 10px;">Gross Premium</th>
                                <th style="width: 20px;">Period</th>
                                <th style="width: 20px;">Due date</th>
                                <th style="width: 40px;">Collects</th>
                                <th style="width: 30px;">Comm.</th>

                                <th style="width: 5px;">Tax</th>
                                <th style="width: 10px;">Customer payable</th>
                                <th style="width: 10px;">Paid status</th>
                                <th style="width: 1%;" class="nowrap"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                            $total = $tax = $customerPayable = 0;
                           
                            $i=0;
                            @endphp
                           

                            @foreach($installmentDetails as $key =>$installmentDetail)
                            @php
                            $total = $total+ floatval($installmentDetail->amount);
                            $tax = $tax + floatval($installmentDetail->vat_amount);
                            $customerPayable = $customerPayable + floatval(($installmentDetail->amount + $installmentDetail->vat_amount));
                            @endphp
                            <tr style="font-weight: bold" class="table-striped-row-light">                                
                               @if($policyDetails->policy_status ==2 && (in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles)) )
                                <td> 
                                    @if($installmentDetail->paid_status ==0)
                                    
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                                <input type="checkbox" id="payments{{$key+1}}" name="payments[]" class="paymentIds custom-control-input" value="{{$installmentDetail->id}}">
                                                <label class="custom-control-label" for="payments{{$key+1}}"></label>
                                    </div>

                                    @endif
                                
                                </td> 
                                 @endif
                                
                                @if( empty($installmentDetail->endorsement_id))
                                 @php
                                 $i++;
                                 @endphp
                                <td style="text-align: left;">{{$i}}</td>
                                @else 
                                <td style="text-align: left;"><img src="{{ asset('Images/icon-policy-payment-2.png') }}" data-toggle="tooltip" data-original-title="Comment/description: Endorsement {{$installmentDetail ->endorsement_number}}"></td>
                                @endif
                                <td class="nowrap">{{$installmentDetail ->endorsement_number}}</td>
                                 @if( empty($installmentDetail->endorsement_id))
                                  <td class="nowrap">{{ number_format($installmentDetail->amount, 2, '.', ',')}} SAR</td>
                                 @else
                                  <td class="nowrap">{{ number_format($installmentDetail->amount, 2, '.', ',')}} SAR</td>
                                 @endif
                                
                                
                                <td>{{date('d.m.Y', strtotime($installmentDetail->start_date)) }} - {{ date('d.m.Y', strtotime($installmentDetail->end_date)) }}</td>
                                <td>{{ date('d.m.Y', strtotime($installmentDetail->due_date)) }}</td>
                                <td><i style="color: #555555">{{ $installmentDetail->collectionString }}</i></td>
                                <td><i style="color: #555555" data-toggle="tooltip" data-original-title=" @if($installmentDetail->amount >0) {{ number_format( (($installmentDetail->amount * $policyDetails->commision)/100), 2, '.', ',')  }} SAR @else {{ number_format( 0, 2, '.', ',')}} @endif"> @if($installmentDetail->amount >0)  {{ $policyDetails->commision }}%   @else {{ number_format( 0, 2, '.', ',')}} @endif</i></td>

                                <td> {{ number_format($installmentDetail->vat_amount, 2, '.', ',')  }} </td>
                                <td class="nowrap"><a onclick="">{{ number_format( ($installmentDetail->amount + $installmentDetail->vat_amount), 2, '.', ',')}} SAR</a></td>
                                <td class="nowrap"> @if ($installmentDetail->paid_status==1) <span class="text-success">Paid</span> @else <span class="text-danger">Unpaid</span>  @endif</td>


                                <td class="iconactions nowrap">
                                    
                                     @if($installmentDetail->default_flag ==0 && empty($installmentDetail->endorsement_id) && $installmentDetail->paid_status==0)
                                    <div class="btn-group">
                                        
                                            <span class="fas fa-edit"></span>                                            
                                            <span class="icon-active"></span>
                                  
                                      
                                        <ul class="dropdown-menu edit" role="menu">
                                            
                                            <li><a class="dpib_edit_installment" data-url="{{route('editinstallment',[$installmentDetail->id])}}">Edit installment</a>
                                            </li> 
                                        </ul>
                                       
                                      
                                    </div>
                                    
                                       @endif
                                   
<!--                                       <div class="btn-group">
                                        <button type="button">
                                            <span class="icon-invoice"></span>
                                            <span class="icon-arrow-down"></span>
                                            <span class="icon-active"></span>
                                        </button>
                                        <ul class="dropdown-menu invoice" role="menu">
                                            <li><a onclick="">Create invoice</a>
                                            </li>            </ul>
                                    </div>-->
                                </td>
                            </tr>
                            @endforeach 
                           

                            <tr class="table-striped-row-dark">
                                @if($policyDetails->policy_status ==2 && (in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles)))
                                <td></td>
                                @endif
                                <td><b>TOTAL SAR</b></td>
                                <td><b></b></td>
                                <td>{{  number_format($total, 2, '.', ',')  }} SAR</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>

                                <td>{{  number_format($tax, 2, '.', ',')     }} SAR</td>
                                <td>{{ number_format($customerPayable, 2, '.', ',')    }} SAR</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                     
                </div>
            </div>
              @endif
        </div>
        <div id="content_document" class="tabcontent col-12" rel="{{route('policydocuments',[$policyDetails->customer_id, $policyDetails->mainId])}}" style="display:none">

      
        </div>
        <div id="content_claims" class="tabcontent col-12" rel="{{route('getclaimdetails',[$policyDetails->mainId])}}" style="display:none">
            claim display area

        </div>
        <div id="content_crm" class="tabcontent col-12"  style="display:none" rel="{{route('endorsementrequest',[$policyDetails->mainId])}}" >
            crm display area
        </div>
           
        

        <div id="content_log" class="tabcontent col-12"  style="display:none;" rel="{{route('policylogdata',[$policyDetails->mainId])}}">
            log display area 

        </div>

    

</div>


<script id='policy_issued_template' type='text/template'>
    
 {{ Form::open(array('route' => array('changepolicystatus',$policyDetails->mainId), 'name' => 'form_policy_status_change','id'=>'form_policy_status_change','files'=>'true' )) }}

    <div class="dialogform"><table class="insly_dialogform">
                <tbody>                    
                    <tr id="field_request_type" class="field">
                        <td class="">
                     Do you really want to activate policy?.
                                <input type='hidden' name='flag' value=2>
                             
                           
                        </td>
                    </tr>
                    
                    
                    

                </tbody></table></div>
    {{ Form::close() }}   
    
    
</script>

<script id='policy_post_template' type='text/template'>
    
 {{ Form::open(array('route' => array('changepolicystatus',$policyDetails->mainId), 'name' => 'form_policy_status_change','id'=>'form_policy_status_change','files'=>'true' )) }}

    <div class="dialogform"><table class="insly_dialogform insly-form">
                <tbody>                    
                    <tr id="field_request_type" class="field">
                        <td style='width:25%'>
              Policy number                              
                        </td>
                        <td>
                             <input type='hidden' name='flag' value=1>
                             <input type='text' name='policy_number' id="policy_number"  value=''>
                            </td>
                    </tr>
   <tr id="field_5dac554a1ac30_prop_field_10068074" class="field">
                <td class="">
                    <div class="label full-height" style="height: 39px;"><span class="title">Send following documents:</span></div>
                </td>
                <td>
                    <div class="form-group row pt-3" style="margin-left:10px;">
                        
                            <div class="col-sm-4 custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="send_document_policy" name="send_document[]" value="Policy"><label style="width: 30%; cursor: pointer; color: rgb(87, 87, 87);background-color:#fff!important" class="custom-control-label" for="send_document_policy"> Policy</label></div>
                            <div class="col-sm-4 custom-control custom-checkbox"> <input type="checkbox" class="custom-control-input" id="send_document_card" name="send_document[]" value="Insurance card"><label style="width: 92%; cursor: pointer; color: rgb(87, 87, 87);background-color:#fff!important" class="custom-control-label" for="send_document_card">Insurance card</label></div>
                        </div>
                        </td>
                        </tr>

                </tbody>
                </table>
    </div>
    {{ Form::close() }}   
    
    
</script>



@endsection
@section('customcss')
<link rel="stylesheet" type="text/css" href="{{ asset('elitedesign/assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('elitedesign/assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
@endsection


@section('pagescript')
<script src="{{ asset('elitedesign/assets/node_modules/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('elitedesign/assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>        
<script src="{{ asset('js/dibcustom/dib-quote-request.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/dibcustom/dib-policy-add.js') }}" type="text/javascript"></script>

<script>
var   seletab = '{{$overviewTab}}';

$(function () {

var dibpolicyAddObj = new DibPolicyAdd();
    dibpolicyAddObj.initialSetting();
});


$('#policytab').find('.active').removeClass('active');
$('#tab_'+seletab).trigger('click');
$('#tab_'+seletab+' a[href="#content_'+seletab+'"]').addClass('active');
$('#tab_'+seletab+' a[href="#tab_'+seletab+'"]').attr('aria-selected',true);

$('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
$($.fn.dataTable.tables(true)).DataTable()
.columns.adjust()
.responsive.recalc();
});


</script>

@endsection