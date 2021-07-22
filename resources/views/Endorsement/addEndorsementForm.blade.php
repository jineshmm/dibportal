@extends('layouts.elite_fullwidth',['notificationCount'=>0 ] )

@section('content')
{{ Form::open(array('route' => array('saveendorsementdetails',$policy_id), 'name' => 'form_endorsement_save','id'=>'form_endorsement_save','files'=>'true') ) }}

@csrf 
<div class="insly-form row">

    <div class="col-md-12">
        <div class="panel panel-default panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Add an endorsement</h3>
            </div>
            <div class="panel-body">
                <table class="insly-form">
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
                                    <input type='hidden' name='endorsement_type' value='{{$endorsementtype}}' />
                                    <input type='hidden' name='endorsement_crm_id' value='{{$crmId}}' />

                                    
                                    <input type="text" id="policy_endorsement_no" name="policy_endorsement_no" value="" autocomplete="off" maxlength="255" class="form-control required" error-message="Endorsement number field is mandatory">
                                </div>
                            </td>
                        </tr>
                        
                        <tr id="field_policy_endorsement_date_issue" class="field"><td class=""><div class="label"><span class="text-danger icon-asterix"></span>
                                    <span class="title">Endorsement count</span></div></td>
                                    <td colspan="4"><div class="element">
                                            <input type="number" id="policy_endorsement_count" name="policy_endorsement_count" value="0" maxlength="10" autocomplete="off" class="form-control required"  style="margin-right: 0px !important" error-message="Endorsement number field is mandatory">
                                            
                                                
                                        </div></td>
                        </tr>
                        
                        
                        
                        <tr id="field_policy_endorsement_premium" class="field"><td class="">
                                <div class="label"><span class="text-danger icon-asterix"></span><span class="title">Endorsement premium(Without vat)</span></div></td>
                            <td colspan="4">
                                <div class="element"><input type="text" id="policy_endorsement_premium" name="policy_endorsement_premium" value="0.00" autocomplete="off" style="width: 490px; width: 50%;" class=" with-comment numberfield currencyfield form-control required" error-message="Endorsement premium field is mandatory" data-m-dec="2"><select id="policy_endorsement_currency" name="policy_endorsement_currency" style="margin-left: 10px; width: 80px !important" data-keephiddenvalue="1" class=" with-comment numberfield" ><option value="SAR" selected="selected">SAR</option>
                                       
                                    </select></div></td>
                        </tr>
                        
                        <tr id="field_policy_endorsement_date_issue" class="field"><td class=""><div class="label"><span class="text-danger icon-asterix"></span>
                                    <span class="title">Issue date</span></div></td>
                                    <td colspan="4"><div class="element"><input type="date" id="policy_endorsement_date_issue" name="policy_endorsement_date_issue" value="{{date('Y-m-d')}}" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_endorsement_date_issue_comment"></div></div></div></td>
                        </tr>
                        <tr id="field_policy_endorsement_date_start" class="field">
                            <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Start date</span></div></td>
                            <td colspan="4"><div class="element"><input type="date" id="policy_endorsement_date_start" name="policy_endorsement_date_start" value="{{date('Y-m-d')}}" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_endorsement_date_start_comment"></div></div></div></td>
                        </tr> 
                        
                        <tr id="field_policy_endorsement_due_date" class="field">
                            <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Due date</span></div></td>
                            <td colspan="4"><div class="element"><input type="date" id="policy_endorsement_due_date" name="policy_endorsement_due_date" value="{{date('Y-m-d')}}" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="policy_endorsement_due_date_comment"></div></div></div></td>
                        </tr> 

                        <tr id="field_invoice_file" class="field">
                        <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Upload invoice</span></div></td>
                        <td><div class="element"><div><input type="file" error-message="Please upload one file!!!" id="invoice_file"  multiple="multiple" name="invoice_file[]"   onchange="FORM.validateFile($(this), 128, 'Maximum file upload size 128 MB exceeded!')"></div></div></td>
                    </tr>   
                     
                               <tr id="field_policy_endorsement_vat" class="field">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger icon-asterix"></span>
                                    <span class="title">Vat</span>
                                </div>
                            </td>
                            <td colspan="4">
                                <div class="element">
                                     <select id="installment_tax" name="endorsement_tax" data-default-value="5" class="form-control">
                                        
                                        <option value="0" {{ isset($endorsementVat) ? (($endorsementVat=="0")? 'selected':''): '' }}>Nil (0%)</option>

                                        <option value="5" {{ isset($endorsementVat) ? (($endorsementVat=="5")? 'selected':''): '' }}>VAT (5%)</option>
                                        <option value="15" {{ isset($endorsementVat) ? (($endorsementVat=="15")? 'selected':''): '' }}>VAT (15%)</option>

                                    </select>  
                                    
                                </div>
                            </td>
                        </tr>
                        
                        
                        


                    </tbody>
                </table>
            </div>
        </div>




        <div class="buttonbar pull-right">
            <div class="submit"><button type="submit" id="submit_save" name="submit_save" class="btn waves-effect waves-light btn-rounded btn-success" >Add</button><button type="button" id="submit_cancel" name="submit_cancel" onclick="FORM.cancel()" class="btn waves-effect waves-light btn-rounded btn-danger">Cancel</button></div>
        </div>

    </div>
</div>

{{ Form::close() }}

@endsection
@section('pagescript') 
<script>
 
$("#form_endorsement_save" ).submit(function( event ) {
 
        var isValid = true;
                                                                                        var errorMessage = "";
                                                                                               var i=0;
                                                                                               $("form#form_endorsement_save .required:visible").each(function(){                
                                                                                                if($(this).val()=='') {
                                                                                                   isValid = false; 
                                                                                                  
                                                                                                    $(this).parent('.form-group').addClass('error');
                                                                                                   if( i==0 ) {
                                                                                                    errorMessage+="<b>The following errors occurred while validating data:"+"</b><br/>";
                                                                                                    i++;
                                                                                                   }
                                                                                                                                                                                                   
                                                                                                   
                                                                                                   errorMessage+="<b>"+ $(this).attr('error-message')+"</b><br/>"

                                                                                                } else {
                                                                                                   $(this).removeClass('error'); 
                                                                                                }
                                                                                               });
                                                                                               
                                                                                                    var regex = /^\$?(([1-9][0-9]{0,2}([0-9]{3})*)|[0-9]+)?(.[0-9]{0,4})?$/;
                                                                                                    var test = $("#policy_endorsement_premium").val();
                                                                                                    if(regex.test(test) === false){
                                                                                                        errorMessage+="<b>Please check premium amount value</b><br/>";
                                                                                                        $("#policy_endorsement_premium_error").show();
                                                                                                        $("#policy_endorsement_premium").addClass('form-control-danger');
                                                                                                        $("#policy_endorsement_premium").parent('.element').addClass('has-danger')
                                                                                                        
                                                                                                        isValid = false; 
                                                                                                    }
                                                                                               

                                                                                           if(isValid) {
                                                                                             $("#policy_endorsement_premium_error").show();      
                                                                                            return;
                                                                                           } else {
                                                                                             DIB.alert(errorMessage,'Error!!!!'); 
                                                                                            
                                                                                           }

  event.preventDefault();
});


</script>

@endsection
