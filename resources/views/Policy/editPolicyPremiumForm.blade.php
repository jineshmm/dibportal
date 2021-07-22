

{{ Form::open(array('route' =>array('updatepremiuminfodata',$policyId), 'name' => 'updatepremiumForm','id'=>'updatepremiumForm') ) }}

@csrf 

<div class="dialogform"><table class="insly_dialogform">
        <tbody>
            <tr id="field_policy_premium_sum" class="field">
                <td class=""> <div class="label"><span class="title">Gross premium</span></div></td>
                <td><div class="element">
                        <input type="text" id="policy_premium_sum" name="policy_premium_sum" value="{{$premiumdata->gross_amount}}" autocomplete="off" style="width: 490px; width: 50%;" class="numberfield currencyfield" data-m-dec="2"><select id="policy_premium_currency" name="policy_premium_currency" style="margin-left: 10px; width: 80px !important" data-keephiddenvalue="1" class="numberfield" ><option value="SAR" selected="selected">SAR</option></select></div></td>
            </tr>
            <tr id="field_policy_additional_sum" class="field">
                <td class=""> <div class="label"><span class="title">Additional amount</span></div></td>
                <td><div class="element">
                        <input type="text" id="policy_premium_addition" name="policy_premium_addition" value="{{$premiumdata->additional_amount}}" autocomplete="off" style="width: 490px; width: 50%;" class="numberfield currencyfield" data-m-dec="2"><select id="policy_premium_currency" name="policy_premium_currency" style="margin-left: 10px; width: 80px !important" data-keephiddenvalue="1" class="numberfield" ><option value="SAR" selected="selected">SAR</option></select></div></td>
            </tr>

            <tr id="field_policy_installments" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Installments</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <select id="policy_installments" name="policy_installments">
                            <option value="0">--- not set ---</option>
                            @php
                            $installmentArray = array(1,2,3,4,5,6,7,8,9,10,11,12);
                            @endphp

                            @foreach($installmentArray as $installment)
                            <option value="{{$installment}}" {{ isset($premiumdata->installment_number) ? (($premiumdata->installment_number==$installment)? 'selected':''): '' }}>{{$installment}}</option>
                            @endforeach

                        </select>


                    </div>
                </td>
            </tr>
            <tr id="field_subtitle_collection" class="field"><td colspan="2" class="subtitle ">Collection</td></tr>    <tr id="field_policy_collection" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Collection</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <select id="policy_collection" name="policy_collection">
                            <option value="">--- not set ---</option>
                            <option value="broker collects all payments" {{ isset($objdata->collection_type) ? (($objdata->collection_type=="broker collects all payments")? 'selected':''): '' }}>broker collects all payments</option>

                            <option value="broker 1st, insurer followup payments" {{ isset($objdata->collection_type) ? (($objdata->collection_type=="broker 1st, insurer followup payments")? 'selected':''): '' }}>broker 1st, insurer followup payments</option>

                            <option value="insurer collects all payments" {{ isset($objdata->collection_type) ? (($objdata->collection_type=="insurer collects all payments")? 'selected':''): '' }}>insurer collects all payments</option>

                        </select>


                    </div>
                </td>
            </tr>

        </tbody></table></div>

{{ Form::close() }}





