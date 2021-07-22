<div class="panel-heading"><h3 class="panel-title">{{$formtitle}}</h3></div>
<div class="panel-body" id="fieldgroup_5dac3deae429d">
    <table class="insly-form">  
        <tbody><tr id="field_5dac3deae429d_prop_policysummary" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Coverage info</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="product_coverage" name="product_coverage" value="{{ isset($productData->coverage_info) ? $productData->coverage_info : '' }}" autocomplete="off" maxlength="255" class='form-control'>
                    </div>
                </td>
            </tr>
            <tr id="field_5dac3deae429d_prop_policyinfo" class="field ">
                <td class="">
                    <div class="label full-height" style="height: 204px;">
                        <span class="text-danger "></span>
                        <span class="title">Info</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <textarea id="product_coverage_info" name="product_coverage_info" wrap="soft" rows="10" class='form-control'>{{ isset($productData->information) ? $productData->information : '' }}</textarea>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>