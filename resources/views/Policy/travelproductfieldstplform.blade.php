<div class="panel-heading"><h3 class="panel-title">{{$formtitle}}</h3></div>
<div class="panel-body" id="fieldgroup_5dac3deae429d">
    <table class="insly-form"> 
        <tbody><tr id="field_country_coverage" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Countries Coverage</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="hidden" id="object_type" name="object_type" value="person">
                        <select id="product_country_coverage" name="product_country_coverage" class='form-control'>
                            <option value="">--- select from here ---</option>
                            <option value="Worldwide" {{ isset($productData->country_coverage) ? (($productData->country_coverage=="Worldwide")? 'selected':''): '' }}>Worldwide</option>

                            <option value="Schengen" {{ isset($productData->country_coverage) ? (($productData->country_coverage=="Schengen")? 'selected':''): '' }}>Schengen</option>

                            <option value="Worldwide except US &amp; Canada" {{ isset($productData->country_coverage) ? (($productData->country_coverage=="Worldwide except US &amp; Canada")? 'selected':''): '' }}>Worldwide except US &amp; Canada</option>

                        </select>


                    </div>
                </td>
            </tr>
            <tr id="field_type_of_coverage" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Type of Coverage</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <select id="product_type_of_coverage" name="product_type_of_coverage" class='form-control'>
                            <option value="">--- select from here ---</option>
                            <option value="Platinium" {{ isset($productData->type_of_coverage) ? (($productData->type_of_coverage=="Platinium")? 'selected':''): '' }}>Platinium</option>

                            <option value="Gold" {{ isset($productData->type_of_coverage) ? (($productData->type_of_coverage=="Gold")? 'selected':''): '' }}>Gold</option>

                            <option value="Silver" {{ isset($productData->type_of_coverage) ? (($productData->type_of_coverage=="Silver")? 'selected':''): '' }}>Silver</option>

                            <option value="Bronze" {{ isset($productData->type_of_coverage) ? (($productData->type_of_coverage=="Bronze")? 'selected':''): '' }}>Bronze</option>

                        </select>


                    </div>
                </td>
            </tr>
            <tr id="field_duration_of_coverage" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Duration of Coverage</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <select id="product_duration_of_coverage" name="product_duration_of_coverage" class='form-control'>
                            <option value="">--- select from here ---</option>
                            <option value="Week" {{ isset($productData->duration_coverage) ? (($productData->duration_coverage=="Week")? 'selected':''): '' }}>Week</option>

                            <option value="2 Weeks" {{ isset($productData->duration_coverage) ? (($productData->duration_coverage=="2 Weeks")? 'selected':''): '' }}>2 Weeks</option>

                            <option value="Month" {{ isset($productData->duration_coverage) ? (($productData->duration_coverage=="Month")? 'selected':''): '' }}>Month</option>

                            <option value="3 Months" {{ isset($productData->duration_coverage) ? (($productData->duration_coverage=="3 Months")? 'selected':''): '' }}>3 Months</option>

                            <option value="6 Months" {{ isset($productData->duration_coverage) ? (($productData->duration_coverage=="6 Months")? 'selected':''): '' }}>6 Months</option>

                            <option value="9 Months" {{ isset($productData->duration_coverage) ? (($productData->duration_coverage=="9 Months")? 'selected':''): '' }}>9 Months</option>

                            <option value="Year" {{ isset($productData->duration_coverage) ? (($productData->duration_coverage=="Year")? 'selected':''): '' }}>Year</option>

                        </select>


                    </div>
                </td>
            </tr>
            <tr id="field_number_of_members" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Number of Members</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="number" id="product_no_of_members" name="product_no_of_members" value="{{ isset($productData->no_of_members) ? $productData->no_of_members : '' }}" autocomplete="off" data-m-dec="0" class='form-control col-3'>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>