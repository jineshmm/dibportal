@extends('layouts.elite_fullwidth',['sidemenustatus' => array(),'countDetails'=>array(),'notificationCount'=>array() ] )
@section('content')
{{ Form::open(array('route' => array('updateclaimdetails',$claimDetails->id), 'name' => 'saveclaimForm','id'=>'saveclaimForm','class'=>'dpib-custom-form') ) }}
<div id="policy_add_form" class="row">

    <div class="insly-form col-md-12">



        <div class="panel panel-default panel-dark">
            <div class="panel-heading" id="fieldgroup_title_customer">
                <!-- <div class="blocktitle"> -->
                <h3 class="panel-title">Customer info</h3>
                <!-- </div> -->
            </div>
            <div class="panel-body" id="fieldgroup_customer">
                <table class="insly-form">
                    <tbody>
                        <tr id="field_customer_oid" class="field">
                            <td class="">
                                <div class="label"><span class="text-danger icon-asterix"></span><span class="title">Customer</span></div>
                            </td>
                            <td><div class="element">{{ Form::select('customer_id',  $allCustomers, $claimDetails->customer_id,array('name'=>'customer_id','id' =>'customer_id','required'=>'required','class'=>'required form-control','error-message' =>"Insurance company field is mandatory"))}}</div></td></tr>							</tbody>
                </table>
            </div>
        </div>
        <div class="panel panel-default panel-dark">
            <div class="panel-heading" id="fieldgroup_title_policy">
                <!-- <div class="blocktitle"> -->
                <h3 class="panel-title">Policy information</h3>
                <!-- </div> -->
            </div>
            <div class="panel-body" id="fieldgroup_policy">
                <table class="insly-form">
                    <tbody>
                        <tr id="field_policy_oid" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="field-info icon-info"  data-original-title="" title=""></span>
                                    <span class="text-danger "></span>
                                    <span class="title">Policy</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                        <select id="complaint_policy" name="complaint_policy" selected_id="{{ isset($claimDetails->policy_id)  ? $claimDetails->policy_id:''}}" class='form-control'>

                                            <option value="" >--Select policy--</option>

                                        </select>
                                </div>
                            </td>
                        </tr>



                    </tbody>
                </table>
            </div>
        </div>


        <div class="panel panel-default panel-dark">
            <div class="panel-heading" id="fieldgroup_title_claim">
                <!-- <div class="blocktitle"> -->
                <h3 class="panel-title">Claim information</h3>
                <!-- </div> -->
            </div>
            <div class="panel-body" id="fieldgroup_claim">
                <table class="insly-form">
                    <tbody>
                        <tr id="field_claim_date_incident" class="field">
                            <td class="">
                                <div class="label"><span class="text-danger icon-asterix"></span><span class="title">Loss date and time</span></div>
                            </td>
                            <td>
                                <div class="element">
                                    <input type="datetime-local" id="claim_date_incident" name="claim_date_incident"   value="{{ !(empty($claimDetails->incident_date)) ? date('Y-m-d', strtotime($claimDetails->incident_date)).'T'.date('h:i', strtotime($claimDetails->incident_date)) :date('Y-m-d').'T'.date('h:i') }}" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important">
                                    
                                    <div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="claim_date_incident_comment"></div>
                                        
                                    </div></div>
                            </td>
                        </tr>
                        <tr id="field_claim_date_submit" class="field">
                            <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Submitted to broker</span></div></td>
                            <td><div class="element"><input type="date" id="claim_date_submit" name="claim_date_submit" value="{{ !(empty($claimDetails->submitted_broker_date)) ? date('Y-m-d', strtotime($claimDetails->submitted_broker_date)) :date('Y-m-d') }}" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="claim_date_submit_comment"></div></div></div>
                            </td>
                        </tr>
                        <tr id="field_claim_date_submitinsurer" class="field">
                            <td class=""><div class="label"><span class="title">Submitted to insurer</span></div></td>
                            <td><div class="element"><input type="date" id="claim_date_submitinsurer" name="claim_date_submitinsurer" value="{{ !(empty($claimDetails->submitted_insurer_date)) ? date('Y-m-d', strtotime($claimDetails->submitted_insurer_date)) :'' }}" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="claim_date_submitinsurer_comment"></div></div></div>
                            </td>
                        </tr>
                        <tr id="field_claim_date_settlement" class="field">
                            <td class=""><div class="label"><span class="title">Settlement date</span></div></td>
                            <td><div class="element"><input type="date" id="claim_date_settlement" name="claim_date_settlement" value="{{ !(empty($claimDetails->settlement_date)) ? date('Y-m-d', strtotime($claimDetails->settlement_date)) :'' }}" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="claim_date_settlement_comment"></div></div></div>
                            </td>
                        </tr>
                        <tr id="field_broker_person_oid" class="field">
                            <td class=""><div class="label full-height" style="height: 32px;"><span class="text-danger icon-asterix"></span><span class="title">Claim handler</span></div></td>
                            <td><div class="element">
                                    {{ Form::select('claim_handle_person_id',  $userData, (!(empty($claimDetails->claim_handler))) ? $claimDetails->claim_handler :'',array('name'=>'claim_handle_person_id','id' =>'claim_handle_person_id','required'=>'required','class'=>'required form-control','error-message' =>"Insurance company field is mandatory"))}}
                                </div>
                            </td>
                        </tr>
                              <tr id="field_prop_claim_insurace_claimnumber" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger "></span>
                                    <span class="title">Insurance claim number</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <input type="text" id="claim_insurace_claimnumber" name="claim_insurace_claimnumber" value="{{(!(empty($claimDetails->insurance_claim_number))) ? $claimDetails->insurance_claim_number :''}}" autocomplete="off" class='form-control'>
                                </div>
                            </td>
                        </tr>

                        							</tbody>
                </table>
            </div>
        </div>





        <div class="panel panel-default panel-dark">
            <div class="panel-heading" id="fieldgroup_title_loss">
                <!-- <div class="blocktitle"> -->
                <h3 class="panel-title">Loss information</h3>
                <!-- </div> -->
            </div>
            <div class="panel-body" id="fieldgroup_loss">
                <table class="insly-form">
                    <tbody>
                        <tr id="field_prop_claim_location" class="field ">
                            <td class="">
                                <div class="label full-height" style="height: 109px;">
                                    <span class="text-danger "></span>
                                    <span class="title">Location of loss or incident</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <textarea id="claim_location" name="claim_location" wrap="soft" rows="5" class='form-control'>{{(!(empty($claimDetails->location))) ? $claimDetails->location :''}}</textarea>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="panel panel-default panel-dark">
            <div class="panel-heading" id="fieldgroup_title_insurer">
                <!-- <div class="blocktitle"> -->
                <h3 class="panel-title">Insurer contacts</h3>
                <!-- </div> -->
            </div>
            <div class="panel-body" id="fieldgroup_insurer">
                <table class="insly-form">
                    <tbody>
                        <tr id="field_prop_claim_insurer_name" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger "></span>
                                    <span class="title">Insurer contact name</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <input type="text" id="claim_insurer_name" name="claim_insurer_name" value="{{(!(empty($claimDetails->insurer_contact_name))) ? $claimDetails->insurer_contact_name :''}}" autocomplete="off" class='form-control'>
                                </div>
                            </td>
                        </tr>
                        <tr id="field_prop_claim_insurer_email" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger "></span>
                                    <span class="title">Insurer contact e-mail</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <input type="text" id="claim_insurer_email" name="claim_insurer_email" value="{{(!(empty($claimDetails->insurer_contact_email))) ? $claimDetails->insurer_contact_email :''}}" autocomplete="off" class='form-control'>
                                </div>
                            </td>
                        </tr>
                        <tr id="field_prop_claim_insurer_phone" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger "></span>
                                    <span class="title">Insurer contact phone</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <input type="text" id="claim_insurer_phone" name="claim_insurer_phone" value="{{(!(empty($claimDetails->insurer_contact_phone))) ? $claimDetails->insurer_contact_phone :''}}" autocomplete="off" class='form-control'>
                                </div>
                            </td>
                        </tr>
                        <tr id="field_claim_id_insurer" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger "></span>
                                    <span class="title">Insurer reference ID</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <input type="text" id="claim_id_insurer" name="claim_id_insurer" value="{{(!(empty($claimDetails->insurer_refernce_id))) ? $claimDetails->insurer_refernce_id :''}}" autocomplete="off" class='form-control'>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="buttonbar pull-right">
            <div class="submit"><button type="submit" id="submit_save" name="submit_save" class="btn waves-effect waves-light btn-rounded btn-success" onclick="FORM.submit()">Update</button>
                <button type="button" id="submit_cancel" name="submit_cancel" onclick="FORM.cancel()" class='btn waves-effect waves-light btn-rounded btn-danger'>Cancel</button></div>
        </div>

    </div>


</div>
{{ Form::close() }}

@endsection

 
@section('customscript')
<script src="{{ asset('js/dibcustom/dib-claim-add.js') }}" type="text/javascript"></script>
<script>
$(function(){
$(document).off('change','#customer_id');
                        $(document).on('change','#customer_id',function(){                            
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
                                     $("#complaint_policy").val($("#complaint_policy").attr('selected_id'))
                                   }
                                       
                                  }); 
              
                        });
                        
                       $("#customer_id").trigger('change');
                       
                     
});
</script>

@endsection
