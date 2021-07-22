
        <div class="panel panel-default panel-dark">
            <div class="panel-heading" id="fieldgroup_title_policy">
                <!-- <div class="blocktitle"> -->
                <h3 class="panel-title">Policy info</h3>							
                <!-- </div> -->
            </div>
            <div class="panel-body" id="fieldgroup_policy">
                <table class="insly-form">
                    <tbody>

                        <tr id="field_policy_type" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger "></span>
                                    <span class="title">Line of business</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <select id="policy_type" name="policy_type" class='form-control'>
                                        

                                        <option value="1">General</option>

                                        <option value="2">Medical</option>

                                        <option value="3">Motor</option>

                                    </select>


                                </div>
                            </td>
                        </tr>
                        <tr id="field_policy_insurer" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger icon-asterix"></span>
                                    <span class="title">Insurer</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    {{ Form::select('insurance_company',  $insurancecompany, null,array('name'=>'policy_insurer','id' =>'policy_insurer','required'=>'required','class'=>'required form-control','error-message' =>"Insurance company field is mandatory" ))}}  


                                </div>
                            </td>
                        </tr>
                        <tr id="field_policy_date_issue" class="field">
                            <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Issue date</span></div></td>
                            <td><div class="element"><input type="date" id="policy_date_issue" name="policy_date_issue" value="{{date('Y-m-d')}}" maxlength="10" autocomplete="off" class="form-control required" style="margin-right: 0px !important" onchange="FORM.checkPastDate('#policy_date_issue');"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_date_issue_comment"></div></div></div></td>
                        </tr>
                        <tr id="field_policy_date_start" class="field">
                            <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Start date</span></div></td>
                            <td><div class="element"><input type="date" id="policy_date_start" name="policy_date_start" value="{{date('Y-m-d')}}" maxlength="10" autocomplete="off" class="form-control required" style="margin-right: 0px !important" onchange="FORM.checkPastDate('#policy_date_start');"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_date_start_comment"></div></div></div></td>
                        </tr>
                        <tr id="field_policy_date_end" class="field policy_openended0">
                            <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">End date</span></div></td>
                            <td><div class="element"><input type="date" id="policy_date_end" name="policy_date_end" value="" maxlength="10" autocomplete="off" class="form-control col-3 required" style="margin-right: 0px !important" onchange="FORM.checkPastDate('#policy_date_end');" error-message="Policy end date is mandatory"><small style="margin-left: 49px;">&nbsp; &nbsp; &nbsp; » &nbsp;<a onclick="POLICY.setEndDate(12, 0)">1 year</a></small><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_date_end_comment"></div></div></div></td>
                        </tr>							
                       <tr id="field_policy_date_start" class="field">
                           <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Sales type</span></div></td>
                            <td><div class="element">
                                <select id="policy_salestype" name="policy_salestype"class='form-control' >
                                                <option value="1">new sales</option>
                                                <option value="2">Renewal</option>

                                </select>
                                </div></td>
                        </tr>

                        <tr id="field_customer_type" class="field"><td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Renewable</span></div></td>
                                            <td>
                                                 <div class="form-group" >
                                                   
                                                    <div class="custom-control custom-radio">
                                                        
                                            
                                                        <input type="radio" id="customer_type_11" name="policy_renewalstatus" value="1" 
                                                        checked="checked" class="get-outta-here dib_customer_type custom-control-input" data-default-value="0" >
                                                        <label class="custom-control-label" for="customer_type_11">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                         
                                                        <input type="radio" id="customer_type_21" name="policy_renewalstatus" value="0"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="1" >
                                                        <label class="custom-control-label" for="customer_type_21">No</label>
                                                    </div>
                                                </div>
                                             </td>
                                        </tr>


                


                        <tr id="field_policy_renewal" class="field" style="display:none">
                           <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Policy number</span></div></td>
                            <td><div class="element">
                                    
         {{ Form::select('old_policies',  array(""=>"--- not set ---")+$oldPolicies, null,array('name'=>'old_policy_number','id' =>'old_policy_number','error-message' =>"Insurance company field is mandatory",'class'=>'form-control required' ))}}  

        
                                
                                </div></td>
                        </tr>
                        
                        
                    
                    </tbody>
                </table>
            </div>
        </div>


    


<script src="{{ asset('js/dibcustom/dib-policy-add.js') }}" type="text/javascript"></script>
<script>

    $(function () {
       FORM.setDatePicker("#savepolicyForm input:text.datefield");
        var dibpolicyAddObj = new DibPolicyAdd();                
           dibpolicyAddObj.initialSetting();
    });
</script>