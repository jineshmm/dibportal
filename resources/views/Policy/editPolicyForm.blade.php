@extends('layouts.elite_fullwidth',['sidemenustatus' => array(),'countDetails'=>array(),'notificationCount'=>array() ] )
@section('headtitle')
 {{$headTitle}}
@endsection

@section('content')
{{ Form::open(array('route' => 'updatepolicyinfo', 'name' => 'savepolicyForm','id'=>'savepolicyForm','class'=>'dpib-custom-form') ) }}

@csrf 
<div id="policy_add_form" class="row">
    <div class="col-md-12">



        <div class="panel panel-default panel-dark">
            <div class="panel-heading" id="fieldgroup_title_customer">
                <!-- <div class="blocktitle"> -->
                <h3 class="panel-title">Customer</h3>
                <!-- </div> -->
            </div>
            <div class="panel-body" id="fieldgroup_customer">
                <table class="insly-form">
                    <tbody>
                        <tr id="field_customer_oid" class="field">
                            <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Customer</span></div></td>
                            <td>
                                <div class="element">
                                    <input type='hidden' name='policy_id' value='{{$policyData->mainId}}' >
                                    {{ Form::select('customer_id',  $allCustomers, $policyData->customer_id,array('name'=>'customer_id','id' =>'customer_id','required'=>'required','class'=>'required form-control','error-message' =>"Insurance company field is mandatory"))}}
                                </div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="panel panel-default panel-dark">
            <div class="panel-heading" id="fieldgroup_title_policy">
                <!-- <div class="blocktitle"> -->
                <h3 class="panel-title">Policy info</h3>
                <!-- </div> -->
            </div>
            <div class="panel-body" id="fieldgroup_policy">
                <table class="insly-form">
                    <tbody>
                        
                             <tr id="field_policy_date_issue" class="field">
                            <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Policy number</span></div></td>
                            <td><div class="element"><input type="text" class="form-control" id="policy_number" name="policy_number" value="{{$policyData->policy_number}}" maxlength="10" autocomplete="off"  style="margin-right: 0px !important" ></div></td>
                           </tr>
                        <tr id="field_policy_type" class="field ">
                            
                       
                           
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger "></span>
                                    <span class="title">Policy type</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <select id="policy_type" name="policy_type" class="form-control" >
                                        <option value="1" {{ isset($policyData->policy_type) ? (($policyData->policy_type=="0")? 'selected':''): '' }}>General</option>

                                        <option value="2" {{ isset($policyData->policy_type) ? (($policyData->policy_type=="1")? 'selected':''): '' }}>Medical</option>

                                        <option value="3" {{ isset($policyData->policy_type) ? (($policyData->policy_type=="2")? 'selected':''): '' }}>Motor</option>

                                       

                                    </select>


                                </div>
                            </td>
                        </tr>
                        <tr id="field_policy_insurer" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger icon-asterix"></span>
                                    <span class="title">Insurer</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">


                                    {{ Form::select('insurance_company',  $insurancecompany, $policyData->insurer_id,array('name'=>'policy_insurer','id' =>'policy_insurer','required'=>'required','class'=>'required form-control','error-message' =>"Insurance company field is mandatory" ))}}  


                                </div>
                            </td>
                        </tr>
                        <tr id="field_policy_coinsurance" class="field " style='display:none'>
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger "></span>
                                    <span class="title">Co-insurance</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <input type="hidden" name="policy_coinsurance" value="">
                                    <label class="label-without-margins" style="cursor: pointer; color: rgb(87, 87, 87);">
                                        <input type="checkbox" id="policy_coinsurance" name="policy_coinsurance" value="1" class="form-control">
                                        <span class="icon-check-empty"></span>
                                        yes
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr id="field_policy_date_issue" class="field">
                            <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Issue date</span></div></td>
                            <td><div class="element"><input type="date" id="policy_date_issue" name="policy_date_issue" value="{{ date('Y-m-d', strtotime($policyData->issue_date)) }}" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important" onchange="FORM.checkPastDate('#policy_date_issue');"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_date_issue_comment"></div></div></div></td>
                        </tr>
                        <tr id="field_policy_date_start" class="field">
                            <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Start date</span></div></td>
                            <td><div class="element"><input type="date" id="policy_date_start" name="policy_date_start" value="{{ date('Y-m-d', strtotime($policyData->start_date)) }}" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important" onchange="FORM.checkPastDate('#policy_date_start');"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_date_start_comment"></div></div></div></td>
                        </tr>
                        <tr id="field_policy_date_end" class="field policy_openended0">
                            <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">End date</span></div></td>
                            <td><div class="element"><input type="date" id="policy_date_end" name="policy_date_end" value="{{ date('Y-m-d', strtotime($policyData->end_date)) }}" maxlength="10" autocomplete="off" class="datefield form-control col-3" style="margin-right: 0px !important" onchange="FORM.checkPastDate('#policy_date_end');"><small style="margin-left: 49px;">&nbsp; &nbsp; &nbsp; » &nbsp;<a onclick="POLICY.setEndDate(12, 0)">1 year</a></small><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_date_end_comment"></div></div></div></td>
                        </tr>							</tbody>
                </table>
            </div>
        </div>


        <div class="panel panel-default panel-dark">
            <div class="panel-heading" id="fieldgroup_title_sales">
                <!-- <div class="blocktitle"> -->
                <h3 class="panel-title">Sales</h3>
                <!-- </div> -->
            </div>
            <div class="panel-body" id="fieldgroup_sales">
                <table class="insly-form">
                    <tbody>
                        <tr id="field_policy_salestype" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger icon-asterix"></span>
                                    <span class="title">Sales type</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <select id="policy_salestype" name="policy_salestype" class="form-control">
                                        <option value="" >--- not set ---</option>
                                        <option value="1" {{ isset($policyData->sales_type) ? (($policyData->sales_type=="1")? 'selected':''): '' }}>new sales</option>

                                        <option value="2" {{ isset($policyData->sales_type) ? (($policyData->sales_type=="2")? 'selected':''): '' }}>renewal</option>

                                        <option value="3" {{ isset($policyData->sales_type) ? (($policyData->sales_type=="3")? 'selected':''): '' }}>manual renewal</option>

                                    </select>


                                </div>
                            </td>
                        </tr>
                        <tr id="field_previous_policy_oid" class="field" style='display:none'>
                            <td class=""><div class="label"><span class="title">Previous policy</span></div></td>
                            <td><div class="element"><input type="hidden" id="previous_policy_oid" name="previous_policy_oid" value="0"><input type="hidden" id="previous_policy_oid_updaterenewal" name="previous_policy_oid_updaterenewal" value=""><div id="previous_policy_oid_display" style="display: block; float: left; padding: 7px 0px 2px 0px"><i>none</i></div><div class="pull-right"><button type="button" onclick="CHOOSEPOLICY.reset('previous_policy_oid', 'none')">none</button><button type="button" onclick="CHOOSEPOLICY.openDialog('previous_policy_oid')">Choose policy</button></div></div></td></tr>							</tbody>
                </table>
            </div>
        </div>


        <div class="panel panel-default panel-dark">
            <div class="panel-heading" id="fieldgroup_title_renewal">
                <!-- <div class="blocktitle"> -->
                <h3 class="panel-title">Renewal</h3>
                <!-- </div> -->
            </div>
            <div class="panel-body" id="fieldgroup_renewal">
                <table class="insly-form">
                    <tbody>
                        <tr id="field_policy_renewalstatus" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger "></span>
                                    <span class="title">Renewable</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <select id="policy_renewalstatus" name="policy_renewalstatus" data-default-value="0" class="form-control">
                                        <option value="0" {{ isset($policyData->renewal_status) ? (($policyData->renewal_status==0)? 'selected="selected"':''): '' }}>No</option>

                                        <option value="1" {{ isset($policyData->renewal_status) ? (($policyData->renewal_status==1)? 'selected':''): '' }}>Yes</option>

                                    </select>


                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>




        <div class="buttonbar pull-right">
            <div class="submit"><button type="submit" id="submit_save" name="submit_save" class="submit_policy btn waves-effect waves-light btn-rounded btn-success" >Update</button><button type="button" id="submit_cancel" name="submit_cancel" onclick="FORM.cancel()" class='btn waves-effect waves-light btn-rounded btn-danger'>Cancel</button></div>
        </div>

    </div>
</div>
{{ Form::close() }}
@endsection
@section('customscript')
<script src="{{ asset('js/global/datatable/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/dibcustom/dib-policy-add.js') }}" type="text/javascript"></script>
@endsection

@section('pagescript')
<script>

                $(function () {

                    var dibpolicyAddObj = new DibPolicyAdd();
                    dibpolicyAddObj.initialSetting();


                });
</script>



@endsection
