
    @extends('layouts.elite_fullwidth',['sidemenustatus' => array(),'countDetails'=>array(),'notificationCount'=>array() ] )
@section('headtitle')
     Import customer details
@endsection

@section('content')

    <div class="row col-12 dpib-custom-form">
        <div class="col-md-12">
            <div class="card">
                      <div class="card-body">
                    <div class="insly-form">
{{ Form::open(array('route' => array('customerdataimport'), 'name' => 'customerimport','id'=>'customerimport','files'=>'true' )) }}
    @csrf    
    <div class="dialogform">
        <table class="insly_dialogform">
                <tbody><tr id="field_document_file" class="field">
                        <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">File</span></div></td>
                        <td><div class="element"><div><input type="file" error-message="Please upload one file!!!" id="document_file"  class="required" name="document_file"  accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" onchange="FORM.validateFile($(this), 128, 'Maximum file upload size 128 MB exceeded!')"></div></div></td>
                    </tr>   
                    

                    

                </tbody></table></div>
    
    <div class="buttonbar pull-right">
                            <div class="submit"><button type="button" id="submit_save" name="submit_save" class="btn waves-effect waves-light btn-rounded btn-success" >Import</button><button type="button" id="submit_cancel" class="btn waves-effect waves-light btn-rounded btn-danger" name="submit_cancel" onclick="FORM.cancel()">Cancel</button></div>
                        </div>
    {{ Form::close() }}
                       
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('pagescript')

<script>
   
   var selectedAddressType = []; 
   
    $(function () {
  
         $(document).on('click','#submit_save',function(e){
             
            var isValid = true;
         var errorMessage = "";
                var i=0;
                $(".required:visible").each(function(){                
                 if($(this).val()=='') {
                    isValid = false; 
                    $(this).addClass('form-control-danger');
                    $(this).parent('.element').addClass('has-danger')
                    if( i==0 ) {
                     errorMessage+="<b>The following errors occurred while validating data:"+"</b><br/>";
                     i++;
                    }
                    errorMessage+="<b>"+ $(this).attr('error-message')+"</b><br/>"
                  
                 } else {
                    $(this).removeClass('error');
                    $(this).removeClass('form-control-danger');
                    $(this).parent('.element').removeClass('has-danger')
                 }
                });
                

            if(isValid) {
              $("#customerimport").submit();
            } else {
              DIB.alert(errorMessage,'Error!!!!');
            }
                    
         })
         
         
      
    })
    function validateEmail($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test( $email );
    }

</script>
@endsection

