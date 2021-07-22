

<div id="claim_add_form" class="insly-form">



    <!-- -->
    <div class="insly-form">



        <div class="panel panel-default panel-dark">
            <div class="panel-heading" id="fieldgroup_title_claim">
                <!-- <div class="blocktitle"> -->
                <h3 class="panel-title">Claim information</h3>
                <!-- </div> -->
            </div>
            <div class="panel-body" id="fieldgroup_claim">
                <table class="insly-form">
                    <tbody>
                        <tr id="field_claimstatus_oid" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger icon-asterix"></span>
                                    <span class="title">Status</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    {{ Form::select('claim_status',  $claimStatus, '',array('name'=>'claim_status','id' =>'claim_status','required'=>'required','class'=>'required form-control','error-message' =>"Insurance company field is mandatory"))}}


                                </div>
                            </td>
                        </tr>
                        <tr id="field_claim_date_incident" class="field">
                            <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Loss date and time</span></div></td>
                            <td><div class="element"><input type="datetime-local" id="claim_date_incident" name="claim_date_incident" value="{{date('Y-m-d')}}T{{date('h:i')}}" maxlength="10" autocomplete="off" class="form-control" style="margin-right: 0px !important">
                                    
                                    
                                   
                                    <div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="claim_date_incident_comment"></div></div></div></td>
                        </tr>
                        <tr id="field_claim_date_submit" class="field">
                            <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Submitted to broker</span></div></td>
                            <td><div class="element"><input type="date" id="claim_date_submit" name="claim_date_submit" value="{{date('Y-m-d')}}" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="claim_date_submit_comment"></div></div></div>
                            </td>
                        </tr>
                        <tr id="field_claim_date_submitinsurer" class="field">
                            <td class=""><div class="label"><span class="title">Submitted to insurer</span></div></td>
                            <td><div class="element"><input type="date" id="claim_date_submitinsurer" name="claim_date_submitinsurer" value="" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="claim_date_submitinsurer_comment"></div></div></div>
                            </td>
                        </tr>
                        <tr id="field_claim_date_settlement" class="field"><td class="">
                                <div class="label"><span class="title">Settlement date</span></div></td>
                            <td><div class="element"><input type="date" id="claim_date_settlement" name="claim_date_settlement" value="" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="claim_date_settlement_comment"></div></div></div>
                            </td>
                        </tr>
                        <tr id="field_broker_person_oid" class="field"><td class=""><div class="label full-height" style="height: 32px;"><span class="text-danger icon-asterix"></span><span class="title">Claim handler</span></div></td>
                            <td><div class="element"><div style="width: 60%; float: left">
                                        
                                        {{ Form::select('claim_handle_person_id',  $userData, '',array('name'=>'claim_handle_person_id','id' =>'claim_handle_person_id','required'=>'required','class'=>'required form-control','error-message' =>"Insurance company field is mandatory"))}}
                                        
                                        </div>
                                    </div>
                            </td>
                        </tr>							</tbody>
                </table>
            </div>
        </div>


        <div class="panel panel-default panel-dark">
            <div class="panel-heading" id="fieldgroup_title_loss">
                <!-- <div class="blocktitle"> -->
                <h3 class="panel-title">Loss information</h3>
                <!-- </div> -->
            </div>
            <div class="panel-body" id="fieldgroup_loss">
                <table class="insly-form">
                    <tbody>
                        <tr id="field_prop_claim_location" class="field ">
                            <td class="">
                                <div class="label full-height" style="height: 109px;">
                                    <span class="text-danger "></span>
                                    <span class="title">Location of loss or incident</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <textarea id="claim_location" name="claim_location" wrap="soft" rows="5" class='form-control'></textarea>
                                </div>
                            </td>
                        </tr>
                        <tr id="field_prop_claim_deductible_amt" class="field">
                            <td class=""><div class="label"><span class="title">Deductible/excess amount</span></div></td>
                            <td><div class="element"><input type="text" id="claim_deductible_amt" name="claim_deductible_amt" value="0.00" autocomplete="off" style="width: 490px; width: 50%;" class="numberfield currencyfield form-control" data-m-dec="2">
                                    <select id="prop_claim_deductible_amt_currency" name="claim_deductible_amt_currency" style="margin-left: 10px; width: 80px !important" data-keephiddenvalue="1" class="numberfield form-control" onchange="FORM.updateCurrency('prop_claim_deductible_amt_currency')"><option value="SAR" selected="selected">SAR</option></select></div></td>
                        </tr>							</tbody>
                </table>
            </div>
        </div>


        <div class="panel panel-default panel-dark">
            <div class="panel-heading" id="fieldgroup_title_reserve">
                <!-- <div class="blocktitle"> -->
                <h3 class="panel-title">Reserve</h3>
                <!-- </div> -->
            </div>
            <div class="panel-body" id="fieldgroup_reserve">
                <table class="insly-form">
                    <tbody>
                        <tr id="field_claim_reserve_sum" class="field">
                            <td class=""><div class="label"><span class="title">Reserve sum</span></div></td>
                            <td><div class="element"><input type="text" id="claim_reserve_sum" name="claim_reserve_sum" value="0.00" autocomplete="off" style="width: 490px; width: 50%;" class="numberfield currencyfield form-control" data-m-dec="2">
                                    <select id="claim_reserve_currency" name="claim_reserve_currency" style="margin-left: 10px; width: 80px !important" data-keephiddenvalue="1" class="numberfield form-control" ><option value="SAR" selected="selected">SAR</option></select></div></td>
                        </tr>
                        <tr id="field_claim_reserve_date" class="field">
                            <td class=""><div class="label"><span class="title">Reserve Date</span></div></td>
                            <td><div class="element"><input type="date" id="claim_reserve_date" name="claim_reserve_date" value="" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important">
                                    <div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="claim_reserve_date_comment"></div></div></div></td>
                        </tr>    
                        <tr id="field_claim_reserve_description" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger "></span>
                                    <span class="title">Reserve description</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <input type="text" id="claim_reserve_description" name="claim_reserve_description" value="" autocomplete="off" class='form-control'>
                                </div>
                            </td>
                        </tr>
                        <tr id="field_claim_reserve_type" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger "></span>
                                    <span class="title">Reserve Type</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    
                                    {{ Form::select('claim_reserve_type',  $reserveType, '',array('name'=>'claim_reserve_type','id' =>'claim_reserve_type','required'=>'required','class'=>'required form-control','error-message' =>"Insurance company field is mandatory"))}}
                                    


                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>




        <div class="panel panel-default panel-dark">
            <div class="panel-heading" id="fieldgroup_title_insurer">
                <!-- <div class="blocktitle"> -->
                <h3 class="panel-title">Insurer contacts</h3>
                <!-- </div> -->
            </div>
            <div class="panel-body" id="fieldgroup_insurer">
                <table class="insly-form">
                    <tbody>
                        <tr id="field_prop_claim_insurer_name" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger "></span>
                                    <span class="title">Insurer contact name</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <input type="text" id="claim_insurer_name" name="claim_insurer_name" value="" autocomplete="off" class='form-control'>
                                </div>
                            </td>
                        </tr>
                        <tr id="field_prop_claim_insurer_email" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger "></span>
                                    <span class="title">Insurer contact e-mail</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <input type="text" id="claim_insurer_email" name="claim_insurer_email" value="" autocomplete="off" class='form-control'>
                                </div>
                            </td>
                        </tr>
                        <tr id="field_prop_claim_insurer_phone" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger "></span>
                                    <span class="title">Insurer contact phone</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <input type="text" id="claim_insurer_phone" name="claim_insurer_phone" value="" autocomplete="off" class='form-control'>
                                </div>
                            </td>
                        </tr>
                        <tr id="field_claim_id_insurer" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger "></span>
                                    <span class="title">Insurer reference ID</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <input type="text" id="claim_id_insurer" name="claim_id_insurer" value="" autocomplete="off" class='form-control'>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>





    </div>




</div>