
{{ Form::open(array('route' => array('createquote',$customerId, $crmId), 'name' => 'form_quote_add','id'=>'form_quote_add','files'=>'true' )) }}
    @csrf    
    <div class="dialogform">
        <table class="insly_dialogform">
                <tbody><tr id="field_document_file" class="field"><td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">File</span></div></td><td><div class="element"><div><input type="file" id="quote_file" name="quote_file[]"  onchange="FORM.validateFile($(this), 128, 'Maximum file upload size 128 MB exceeded!')"></div></div></td></tr> 
                    
                    <tr id="field_document_comment" class="field ">
                        <td class="">
                            <div class="label ">
                                <span class="text-danger "></span>
                                <span class="title">Comment</span>
                            </div>
                        </td>
                        <td>
                            <div class="element">
                                <input type="text" id="quote_comment" name="quote_comment" value="" autocomplete="off" maxlength="255">
                                 <input type="hidden" id="company_id" name="companyId" value="{{$companyId}}" >
                                 <input type="hidden" id="broking_id" name="brkId" value="{{$brokingId}}" >
                            </div>
                        </td>
                    </tr>
                

                </tbody></table></div>
    {{ Form::close() }}
<script>
        


</script>
