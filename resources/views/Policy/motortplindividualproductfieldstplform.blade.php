<div class="panel-heading"><h3 class="panel-title">{{$formtitle}}</h3></div>
<div class="panel-body" id="fieldgroup_5dac3deae429d">
    <table class="insly-form">    
        <tbody>
            <tr id="field_third_party" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Third party liability: property coverage</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="product_third_party_liability" name="product_third_party_liability" value="{{ isset($productData->third_party_liability) ? $productData->third_party_liability : '0.00' }}" autocomplete="off" maxlength="255" class='form-control required' error-message="Property coverage field is mandatory">
                    </div>
                </td>
            </tr>
            <tr id="field_own_damage" class="field"><td class=""><div class="label"><span class="title">Own damage: property coverage</span></div></td>
                <td><div class="element"><input type="text" id="product_own_damage" name="product_own_damage" value="{{ isset($productData->property_coverage) ? $productData->property_coverage : '0.00' }}" autocomplete="off" style="width: 490px; width: 50%;" class="numberfield currencyfield form-control" data-m-dec="22"></div></td>
            </tr>
        </tbody>
    </table>
</div>