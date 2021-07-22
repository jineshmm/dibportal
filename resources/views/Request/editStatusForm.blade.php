
{{ Form::open(array('route' => array('customercrmstatusedit', $customerId,$crmId), 'name' => 'form_crm_status_edit','id'=>'form_crm_status_edit','files'=>'true' )) }}
    @csrf    
    <div class="dialogform"><table class="insly_dialogform">
                <tbody>                    
                    <tr id="field_documenttype_oid" class="field ">
                        <td class="">
                            <div class="label ">
                                <span class="text-danger "></span>
                                <span class="title">Type</span>
                            </div>
                        </td>
                        <td>
                            
                            <div class="element">
                             {{ Form::select('crm_status',[''=>'--- other ---'] +  $statusArray, $status,array('id' =>'crm_status','required'=>'required','class'=>'required','error-message' =>"Gender field is mandatory" ))}}  
                            </div>
                        </td>
                    </tr>
                    <tr id="field_document_comment" class="field" style='display:none'>
                        <td class="">
                            <div class="label ">
                                <span class="text-danger"></span>
                                <span class="title">Comment</span>
                            </div>
                        </td>
                        <td>
                            <div class="element">
                                <input type="text" id="crm_comment" name="crm_comment" value="" autocomplete="off" maxlength="255">
                            </div>
                        </td>
                    </tr>
                    
                    
                    

                </tbody></table></div>
    {{ Form::close() }}
<script>
     $(document).on('click','#crm_status',function(){
       $("#field_document_comment").hide();
       var arr = [ '5', '8', '10'];       
       if( jQuery.inArray( $(this).val(), arr )  > -1   ) {
          $("#field_document_comment").show();
       }
     })  
      var disabledOptions = [] ;
     @if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES', Auth::user()->roles))
       disabledOptions = ['3','4','7','8'] ;
    @elseif (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) 
        disabledOptions = ['0','1','2','9','10'] ;
    @endif   
     
     $("#crm_status option").each(function()
        {
  
            if( jQuery.inArray($(this).attr('value') , disabledOptions )  > -1   ) {
                  $(this).attr('disabled','disabled');
                  
            }
        });


</script>
