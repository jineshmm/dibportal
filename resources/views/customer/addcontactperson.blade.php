
@if (isset($contactId))
                        {{ Form::open(array('route' => array('updatecontactperson', $customerId,$contactId), 'name' => 'form_contact_person_add','id'=>'form_contact_person_add','files'=>'true' )) }}
                        @else
                       {{ Form::open(array('route' => array('savecontactperson', $customerId), 'name' => 'form_contact_person_add','id'=>'form_contact_person_add','files'=>'true' )) }}
     @endif


    @csrf 
                     
                            <div class="dialogform" id="fieldgroup_addperson">
                                <table class="insly-form">
                                    <tbody>
                                        <tr id="field_addperson_name" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger icon-asterix"></span>
                                                    <span class="title">Contact person name</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    <input type="text" id="addperson_name" name="addperson_name" value="{{ isset($contactperson->person_name) ? $contactperson->person_name : '' }}" autocomplete="off" maxlength="255" class="required" required error-message="Contact person name is mandatory">
                                                </div>
                                            </td>
                                        </tr>
                                        
                                          <tr id="field_addperson_name" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger icon-asterix"></span>
                                                    <span class="title">Gender</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                   @php
                                                    $selected = '';
                                                    @endphp

                                                    @if (isset($contactperson->person_gender) )
                                                    @php
                                                    $selected = $contactperson->person_gender ;
                                                    @endphp                                                            
                                                    @endif

                                                    {{ Form::select('contactperson_gender', array('' => '--- not entered ---','male' => 'male','female' => 'female'), $selected,array('id' =>'contactperson_gender','required'=>'required','class'=>'required','error-message' =>"Gender field is mandatory" ))}}
                                                </div>
                                            </td>
                                        </tr> 
                                        
                                        <tr id="field_addperson_gender" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">Contact person title</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    <input type="text" id="addperson_title" name="addperson_title" value="{{ isset($contactperson->person_title) ? $contactperson->person_title : '' }}" autocomplete="off" maxlength="32">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="field_addperson_email" class="field"><td class=""><div class="label"><span class="title">E-mail address</span></div></td>
                                            <td><div class="element">
                                                    <input type="text" id="addperson_email" name="addperson_email" value="{{ isset($contactperson->email) ? $contactperson->email : '' }}" autocomplete="off" maxlength="255"></div></td></tr>    <tr id="field_addperson_mobile" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">Mobile phone</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    <input type="text" id="addperson_mobile" name="addperson_mobile" value="{{ isset($contactperson->mobile_phone) ? $contactperson->mobile_phone : '' }}" autocomplete="off" maxlength="255">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="field_addperson_phone" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">Phone</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    <input type="text" id="addperson_phone" name="addperson_phone" value="{{ isset($contactperson->phone) ? $contactperson->phone : '' }}" autocomplete="off" maxlength="255">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="field_addperson_idcode" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">Contact person ID code</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    <input type="text" id="addperson_idcode" name="addperson_idcode" value="{{ isset($contactperson->contact_person_id_code) ? $contactperson->contact_person_id_code : '' }}" autocomplete="off" maxlength="255">
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
  {{ Form::close() }}  
  
  