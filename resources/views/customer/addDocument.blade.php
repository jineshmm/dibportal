
{{ Form::open(array('route' => array('customerdocdetailsave', $customerId), 'name' => 'form_document_add','id'=>'form_document_add','files'=>'true' )) }}
    @csrf    
    <div class="dialogform">
        <table class="insly_dialogform">
                <tbody><tr id="field_document_file" class="field"><td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">File</span></div></td><td><div class="element"><div><input type="file" id="document_file" name="document_file[]" multiple="multiple" onchange="FORM.validateFile($(this), 128, 'Maximum file upload size 128 MB exceeded!')"></div></div></td></tr>    <tr id="field_documenttype_oid" class="field ">
                        <td class="">
                            <div class="label ">
                                <span class="text-danger "></span>
                                <span class="title">Type</span>
                            </div>
                        </td>
                        <td>
                            <div class="element">
                             {{ Form::select('documenttype_oid',[''=>'--- other ---'] +  $documentType, null,array('id' =>'documenttype_oid','required'=>'required','class'=>'required','error-message' =>"Gender field is mandatory" ))}}  
                            </div>
                        </td>
                    </tr>
                    <tr id="field_document_comment" class="field ">
                        <td class="">
                            <div class="label ">
                                <span class="text-danger "></span>
                                <span class="title">Comment</span>
                            </div>
                        </td>
                        <td>
                            <div class="element">
                                <input type="text" id="document_comment" name="document_comment" value="" autocomplete="off" maxlength="255">
                            </div>
                        </td>
                    </tr>
                    <tr style="display:none;">
                        <td></td>
                        <td>
                            
                        </td>
                    </tr>
                    <tr style="display:none;">
                        <td></td>
                        <td>
                            
                        </td>
                    </tr>

                </tbody></table></div>
    {{ Form::close() }}

