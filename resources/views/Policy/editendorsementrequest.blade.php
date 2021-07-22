
  
{{ Form::open(array('route' => array('updateendorsementcrmrequest',$policyId), 'name' => 'form_endorsement_request_create','id'=>'form_endorsement_request_create','files'=>'true' )) }}

@csrf    
    <div class="dialogform"><table class="insly_dialogform">
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
                                <input type='hidden' name='crm_request_id' value='{{$requestDetails->id}}'>
                             {{ Form::select('request_type',  $typeArray, $requestDetails->type,array('id' =>'request_type','required'=>'required','class'=>'required','error-message' =>"Gender field is mandatory" ))}}  
                            </div>
                        </td>
                    </tr>
                    <tr id="field_request_comment" class="field">
                        <td class="">
                            <div class="label ">
                                <span class="text-danger "></span>
                                <span class="title">Description</span>
                            </div>
                        </td>
                        <td>
                            <div class="element">
                                <textarea id="request_comment" name="request_comment"  autocomplete="off" maxlength="255" wrap="soft" rows="4" autocomplete="off" maxlength="255" >{{$requestDetails->description}}</textarea>
                            </div>
                        </td>
                    </tr>
                    
                    
                    

                </tbody></table></div>
    {{ Form::close() }}
<script>
        


</script>
