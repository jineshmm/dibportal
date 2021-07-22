
{{ Form::open(array('route' => array('sendissuancemaildocument',$customerId, $crmId,$quoteId), 'name' => 'form_issuance_doc_sendmail','id'=>'form_issuance_doc_sendmail','files'=>'true' )) }}
@csrf    
<div class="dialogform">
    <table class="insly_dialogform" id='brokenslip_creation_table'>
        <tbody>
        
             <tr id="field_user" class="field">
                <td class="">
                    <div class="label ">
                        <span class="title">To</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="email" id="to_data" name="to_data" value="" autocomplete="off" maxlength="255"  class="required" required error-message="Please enter mail address">

                    </div>
                </td>
            </tr>
            <tr id="field_user" class="field">
                <td class="">
                    <div class="label ">
                        <span class="title">CC</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="cc_data" name="cc_data" value="" autocomplete="off" maxlength="255"  required error-message="Contact person name is mandatory">

                    </div>
                </td>
            </tr>

            <tr id="field_addperson_name" class="field">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Subject</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="subject" name="subject" value="" autocomplete="off" maxlength="255"  class="required" required error-message="Please enter content in subject box">
                    </div>
                </td>
            </tr> 
            <tr id="field_document_file" class="field">
                <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">File</span></div>
                </td>
                <td><div class="element"><div><input type="file" id="quote_file" name="quote_related_file[]" multiple  onchange="FORM.validateFile($(this), 10, 'Maximum file upload size 128 MB exceeded!')"></div></div>
                </td>
            </tr> 

            <tr id="field_activity" class="field dib_task">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Message</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                          <textarea id="message" name="message" wrap="soft" rows="4" autocomplete="off" maxlength="255" class="editor required" required error-message="Please enter content in message box"></textarea>
                    </div>
                </td>
            </tr>

        </tbody></table></div>
{{ Form::close() }}