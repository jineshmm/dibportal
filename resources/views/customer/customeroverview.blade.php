
@extends('layouts.elite_fullwidth',['sidemenustatus' => array(),'countDetails'=>array(),'notificationCount'=>array() ] )

@section('headtitle')
  Customer @if(!empty($customers->accountNo)):: {{$customers->accountNo}} @endif
@endsection

@section('content')

     
<!--                  content here  -->
<!--TAB AREA-->
<div class="row">
    
    
    
    
    
    
    
    <div class="col-md-12 card" >
        
        
        <ul class="nav nav-tabs customtab card-body" role="tablist">
                                <li id="tab_overview" class="nav-item" onclick="TAB.select('overview', null, 1)"> <a class="nav-link {{ empty($overviewTab) || $overviewTab == 'overview' ? 'active' : '' }}" data-toggle="tab" href="#content_overview" role="tab" aria-selected="true"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Overview</span></a> </li>
                                
                                <li id="tab_policy" class="nav-item" onclick="TAB.select('policy', '{{route('policylisting', $customers->customId)}}', 0)"> <a class="nav-link {{ !empty($overviewTab) && $overviewTab == 'policy' ? 'active' : '' }}" data-toggle="tab" href="#content_policy" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Policies ({{$policyCount}})</span></a> </li>
                                 @if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles) || in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles))  
                                <li id="tab_quotes" class="nav-item" onclick="TAB.select('quotes', '{{route('quotelisting', $customers->customId)}}', 0)"> <a class="nav-link {{ !empty($overviewTab) && $overviewTab == 'quote' ? 'active' : '' }}" data-toggle="tab" href="#content_quotes" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Quotes ({{$quoteCount}})</span></a> </li>
                                @endif
                                <li id="tab_document" class="nav-item" onclick="TAB.select('document', '{{route('customerdocdata', $customers->customId)}}', 0)"> <a class="nav-link {{ !empty($overviewTab) && $overviewTab == 'document' ? 'active' : '' }}" data-toggle="tab" href="#content_document" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Documents ({{$documentcount}})</span></a> </li>
                            <li id="tab_crm" class="nav-item" onclick="TAB.select('crm', '{{route('quoterequestlist', $customers->customId)}}', 0)"> <a class="nav-link {{ !empty($overviewTab) && $overviewTab == 'crm' ? 'active' : '' }}" data-toggle="tab" href="#content_crm" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Requests</span></a> </li>
        <li id="tab_log" class="nav-item" onclick="TAB.select('log', '{{route('customerlogdata', $customers->customId)}}', 0)"> <a class="nav-link {{ !empty($overviewTab) && $overviewTab == 'log' ? 'active' : '' }}" data-toggle="tab" href="#content_log" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Log</span></a> </li>
        </ul>
        

    </div>
</div>
<!--TAB CONTENT AREA-->
<div class="row card">

        <div id="content_overview" class="tabcontent col-md-12 card-body">
            <div class="row">
  
                
                
                <div id="main-content" class="col-md-7">
                    <div id="panel-customer_overview" class="panel panel-default open">
                        <div class="panel-heading">
            @if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || Auth::user()->id == $customers->customer_management_user || Auth::user()->id == $customers->created_user)                
            <ul class="panel-actions list-inline pull-right">
               
                <li ><a href="{{ route('editcustomer',$customers->customId)}}"><span class="fas fa-edit text-blue" data-toggle="tooltip" title="" data-original-title="Edit customer"></span></a></li>

<!--                <li><div class="btn-group"><button type="button"><span class="icon-settings"></span><span class="icon-arrow-down"></span><span class="icon-active"></span></button>
                        <ul id="settings-menu" class="dropdown-menu" role="menu">
                            <li><a >Merge customer</a></li>
                        </ul>
                    </div>
                </li>-->
            </ul>
              @endif              
                            <h3 class="panel-title">Customer info</h3></div>
            <div id="customer_overview" class="panel-collapse panel-body">
                <table class="info-table">
                    <tbody>
                        <tr><td>Customer type:</td><td>{{ ($customers->type ==1) ? 'Company' : 'Individual' }}</td></tr>
                        <tr><td>Name:</td><td>{{$customers->customerName}}</td></tr>
                        <tr><td>ID code / reg no:</td><td>{{$customers->id_code}}</td></tr>
                        <tr><td>Customer code:</td><td>{{$customers->customer_code}}</td></tr>
                        <tr class="subtitle"><th colspan="2">Contact info</th></tr>
                        <tr><td>E-mail address:</td><td><a>{{$customers->email}}</a></td></tr>
                        <tr><td>Phone:</td><td class="phoneNumber">{{$customers->customerPhone}}</td></tr>
                        <tr><td>Mobile phone:</td><td class="phoneNumber">{{$customers->mobile}}</td></tr>
                        <tr><td>Preferred communication channel:</td><td>{{$customers->prefered_communication_type}}</td></tr>
                        <tr class="subtitle"><th colspan="2">Customer management</th></tr>
                        <tr><td>Account manager:</td><td><span class="person-popover" data-toggle="popover" data-title="Dbroker company" data-content="<div class='person-popover-wrapper'><table class='person-popover-table'><td class='image'><span class='icon-profile'></span></td><td class='info'>E-mail address: <a href='mailto:y.alkashef@dbroker.com.sa'>y.alkashef@dbroker.com.sa</a><br/>Mobile phone: 0590003193</td></table></div>" data-original-title="" title="">{{$customers->accountManager}}</span></td></tr>
                        <tr><td>Customer group:</td><td>{{$customers->customer_group}}</td></tr>
                        <tr><td>Language:</td><td>english</td></tr>
                    </tbody>
                </table>
            </div></div>
            <div id="panel-customer_address" class="panel panel-default open">
                <div class="panel-heading">
                    <ul class="panel-actions list-inline pull-right">
                         <li ><span class="fas fa-plus text-blue dpib_add_contact_address_more" data-toggle="tooltip"  data-url="{{ route('addcustomeraddress',$customers->customId)}}" title="" data-original-title="Add address"></span></li>
                        
                    </ul>
<h3 class="panel-title">Customer addresses</h3>
                </div>
<div id="customer_address" class="panel-collapse panel-body">
    
    
    @if(count($addressDetails) >0)
            <table class="table table-bordered table-striped table-hovered color-table">
                <thead>
                   <tr> <th>ADDRESS</th><th class="nowrap">MODIFIED </th><th class="nowrap"> </th></tr> 
                    
                </thead>
                <tbody>
                    
                    
                    @foreach($addressDetails as $address) 
                    <tr>
                        
                        <td>{{ $address->building_no  }} {{ $address->	street_name  }} {{ $address->	district_name  }}<br />
                            {{ $address->city_name  }}  {{ $address->zip_code  }} {{ $address->additional_no  }}  <br />
                            {{ $address->unit_no  }}
                        </td>
                        <td>{{ $address->modifies_at  }} </td>
                        <td class="nowrap iconactions"><a  class="dpib_editcontactaddress" data-url="{{route('editcontactaddress',[$address->customer_id,$address->id])}}"><span class="fas fa-edit text-blue mr-right" data-toggle="tooltip" title="" data-original-title="Edit a contact address"></span></a><a class="dpib_deletecontactaddress" data-url="{{route('deletecontactaddressconfirm',[$address->customer_id,$address->id])}}"><span class="fas fa-archive text-blue" data-toggle="tooltip" title="" data-original-title="Delete contact person"></span></a></td>
                    </tr>
                     @endforeach

                        
                </tbody></table>
            @endif

   </div>
</div>

<div id="panel-customer_contact" class="panel panel-default open">
    <div class="panel-heading">
                                                                      <ul class="panel-actions list-inline pull-right">
                                                                          <li ><span class="fas fa-plus text-blue dpib_add_contact_person_more" data-toggle="tooltip" title="" data-original-title="Add a contact person"></span></li>
                                                                      </ul>
        <h3 class="panel-title">Contact persons</h3></div>
        <div id="customer_contact" class="panel-collapse panel-body">
            @if(count($contactpersonDetails) >0)
            <table class="table table-bordered table-striped table-hovered">
                <thead>
                   <tr> <th>PERSON</th><th>CONTACTS</th><th class="nowrap"> </th><th class="nowrap"> </th></tr> 
                    
                </thead>
                <tbody>
                    
                    
                    @foreach($contactpersonDetails as $contactperson) 
                    <tr>
                        <td>{{ $contactperson->person_name  }}<br /><small>{{ $contactperson->person_title  }}</small></td>
                        <td>Email: <a href="mailto:{{ $contactperson->email  }}">{{ $contactperson->email  }} </a><br />
                            Mobile: {{ $contactperson->mobile_phone  }} <br />
                            Phone: {{ $contactperson->phone  }}
                        </td>
                        <td>{{ $contactperson->updated_date  }} </td>
                        <td class="nowrap iconactions"><a class="dpib_editcontactperson" data-url="{{route('editcontactperson',[$contactperson->customer_id,$contactperson->id])}}"><span class="fas fa-edit text-blue mr-right" data-toggle="tooltip" title="" data-original-title="Edit a contact person"></span></a><a class="dpib_deletecontactperson" data-url="{{route('deletecontactpersonconfirm',[$contactperson->customer_id,$contactperson->id])}}"><span class="fas fa-archive text-blue" data-toggle="tooltip" title="" data-original-title="Delete contact person"></span></a></td>
                    </tr>
                     @endforeach

                        
                </tbody></table>
            @endif
        </div>
  </div>
<div id="panel-related_customer" class="panel panel-default open">
    <div class="panel-heading">
                                                                      <ul class="panel-actions list-inline pull-right">
                                                                          <li ><span class="fas fa-plus text-blue dpib_connect_customers" data-toggle="tooltip" title="" data-original-title="Connect customer" data-url="{{route('addcontactconnection',[$customers->customId])}}" ></span></li>
                                                                      </ul>
        <h3 class="panel-title">Related customers</h3></div>
        <div id="customer_contact" class="panel-collapse panel-body">
            @if(count($customerRelationdetails) >0)
            <table class="table table-bordered table-striped table-hovered">
                <thead>
                   <tr> <th>RELATED CUSTOMER</th><th>RELATION</th><th class="nowrap"> </th></tr> 
                    
                </thead>
                <tbody>
                    
                    
                    @foreach($customerRelationdetails as $relationdetails) 
                    <tr>
                        <td><a href="{{route('customeroverview',[$relationdetails->related_customer_id])}}"   target="_blank">{{ $relationdetails->name  }}</a></td>
                        <td> {{$relationdetails->relation_type}}</td>
                       
                        <td class="nowrap iconactions"><a  class="dpib_connect_customers_edit" data-url="{{route('editconnectiondetailsform',[$customers->customId, $relationdetails->mainId])}}"><span class="fas fa-edit text-blue mr-right" data-toggle="tooltip" title="" data-original-title="Edit a connection details"></span></a><a class="dpib_deletecustomerconnection" data-url="{{route('deletecustomerconnection',[$contactperson->customer_id,$relationdetails->mainId])}}"><span class="fas fa-archive text-blue" data-toggle="tooltip" title="" data-original-title="Delete customer connection"></span></a></td>
                    </tr>
                     @endforeach

                        
                </tbody></table>
            @endif
        </div>
  </div>



                
 </div>
                              <aside class="col-4 row">
                    <div id="panel-customer_balance" class="panel panel-default open col-12">
                        <div class="panel-heading"><h3 class="panel-title">Customer balance</h3></div>
                        <div id="customer_balance" class="panel-collapse panel-body"><div style="padding: 10px;">0.00</div></div>
                            
                    </div>
                    <div id="panel-customer_profile" class="panel panel-default open col-12">
                        <div class="panel-heading col-12">
                            <h3 class="panel-title">Policies</h3>
                        </div>
                        <div id="customer_profile" class="panel-collapse panel-body col-12">
                            @if(count($policyAmountDetails) > 0)
                            <table class="table table-sm table-bordered" style="font-size:smaller;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Gross premium</th>
                                        <th>Customer payable</th>
                                        <th>Comm.</th>
                                    </tr>
                                </thead>
                                <tbody>
                            
                                @foreach($policyAmountDetails as $key => $amountDetails)
                                <tr><td class="nowrap" style="color: #000;font-weight: bold">{{$key}}:</td>
                                    <td class="nowrap"><span style="color: #000;font-weight: bold"><b>{{(!empty($amountDetails)) ? number_format($amountDetails->grossAmount,2, '.', ',') :0.00  }}</b></span></td>
                                        <td class="nowrap"><span style="color: #000;font-weight: bold"><b>{{(!empty($amountDetails)) ? number_format($amountDetails->grossAmount + $amountDetails->endorsementAmount+$vatDetails[$key]->installmentVat+$amountDetails->additionAmount,2, '.', ',')  :0.00  }}</b></span></td>
                                        <td class="nowrap"><span style="color: #000;font-weight: bold"><b>{{(!empty($amountDetails)) ? number_format($amountDetails->commision ,2, '.', ',') :0.00  }}</b></span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            </table>
                             @endif
                            

                        </div>
                    </div>
                    <div id="panel-customer_salesopportunities" class="panel panel-default open col-12" style="display:none">
                        <div class="panel-heading"><h3 class="panel-title">Sales opportunities</h3></div>
                        <div id="customer_salesopportunities" class="panel-collapse panel-body">

                        </div>
                    </div>
                    <div id="panel-entries-block" class="panel panel-default open col-12" style="display:none">
                        <div class="panel-heading">
                            <ul class="panel-actions list-inline pull-right">
                                <li><span class="fas fa-plus text-blue" data-toggle="tooltip" title="" data-original-title="Add a task"></span></li>
                            </ul><h3 class="panel-title">Tasks </h3></div>
                            <div id="entries-block" class="panel-collapse panel-body">

                            </div>
                    </div>
                    <div id="panel-customer_reminder" class="panel panel-default open col-12">
                        <div class="panel-heading">
                            <ul class="panel-actions list-inline pull-right">
                                <li ><span class="fas fa-plus text-blue dp_customer_note_add" data-toggle="tooltip" title="" data-original-title="Add"></span></li>
                            </ul><h3 class="panel-title">Notes</h3>
                        </div>
                        <div id="customer_reminder" class="panel-collapse panel-body">
                            <ul class="list-group list-notes" style="padding-left:29px">
                                @if ($notes->count() >0)
                                 @foreach ($notes as $note)
                                     <li style="padding-left: 8px">{{ $note->comment }}</li> 
                                  
                                 @endforeach
                                @else
                                 <li style="padding-left: 8px">No notes.</li>
                                @endif
                                
    <!-- ko if: _.size(notes()) == 0 -->
    
    <!-- /ko -->

    <!-- ko foreach:notes--><!-- /ko -->
</ul>
<div id="add_note_template_1" style="display:none;">
    <textarea class="note_add_entry full-width" name="note_add_entry" rows="10" wrap="soft" default_setting='{{route('newnote', $customers->customId)}}' overview_url="{{route('customeroverview', $customers->customId)}}" style="width:100%"></textarea>
</div>
                            
                            
                            
                            
<div id="add_note_template_2" style="display:none;">
    <textarea class="note_add_entry full-width" name="note_add_entry" rows="10" wrap="soft" data-bind="value: noteAddEntry"></textarea>
    <div class="well" data-bind="fileDrag: multiFileData">
        <div class="form-group row">
            <div class="col-md-6">
                <!-- ko foreach: {data: multiFileData().fileArray, as: 'file'} -->
                <span class="img-rounded thumb" data-bind="css: window.FileIcons.getClassWithColor(file.name), visible: file, text: file.name"></span><br>
                <!-- /ko -->
                <div data-bind="ifnot: multiFileData().fileArray().length">
                    <label class="drag-label">Drag files here</label>
                </div>
            </div>
            <div class="col-md-6">
                
            </div>
        </div>
    </div>
</div>

</div>
                        
</div>
                    
                </aside>
 </div>
 </div>
        
        
        
        
        
        
        
        
    <div id="content_policy" class="tabcontent col-12" rel="{{route('policylisting', $customers->customId)}}" style="display:none">
                Policy display area
                
            </div>

        <div id="content_quotes" class="tabcontent col-12" rel="" style="display:none">
            
            Offer display area
        </div>
        <div id="content_invoice" class="tabcontent col-12" rel="" style="display:none">
            
            invoice display area
        </div>
        <div id="content_document" class="tabcontent col-12" rel="" style="display:none">
            
           document display area 
        </div>
        
        <div id="content_crm" class="tabcontent col-12"  style="display:none">
            crm display area
        </div>

        <div id="content_log" class="tabcontent col-12"  style="display:none;">
           log display area 
            
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
<script src="{{ asset('js/dibcustom/dib-customer-overview.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/dibcustom/dib-quote-request.js') }}" type="text/javascript"></script>
        


@endsection
@section('pagescript')
<script>
    var customerOverview ='';
   $(function() {
     //dp_customer_note_add 
     
     @if (Session::get('overviewtabselected'))
         var tabValue = "{!! Session::get('overviewtabselected') !!}";
         $('#tab_'+tabValue).find('a').trigger('click');
     @endif
     var options ={'contactpersonAddUrl': '{!! route("addcontactperson",$customers->customId) !!}'}
     customerOverview = new Customeroverview(options);
     customerOverview.initialSettings();
     
     var options = {};
      var quoteRequest = new DibQuoterequest(options);
      quoteRequest.initialSetting();
   }); 

   $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
$($.fn.dataTable.tables(true)).DataTable()
.columns.adjust()
.responsive.recalc();
});

   
</script>
@endsection