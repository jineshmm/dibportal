@extends('layouts.elite_fullwidth',['sidemenustatus' => array(),'countDetails'=>array(),'notificationCount'=>array() ] )
@section('content')

<div id="policy_add_form" class="row">
    {{ Form::open(array('route' => array('editproductdata',$productData->mainId),'name' => 'objectForm','id'=>'objectForm','class'=>'col-12') ) }} 

    <div class="col-md-12">
        <div class="panel panel-default panel-dark card">
            <div class="card-body">
        {!! $innerHtml !!}
        <div class="productformblock" id="form_commission">
            <div class="panel-heading">
                <h3 class="panel-title">Commission</h3></div>
            <div class="panel-body" id="fieldgroup_commission">
                <table class="insly-form">    <tbody><tr id="field_policy_commission" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger "></span>
                                    <span class="title">Commission</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <input type='hidden' name='policy_id' value='{{$productData->mainId}}' />
                                    <input type='hidden' name='editFlag' value='1' />
                                     <input type='hidden' name='policy_product' value='{{$productData->product_id}}' />
                                    
                                    <input type="text" id="policy_commission" name="policy_commission" value="{{ isset($productData->commision) ? $productData->commision : '' }}" autocomplete="off" class="with-comment with-comment form-control col-3 required" error-message="Commission field is mandatory" data-m-dec="4">
                                    <small>% &nbsp;&nbsp; <span class="warning">Commission not entered for this insurer/product.</span></small>
                                </div>
                            </td>
                        </tr>
                    </tbody></table></div>
        </div>
        <div class="panel panel-default panel-dark productformblock" id="form_tax">
            <div class="panel-heading">
                <h3 class="panel-title">Tax</h3></div><div class="panel-body" id="fieldgroup_tax">
                    <table class="insly-form">
                        <tbody>
                            <tr id="field_policy_tax" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger icon-asterix"></span>
                                    <span class="title">VAT</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <select id="policy_tax" name="policy_tax" data-default-value="5" class='form-control'>
                                        <option value="">--- select from here ---</option>
                                        <option value="0" {{ isset($productData->tax) ? (($productData->tax=="0")? 'selected':''): '' }}>Nil (0%)</option>

                                        <option value="5" {{ isset($productData->tax) ? (($productData->tax=="5")? 'selected':''): '' }}>VAT (5%)</option>
                                        <option value="15" {{ isset($productData->tax) ? (($productData->tax=="15")? 'selected':''): '' }}>VAT (15%)</option>

                                    </select>


                                </div>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                </div>
        </div>

        <div class="buttonbar pull-right">
            <div class="submit"><button type="submit" id="submit_save" name="submit_save"  class="btn waves-effect waves-light btn-success mr-right" >Edit</button><button type="button" id="submit_cancel" name="submit_cancel" onclick="FORM.cancel()" class="btn waves-effect waves-light btn-danger">Cancel</button></div>
        </div>
    </div>
    
</div> 
    </div>
        {{ Form::close() }}
    </div>
@endsection
@section('customscript')
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
