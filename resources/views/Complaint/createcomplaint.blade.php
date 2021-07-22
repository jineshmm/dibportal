
@if(isset($complaintDetails->id))
{{ Form::open(array('route' => array('updatecomplaint',$complaintDetails->id), 'name' => 'form_complaint_create','id'=>'form_complaint_create','files'=>'true' )) }}
@else
{{ Form::open(array('route' => array('savecomplaint'), 'name' => 'form_complaint_create','id'=>'form_complaint_create','files'=>'true' )) }}
@endif 

@csrf    
<div class="dialogform">
    <table class="insly_dialogform">
        <tbody>                    
            <tr id="field_request_type" class="field">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Client</span>
                    </div>
                </td>
                <td>

                    <div class="element">

                        {{ Form::select('complaint_client',  $customers, empty($complaintDetails->client_id) ? '':$complaintDetails->client_id ,array('id' =>'complaint_client','required'=>'required','class'=>'required form-control','error-message' =>"Gender field is mandatory" ))}}  
                    </div>
                </td>
            </tr>
            <tr id="field_request_type" class="field">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Policy</span>
                    </div>
                </td>
                <td>

                    <div class="element">
                        <select id="complaint_policy" name="complaint_policy" selected_id="{{ isset($complaintDetails->policy_id)  ? $complaintDetails->policy_id:''}}" class='form-control'>

                            <option value="" >--Select policy--</option>

                        </select>


                    </div>
                </td>
            </tr>
            <tr id="field_request_type" class="field">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Complaint type</span>
                    </div>
                </td>
                <td>

                    <div class="element">
                        {{ Form::select('complaint_type',  $complaintType, empty($complaintDetails->compliant_type)?'':$complaintDetails->compliant_type,array('id' =>'complaint_type','required'=>'required','class'=>'required form-control','error-message' =>"Gender field is mandatory" ))}}  
                    </div>
                </td>
            </tr>


            <tr id="field_request_comment" class="field">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Request date</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="date" name="complaint_request_date" id="complaint_request_date"  autocomplete="off"  class="form-control"  value="{{ isset($complaintDetails->requested_date)  ? date('Y-m-d',strtotime($complaintDetails->requested_date)):date('Y-m-d')}}" />
                    </div>
                </td>
            </tr>


            <tr id="field_request_comment" class="field">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Request validity</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        {{ Form::select('complaint_validity',  $requestValidity, empty($complaintDetails->request_validity) ? 1: $complaintDetails->request_validity,array('id' =>'request_policy','required'=>'required','class'=>'required form-control','error-message' =>"Gender field is mandatory" ))}}  
                    </div>
                </td>
            </tr>

            <tr id="field_request_comment" class="field">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Request status</span>
                    </div>
                </td>
                <td>
                    <div class="element">

                        {{ Form::select('complaint_status', array(1 => 'Open',2 => 'Close'), isset($complaintDetails->request_status) ? $complaintDetails->request_status : 1,array('id' =>'complaint_status','required'=>'required','class'=>'required form-control','error-message' =>"Gender field is mandatory" ))}}


                    </div>
                </td>
            </tr>
            <tr id="field_request_comment" class="field">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Remarks</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <textarea id="complaint_remarks" name="complaint_remarks"  autocomplete="off" maxlength="255" wrap="soft" rows="4" class='form-control'>{{ isset($complaintDetails->remarks)  ? $complaintDetails->remarks:'' }}</textarea>
                    </div>
                </td>
            </tr>
            <tr id="field_request_comment" class="field">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Handle user</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        {{ Form::select('complaint_handle_user',  $users, empty($complaintDetails->request_validity) ? Auth::user()->id:$complaintDetails->handle_user ,array('id' =>'request_policy','required'=>'required','class'=>'required form-control','error-message' =>"Gender field is mandatory" ))}}  
                    </div>
                </td>
            </tr>

            <tr id="field_request_comment" class="field">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Bill amount</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="complaint_bill_amount" name="complaint_bill_amount"  autocomplete="off"  wrap="soft"   maxlength="255" value="{{ isset($complaintDetails->bill_amount)  ? $complaintDetails->bill_amount:'0.00' }}"  class='form-control'/>
                    </div>
                </td>
            </tr>

            <tr id="field_request_comment" class="field">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Approve amount</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="complaint_approve_amount" name="complaint_approve_amount"  autocomplete="off"  wrap="soft" value="{{ isset($complaintDetails->approve_amount)  ? $complaintDetails->approve_amount:'0.00' }}" maxlength="255" class='form-control'/>
                    </div>
                </td>
            </tr>


            <tr id="field_request_comment" class="field">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Closed date</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="date" name="complaint_closed_date" id="complaint_closed_date"  autocomplete="off"  class="form-control"  value="{{ (isset($complaintDetails->closed_date) && !empty($complaintDetails->closed_date))  ? date('Y-m-d',strtotime($complaintDetails->closed_date)):''}}" />
                    </div>
                </td>
            </tr>


        </tbody>
    </table>
</div>
{{ Form::close() }}

