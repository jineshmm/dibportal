@extends('layouts.elite_fullwidth',['sidemenustatus' => array(),'countDetails'=>array(),'notificationCount'=>array() ] )
@section('content')
{{ Form::open(array('route' => 'saveclaimdetails', 'name' => 'saveclaimForm','id'=>'saveclaimForm','class'=>'dpib-custom-form') ) }}

<div id="policy_add_form" class="row card policy_tab_form">

    <div class="card-body">
        <div class="vtabs customvtab">

            <ul class="nav nav-tabs tabs-vertical" role="tablist">
                <li  class="nav-item" id='step1' disabled='disabled'> 

                    <a class="nav-link active" data-toggle="tab" href="#home1" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down"><div class="step">Step 1</div>CUSTOMER AND POLICY INFORMATION</span> </a>

                </li>

                <li  class="nav-item" id='step2' disabled='disabled'> 

                    <a class="nav-link" data-toggle="tab" href="#home2" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down"><div class="step">Step 2</div>CLAIM INFORMATION</span> </a>

                </li>



                <li  class="nav-item" id='step3' disabled='disabled'> 

                    <a class="nav-link" data-toggle="tab" href="#home3" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down"><div class="step">Step 3</div>CLAIMANT(S)</span> </a>

                </li>




            </ul>


            <input type="hidden" name="claim_id" id="claim_id" value="{{ isset($claimId) ? $claimId : 0 }}"  />
            <input type="hidden" name="claim_step" id="claim_step" value="1"  />
            <input type="hidden" name="step_type" id="step_type" value="next"  />



            <div class="tab-content col-12" >
                <div class="tab-pane  p-20 col-12" id="home1" role="tabpanel">
                    <panel id="claim_step1_section" >
                        <div id="panel-customer_select" class="panel panel-default open">
                            <div class="panel-heading"><h3 class="panel-title">Customer</h3></div>
                            <div id="customer_select" class="panel-collapse panel-body">
                                @php
                                $disabled = false;
                                @endphp
                                @if ($customerId > 0 )

                                <input type="hidden" name="customer_id" value="{{$customerId}}" />
                                @php
                                $disabled =true;
                                @endphp

                                @endif
                                <table class="insly-form" >
                                    <tbody>
                                        <tr id="field_customer_oid" class="field"><td class=""><div class="label"><span class="title">Customer</span></div></td><td>
                                                <div class="element">

                                                    {{ Form::select('customer_id',  $allCustomers, $customerId,array('name'=>'customer_id','id' =>'customer_id','required'=>'required','class'=>'required form-control','error-message' =>"Insurance company field is mandatory",'disabled'=>$disabled,'style'=>'width:100%'))}}



                                            </td></tr>
                                        <tr id="field_request_type" class="field">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">Policy</span>
                                                </div>
                                            </td>
                                            <td>

                                                <div class="element">
                                                    <select id="complaint_policy" name="complaint_policy" selected_id="{{ $policyId }}" class='form-control'>

                                                        <option value="" >--Select policy--</option>

                                                    </select>


                                                </div>
                                            </td>
                                        </tr>


                                    </tbody></table>
                            </div>
                        </div>
                    </panel>
                </div>
                <div class="tab-pane  p-20 col-12" id="home2" role="tabpanel">
                    <panel id="claim_step2_section" >

                    </panel>
                </div>
                <div class="tab-pane  p-20 col-12" id="home3" role="tabpanel">
                    <panel id="claim_step3_section" >

                    </panel>
                </div>
                <div class="tab-pane  p-20 col-12" id="home4" role="tabpanel">
                    <panel id="claim_step4_section" >

                    </panel>
                </div>


            </div>
        </div>

        <div class="buttonbar pull-right">
            <button type="button" class="submit_claim primary btn waves-effect waves-light btn-rounded btn-success" name="submit_save" id="step-continue" step=1 step-type="next">Continue</button> 
            <button type="button"  class="submit_claim btn waves-effect waves-light btn-rounded btn-info" id="step-backward" name="submit_save" step=1 step-type="back" style="display:none">Back</button> 
            <button type="button" id="submit_cancel" name="submit_cancel" class='btn waves-effect waves-light btn-rounded btn-danger'>Cancel</button>
        </div>
    </div>
</div>
{{ Form::close() }}
@endsection
@section('customcss')
 <link href="{{ asset('elitedesign/dist/css/pages/tab-page.css') }} " rel="stylesheet">
 @endsection
 
@section('customscript')
<script src="{{ asset('js/dibcustom/dib-claim-add.js') }}" type="text/javascript"></script>
<script>

$(function () {
    var dibclaimAddObj = new DibClaimAdd();
    dibclaimAddObj.initialSetting();
    $("#customer_id").select2({dropdownParent: $("#policy_add_form")});
    $(".customvtab").tabs({disabled: [1, 2]});
    $(document).off('change', '#customer_id');
    $(document).on('change', '#customer_id', function () {
        $.ajax({
            url: "{!! route('clientpolicies') !!}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "post",
            data: {'customer_id': $(this).val(), 'selectedoption': ''}

        }).done(function (data) {
            if (data.status) {
                $("#complaint_policy").empty().html(data.optionstring);
                $("#complaint_policy").val($("#complaint_policy").attr('selected_id'))
            }

        });

    });
    $("#customer_id").trigger('change');

});
</script>



@endsection
