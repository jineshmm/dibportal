<div class="panel-heading"><h3 class="panel-title">{{$formtitle}}</h3></div>
<div class="panel-body" id="fieldgroup_5dac3deae429d">
    <table class="insly-form"> 
        <tbody>
            <tr id="field_product_risk_covered" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Risks covered</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <select id="product_risk_covered" name="product_risk_covered">
                            <option value="">--- select from here ---</option>
                            <option value="basic risks (fire)" {{ isset($productData->risk_covered) ? (($productData->risk_covered=="basic risks (fire)")? 'selected':''): '' }}>basic risks (fire)</option>

                            <option value="most risks (fire, storm, hail)" {{ isset($productData->risk_covered) ? (($productData->risk_covered=="most risks (fire, storm, hail)")? 'selected':''): '' }}>most risks (fire, storm, hail)</option>

                            <option value="all risks" {{ isset($productData->risk_covered) ? (($productData->risk_covered=="all risks")? 'selected':''): '' }}>all risks</option>

                        </select>


                    </div>
                </td>
            </tr>
            <tr id="field_product_insured_sum" class="field"><td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Insured sum</span></div></td>
                <td><div class="element"><input type="text" id="product_insured_sum" name="product_insured_sum" value="{{ isset($productData->insured_sum) ? $productData->insured_sum : '' }}" palceholder="0.00" autocomplete="off" style="width: 490px; width: 50%;" class="numberfield currencyfield" data-m-dec="22"></div></td>
            </tr>
            <tr id="field_deductible" class="field"><td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Deductible</span></div></td>
                <td><div class="element"><input type="text" id="product_deductible" name="product_deductible" value="{{ isset($productData->deductable) ? $productData->deductable : '' }}" palceholder="0.00" autocomplete="off" style="width: 490px; width: 50%;" class="numberfield currencyfield" data-m-dec="22"></div></td>
            </tr> 
            <tr id="field_personal_property_cover" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Personal property coverage</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <select id="product_personal_property_coverage" name="product_personal_property_coverage">
                            <option value="">--- select from here ---</option>
                            <option value="yes" {{ isset($productData->personal_property_coverage) ? (($productData->personal_property_coverage=="yes")? 'selected':''): '' }}>yes</option>

                        </select>


                    </div>
                </td>
            </tr>
            <tr id="field_temporary_rental_cost" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Temporary rental costs coverage</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <select id="product_temporary_rental_costs_coverage" name="product_temporary_rental_costs_coverage">
                            <option value="">--- select from here ---</option>
                            <option value="yes" {{ isset($productData->temporary_rental_cost_coverage) ? (($productData->temporary_rental_cost_coverage=="yes")? 'selected':''): '' }}>yes</option>

                        </select>


                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>