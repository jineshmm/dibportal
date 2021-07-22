        <div class="panel panel-default panel-dark">
            <div class="panel-heading" id="fieldgroup_title_policy">
                <!-- <div class="blocktitle"> -->
                <h3 class="panel-title">Premium / Payment</h3>							
                <!-- </div> -->
            </div>
            <div class="panel-body" id="fieldgroup_policy">

                <table class="insly-form">
                    <tbody>
                        <tr id="field_policy_premium_sum" class="field">
                            <td class=""><div class="label"><span class="title">Gross premium</span></div></td>
                            <td><div class="element"><input type="text" id="policy_premium_sum" name="policy_premium_sum" value="0.00" autocomplete="off" style="width: 490px; width: 50%;" class="numberfield currencyfield form-control required" error-message="Amount field is mandatory" data-m-dec="2"><select id="policy_premium_currency" name="policy_premium_currency" style="margin-left: 10px; width: 80px !important" data-keephiddenvalue="1" class="numberfield form-control" onchange="FORM.updateCurrency('policy_premium_currency')"><option value="SAR" selected="selected">SAR</option></select></div></td>
                        </tr> 
                        <tr id="field_policy_premium_sum" class="field">
                            <td class=""><div class="label"><span class="title">Policy issuance amount</span></div></td>
                            <td><div class="element"><input type="text" id="policy_additional_amount" name="policy_additional_amount" value="0.00" autocomplete="off" style="width: 490px; width: 50%;" class="numberfield currencyfield form-control" data-m-dec="2"><select id="policy_premium_currency" name="policy_premium_currency" style="margin-left: 10px; width: 80px !important" data-keephiddenvalue="1" class="numberfield form-control" onchange="FORM.updateCurrency('policy_premium_currency')"><option value="SAR" selected="selected">SAR</option></select></div></td>
                        </tr>
                        <tr id="field_policy_collection" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger "></span>
                                    <span class="title">Collection</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">

                                    <input type="hidden" id="broker_commission" name="broker_commission" value="{{ isset($broker_commission) ? $broker_commission : 0 }}" >
                                    <select id="policy_collection" name="policy_collection" data-default-value="0" class="form-control">
                                        <option value="">--- not set ---</option>
                                        <option value="broker collects all payments">broker collects all payments</option>
                                        <option value="broker 1st, insurer followup payments">broker 1st, insurer followup payments</option>
                                        <option value="insurer collects all payments">insurer collects all payments</option>
                                    </select>


                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>


            </div>
        </div>

        <div class="panel panel-default panel-dark">
            <div class="panel-heading" id="fieldgroup_title_commissionschedule">
                <!-- <div class="blocktitle"> -->
                <h3 class="panel-title">Installment schedule</h3>							
                <!-- </div> -->
            </div>
            <div class="panel-body" id="fieldgroup_installmentschedule">
                <table class="insly-form" id='dpib_installment_table'>
                    <tbody>
                        <tr id="field_policy_installments" class="field">
                            <td><div class="label">Installments</div></td>
                            <td colspan="3">
                                <div class="element">
                                    <select id="policy_installments" name="policy_installments" style="width: 500px" class="form-control"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select><button type="button" class="btn waves-effect waves-light btn-outline-primary" id="dip_installment_generate" openUrl="{{route("generateinstallment")}}">Generate installment schedule</button></div></td>
                                </tr>							</tbody>
                </table>


            </div>

        </div>

        <div class="panel panel-default panel-dark">
            <div class="panel-heading" id="fieldgroup_title_installmentschedule">
                <!-- <div class="blocktitle"> -->
                <h3 class="panel-title">Commission</h3>							
                <!-- </div> -->
            </div>
            <div class="panel-body" id="fieldgroup_commissionschedule">
                <table class="table">
                    <tbody>

                        <tr><td colspan="4" style="text-align: right; padding-right: 10px; color: #808080"></td><td style="text-align: center; color: #808080" id="policy_commission_disp"></td><td style="text-align: center; color: #808080" id="policy_commission_sum_disp"></td></tr>


                        <tr><th></th><th>Internal commission</th><th style="text-align: center; padding: 10px"></th><th></th><th></th><th></th></tr>

                        <tr><td style="text-align: center">1</td><td style="text-align: center">

                                {{ Form::select('user_details',  array(""=>"--- not set ---")+$userDetails, null,array('name'=>'person_sales_person','id' =>'person_sales_person','required'=>'required','class'=>'personfld form-control','error-message' =>"Insurance company field is mandatory","data-role"=>"sales_person","style"=>"width: 95%","onchange"=>"POLICY.COMMISSIONSPLIT.changePerson('sales_person')","data-intext"=>"internal" ))}}     

                            </td>
                            <td style="text-align: center">sales person</td>
                            <td class="" style="text-align: center"><select name="type_sales_person" class="form-control" id="type_sales_person" style="width: 80%" onchange="POLICY.COMMISSIONSPLIT.changeType('sales_person')"><option value="0" selected="selected">% of internal commission</option><option value="1">specified sum</option></select></td><td class="" style="text-align: center">
                                <input type="text" autocomplete="off" class="commissionfld form-control" id="perc_sales_person" name="perc_sales_person" data-role-default="0.00" value="0.00" maxlength="6" style="width: 80%; text-align: center" onchange="POLICY.COMMISSIONSPLIT.recalcSplit()" onblur="POLICY.COMMISSIONSPLIT.recalcSplit()"></td>
                            <td class="" style="text-align: center"><input type="hidden" value="" name="commission_sales_person" id="commission_sales_person"><input type="text" autocomplete="off" class="commissionfld form-control" id="sum_sales_person" name="sum_sales_person" value="0.00" maxlength="12" style="width: 80%; text-align: center" disabled="disabled" onchange="POLICY.COMMISSIONSPLIT.recalcSplit()"></td>
                        </tr>
                        <tr>
                            <td style="text-align: center"></td>
                            <td style="text-align: center">Diamond Insurance Broker</td>
                            <td style="text-align: center">broker company</td>
                            <td class="" style="text-align: center">rounding correction</td>
                            <td class="" style="text-align: center; padding: 7px"></td>
                            <td class="" style="text-align: center; padding: 7px"><span id="total_sum_broker"></span></td>
                        </tr>

                        <tr><th colspan="4" style="text-align: right; padding-right: 10px">Total:</th><th style="text-align: center; padding: 7px"></th><th style="text-align: center; padding: 7px"><span id="total_sum">0.00</span></th></tr>
                    </tbody>
                </table>

            </div>

        </div>

<script src="{{ asset('js/dibcustom/dib-policy-add.js') }}" type="text/javascript"></script>
<script>

                                $(function () {
                                    var dibpolicyAddObj = new DibPolicyAdd();
                                    dibpolicyAddObj.initialSetting();

                                });
</script>

