@extends('layouts.elite_fullwidth',['sidemenustatus' => array(),'countDetails'=>array(),'notificationCount'=>array() ] )


@section('content')

<div class="row col-12 dpib-custom-form">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                {{ Form::open(array('route' => 'savechecklistform', 'name' => 'form_comparison_pdf_doc','id'=>'form_comparison_pdf_doc','class'=>'form_comparison_pdf_doc' ) ) }}

                @csrf

                <div class="insly-form">




                    <div class="panel panel-default panel-dark">
                        <div class="panel-heading" id="fieldgroup_title_contactinfo">
                            <!-- <div class="blocktitle"> -->
                            <h3 class="panel-title">File documents</h3>       
                            <!-- </div> -->
                        </div>
                        <div class="panel-body" id="fieldgroup_contactinfo">
                            <table class="insly-form panel-body mt-sm-2 mt-lg-4 mt-md-2" style="margin-top: 20px" id='dpib_installment_table'>
                                <tbody>

                                    
                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">File Number:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control">


                                                <input type="text" id="file_number" name="file_number" value=""  class="form-control" data-default-value="1" >
                                                
                                            </div>
                                            
                                        </td>



                                    </tr>

                                    <tr class="field installmentschedulerow">
                                        <td>
                                            <input type="hidden" name="policy_number" value="{{$policyDetails->policy_number}}" />
                                            <input type="hidden" name="insurer" value="{{$policyDetails->customerName}}" />
                                            <input type="hidden" name="insured" value="{{$policyDetails->insurerName}}" />
                                            
                                            
                                            <input type="hidden" name="policy_type" value="{{$policyDetails->policystatusString}}" />
                                            <input type="hidden" name="categoryTitle" value="{{$policyDetails->categoryTitle}}" />
                                            <input type="hidden" name="issue_date" value="{{date('Y-m-d',strtotime($policyDetails->issue_date))}}" />
                                            <input type="hidden" name="inception_date" value="{{date('Y-m-d',strtotime($policyDetails->issue_date))}}" />
                                            <input type="hidden" name="expiry_date" value="{{date('Y-m-d',strtotime($policyDetails->end_date))}}" />
                                            <input type="hidden" name="lob_type" value="{{$policyDetails->lobId}}" />
                                             <input type="hidden" name="product" value="{{$policyDetails->product_name}}" />
                                            
                                            <div class="label">UW DOCS:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio checklist-radio">


                                                <input type="radio" id="uw_docs_11" name="uw_docs" value="1"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="1" >
                                                <label class="custom-control-label" for="uw_docs_11">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio checklist-radio">

                                                <input type="radio" id="uw_docs_12" name="uw_docs" value="0"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="0" >
                                                <label class="custom-control-label" for="uw_docs_12">No</label>
                                            </div>
                                            <div class="custom-control custom-radio checklist-radio">

                                                <input type="radio" id="uw_docs_13" name="uw_docs" value="2"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="2" >
                                                <label class="custom-control-label" for="uw_docs_13">NA</label>
                                            </div>
                                        </td>



                                    </tr>

                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">QUOTES:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio checklist-radio">


                                                <input type="radio" id="quotes_11" name="quotes" value="1"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="0" >
                                                <label class="custom-control-label" for="quotes_11">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio checklist-radio">

                                                <input type="radio" id="quotes_12" name="quotes" value="0"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="1" >
                                                <label class="custom-control-label" for="quotes_12">No</label>
                                            </div>
                                            <div class="custom-control custom-radio checklist-radio">

                                                <input type="radio" id="quotes_13" name="quotes" value="2"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="1" >
                                                <label class="custom-control-label" for="quotes_13">NA</label>
                                            </div>
                                        </td>



                                    </tr>

                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">ISSUANCE DOCS:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio checklist-radio">


                                                <input type="radio" id="issuance_11" name="issuance" value="1"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="0" >
                                                <label class="custom-control-label" for="issuance_11">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio checklist-radio">

                                                <input type="radio" id="issuance_12" name="issuance" value="0"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="1" >
                                                <label class="custom-control-label" for="issuance_12">No</label>
                                            </div>
                                            <div class="custom-control custom-radio checklist-radio">

                                                <input type="radio" id="issuance_13" name="issuance" value="0"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="1" >
                                                <label class="custom-control-label" for="issuance_13">NA</label>
                                            </div>
                                        </td>



                                    </tr>


                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">POLICY DOCS:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio checklist-radio">


                                                <input type="radio" id="policy_11" name="policy" value="1"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="0" >
                                                <label class="custom-control-label" for="policy_11">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio checklist-radio">

                                                <input type="radio" id="policy_12" name="policy" value="0"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="1" >
                                                <label class="custom-control-label" for="policy_12">No</label>
                                            </div>
                                            <div class="custom-control custom-radio checklist-radio">

                                                <input type="radio" id="policy_13" name="policy" value="2"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="1" >
                                                <label class="custom-control-label" for="policy_13">NA</label>
                                            </div>
                                        </td>



                                    </tr>


                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">SPECIAL NOTES:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio checklist-radio">


                                                <input type="radio" id="special_11" name="special" value="1"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="0" >
                                                <label class="custom-control-label" for="special_11">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio checklist-radio">

                                                <input type="radio" id="special_12" name="special" value="0"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="1" >
                                                <label class="custom-control-label" for="special_12">No</label>
                                            </div>
                                            <div class="custom-control custom-radio checklist-radio">

                                                <input type="radio" id="special_13" name="special" value="0"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="1" >
                                                <label class="custom-control-label" for="special_13">NA</label>
                                            </div>
                                        </td>



                                    </tr>


                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">PAYMENT:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio checklist-radio">


                                                <input type="radio" id="payment_11" name="payment" value="1"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="0" >
                                                <label class="custom-control-label" for="payment_11">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio checklist-radio">

                                                <input type="radio" id="payment_12" name="payment" value="0"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="1" >
                                                <label class="custom-control-label" for="payment_12">No</label>
                                            </div>
                                            <div class="custom-control custom-radio checklist-radio">

                                                <input type="radio" id="payment_13" name="payment" value="2"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="1" >
                                                <label class="custom-control-label" for="payment_13">NA</label>
                                            </div>
                                        </td>



                                    </tr>


                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">ANNOUNCEMENT EMAIL:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio checklist-radio">


                                                <input type="radio" id="announce_11" name="announce" value="1"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="0" >
                                                <label class="custom-control-label" for="announce_11">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio checklist-radio">

                                                <input type="radio" id="announce_12" name="announce" value="0"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="1" >
                                                <label class="custom-control-label" for="announce_12">No</label>
                                            </div>
                                            <div class="custom-control custom-radio checklist-radio">

                                                <input type="radio" id="announce_13" name="announce" value="2"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="1" >
                                                <label class="custom-control-label" for="announce_13">NA</label>
                                            </div>
                                        </td>



                                    </tr>


                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">INSLY:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio checklist-radio">


                                                <input type="radio" id="insly_11" name="insly" value="1"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="0" >
                                                <label class="custom-control-label" for="insly_11">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio checklist-radio">

                                                <input type="radio" id="insly_12" name="insly" value="0"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="1" >
                                                <label class="custom-control-label" for="insly_12">No</label>
                                            </div>
                                            <div class="custom-control custom-radio checklist-radio">

                                                <input type="radio" id="insly_13" name="insly" value="2"  class="get-outta-here dib_customer_type custom-control-input" data-default-value="1" >
                                                <label class="custom-control-label" for="insly_13">NA</label>
                                            </div>
                                        </td>



                                    </tr>







                                </tbody>
                            </table> 
                        </div>
                    </div>


                    <div class="panel panel-default panel-dark">
                        <div class="panel-heading" id="fieldgroup_title_contactinfo">
                            <!-- <div class="blocktitle"> -->
                            <h3 class="panel-title">T&C SUMMARY</h3>       
                            <!-- </div> -->
                        </div>
                        <div class="panel-body" id="fieldgroup_contactinfo">
                            <table class="insly-form panel-body mt-sm-2 mt-lg-4 mt-md-2" style="margin-top: 20px" id='dpib_installment_table'>
                                <tbody>

                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">Rates:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control">


                                                <input type="text" id="rates" name="rates" value="0"  class="form-control" data-default-value="1" >
                                                
                                            </div>
                                            
                                        </td>



                                    </tr>

                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">Deductible:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control">


                                                <input type="text" id="deductible" name="deductible" value="0"  class="form-control" data-default-value="0" >
                                                
                                            </div>
                                            
                                        </td>



                                    </tr>   
                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">Special note:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control">


                                                <input type="text" id="special_note" name="special_note" value=""  class="form-control" data-default-value="0" >

                                            </div>

                                        </td>



                                    </tr>



                                    <!--General-->
                                    @if( $policyDetails->lobId ==1)

                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">Annual TSI/Limit:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control">


                                                <input type="text" id="annual_limit" name="annual_limit" value="0"  class="form-control" data-default-value="1" >
                                               
                                            </div>
                                            
                                        </td>



                                    </tr>

                                    


                                    <!--Motor-->
                                    @elseif( $policyDetails->lobId ==2)
                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">Partial Loss Depreciation:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control">

                                                <input type="text" id="partial_depreciation" name="partial_depreciation" value=""  class="form-control" data-default-value="0" >
                                                
                                            </div>
                                            
                                        </td>



                                    </tr>

                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">Total Loss depreciation:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control">


                                                <input type="text" id="total_loss_depreciation" name="total_loss_depreciation" value="0"  class="form-control" data-default-value="0">
                                                
                                            </div>
                                           
                                        </td>



                                    </tr>

                                    
                                    <!--Medical-->
                                    @else
                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">Class:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control">
                                                <input type="text" id="dip_class" name="dip_class" value="0"  class="form-control" data-default-value="0" >
                                            </div>
                                            
                                        </td>



                                    </tr>

                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">Dental:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control">
                                                <input type="text" id="dental" name="dental" value="0"  class="form-control" data-default-value="0" >
                                                
                                            </div>
                                            
                                        </td>



                                    </tr>

                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">Opticals:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control">
                                                <input type="text" id="opticals" name="opticals" value=""  class="form-control" data-default-value="0" >
                                            </div>
                                            
                                        </td>



                                    </tr>


                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">Maternity:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control ">

                                                <input type="text" id="maternity" name="maternity" value=""  class="form-control" data-default-value="0" >
                                                
                                            </div>
                                            
                                        </td>



                                    </tr>


                                    <tr class="field installmentschedulerow">
                                        <td>

                                            <div class="label">Parents:</div>
                                        </td>
                                        <td>
                                            <div class="custom-control">
                                                <input type="text" id="parents" name="parents" value=""  class="form-control" data-default-value="0" >
                                               
                                            </div>
                                            
                                        </td>



                                    </tr>



                                    @endif




                                </tbody>
                            </table> 
                        </div>
                    </div>           

                    <div class="buttonbar pull-right" >
                        <div class="submit"><button type="submit" id="submit_save" name="submit_save" class="btn waves-effect waves-light btn-rounded btn-success">Create</button><button type="button" id="submit_cancel" class="btn waves-effect waves-light btn-rounded btn-danger" name="submit_cancel" onclick="FORM.cancel()">Cancel</button></div>
                    </div>             




                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>






    @endsection

    @section('pagescript')


    <script>

        $(function () {

            //     $(document).on('click','#insurance_company',function(){
            //             var template = _.template($("#company_category_premium_template").html());
            //             var data = {'companies':$(this).val()};
            //             var result = template(data);
            //             $("#company_category_premium_details").html("");
            //             $("#company_category_premium_details").html(result);
            //             
            //
            //     });

        })


    </script>
    @endsection











