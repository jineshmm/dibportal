<div class="panel-heading"><h3 class="panel-title">{{$formtitle}}</h3></div>
<div class="panel-body" id="fieldgroup_5dac3deae429d">
    <table class="insly-form">   
        <tbody>
            <tr id="field_car_value" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Car Value</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="product_car_value" name="product_car_value" value="{{ isset($productData->car_value) ? $productData->car_value : '0.00' }}" autocomplete="off" data-m-dec="0" class='form-control required' error-message="Car value is mandatory">
                    </div>
                </td>
            </tr>
            <tr id="field_deductible" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Deductible</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="product_deductible" name="product_deductible" value="{{ isset($productData->deductable) ? $productData->deductable : '0.00' }}" autocomplete="off" data-m-dec="0" class='form-control'>
                    </div>
                </td>
            </tr>
            <tr id="field_number_of_passenger" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Number of Passengers</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="product_number_of_passenger" name="product_number_of_passenger" value="{{ isset($productData->no_of_passengers) ? $productData->no_of_passengers : '' }}" autocomplete="off" data-m-dec="0" class='form-control'>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>