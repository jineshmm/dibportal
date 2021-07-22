@if (isset($endorsementId))

                        {{ Form::open(array('route' => array('updateendorsementdetails', $policy_id,$endorsementId),'name' => 'form_endorsement_save','id'=>'form_endorsement_save') ) }}
                        @else
                       {{ Form::open(array('route' => array('saveendorsementdetails',$policy_id), 'name' => 'form_endorsement_save','id'=>'form_endorsement_save') ) }}
                        @endif

                        @csrf


          <div class="dialogform">
              <table class="insly_dialogform">
                    <tbody>
                        <tr id="field_policy_endorsement_no" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger icon-asterix"></span>
                                    <span class="title">Endorsement number</span>
                                </div>
                            </td>
                            <td colspan="4">
                                <div class="element">
                                   
                                    <input type="text" id="policy_endorsement_no" name="policy_endorsement_no" value="{{ isset($endorsementData->endorsement_number) ? $endorsementData->endorsement_number : '' }}" autocomplete="off" maxlength="255" class="form-control required" error-message="Endorsement number field is mandatory">
                                </div>
                            </td>
                        </tr>
                          <tr id="field_policy_endorsement_count" class="field"><td class=""><div class="label"><span class="text-danger icon-asterix"></span>
                                    <span class="title">Endorsement count</span></div></td>
                                    <td colspan="4"><div class="element">
                                            <input type="number" id="policy_endorsement_count" name="policy_endorsement_count" value="{{ isset($endorsementData->endorsement_count) ? $endorsementData->endorsement_count  : 0 }}" maxlength="10" autocomplete="off" class="form-control" error-message="Endorsement count is field is mandatory" style="margin-right: 0px !important">
                                            
                                                
                                        </div></td>
                        </tr>
                    <tr id="field_policy_endorsement_no" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger icon-asterix"></span>
                                    <span class="title">Endorsement type</span>
                                </div>
                            </td>
                            <td colspan="4">
                                <div class="element">
                                   {{ Form::select('endorsement_type',  $typeArray, isset($endorsementData->endorsement_type) ? $endorsementData->endorsement_type : '' ,array('id' =>'endorsement_type','required'=>'required','class'=>'required form-control','error-message' =>"Gender field is mandatory" ))}}  
                                </div>
                            </td>
                        </tr>
                        
                        
                        <tr id="field_policy_endorsement_premium" class="field"><td class="">
                                <div class="label"><span class="text-danger icon-asterix"></span><span class="title">Endorsement premium(Without vat)</span></div></td>
                            
                            @if(isset($endorsementData->endorsement_type) && in_array($endorsementData->endorsement_type,[1,9]) )
                              @php
                              $amount = $endorsementData->amount;
                              @endphp
                            @elseif(isset($endorsementData->endorsement_type) && in_array($endorsementData->endorsement_type,[3,4]))
                                @php
                                  $amount = $endorsementData->amount * -1;
                                @endphp
                            @endif
                            
                            <td colspan="4"><div class="element"><input type="text" id="policy_endorsement_premium" name="policy_endorsement_premium" value="{{ isset($amount) ? $amount : '0.00' }}" autocomplete="off" style="width: 490px; width: 50%;" class=" with-comment numberfield currencyfield form-control required" data-m-dec="2" error-message="Premium amount field is mandatory"><select id="policy_endorsement_currency" name="policy_endorsement_currency" style="margin-left: 10px; width: 80px !important" data-keephiddenvalue="1" class=" with-comment numberfield" onchange=""><option value="SAR" selected="selected">SAR</option>
                                       
                                    </select>
                                    <span style='display:none' id='policy_endorsement_premium_error'>Please check the value</span>
                                
                                
                                </div></td>
                        </tr>
                        <tr id="field_policy_endorsement_date_issue" class="field"><td class=""><div class="label"><span class="text-danger icon-asterix"></span>
                                    <span class="title">Issue date</span></div></td>
                                    <td colspan="4"><div class="element"><input type="date" id="policy_endorsement_date_issue" name="policy_endorsement_date_issue" value="{{ isset($endorsementData->issue_date) ? date('Y-m-d', strtotime($endorsementData->issue_date))  : date('Y-m-d') }}" maxlength="10" autocomplete="off" class="form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_endorsement_date_issue_comment"></div></div></div></td>
                        </tr>
                        <tr id="field_policy_endorsement_date_start" class="field">
                            <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Start date</span></div></td>
                            <td colspan="4"><div class="element"><input type="date" id="policy_endorsement_date_start" name="policy_endorsement_date_start" value="{{ isset($endorsementData->start_date) ? date('Y-m-d', strtotime($endorsementData->start_date))  : date('Y-m-d') }}" maxlength="10" autocomplete="off" class="form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_endorsement_date_start_comment"></div></div></div></td>
                        </tr>  
                         <tr id="field_policy_endorsement_due_date" class="field">
                            <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Due date</span></div></td>
                            <td colspan="4"><div class="element"><input type="date" id="policy_endorsement_due_date" name="policy_endorsement_due_date" value="{{ isset($endorsementData->due_date) ? date('Y-m-d', strtotime($endorsementData->due_date))  : date('Y-m-d') }}" maxlength="10" autocomplete="off" class="form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_endorsement_due_date_comment"></div></div></div></td>
                        </tr>
                        
                        <tr id="field_policy_endorsement_vat" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger icon-asterix"></span>
                                    <span class="title">Vat</span>
                                </div>
                            </td>
                            <td colspan="4">
                                <div class="element">
                                     <select id="installment_tax" name="endorsement_tax" data-default-value="5" class="form-control">
                                        
                                        <option value="0" {{ isset($endorsementData->vat_percentage) ? (($endorsementData->vat_percentage=="0")? 'selected':''): '' }}>Nil (0%)</option>

                                        <option value="5" {{ isset($endorsementData->vat_percentage) ? (($endorsementData->vat_percentage=="5")? 'selected':''): '' }}>VAT (5%)</option>
                                        <option value="15" {{ isset($endorsementData->vat_percentage) ? (($endorsementData->vat_percentage=="15")? 'selected':''): '' }}>VAT (15%)</option>

                                    </select>  
                                    
                                </div>
                            </td>
                        </tr>


                    </tbody>
                </table>
            </div>
       



{{ Form::close() }}

