@extends('layouts.elite',['notificationCount'=>0 ] )


@section('headtitle')
Upcoming renewals
@endsection




@section('content')


  <div class="row mt-1 mb-4 ml-1">
<div id="filter-insly_customer" class="panel-filter open extend-filter col-md-12">
 				 {{ Form::open(array('route' => array('renewallistdaysfilter'), 'name' => 'form_management_customer_filter','id'=>'form_management_customer_filter') ) }}
                                    @csrf
                                    <table class="table-filter filtersperrow4">
                                        <tbody>
                                            <tr>
                                                <td id="filter_customer_name" class=" filterlevel1"><div><label for="filter_customer_name">Name:</label><div><input type="text" autocomplete="off" id="filter_customer_name" name="filter_customer_name" value="{{isset($formData['filtername']) ? $formData['filtername']:'' }}" class=""></div></div></td>
                                                <td id="filter_customer_type" class="extend-filter filterlevel2"><div><label for="filter_customer_type">Customer type:</label><div><select id="filter_customer_type" name="filter_customer_type"><option value="">--- all ---</option><option value="1">company</option><option value="0">individual</option></select></div></div></td>
                                                <td id="filter_customergroup_oid" class="extend-filter filterlevel2"><div><label for="filter_customergroup_oid">Customer group:</label><div>
                                                            {{ Form::select('filter_customergroup_oid',[''=>'---- not set ----'] +   $usergroup, isset($formData['filtergroup']) ? $formData['filtergroup']:'' ,array('id' =>'filter_customergroup_oid',"error-message"=>'Account manager field is mandatory'))}} 
                                                            </div></div></td>
                                               <td id="filter_customer_type" class="extend-filter filterlevel2"><div><label for="filter_customer_type">Remaining days:</label><div>
                                                           {{ Form::select('filter_remaining_days',[''=>'---- not set ----'] +   [''=>'--- all ---','20'=>20,'40'=>40,'60'=>60], isset($formData['filterdays']) ? $formData['filterdays']:'' ,array('id' =>'filter_remaining_days',"error-message"=>'Account manager field is mandatory'))}} 
                                                           
                                                           </div></div></td>             
                                            </tr>

                                            <tr>
                                                <td colspan="4" class="panel-buttons"><button type="submit" id="submit_save" name="submit_save" class="submit_policy btn waves-effect waves-light btn-rounded btn-primary">Apply filters</button> <button type="button" id="mg_clear_filter" name="clear_filter" class="submit_policy btn waves-effect waves-light btn-rounded btn-primary">Clear filters</button>  </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                {{ Form::close() }}
 			</div>



  </div>





<div class="row">          
          @foreach($renewalDatas as $key =>$renewals)
          
          @if($key % 3 ==0)
          </div>
           <div class="row"> 
          @endif
          

<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="ribbon-wrapper-reverse card">
                                    @php
                                     $badgeColor = "ribbon-success";
                                     $customerStatus ='Active';
                                    @endphp
                                    
                                     @if($renewals->datediff <= 20)
                                    
                                     @php
                                     $badgeColor="ribbon-danger";
                                     $customerStatus ='Inactive';
                                    @endphp
                                         
                                   
                                    
                                    @elseif($renewals->datediff <=40)
                                     @php
                                     $badgeColor = "ribbon-success";
                                     $customerStatus ='Active';
                                    @endphp
                                    
                                     @endif
                                   
                                    
                                    <div class="ribbon  ribbon-right {!!$badgeColor !!}">Remaining {{$renewals->datediff}} days</div>
                                    <h5 ><a href="{!! route('customeroverview',$renewals->customer_id) !!}" style='font-size:1.5em'><b>{{ ucfirst(trans($renewals->name)) }}</b></a></h5>  
                                  <table style="width:100%">
                        <tbody>
                             <tr class="text-danger">
                            <td>
                              Policy  </td><td>
                                <a href='{!! route("policyoverview",$renewals->policy_id) !!}'>{{ $renewals->policy_number }}</a>
                                
                            </td>
                            <td><span class="fas fa-envelope text-blue large-size" data-toggle="tooltip" title="" data-original-title="Send mail"></span> <span class="icon-add icon-add fas fa-plus large-size" data-toggle="tooltip" title="" data-original-title="Create request" style="margin-left:8px" id="dpib_client_renewal_request_add" policy_id="{{$renewals->policy_id}}" customer_id="{{$renewals->customer_id}}" ></span></td>
                          </tr> 
                         <tr>
                            <td> Customer type</td><td>{{ ($renewals->type==1) ? 'Company':'Individual'}}</td><td></td>                            
                          </tr>
                     
                          <tr>
                            <td> Customer code</td><td>{{($renewals->id_code !='') ? $renewals->id_code:'-' }}  </td><td></td>
                          </tr>
                    
                          <tr>
                            <td> Customer group</td><td>{{($renewals->customer_group !='') ? $renewals->customer_group:'-' }} </td><td></td>
                          </tr>
                    

                          <tr>
                           <td> Phone</td><td> {{$renewals->customerPhone}} </td> <td></td>                           
                          </tr> 
                          <tr>
                            <td> Email</td><td>{{$renewals->customerEmail}}</td> <td></td>                           
                          </tr> 
                          <tr>
                            <td> Remaining days</td><td>{{$renewals->datediff}}</td>   <td></td>                         
                          </tr> 
                         
                         
                         
                        </tbody>
                                </table> 
                                </div>
                            </div>
          
          
          @endforeach
   </div>
  
   <div class="row">
       <div class="col-12"  style="float:right"></div>
       {!! $renewalDatas->links() !!}
   </div> 

  <script id='client_request_add_template' type='text/template'>
        
        {{ Form::open(array('route' => array('saverenewalcrmrequest', 0), 'name' => 'form_quote_request_add','id'=>'form_quote_request_add','files'=>'true' )) }}
				     <div class="col-lg-12">
                                <div class="card">
                          
                                    <div class="card-body card-block">
                                        <div class="form-group">
                                            <label for="vat" class="form-control-label">Line of business</label>
                                            {{ Form::select('request_lineof_business', ['' =>'----Not Select----']+$lineofbusiness, '',array('id' =>'request_lineof_business','required'=>'required','style'=>'width: 100%;','class'=>'request_lineof_business required form-control','error-message' =>"Line of business is mandatory" ))}}
                                        </div> 
                                        <div class="form-group">
                                                                                      
                                              <input type="hidden" name="customer_id" value='<%- customerId %>' />
                                             <input type="hidden" name="policy_id" value='<%- policyId %>' />
                                            <label for="vat" class="form-control-label">Description</label>
                                            <textarea class="note_add_entry form-control" name="request_description"  rows="9" wrap="soft" ></textarea>
                                                    
                                        </div>
                                        <div class="form-group">
                                                                                      
                                             <div class="label"><span class="title" style="float:left"> File</span></div></td><td><div class="element"><div><input type="file" id="document_file" name="document_file[]" multiple="multiple" onchange="FORM.validateFile($(this), 128, 'Maximum file upload size 128 MB exceeded!')"></div></div>
                                                    
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                            </div>
                            {{Form::close()}}
			
</script>
                     

@endsection

 @section('customcss')
  <link rel="stylesheet" type="text/css" href=" {{ asset('elitedesign/dist/css/pages/ribbon-page.css') }} ">
   
@endsection
          

  @section('customscript')
      <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('elitedesign/dist/js/perfect-scrollbar.jquery.min.js') }}"></script>

    <!--stickey kit -->
    <script src="{{ asset('elitedesign/assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
     <script src="{{ asset('elitedesign/assets/node_modules/sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('elitedesign/assets/node_modules/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('elitedesign/assets/node_modules/morrisjs/morris.min.js') }}"></script>

    <!-- This is data table -->
   
        <script src="{{ asset('elitedesign/assets/node_modules/skycons/skycons.js') }}"></script>
        
   
  @endsection






@section('pagescript')
<script>
    
 
   $(function(){

//Claims
$(document).on('click','#mg_clear_filter',function(){
   window.location.href = "{!! route('renewalnotificationlist')  !!}";
    
})
      $(document).off('click','#dpib_client_renewal_request_add');
    $(document).on('click','#dpib_client_renewal_request_add',function(){
        var template = _.template($("#client_request_add_template").html());      
        var policyId = $(this).attr('policy_id');
        var customerId = $(this).attr('customer_id');
        var data = {'policyId':policyId,'customerId':customerId};
         var dialogElement =$("#db_client_request_popup");
        var result = template(data);

            $("#db_client_request_popup").remove();
                $('body').append('<div id="db_client_request_popup" title="Add renewal request" class="col-lg-12" >' + result + '</div>');
              
                $("#db_client_request_popup").dialog({
                                                            minWidth: 600,                                                            
                                                            modal:true,
                                                            buttons: {
                                                                    "Create": {
                                                                                class: "btn waves-effect waves-light btn-rounded btn-success",
                                                                                text: "Create",                            
                                                                                click: function () {                               
                                                                                 var isValid = true;
                                                                                        var errorMessage = "";
                                                                                               var i=0;
                                                                                               $("form#form_quote_request_add .required:visible").each(function(){                
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


                                                                                           if(isValid) {
                                                                                               $("form#form_quote_request_add").submit();
                                                                                           } else {
                                                                                             DIB.alert(errorMessage,'Error!!!!');    
                                                                                           }  
        
        
       
                                                                                }
                                                                               },
                                                                        "cancel": {
                                                                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                                                                                    text: "Cancel",
                                                                           click:function(){  $(this).dialog("close"); }

                                                                        }
                                                            },
                                                            open:function() {
                                                            $('.modal-backdrop').remove();                                                            
                                                             $("#customer_select").select2({dropdownParent: $("#db_client_request_popup")});
                                                            }
});


    });

   });


   


</script>




@endsection
