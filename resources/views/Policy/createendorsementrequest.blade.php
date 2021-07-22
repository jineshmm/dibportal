
{{ Form::open(array('route' => array('saveendorsementcrmrequest',$policyId), 'name' => 'form_endorsement_request_create','id'=>'form_endorsement_request_create','files'=>'true' )) }}
    @csrf    
    <div class="dialogform">
        <table class="insly_dialogform">
                <tbody>                    
                    <tr id="field_request_type" class="field">
                        <td class="">
                            <div class="label ">
                                <span class="text-danger "></span>
                                <span class="title">Type</span>
                            </div>
                        </td>
                        <td>
                            
                            <div class="element">
                             {{ Form::select('request_type',  $typeArray, '',array('id' =>'request_type','required'=>'required','class'=>'required form-control','error-message' =>"Gender field is mandatory" ))}}  
                            </div>
                        </td>
                    </tr>
                    <tr id="field_request_comment" class="field">
                        <td class="">
                            <div class="label">
                                <span class="text-danger "></span>
                                <span class="title">Description</span>
                            </div>
                        </td>
                        <td>
                            <div class="element">
                                <textarea id="request_comment" name="request_comment"  autocomplete="off" maxlength="255" wrap="soft" rows="4" autocomplete="off" maxlength="255" class="form-control"></textarea>
                            </div>
                        </td>
                    </tr>
                    
                    
                    

                </tbody>
        </table>
    </div>
    {{ Form::close() }}
