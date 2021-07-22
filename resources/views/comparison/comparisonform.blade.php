@extends('layouts.elite_fullwidth',['sidemenustatus' => array(),'countDetails'=>array(),'notificationCount'=>array() ] )


@section('content')

    <div class="row col-12 dpib-custom-form">
        <div class="col-md-12">
            <div class="card">
                      <div class="card-body">

                       {{ Form::open(array('route' => array('comparisonpdfdoc',$customerId, $crmId), 'name' => 'form_comparison_pdf_doc','id'=>'form_comparison_pdf_doc','class'=>'form_comparison_pdf_doc' ) ) }}

 @csrf

                    <div class="insly-form">

 <div class="panel panel-default panel-dark">
                            <div class="panel-heading" id="fieldgroup_title_contactinfo">
                                <!-- <div class="blocktitle"> -->
                                <h3 class="panel-title">Insurance Companies</h3>       
                                <!-- </div> -->
                            </div>
                            <div class="panel-body" id="fieldgroup_contactinfo">
                               <table class="insly_dialogform" id='brokenslip_creation_table'>
        <tbody>
            <tr id="field_documenttype_oid" class="field dp_main_tr">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Insurance company</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        {{ Form::select('insurance_company',  $insuranceCompany, null,array('multiple'=>'multiple','name'=>'insurance_company[]','id' =>'insurance_company','required'=>'required','class'=>'required form-control','error-message' =>"Insurance company field is mandatory" ))}}  
                    </div>
              </td></tr></tbody></table> 
                            </div>
                        </div>










                        

<div class="panel panel-default panel-dark">
                            <div class="panel-heading" id="fieldgroup_title_contactinfo">
                                <!-- <div class="blocktitle"> -->
                                <h3 class="panel-title">Category count info</h3>       
                                <!-- </div> -->
                            </div>
                            <div class="panel-body" id="fieldgroup_contactinfo">
                                <table class="insly-form panel-body mt-sm-2 mt-lg-4 mt-md-2" style="margin-top: 20px" id='dpib_installment_table'>
    <tbody>
                
                                                                <tr class="installmentschedulerow" style="height: 30px">
                                                                    <th style="font-weight: bold"></th>
                                                                @foreach($categoryclasses as $category)
                                                                    <th style=" text-align: left; font-weight: bold">{{$category}}</th>
                                                                    @endforeach
                                                                   
                                                                </tr>
                                                            
                                                                <tr class="field installmentschedulerow">
                                                                    <td>
                                                                       
                                                                        <div class="label">Employee:</div>
                                                                    </td>
                                                                     @foreach($categoryclasses as $category)
                                                                      <td class="element" style="text-align: center"><input type="text" autocomplete="off" class="form-control" maxlength="25" id="countDetails_employee_{{$category}}" name="countDetails[employee][{{$category}}]" value="0"></td>
                                                                      @endforeach

                                                                   

                                                                </tr>


                                                                 <tr class="field installmentschedulerow">
                                                                    <td>
                                                                       
                                                                        <div class="label">Spouse:</div>
                                                                    </td>
                                                                    @foreach($categoryclasses as $category)
                                                                      <td class="element" style="text-align: center"><input type="text" autocomplete="off" class=" form-control" maxlength="25" id="countDetails_spouse_{{$category}}" name="countDetails[spouse][{{$category}}]" value="0"></td>
                                                                      @endforeach
                                                                    
                                                                    

                                                                </tr>

                                                                 <tr class="field installmentschedulerow">
                                                                    <td>
                                                                        <div class="label">Child:</div>
                                                                    </td>
                                                                    @foreach($categoryclasses as $category)
                                                                      <td class="element" style="text-align: center"><input type="text" autocomplete="off" class=" form-control" maxlength="25" id="countDetails_child_{{$category}}" name="countDetails[child][{{$category}}]" value="0"></td>
                                                                      @endforeach
                                                                    

                                                                </tr>

                                                                <tr class="field installmentschedulerow">
                                                                    <td>
                                                                       
                                                                        <div class="label">Senior (65+):</div>
                                                                    </td>
                                                                   @foreach($categoryclasses as $category)
                                                                      <td class="element" style="text-align: center"><input type="text" autocomplete="off" class=" form-control" maxlength="25" id="countDetails_senior_{{$category}}" name="countDetails[senior][{{$category}}]" value="0"></td>
                                                                      @endforeach

                                                                </tr>
                                                            
                                                            
    </tbody>
            </table> 
                            </div>
                        </div>

                       
    <div id='company_category_premium_details'>
     


    </div>            

             <div class="buttonbar pull-right" >
                            <div class="submit"><button type="submit" id="submit_save" name="submit_save" class="btn waves-effect waves-light btn-rounded btn-success">Create</button><button type="button" id="submit_cancel" class="btn waves-effect waves-light btn-rounded btn-danger" name="submit_cancel" onclick="FORM.cancel()">Cancel</button></div>
                        </div>             



                        
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

<script id='company_category_premium_template' type='text/template'>

 
<% _.each(companies, function(result, index) { %>

 <div class="panel panel-default panel-dark">
                            <div class="panel-heading" id="fieldgroup_title_contactinfo">
                                <!-- <div class="blocktitle"> -->
                                <h3 class="panel-title">Category premium (<%= result  %>)</h3>       
                                <!-- </div> -->
                            </div>
                            <div class="panel-body" id="fieldgroup_contactinfo">
                                <table class="insly-form panel-body mt-sm-2 mt-lg-4 mt-md-2" style="margin-top: 20px" id='dpib_installment_table'>
    <tbody>
                <input type="hidden" name="company[<%= index %>]" value="<%= result %>">
                                                                <tr class="installmentschedulerow" style="height: 30px">
                                                                    <th style="font-weight: bold"></th>
                                                                 @foreach($categoryclasses as $category)
                                                                    <th style=" text-align: left; font-weight: bold">{{$category}}</th>
                                                                    @endforeach
                                                                    
                                                                   
                                                                </tr>
                                                            
                                                                <tr class="field installmentschedulerow">
                                                                    <td>
                                                                       
                                                                        <div class="label">Employee:</div>
                                                                    </td>
                                                                    @foreach($categoryclasses as $category)
                                                                      <td class="element" style="text-align: center"><input type="text" autocomplete="off" class=" form-control" maxlength="25" id="premium_employee_<%= index %>_{{$category}}" name="premium[employee][<%= index %>][{{$category}}]" value=""></td>
                                                                      @endforeach

                                                                </tr>


                                                                 <tr class="field installmentschedulerow">
                                                                    <td>
                                                                       
                                                                        <div class="label">Spouse:</div>
                                                                    </td>
                                                                    @foreach($categoryclasses as $category)
                                                                      <td class="element" style="text-align: center"><input type="text" autocomplete="off" class=" form-control" maxlength="25" id="premium_spouse_<%= index %>_{{$category}}" name="premium[spouse][<%= index %>][{{$category}}]" value=""></td>
                                                                      @endforeach

                                                                </tr>

                                                                 <tr class="field installmentschedulerow">
                                                                    <td>
                                                                       
                                                                        <div class="label">Child:</div>
                                                                    </td>
                                                                    @foreach($categoryclasses as $category)
                                                                      <td class="element" style="text-align: center"><input type="text" autocomplete="off" class=" form-control" maxlength="25" id="premium_child_<%= index %>_{{$category}}" name="premium[child][<%= index %>][{{$category}}]" value=""></td>
                                                                      @endforeach

                                                                </tr>

                                                                <tr class="field installmentschedulerow">
                                                                    <td>
                                                                       
                                                                        <div class="label">Senior (65+):</div>
                                                                    </td>
                                                                     @foreach($categoryclasses as $category)
                                                                      <td class="element" style="text-align: center"><input type="text" autocomplete="off" class=" form-control" maxlength="25" id="premium_senior_<%= index %>_{{$category}}" name="premium[senior][<%= index %>][{{$category}}]" value=""></td>
                                                                      @endforeach

                                                                </tr>
                                                            
                                                            
    </tbody>
            </table> 
                            </div>
                        </div>


<% }); %>




</script>




@endsection

@section('pagescript')


<script>
   
   $(function(){

     $(document).on('click','#insurance_company',function(){
             var template = _.template($("#company_category_premium_template").html());
             var data = {'companies':$(this).val()};
             var result = template(data);
             $("#company_category_premium_details").html("");
             $("#company_category_premium_details").html(result);
             

     });

   })
   

</script>
@endsection











