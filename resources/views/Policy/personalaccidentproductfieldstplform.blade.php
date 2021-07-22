<div class="panel-heading"><h3 class="panel-title">{{$formtitle}}</h3></div>
<div class="panel-body" id="fieldgroup_5dac3deae429d">
    <table class="insly-form">
        <tbody><tr id="field_coverage" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Coverage</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <select id="product_individual_coverage" name="product_individual_coverage" class='form-control required' error-message="Coverage field is mandatory">
                            <option value="">--- select from here ---</option>
                            <option value="standard coverage" {{ isset($productData->coverage) ? (($productData->coverage=="standard coverage")? 'selected':''): '' }}>standard coverage</option>

                            <option value="standard + leisure sports activities" {{ isset($productData->coverage) ? (($productData->coverage=="standard + leisure sports activities")? 'selected':''): '' }}>standard + leisure sports activities</option>

                            <option value="standard + professional sports" {{ isset($productData->coverage) ? (($productData->coverage=="standard + professional sports")? 'selected':''): '' }}>standard + professional sports</option>

                        </select>


                    </div>
                </td>
            </tr>
            <tr id="field_5dad78f9ba50d_prop_field_10001055" class="field"><td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Accident sum covered</span></div></td>
                <td><div class="element"><input type="text" id="product_insured_sum" name="product_accident_sum" value="{{ isset($productData->accident_sum_covered) ? $productData->accident_sum_covered : '' }}" autocomplete="off" style="width: 490px; width: 50%;" class="numberfield currencyfield form-control" data-m-dec="22"></div></td>
            </tr>
            <tr id="field_5dad78f9ba50d_prop_field_10001056" class="field">
                <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Death sum covered</span></div></td>
                <td><div class="element"><input type="text" id="product_death_sum" name="product_death_sum" value="{{ isset($productData->death_sum_covered) ? $productData->death_sum_covered : '' }}" autocomplete="off" style="width: 490px; width: 50%;" class="numberfield currencyfield form-control" data-m-dec="22"></div></td>
            </tr>
        </tbody>
    </table>
</div>