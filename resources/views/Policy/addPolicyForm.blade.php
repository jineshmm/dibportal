@extends('layouts.elite_fullwidth',['sidemenustatus' => array(),'countDetails'=>array(),'notificationCount'=>array() ] )
@section('headtitle')
 Create Policy
@endsection
@section('content')
{{ Form::open(array('route' => 'savepolicydetails', 'name' => 'savepolicyForm','id'=>'savepolicyForm','class'=>'dpib-custom-form') ) }}


<div id="policy_add_form" class="row card policy_tab_form">

    <div class="card-body">
        <div class="vtabs customvtab">


            <ul class="nav nav-tabs tabs-vertical" role="tablist">
                <li  class="nav-item" id='step1'> 

                    <a class="nav-link active" data-toggle="tab" href="#home1" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down"><div class="step">Step 1</div>Customer</span> </a>

                </li>


                <li  class="nav-item" id='step2'> 

                    <a class="nav-link" data-toggle="tab" href="#home2" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down"><div class="step">Step 2</div>Policy</span> </a>

                </li>



                <li  class="nav-item" id='step3'> 

                    <a class="nav-link" data-toggle="tab" href="#home3" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down"><div class="step">Step 3</div>Coverage</span> </a>

                </li>


                <li  class="nav-item" id='step4'> 

                    <a class="nav-link" data-toggle="tab" href="#home4" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down"><div class="step">Step 4</div>Premium & Commisiion</span> </a>

                </li>
            </ul>
            <input type="hidden" name="policy_id" id="policy_id" value="{{ isset($policyId) ? $policyId : 0 }}"  />
            <input type="hidden" name="policy_step" id="policy_step" value="1"  />
            <input type="hidden" name="step_type" id="step_type" value="next"  />
            <input type="hidden" name="crmId" id="crmId" value="{{ isset($crmId) ? $crmId : null }}"  />


            <div class="tab-content col-12" >

                <div class='col-12 tab-pane active' style='margin-top:40px;' id='home1'>
                    <panel id="policy_step1_section">
                        <div id="panel-customer_select" class="panel panel-default open">
                            <div class="panel-heading"><h3 class="panel-title">Customer</h3></div>
                            <div id="customer_select" class="panel-collapse panel-body">
                                @php
                                $disabled = ''
                                @endphp
                                @if ($customerId > 0 )
                                @php
                                $disabled ='disabled=disabled';
                                @endphp

                                @endif
                                <table class="insly-form">
                                    <tbody><tr id="field_customer_oid" class="field"><td class=""><div class="label"><span class="title">Customer</span></div></td><td>
                                                <div class="element">
                                                    @if ($customerId > 0 )
                                                    <input type="text" id="customer_name" class='form-control' name="customer_name" value="{{ isset($customerData) ? $customerData[0] : '' }}"  {{$disabled}} >
                                                    <input type="hidden" name="customer_id" id="customer_id" value="{{ $customerId }}"  />
                                                    @else
                                                    {{ Form::select('customer_id',  $allCustomers, $customerId,array('name'=>'customer_id','id' =>'customer_id','required'=>'required','class'=>'required form-control custom-select','error-message' =>"Insurance company field is mandatory"))}}
                                                    @endif  
                                                </div>

                                            </td></tr></tbody></table>
                            </div>
                        </div>
                    </panel>


                   



                </div>

                <div class="tab-pane  p-20 col-12" id="home2" role="tabpanel">
                 
                    <panel id="policy_step2_section" >

                    </panel>

                </div>
                <div class="tab-pane p-20 col-12" id="home3" role="tabpanel">
            
                    <panel id="policy_step3_section" >

                    </panel>

                </div>

                <div class="tab-pane p-20 col-12" id="home4" role="tabpanel">
       
                    <panel id="policy_step4_section" >

                    </panel>

                </div>


            </div>
        </div>

        <div class="buttonbar pull-right"><button type="button" class="submit_policy btn waves-effect waves-light btn-rounded btn-success" name="submit_save" id="step-continue" step=1 step-type="next">Continue</button>
            <button type="button" class ="submit_policy btn waves-effect waves-light btn-rounded btn-info"  id="step-backward" name="submit_save" step=1 step-type="back" style="display:none" >Back</button>
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
<script src="{{ asset('js/global/datatable/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/dibcustom/dib-policy-add.js') }}" type="text/javascript"></script>
@endsection

@section('pagescript')
<script>

$(function () {

    var dibpolicyAddObj = new DibPolicyAdd();
    dibpolicyAddObj.initialSetting();

    $(".customvtab").tabs({disabled:[ 1, 2, 3 ]});
    @if ($customerId == 0 )
   $("#customer_id").select2();
   @endif
    $("#customer_name").autocomplete({
        autoFocus: true,
        minLength: 2,
        source: function (request, response) {
            // Fetch data
            $.ajax({
                url: '{!! route("seachcustomer",["all"]) !!}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                dataType: "json",
                data: {
                    search: request.term
                },
                success: function (data) {

                    response($.map(data, function (item)
                    {

                        return{
                            label: item.name,
                            value: item.id
                        }
                    }))

                }

            });
        },
        select: function (event, ui) {
            // Set selection
            $('#customer_name').val(ui.item.label); // display the selected text
            $('#customer_id').val(ui.item.value); // save selected id to input
            return false;
        }
    });



});
</script>



@endsection
