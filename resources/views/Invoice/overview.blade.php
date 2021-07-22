@extends('layouts.elite_fullwidth',['sidemenustatus' => array(),'countDetails'=>array(),'notificationCount'=>array() ] )
@section('content')


<!--                  content here  -->
<!--TAB AREA-->
<div class="row">
 
    
    
        <div class="col-md-12 card">
        
        
        <ul class="nav nav-tabs customtab card-body" role="tablist">
                               <li id="tab_overview" class="nav-item" onclick="TAB.select('overview', null, 1)"> <a class="nav-link {{ empty($overviewTab) || $overviewTab == 'overview' ? 'active' : '' }}" data-toggle="tab" href="#content_overview" role="tab" aria-selected="true"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Overview</span></a> </li>
                                <li id="tab_payment" class="nav-item" onclick="TAB.select('payment', '{{route('invoicepaymentlist',$invoicedetails[0]->invoiceId)}}', 1)"> <a class="nav-link {{ !empty($overviewTab) && $overviewTab == 'payment' ? 'active' : '' }}" data-toggle="tab" href="#content_payment" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Payments</span></a> </li>
                                <li id="tab_debt" class="nav-item" onclick="TAB.select('debt', '{{route('debtmanagement',$invoicedetails[0]->invoiceId)}}', 1)"> <a class="nav-link {{ !empty($overviewTab) && $overviewTab == 'debt' ? 'active' : '' }}" data-toggle="tab" href="#content_debt" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Debt management</span></a> </li>
                                <li id="tab_log" class="nav-item" onclick="TAB.select('log', '{{route('invoicelog',$invoicedetails[0]->invoiceId)}}', 0)"> <a class="nav-link {{ !empty($overviewTab) && $overviewTab == 'log' ? 'active' : '' }}" data-toggle="tab" href="#content_log" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Log</span></a> </li>
        </ul>
        

    </div>
    
    
    
    
</div>
<!--TAB CONTENT AREA-->
<div class="row card">
    
        <div id="content_overview" class="tabcontent col-md-12 card-body">
            <div class="row">
                
                <div id="main-content" class="col-md-8">
                    <div id="panel-customer_overview" class="panel panel-default open"><div class="panel-heading">
                            <ul class="panel-actions list-inline pull-right">

                                <li ><a class='dpib_invoice_edit'><span class="fas fa-edit text-blue" data-toggle="tooltip" title="" data-original-title="Edit invoice"></span></a></li>


                            </ul><h3 class="panel-title">Invoice info</h3></div>
                        <div id="customer_overview" class="panel-collapse panel-body">
                            <table class="info-table">
                                <tbody>
                                    <tr><td> Invoice number:</td><td>{{$invoicedetails[0]->invoiceId}}</td></tr>
                                    <tr><td>Customer:</td><td><a href='{{route('customeroverview',$invoicedetails[0]->customer_id)}}'>{{ $invoicedetails[0]->customerName}}</a> </td></tr>

                                    <tr><td>Date:</td><td>{{date('d.m.Y', strtotime($invoicedetails[0]->generated_date)) }}</td></tr>


                                    <tr><td>Invoice sum:</td><td>{{number_format($invoicedetails[0]->invoice_sum, 2, '.', ',') }}  SAR </td></tr>
                                    <tr><td>Due date:</td><td class="phoneNumber">{{date('d.m.Y', strtotime($invoicedetails[0]->invoice_due_date)) }} </td></tr>
                                    <tr><td>Policy number:</td><td class="phoneNumber"> <span class='text-success' style='font-weight: bold'> <a href='{{route('policyoverview',$invoicedetails[0]->policy_id)}}'>{{$invoicedetails[0]->policy_number}}</a></span></td></tr>
                                    <tr><td>Status:</td><td>
                                            @if($invoicedetails[0]->paid_status ==1)
                                            <span class='text-success' style='font-weight: bold'> {{$invoicedetails[0]->invoiceStatusString }} </span>
                                            @else
                                            <span class='text-danger' style='font-weight: bold'> {{$invoicedetails[0]->invoiceStatusString }} </span>
                                            @endif
                                            
                                            
                                        </td></tr>



                                </tbody>
                            </table>
                        </div></div>





                </div>
                <aside class="col-3">
                    <div style="background: #f8f8f8; border: 1px #cccccc solid; margin-bottom: 20px;" class="rounded"><a target="_blank" href="{!! route('getfiledownload',[$invoicedetails[0]->customer_id,'invoice',0,$invoicedetails[0]->file_name,$invoicedetails[0]->policy_id]) !!}" style="display: block; padding: 40px 0px 36px 40px;"><span class="fas fa-file-pdf text-blue" style='font-size: xx-large !important ;'></span><span style="position:absolute;margin-left:10px;">Open invoice PDF</span></a>
                    </div>
                    <div id="panel-invoice_payment" class="panel panel-default open">
                        <div class="panel-heading"><ul class="panel-actions list-inline pull-right"><li class='dpib_invoice_payment'><span class="fas fa-plus text-blue" data-toggle="tooltip" title="" data-original-title="Add payment"></span></li></ul><h3 class="panel-title">Payment(s)</h3></div>
                        <div id="invoice_payment" class="panel-collapse panel-body">
                            @if($paymentDetails !==null) 
                           <table class="info-table" width='100%'>
                               <tbody>
                                   <tr><td style="width: 50%">Payments made:</td><td style="width: 50%"> {{ $paymentDetails->count }}</td></tr>
                                   <tr><td style="width: 50%">Last payment:</td><td style="width: 50%">{{date('d.m.Y', strtotime($paymentDetails->lastPayment)) }}</td></tr>
                                   <tr><td style="width: 50%">Paid:</td><td style="width: 50%"><span style="color: #008000"><b> {{ number_format($paymentDetails->paidsum, 2, '.', ',') }} SAR</b></span></td></tr>
                               </tbody>
                           </table>
                            @else
                            <table class="info-table"><tbody><tr><td class="no-data">No payments to this invoice.</td></tr></tbody></table>
                            @endif
                            
                        </div></div>
                </aside>
            </div>
            <div class="col-md-12">
                <div id="panel-customer_address" class="panel panel-default open">
                    <div class="panel-heading">
                        <h3 class="panel-title">Linked installments</h3>
                    </div>
                    <div id="customer_address" class="panel-collapse panel-body">


                        @if(count($invoicedetails) >0)
                        <table class="display nowrap table table-hover table-striped table-bordered dataTable" width='100%'>
                            <thead>
                                <tr> <th class="wrap">Description</th><th>Amount</th><th class="nowrap">Vat(%) </th><th class="nowrap">Total</th></tr> 
                                @foreach($invoicedetails as $invoicedetail)
                                <tr>
                                    <td class="nowrap">{{$invoicedetail->description}}</td>
                                    <td>{{ number_format($invoicedetail->amount, 2, '.', ',') }} SAR  </td>
                                    <td>{{ number_format($invoicedetail->vat_amount, 2, '.', ',')}}SAR</td>
                                    <td>{{ number_format($invoicedetail->vat_amount+$invoicedetail->amount, 2, '.', ',') }} SAR</td>
                                </tr>


                                @endforeach
                            </thead>
                            <tbody>





                            </tbody></table>
                        @endif

                    </div>
                </div>

            </div>


        </div>




        <div id="content_payment" class="tabcontent col-12" rel='{{route('invoicepaymentlist',$invoicedetails[0]->invoiceId)}}' style="display:none">


        </div>
        <div id="content_debt" class="tabcontent col-12" rel='{{route('debtmanagement',$invoicedetails[0]->invoiceId)}}' style="display:none">


        </div>
    
    
        <div id="content_log" class="tabcontent col-12" rel='{{route('invoicelog',$invoicedetails[0]->invoiceId)}}' style="display:none">


        </div>


    </div>



<script id='invoice_edit_template' type='text/template'>
        
        {{ Form::open(array('route' => array('editinvoicedetails',$invoicedetails[0]->invoiceId), 'name' => 'form_invoice_edit','id'=>'form_invoice_edit','files'=>'true' )) }}
    @csrf   
   
<div class="dialogform">
    <table class="insly_dialogform">
    <tbody>
    <tr style="display:none;">
                    <td></td>
                <td>
                                                <input type="hidden" id="invoiceId" name="claimanttype" value="">
                                    </td>
    </tr>
    <tr id="field_policy_date_start" class="field">
                            <td class="" style="width: 20%"><div class="label" style="width:100%"><span class="text-danger icon-asterix"></span><span class="title">Invoice date</span></div></td>
                            <td><div class="element">
                                <input type="date" id="invoice_date" name="invoice_date" value="<%- moment(invoiceData.generated_date).format('DD-MM-YYYY') %>" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important" onchange="FORM.checkPastDate('#policy_date_start');">

                                

                                <div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_date_start_comment"></div></div></div></td>
                        </tr>
                        <tr id="field_policy_date_end" class="field policy_openended0">
                            <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Due date</span></div></td>
                            <td>
                                <div class="element">
                                    <input type="date" id="invoice_due_date" name="invoice_due_date" value="<%- moment(invoiceData.invoice_due_date).format('DD-MM-YYYY')  %>" maxlength="10" autocomplete="off" class="datefield" style="margin-right: 0px !important">
                                        
                                        <div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_date_end_comment"></div></div></div></td>
                        </tr>
  

    </tbody>
    </table>
    </div>
{{ Form::close() }}
</script>

<script id='invoice_payment_template' type='text/template'>
        
        {{ Form::open(array('route' => array('saveinvoicepayment',$invoicedetails[0]->invoiceId), 'name' => 'form_invoice_payment','id'=>'form_invoice_payment','files'=>'true' )) }}
    @csrf   
   
<div class="dialogform">
    <table class="insly_dialogform">
    <tbody>

    
                         <tr id="field_paymentmode" class="field">
                            <td class="" style="width: 20%"><div class="label" style="width:100%"><span class="text-danger icon-asterix"></span><span class="title">Payment type</span></div></td>
                            <td><div class="element">
                                
                                {{ Form::select('paymentmode',  $paymentMode, '',array('name'=>'paymentmode','id' =>'paymentmode','required'=>'required','class'=>'required','error-message' =>"Insurance company field is mandatory"))}}        
                                        </div>
                            </td>
                        </tr>
                        <tr id="field_policy_payment_date" class="field">
                            <td class="" style="width: 25%"><div class="label" style="width:100%"><span class="text-danger icon-asterix"></span><span class="title">Payment date</span></div></td>
                            <td><div class="element"><input type="date" id="payment_date" name="payment_date" value="{{date('Y-m-d')}}" maxlength="10" autocomplete="off" class="datefield" style="margin-right: 0px !important" onchange="FORM.checkPastDate('#policy_date_start');"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_date_start_comment"></div></div></div>
                            </td>
                        </tr>
                        
                        <tr id="field_policy_paymentsum" class="field">
                            <td class="" style="width: 25%"><div class="label" style="width:100%"><span class="text-danger icon-asterix"></span><span class="title">Payment sum</span></div></td>
                            <td>
                                <div class="element">
                                    <input type="text" id="payment_sum" name="payment_sum" value="" maxlength="10" autocomplete="off"  style="margin-right: 0px !important">
                                        
                                        <div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_payment_sum"></div></div></div>
                            </td>
                        </tr>
                        
                          <tr id="field_policy_payername" class="field">
                            <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Payer name</span></div></td>
                            <td>
                                <div class="element">
                                    <input type="text" id="payer_name" name="payer_name" value="" maxlength="10" autocomplete="off"  style="margin-right: 0px !important">
                                        
                                        <div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_payer_name"></div></div></div>
                            </td>
                        </tr>
                        
                        <tr id="field_policy_payment_ref" class="field">
                            <td class=""><div class="label"><span class="title">Reference no:</span></div></td>
                            <td>
                                <div class="element">
                                    <input type="text" id="payment_refernce_number" name="payment_reference_number" value="" maxlength="10" autocomplete="off"  style="margin-right: 0px !important">
                                        
                                        <div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_payment_ref"></div></div></div>
                            </td>
                        </tr>
                        
                        <tr id="field_policy_payment_document" class="field">
                            <td class=""><div class="label"><span class="title">Document</span></div></td>
                            <td>
                                <div class="element">
                                     <input type="file" id="payment_document" name="payment_document" value="" autocomplete="off" maxlength="255"  required error-message="Contact person name is mandatory" >
                                        
                                        <div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_payment_ref"></div></div></div>
                            </td>
                        </tr>
                        
                        
                        
  

    </tbody>
    </table>
    </div>
{{ Form::close() }}
</script>

@endsection
@section('customcss')
<link rel="stylesheet" type="text/css" href=" {{ asset('elitedesign/assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css') }} ">
<link rel="stylesheet" type="text/css" href="{{ asset('elitedesign/assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css') }}"> 


@endsection


@section('pagescript')
<script src="{{ asset('elitedesign/assets/node_modules/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('elitedesign/assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/global/datatable/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/global/datatable/datetime.js') }}" type="text/javascript"></script>

<script>
var invoiceData = @json($invoicedetails[0]);
$(function(){

 $(document).off('click','.dpib_invoice_edit');
    $(document).on('click','.dpib_invoice_edit',function(){
        var template = _.template($("#invoice_edit_template").html());
        var data = {invoiceData:invoiceData};
          var result = template(data);
            $("#db_invoice_edit_popup").remove();
                $('body').append('<div id="db_invoice_edit_popup" title="Edit invoice" class="col-lg-12" >' + result + '</div>');
                var dialogElement = $("#db_invoice_edit_popup");
        dialogElement.dialog({
                                                             width: 600,
                                                             resizable: false,
                                                             bgiframe: true,
                                                            modal: true,
                                                            buttons: {
                                                                    "Update": {
                                                                      class: "btn waves-effect waves-light btn-rounded btn-success",
                                                                      text: "Update",
                                                                      click: function() {
                                                                        DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));

                                                                        $("form#form_invoice_edit").submit();

                                                                      }
                                                                    },
                                                                    "cancel": {
                                                                      class: "btn waves-effect waves-light btn-rounded btn-danger",
                                                                       text: "Cancel",
                                                                      click: function() {
                                                                        dialogElement.dialog('close');
                                                                        dialogElement.remove();
                                                                      }
                                                                    }

                                                            },
                                                            open:function() {
                                                                                                             

                                                            
                                                          
                                                            }
});
                 
       
    });
    

    
    $(document).off('click','.dpib_invoice_payment');
    $(document).on('click','.dpib_invoice_payment',function(){
        var template = _.template($("#invoice_payment_template").html());
      
          var result = template();
            $("#db_invoice_payment_popup").remove();
                $('body').append('<div id="db_invoice_payment_popup" title="Add payment" class="col-lg-12" >' + result + '</div>');
                var dialogElement = $("#db_invoice_payment_popup");
        dialogElement.dialog({
                                                             width: 600,
                                                             resizable: false,
                                                             bgiframe: true,
                                                            modal: true,
                                                            buttons: {
                                                                    "Add": {
                                                                       class: "btn waves-effect waves-light btn-rounded btn-success",
                                                                        text: "Add",
                                                                      click: function() {
                                                                        DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));

                                                                        $("form#form_invoice_payment").submit();

                                                                      }
                                                                    },
                                                                    "cancel": {
                                                                      class: "btn waves-effect waves-light btn-rounded btn-danger",
                                                                       text: "Cancel",
                                                                      click: function() {
                                                                        dialogElement.dialog('close');
                                                                        dialogElement.remove();
                                                                      }
                                                                    }

                                                            },
                                                            open:function() {
                                                                                                             

                                                            
                                                          
                                                            }
});
                 
       
    });
    
    
    
});
</script>


@endsection